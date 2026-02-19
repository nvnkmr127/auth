<?php

namespace App\Livewire\Auth;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;

#[Layout('layouts.guest')]
class LoginForm extends Component
{
    #[Validate('required|email')]
    public string $email = '';

    #[Validate('required')]
    public string $password = '';

    public bool $remember = false;
    public bool $requiresOtp = false;

    #[Validate('required_if:requiresOtp,true|digits:6')]
    public string $otpCode = '';

    public function login(\App\Services\OtpService $otpService)
    {
        if ($this->requiresOtp) {
            return $this->verifyOtp($otpService);
        }

        $this->validateOnly('email');
        $this->validateOnly('password');

        if (Auth::validate(['email' => $this->email, 'password' => $this->password])) {
            $user = \App\Models\User::where('email', $this->email)->first();

            if (!$user->is_active) {
                $this->addError('email', 'This account is inactive.');
                return;
            }

            // Check if user requires OTP
            if ($user->otp_enabled) {
                $otpService->sendOtp($user);
                $this->requiresOtp = true;
                $this->dispatch('notify', message: 'Verification code sent.');
                return;
            }

            // Bypass OTP and login directly
            Auth::login($user, $this->remember);
            $this->captureLoginDetails($user);
            session()->regenerate();

            return redirect()->intended(route('apps.index'));
        }

        $this->addError('email', 'The provided credentials do not match our records.');
    }

    public function verifyOtp(\App\Services\OtpService $otpService)
    {
        $this->validateOnly('otpCode');

        $user = \App\Models\User::where('email', $this->email)->first();

        if ($otpService->verifyOtp($user, $this->otpCode)) {
            Auth::login($user, $this->remember);
            $this->captureLoginDetails($user);
            session()->regenerate();

            return redirect()->intended(route('apps.index'));
        }

        $this->addError('otpCode', 'Invalid or expired verification code.');
    }

    private function captureLoginDetails($user)
    {
        $ip = request()->ip();
        $userAgent = request()->userAgent();

        // Simple Geolocation (Optional/Best Effort)
        $location = 'Unknown';
        try {
            $response = \Illuminate\Support\Facades\Http::get("http://ip-api.com/json/{$ip}");
            if ($response->successful()) {
                $geo = $response->json();
                $location = ($geo['city'] ?? 'Unknown') . ', ' . ($geo['country'] ?? 'Unknown');
            }
        } catch (\Exception $e) {
            // Fail silently
        }

        $user->update([
            'last_login_at' => now(),
            'last_login_ip' => $ip,
            'last_login_device' => $userAgent,
            'last_login_location' => $location,
        ]);

        app(\App\Services\AuditService::class)->log(
            action: 'user.login',
            module: 'Auth',
            target: $user,
            userId: $user->id
        );
    }

    public function render()
    {
        return view('livewire.auth.login-form');
    }
}
