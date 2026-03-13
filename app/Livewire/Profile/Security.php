<?php

namespace App\Livewire\Profile;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Livewire\Component;
use PragmaRX\Google2FA\Google2FA;

class Security extends Component
{
    public $recoveryCodes = [];
    public $showRecoveryModal = false;
    public $twoFactorSecret = '';
    public $twoFactorQrCode = '';

    public function enable2fa()
    {
        $user = Auth::user();
        
        // Check if already enabled
        if ($user->two_factor_confirmed_at) {
            $this->dispatch('notify', message: 'Two-Factor Authentication is already enabled.', type: 'warning');
            return;
        }

        // Generate proper TOTP secret using google2fa
        $google2fa = new Google2FA();
        $secret = $google2fa->generateSecretKey();
        
        $user->two_factor_secret = $secret;
        
        // Generate proper recovery codes
        $codes = [];
        for ($i = 0; $i < 8; $i++) {
            $codes[] = strtoupper(Str::random(4) . '-' . Str::random(4) . '-' . Str::random(4));
        }
        $user->two_factor_recovery_codes = json_encode($codes);
        $user->save();

        // Generate QR code for authenticator app
        $this->twoFactorQrCode = $google2fa->getQRCodeUrl(
            config('app.name'),
            $user->email,
            $secret
        );
        $this->twoFactorSecret = $secret;

        $this->recoveryCodes = $codes;
        $this->showRecoveryModal = true;

        $this->dispatch('notify', message: 'Two-Factor Authentication setup. Please scan the QR code and confirm.');
    }

    public function confirm2fa(string $code)
    {
        $user = Auth::user();
        $google2fa = new Google2FA();
        
        $valid = $google2fa->verifyKey($user->two_factor_secret, $code);
        
        if ($valid) {
            $user->two_factor_confirmed_at = now();
            $user->save();
            $this->dispatch('notify', message: 'Two-Factor Authentication confirmed and activated.');
        } else {
            $this->dispatch('notify', message: 'Invalid verification code. Please try again.', type: 'error');
        }
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
