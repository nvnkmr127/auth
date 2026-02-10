<?php

namespace App\Livewire\Admin;

use App\Models\App;
use App\Services\AuditService;
use Livewire\Component;
use Livewire\WithPagination;

class AppManager extends Component
{
    use WithPagination;

    public $showModal = false;
    public $isEditing = false;
    public $editId = null;

    // Form Fields
    public $name = '';
    public $slug = '';
    public $domain = '';
    public $status = 'active';

    protected function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:apps,slug,' . ($this->editId ?? 'NULL'),
            'domain' => 'nullable|url|max:255',
            'status' => 'required|in:active,inactive,maintenance',
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
        $this->status = $app->status;

        $this->isEditing = true;
        $this->showModal = true;
    }

    public function save(AuditService $audit)
    {
        $this->validate();

        if ($this->isEditing) {
            $app = App::findOrFail($this->editId);
            $oldValues = $app->toArray();

            $app->update([
                'name' => $this->name,
                'slug' => $this->slug,
                'domain' => $this->domain,
                'status' => $this->status,
            ]);

            $audit->log('app.updated', 'Apps', $app, ['old' => $oldValues, 'new' => $app->toArray()]);
            $this->dispatch('notify', message: 'Application updated successfully.');
        } else {
            $app = App::create([
                'name' => $this->name,
                'slug' => $this->slug,
                'domain' => $this->domain,
                'status' => $this->status,
            ]);

            $audit->log('app.created', 'Apps', $app, ['new' => $app->toArray()]);
            $this->dispatch('notify', message: 'Application created successfully.');
        }

        $this->showModal = false;
        $this->resetForm();
    }

    public function delete($id, AuditService $audit)
    {
        $app = App::findOrFail($id);
        $audit->log('app.deleted', 'Apps', $app, ['old' => $app->toArray()]);
        $app->delete();
        $this->dispatch('notify', message: 'Application deleted successfully.');
    }

    public function resetForm()
    {
        $this->name = '';
        $this->slug = '';
        $this->domain = '';
        $this->status = 'active';
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
