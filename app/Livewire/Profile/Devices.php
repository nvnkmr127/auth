<?php

namespace App\Livewire\Profile;

use App\Models\UserDevice;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Devices extends Component
{
    public function trustDevice($id)
    {
        $device = Auth::user()->devices()->findOrFail($id);
        $device->update(['is_trusted' => true]);
        $this->dispatch('notify', message: 'Device marked as trusted.');
    }

    public function removeDevice($id)
    {
        $device = Auth::user()->devices()->findOrFail($id);
        $device->delete();
        $this->dispatch('notify', message: 'Device removed and session revoked.');
    }

    public function render()
    {
        return view('livewire.profile.devices', [
            'devices' => Auth::user()->devices()->latest('last_active_at')->get()
        ])->layout('layouts.app');
    }
}
