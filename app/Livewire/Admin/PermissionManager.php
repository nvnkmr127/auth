<?php

namespace App\Livewire\Admin;

use App\Models\Permission;
use App\Services\AuditService;
use Livewire\Component;
use Livewire\WithPagination;

class PermissionManager extends Component
{
    use WithPagination;

    public $showModal = false;
    public $isEditing = false;
    public $editId = null;

    // Form Fields
    public $name = '';
    public $key = '';
    public $group = '';

    protected function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'key' => 'required|string|max:255|unique:permissions,key,' . ($this->editId ?? 'NULL'),
            'group' => 'required|string|max:255',
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
        $permission = Permission::findOrFail($id);
        $this->editId = $permission->id;
        $this->name = $permission->name;
        $this->key = $permission->key;
        $this->group = $permission->group;

        $this->isEditing = true;
        $this->showModal = true;
    }

    public function save(AuditService $audit)
    {
        $this->validate();

        if ($this->isEditing) {
            $permission = Permission::findOrFail($this->editId);
            $oldValues = $permission->toArray();

            $permission->update([
                'name' => $this->name,
                'key' => $this->key,
                'group' => $this->group,
            ]);

            $audit->log('permission.updated', 'Permissions', $permission, ['old' => $oldValues, 'new' => $permission->toArray()]);
            $this->dispatch('notify', message: 'Permission updated successfully.');
        } else {
            $permission = Permission::create([
                'name' => $this->name,
                'key' => $this->key,
                'group' => $this->group,
            ]);

            $audit->log('permission.created', 'Permissions', $permission, ['new' => $permission->toArray()]);
            $this->dispatch('notify', message: 'Permission created successfully.');
        }

        $this->showModal = false;
        $this->resetForm();
    }

    public function delete($id, AuditService $audit)
    {
        $permission = Permission::findOrFail($id);

        // Check if permission is in use
        if ($permission->roles()->count() > 0) {
            $this->dispatch('notify', message: 'Permission is currently mapped to active roles and cannot be purged.', type: 'error');
            return;
        }

        $audit->log('permission.deleted', 'Permissions', $permission, ['old' => $permission->toArray()]);
        $permission->delete();
        $this->dispatch('notify', message: 'Permission protocol purged successfully.');
    }

    public function resetForm()
    {
        $this->name = '';
        $this->key = '';
        $this->group = '';
        $this->editId = null;
        $this->resetValidation();
    }

    public function render()
    {
        $permissions = Permission::query()
            ->orderBy('group')
            ->orderBy('key')
            ->paginate(20);

        return view('livewire.admin.permission-manager', [
            'permissions' => $permissions,
        ])->layout('layouts.app');
    }
}
