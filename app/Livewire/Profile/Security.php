<?php

namespace App\Livewire\Profile;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Livewire\Component;

class Security extends Component
{
    public $recoveryCodes = [];
    public $showRecoveryModal = false;

    public function enable2fa()
    {
        $user = Auth::user();

        // Generate mock secret and recovery codes
        $user->two_factor_secret = Str::random(32);
        $codes = [];
        for ($i = 0; $i < 4; $i++) {
            $codes[] = Str::random(10) . '-' . Str::random(10);
        }
        $user->two_factor_recovery_codes = json_encode($codes);
        $user->two_factor_confirmed_at = now();
        $user->save();

        $this->recoveryCodes = $codes;
        $this->showRecoveryModal = true;

        $this->dispatch('notify', message: 'Two-Factor Authentication activated.');
    }

    public function disable2fa()
    {
        $user = Auth::user();
        $user->two_factor_secret = null;
        $user->two_factor_recovery_codes = null;
        $user->two_factor_confirmed_at = null;
        $user->save();

        $this->dispatch('notify', message: 'MFA protection deactivated.', type: 'error');
    }

    public function render()
    {
        return view('livewire.profile.security', [
            'user' => Auth::user(),
        ])->layout('layouts.app');
    }
}
