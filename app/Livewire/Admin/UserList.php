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

        $this->isEditing = true;
        $this->showModal = true;
    }

    public function save(AuditService $audit)
    {
        $data = $this->validate();

        if ($this->isEditing) {
            $user = User::findOrFail($this->editId);
            $oldValues = $user->toArray();

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

            $audit->log('user.updated', 'Users', $user, ['old' => $oldValues, 'new' => $user->toArray()]);
            $this->dispatch('notify', message: 'User updated successfully.');
        } else {
            $user = User::create([
                'name' => $this->name,
                'email' => $this->email,
                'phone' => $this->phone,
                'otp_enabled' => $this->otp_enabled,
                'password' => Hash::make($this->password),
            ]);

            $audit->log('user.created', 'Users', $user, ['new' => $user->toArray()]);
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
            ->withCount('appAccesses')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('livewire.admin.user-list', [
            'users' => $users,
        ])->layout('layouts.app');
    }
}
