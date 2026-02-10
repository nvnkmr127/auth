<?php

namespace App\Livewire\Admin;

use App\Models\App;
use App\Models\Role;
use App\Models\User;
use App\Models\UserAppAccess;
use Livewire\Component;

class UserAppAccessManager extends Component
{
    public User $user;
    public $appAccesses = [];

    public function mount(User $user)
    {
        $this->user = $user;
        $this->loadAccesses();
    }

    public function loadAccesses()
    {
        $apps = App::where('status', 'active')->get();
        // Pre-fetch accesses
        $existingAccesses = $this->user->appAccesses->keyBy('app_id');

        $this->appAccesses = $apps->map(function ($app) use ($existingAccesses) {
            $access = $existingAccesses->get($app->id);
            return [
                'app_id' => $app->id,
                'app_name' => $app->name,
                'has_access' => (bool) $access,
                'role_id' => $access ? $access->role_id : null,
                'status' => $access ? $access->status : 'active',
            ];
        })->keyBy('app_id')->toArray();
    }

    public function toggleAccess($appId)
    {
        if (!isset($this->appAccesses[$appId]))
            return;

        $hasAccess = $this->appAccesses[$appId]['has_access'];

        if ($hasAccess) {
            // Revoke
            UserAppAccess::where('user_id', $this->user->id)
                ->where('app_id', $appId)
                ->delete();
            $this->appAccesses[$appId]['has_access'] = false;
            $this->appAccesses[$appId]['role_id'] = null;
        } else {
            // Grant with default role (if available) or require selection
            // For now, let's look for a default role
            $defaultRole = Role::where('app_id', $appId)->orWhere('is_global', true)->first();

            if (!$defaultRole) {
                // Prevent enabling if no roles exist? Or handle gracefully.
                // Ideally we show an error, but for now fallback null (will look like pending setup)
            }

            $roleId = $defaultRole ? $defaultRole->id : null;

            if ($roleId) {
                UserAppAccess::create([
                    'user_id' => $this->user->id,
                    'app_id' => $appId,
                    'role_id' => $roleId,
                    'status' => 'active'
                ]);

                $this->appAccesses[$appId]['has_access'] = true;
                $this->appAccesses[$appId]['role_id'] = $roleId;
            }
        }
    }

    public function updateRole($appId, $roleId)
    {
        if (!$this->appAccesses[$appId]['has_access'])
            return;

        UserAppAccess::updateOrCreate(
            ['user_id' => $this->user->id, 'app_id' => $appId],
            ['role_id' => $roleId]
        );
        $this->appAccesses[$appId]['role_id'] = $roleId;
    }

    public function render()
    {
        $apps = App::with('roles')->where('status', 'active')->get();
        $globalRoles = Role::where('is_global', true)->get();

        return view('livewire.admin.user-app-access-manager', [
            'apps' => $apps,
            'globalRoles' => $globalRoles,
        ])->layout('layouts.app');
    }
}
