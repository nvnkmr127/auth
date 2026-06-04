<?php

namespace App\Livewire\Admin;

use App\Models\User;
use App\Services\AuditService;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;
use Livewire\WithPagination;

class UserList extends Component
{
    use WithPagination;

    public $search = '';
    public $showModal = false;
    public $isEditing = false;
    public $editId = null;

    // Form Fields
    public $name = '';
    public $email = '';
    public $password = '';
    public $password_confirmation = '';
    public $phone = '';
    public $otp_enabled = false;
    public $selectedRoles = [];

    public function updatedSearch()
    {
        $this->resetPage();
    }

    protected function rules()
    {
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . ($this->editId ?? 'NULL'),
            'phone' => 'nullable|string|max:20',
            'otp_enabled' => 'boolean',
            'selectedRoles' => 'array',
            'selectedRoles.*' => 'exists:roles,id',
        ];

        if (!$this->isEditing) {
            $rules['password'] = 'required|string|min:8|confirmed';
        } else {
            $rules['password'] = 'nullable|string|min:8|confirmed';
        }

        return $rules;
    }

    public function create()
    {
        $this->resetForm();
        $this->showModal = true;
        $this->isEditing = false;
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        $this->editId = $user->id;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->phone = $user->phone;
        $this->otp_enabled = $user->otp_enabled;
        $this->password = '';
        $this->password_confirmation = '';
        $this->selectedRoles = $user->roles()
            ->whereNull('user_roles.app_id')
            ->pluck('roles.id')
            ->map(fn($id) => (string) $id)
            ->toArray();

        $this->isEditing = true;
        $this->showModal = true;
    }

    public function save(AuditService $audit)
    {
        $data = $this->validate();

        if ($this->isEditing) {
            $user = User::findOrFail($this->editId);
            $oldValues = $user->toArray();
            $oldRoles = $user->roles()->whereNull('user_roles.app_id')->pluck('name')->toArray();

            $updateData = [
                'name' => $this->name,
                'email' => $this->email,
                'phone' => $this->phone,
                'otp_enabled' => $this->otp_enabled,
            ];

            if ($this->password) {
                $updateData['password'] = Hash::make($this->password);
            }

            $user->update($updateData);

            // Sync global roles
            $globalRoles = \App\Models\Role::where('is_global', true)->get();
            $globalRoleIds = $globalRoles->pluck('id')->toArray();
            $selectedGlobalIds = array_intersect($this->selectedRoles, $globalRoleIds);

            \App\Models\UserRole::where('user_id', $user->id)
                ->whereNull('app_id')
                ->delete();

            foreach ($selectedGlobalIds as $roleId) {
                \App\Models\UserRole::create([
                    'user_id' => $user->id,
                    'role_id' => $roleId,
                    'app_id' => null,
                ]);
            }

            $newRoles = $user->roles()->whereNull('user_roles.app_id')->pluck('name')->toArray();

            $audit->log('user.updated', 'Users', $user, [
                'old' => array_merge($oldValues, ['global_roles' => $oldRoles]),
                'new' => array_merge($user->fresh()->toArray(), ['global_roles' => $newRoles])
            ]);
            $this->dispatch('notify', message: 'User updated successfully.');
        } else {
            $user = User::create([
                'name' => $this->name,
                'email' => $this->email,
                'phone' => $this->phone,
                'otp_enabled' => $this->otp_enabled,
                'password' => Hash::make($this->password),
            ]);

            // Sync global roles
            $globalRoles = \App\Models\Role::where('is_global', true)->get();
            $globalRoleIds = $globalRoles->pluck('id')->toArray();
            $selectedGlobalIds = array_intersect($this->selectedRoles, $globalRoleIds);

            foreach ($selectedGlobalIds as $roleId) {
                \App\Models\UserRole::create([
                    'user_id' => $user->id,
                    'role_id' => $roleId,
                    'app_id' => null,
                ]);
            }

            $newRoles = $user->roles()->whereNull('user_roles.app_id')->pluck('name')->toArray();

            $audit->log('user.created', 'Users', $user, [
                'new' => array_merge($user->toArray(), ['global_roles' => $newRoles])
            ]);
            $this->dispatch('notify', message: 'User created successfully.');
        }

        $this->showModal = false;
        $this->resetForm();
    }

    public function delete($id, AuditService $audit)
    {
        if ($id === auth()->id()) {
            $this->dispatch('notify', message: 'You cannot decommission your own identity node.', type: 'error');
            return;
        }

        $user = User::findOrFail($id);
        
        // Prevent deletion of admin accounts to avoid locking out all administrators
        if ($user->isAdmin()) {
            $this->dispatch('notify', message: 'Cannot delete administrator accounts. Please remove admin privileges first.', type: 'error');
            return;
        }

        $audit->log('user.deleted', 'Users', $user, ['old' => $user->toArray()]);
        $user->delete();
        $this->dispatch('notify', message: 'User identity purged successfully.');
    }

    public function resetForm()
    {
        $this->name = '';
        $this->email = '';
        $this->password = '';
        $this->password_confirmation = '';
        $this->phone = '';
        $this->otp_enabled = false;
        $this->selectedRoles = [];
        $this->editId = null;
        $this->resetValidation();
    }

    public function render()
    {
        $users = User::query()
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%')
                    ->orWhere('email', 'like', '%' . $this->search . '%');
            })
            ->with(['roles' => function ($query) {
                $query->whereNull('user_roles.app_id');
            }])
            ->withCount('appAccesses')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $globalRoles = \App\Models\Role::where('is_global', true)->get();

        return view('livewire.admin.user-list', [
            'users' => $users,
            'globalRoles' => $globalRoles,
        ])->layout('layouts.app');
    }
}
