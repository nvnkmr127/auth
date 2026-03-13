<?php

namespace App\Livewire\Admin;

use App\Models\App;
use App\Services\AuditService;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;

class AppManager extends Component
{
    use WithPagination, WithFileUploads;

    public $showModal = false;
    public $isEditing = false;
    public $editId = null;

    // Form Fields
    public $name = '';
    public $slug = '';
    public $domain = '';
    public $sync_token = '';
    public $status = 'active';
    public $icon;
    public $existingIcon;

    protected function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:apps,slug,' . ($this->editId ?? 'NULL'),
            'domain' => 'nullable|url|max:255',
            'sync_token' => 'nullable|string|max:255',
            'status' => 'required|in:active,inactive,maintenance',
            'icon' => $this->icon ? 'nullable|image|max:1024' : 'nullable',
        ];
    }

    public function create()
    {
        $this->resetForm();
        $this->showModal = true;
        $this->isEditing = false;
    }

    public function edit($id)
    {
        $app = App::findOrFail($id);
        $this->editId = $app->id;
        $this->name = $app->name;
        $this->slug = $app->slug;
        $this->domain = $app->domain;
        $this->sync_token = $app->sync_token;
        $this->status = $app->status;
        $this->existingIcon = $app->icon;

        $this->isEditing = true;
        $this->showModal = true;
    }

    public function syncConfig($id)
    {
        // Rate limit sync operations to prevent abuse
        $cacheKey = "sso_sync_rate_limit_{$id}";
        if (cache()->get($cacheKey)) {
            $this->dispatch('notify', message: 'Please wait before syncing again. Rate limit in effect.', type: 'warning');
            return;
        }
        cache()->set($cacheKey, true, now()->addMinutes(1));

        $app = App::findOrFail($id);

        if (empty($app->domain) || empty($app->sync_token)) {
            $this->dispatch('notify', message: 'Domain and Sync Token are required to sync.', type: 'error');
            return;
        }

        try {
            // Extract base URL (scheme + host) to handle cases where domain includes paths
            $parsed = parse_url($app->domain);
            $host = $parsed['host'] ?? $app->domain;
            $scheme = $parsed['scheme'] ?? 'https';

            if ($scheme !== 'https') {
                throw new \Exception("Sync failed: HTTPS scheme is required for security.");
            }

            $ip = gethostbyname($host);
            if ($ip === $host) {
                throw new \Exception("Sync failed: Could not resolve domain.");
            }

            if (!filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE)) {
                throw new \Exception("Sync failed: Host resolves to internal or private IP address.");
            }

            $baseUrl = $scheme . '://' . $host;
            if (isset($parsed['port']))
                $baseUrl .= ':' . $parsed['port'];

            $url = rtrim($baseUrl, '/') . '/api/sso/sync';
            \Illuminate\Support\Facades\Log::info("SSO Syncing from: " . $url);

            $response = \Illuminate\Support\Facades\Http::withHeaders([
                'X-SSO-Sync-Token' => $app->sync_token
            ])->get($url);

            if ($response->failed()) {
                \Illuminate\Support\Facades\Log::error("SSO Sync Failed", [
                    'url' => $url,
                    'status' => $response->status(),
                    'body' => $response->body()
                ]);
                throw new \Exception("Sync failed: " . ($response->json('error') ?? $response->status()));
            }

            $data = $response->json();
            $roles = $data['roles'] ?? [];
            $permissions = $data['permissions'] ?? [];
            $groups = $data['groups'] ?? [];

            // 1. Sync Permissions
            foreach ($permissions as $key => $description) {
                // Find group
                $group = 'General';
                foreach ($groups as $groupName => $keys) {
                    if (in_array($key, $keys)) {
                        $group = $groupName;
                        break;
                    }
                }

                \App\Models\Permission::updateOrCreate(
                    ['key' => $key],
                    [
                        'name' => ucfirst(str_replace('_', ' ', $key)),
                        'group' => $group
                    ]
                );
            }

            // 2. Sync Roles for this App
            foreach ($roles as $roleKey => $info) {
                // Check if role exists by key (since key is UNIQUE globally in migration)
                $existingRole = \App\Models\Role::where('key', $roleKey)->first();

                if ($existingRole) {
                    // If it's a global role (like super_admin), don't touch it or try to duplicate it
                    if ($existingRole->is_global) {
                        \Illuminate\Support\Facades\Log::debug("Skipping global role '{$roleKey}' during sync for app '{$app->name}'");
                        continue;
                    }

                    // If it exists but belongs to this app, update it
                    if ($existingRole->app_id == $app->id) {
                        $existingRole->update([
                            'name' => $info['name'] ?? ucfirst($roleKey),
                            'description' => $info['description'] ?? '',
                        ]);
                    } else {
                        // If it belongs to ANOTHER app, log and skip to avoid unique constraint violation
                        \Illuminate\Support\Facades\Log::warning(
                            "Role '{$roleKey}' exists but belongs to app ID {$existingRole->app_id}, cannot sync to app ID {$app->id}"
                        );
                    }
                    continue;
                }

                // If it doesn't exist, create it for this app
                \App\Models\Role::create([
                    'key' => $roleKey,
                    'app_id' => $app->id,
                    'name' => $info['name'] ?? ucfirst($roleKey),
                    'description' => $info['description'] ?? '',
                    'is_global' => false,
                ]);
            }

            $this->dispatch('notify', message: "Successfully synced " . count($roles) . " roles from {$app->name}.");

        } catch (\Exception $e) {
            $this->dispatch('notify', message: 'Sync Error: ' . $e->getMessage(), type: 'error');
        }
    }

    public function save(AuditService $audit)
    {
        $this->validate();

        $data = [
            'name' => $this->name,
            'slug' => $this->slug,
            'domain' => $this->domain,
            'sync_token' => $this->sync_token,
            'status' => $this->status,
        ];

        if ($this->icon) {
            $data['icon'] = $this->icon->store('app-icons', 'public');
        }

        if ($this->isEditing) {
            $app = App::findOrFail($this->editId);
            $oldValues = $app->toArray();

            $app->update($data);

            $audit->log('app.updated', 'Apps', $app, ['old' => $oldValues, 'new' => $app->toArray()]);
            $this->dispatch('notify', message: 'Workspace updated successfully.');
        } else {
            $app = App::create($data);

            $audit->log('app.created', 'Apps', $app, ['new' => $app->toArray()]);
            $this->dispatch('notify', message: 'Workspace created successfully.');
        }

        $this->showModal = false;
        $this->resetForm();
    }

    public function delete($id, AuditService $audit)
    {
        $app = App::findOrFail($id);
        $audit->log('app.deleted', 'Apps', $app, ['old' => $app->toArray()]);
        $app->delete();
        $this->dispatch('notify', message: 'Workspace deleted successfully.');
    }

    public function resetForm()
    {
        $this->name = '';
        $this->slug = '';
        $this->domain = '';
        $this->sync_token = '';
        $this->status = 'active';
        $this->icon = null;
        $this->existingIcon = null;
        $this->editId = null;
        $this->resetValidation();
    }

    public function render()
    {
        return view('livewire.admin.app-manager', [
            'apps' => App::latest()->paginate(10)
        ])->layout('layouts.app');
    }
}
