<?php

namespace App\Livewire\Admin;

use App\Models\AuditLog;
use Livewire\Component;
use Livewire\WithPagination;

class AuditLogViewer extends Component
{
    use WithPagination;

    public $search = '';
    public $module = '';

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedModule()
    {
        $this->resetPage();
    }

    public function render()
    {
        $logs = AuditLog::with(['user', 'target'])
            ->when($this->search, function ($query) {
                $query->where('action', 'like', '%' . $this->search . '%')
                    ->orWhereHas('user', function ($q) {
                        $q->where('name', 'like', '%' . $this->search . '%');
                    });
            })
            ->when($this->module, function ($query) {
                $query->where('module', $this->module);
            })
            ->latest()
            ->paginate(20);

        $modules = AuditLog::select('module')->distinct()->pluck('module');

        return view('livewire.admin.audit-log-viewer', [
            'logs' => $logs,
            'modules' => $modules,
        ])->layout('layouts.app');
    }
}
