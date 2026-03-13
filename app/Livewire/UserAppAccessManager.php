<?php

namespace App\Livewire;

use App\Models\App;
use App\Models\User;
use App\Models\UserAppAccess;
use Livewire\Component;
use Livewire\WithPagination;

class UserAppAccessManager extends Component
{
    use WithPagination;

    public $search = '';
    public $selectedUser = null;
    public $showSlideOver = false;

    // Slide-over state
    public $accessSettings = [];

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function selectUser($userId)
    {
        $this->selectedUser = User::with('appAccesses')->find($userId);
        $this->showSlideOver = true;
        $this->loadAccessSettings();
    }

    public function closeSlideOver()
    {
        $this->showSlideOver = false;
        $this->selectedUser = null;
        $this->accessSettings = [];
    }

    public function loadAccessSettings()
    {
        $apps = App::where('status', 'active')->get();
        $this->accessSettings = [];

        foreach ($apps as $app) {
            $access = $this->selectedUser->appAccesses->where('app_id', $app->id)->first();

            $this->accessSettings[$app->id] = [
                'has_access' => (bool) $access,
                'role' => $access ? $access->role : 'viewer', // Default role
                'app_name' => $app->name,
                'app_slug' => $app->slug,
            ];
        }
    }

    public function toggleAccess($appId)
    {
        if (!$this->selectedUser)
            return;

        $hasAccess = $this->accessSettings[$appId]['has_access'];

        if ($hasAccess) {
            // Revoke access
            UserAppAccess::where('user_id', $this->selectedUser->id)
                ->where('app_id', $appId)
                ->delete();
            $this->accessSettings[$appId]['has_access'] = false;
        } else {
            // Grant access
            UserAppAccess::create([
                'user_id' => $this->selectedUser->id,
                'app_id' => $appId,
                'role' => $this->accessSettings[$appId]['role'],
                'status' => 'active'
            ]);
            $this->accessSettings[$appId]['has_access'] = true;
        }

        // Refresh selected user relation
        $this->selectedUser->refresh();
    }

    public function updateRole($appId, $newRole)
    {
        if (!$this->selectedUser)
            return;

        $this->accessSettings[$appId]['role'] = $newRole;

        // If user already has access, update the DB record immediately
        if ($this->accessSettings[$appId]['has_access']) {
            UserAppAccess::updateOrCreate(
                ['user_id' => $this->selectedUser->id, 'app_id' => $appId],
                ['role' => $newRole]
            );
        }
    }

    public function render()
    {
        $users = User::query()
            ->when($this->search, function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                    ->orWhere('email', 'like', '%' . $this->search . '%');
            })
            ->paginate(10);

        return view('livewire.user-app-access-manager', [
            'users' => $users,
            'apps' => App::where('status', 'active')->get()
        ])->layout('layouts.app');
    }
}
