<?php

namespace App\Livewire\Admin;

use App\Models\UsedSsoToken;
use Livewire\Component;
use Livewire\WithPagination;

class SsoSessions extends Component
{
    use WithPagination;

    public function purgeExpired()
    {
        UsedSsoToken::where('expires_at', '<', now())->delete();
        $this->dispatch('notify', message: 'Expired tokens purged from vault.');
    }

    public function render()
    {
        return view('livewire.admin.sso-sessions', [
            'sessions' => UsedSsoToken::latest()->paginate(15),
        ])->layout('layouts.app');
    }
}
