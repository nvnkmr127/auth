<?php

namespace App\Livewire\Admin;

use App\Models\App;
use App\Models\Permission;
use App\Models\Role;
use App\Services\AuditService;
use Livewire\Component;

class RoleManager extends Component
{
    public $showModal = false;
    public $isEditing = false;
    public $editId = null;

    // Form Fields
    public $name = '';
    public $key = '';
    public $description = '';
    public $is_global = false;
    public $app_id = null;
    public $selectedPermissions = [];

    protected function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'key' => 'required|string|max:255|unique:roles,key,' . ($this->editId ?? 'NULL'),
            'description' => 'nullable|string|max:500',
            'is_global' => 'boolean',
            'app_id' => 'nullable|exists:apps,id',
            'selectedPermissions' => 'array',
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
        $role = Role::with('permissions')->findOrFail($id);
        $this->editId = $role->id;
        $this->name = $role->name;
        $this->key = $role->key;
        $this->description = $role->description;
        $this->is_global = (bool) $role->is_global;
        $this->app_id = $role->app_id;
        $this->selectedPermissions = $role->permissions->pluck('id')->map(fn($id) => (string) $id)->toArray();

        $this->isEditing = true;
        $this->showModal = true;
    }

    public function save(AuditService $audit)
    {
        $this->validate();

        // Ensure consistency: Global roles cannot have app_id, App roles must have app_id or be intended for specific use
        if ($this->is_global) {
            $this->app_id = null;
        }

        if ($this->isEditing) {
            $role = Role::findOrFail($this->editId);
            $oldValues = $role->toArray();

            $role->update([
                'name' => $this->name,
                'key' => $this->key,
                'description' => $this->description,
                'is_global' => $this->is_global,
                'app_id' => $this->app_id,
            ]);

            $role->permissions()->sync($this->selectedPermissions);

            $audit->log('role.updated', 'Roles', $role, ['old' => $oldValues, 'new' => $role->toArray()]);
        } else {
            $role = Role::create([
                'name' => $this->name,
                'key' => $this->key,
                'description' => $this->description,
                'is_global' => $this->is_global,
                'app_id' => $this->app_id,
            ]);

            $role->permissions()->sync($this->selectedPermissions);

            $audit->log('role.created', 'Roles', $role, ['new' => $role->toArray()]);
        }

        $this->showModal = false;
        $this->resetForm();
    }

    public function delete($id, AuditService $audit)
    {
        $role = Role::findOrFail($id);
        // Prevent deleting critical system roles if needed, e.g. super_admin
        if (in_array($role->key, ['super_admin'])) {
            // Flash error
            return;
        }

        $audit->log('role.deleted', 'Roles', $role, ['old' => $role->toArray()]);
        $role->delete();
    }

    public function resetForm()
    {
        $this->name = '';
        $this->key = '';
        $this->description = '';
        $this->is_global = false;
        $this->app_id = null;
        $this->selectedPermissions = [];
        $this->editId = null;
        $this->resetValidation();
    }

    public function render()
    {
        $roles = Role::withCount('users', 'permissions')->get();
        $permissions = Permission::all()->groupBy('group');
        $apps = App::where('status', 'active')->get();

        return view('livewire.admin.role-manager', [
            'roles' => $roles,
            'permissionGroups' => $permissions,
            'apps' => $apps,
        ])->layout('layouts.app');
    }
}
