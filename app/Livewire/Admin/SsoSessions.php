<?php

namespace App\Livewire\Admin;

use App\Models\Session;
use Livewire\Component;
use Livewire\WithPagination;

class SsoSessions extends Component
{
    use WithPagination;

    public function terminateSession($sessionId)
    {
        Session::where('id', $sessionId)->delete();
        $this->dispatch('notify', message: 'Session terminated successfully.', type: 'success');
    }

    public function purgeGuestSessions()
    {
        Session::whereNull('user_id')->delete();
        $this->dispatch('notify', message: 'Guest sessions cleared.', type: 'success');
    }

    public function render()
    {
        return view('livewire.admin.sso-sessions', [
            'sessions' => Session::with('user')
                ->whereNotNull('user_id')
                ->orderBy('last_activity', 'desc')
                ->paginate(15),
        ])->layout('layouts.app');
    }
}
