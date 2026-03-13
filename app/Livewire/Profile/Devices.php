<?php

namespace App\Livewire\Profile;

use App\Models\UserDevice;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Devices extends Component
{
    public function mount()
    {
        $user = Auth::user();
        $ip = request()->ip();
        $ua = request()->userAgent();

        if ($user && $ip) {
            // Ensure the current device is registered if it's the first time
            UserDevice::firstOrCreate(
                [
                    'user_id' => $user->id,
                    'ip_address' => $ip,
                    'user_agent' => $ua,
                ],
                [
                    'last_active_at' => now(),
                    'is_trusted' => true // Trust the first device automatically
                ]
            );
        }
    }

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
