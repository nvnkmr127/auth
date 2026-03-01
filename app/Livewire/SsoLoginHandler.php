<?php

namespace App\Livewire;

use App\Models\User;
use App\Services\JwtVerificationService;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use RuntimeException;

class SsoLoginHandler extends Component
{
    public $error = '';

    public function mount(JwtVerificationService $jwtService, \App\Services\AuditService $audit)
    {
        $token = request()->query('token');

        if (empty($token) || !is_string($token)) {
            $this->error = 'No token provided or invalid format.';
            return;
        }

        try {
            // 1. Verify Token
            $payload = $jwtService->verifyToken($token);

            // 2. Find or Update User
            $user = User::updateOrCreate(
                ['email' => $payload->email],
                [
                    'name' => $payload->name ?? 'SSO User',
                    'is_active' => true,
                    'email_verified_at' => now() // Mark as verified since SSO provider verified the email
                ]
            );

            // Set a random password only for new users
            if (!$user->wasRecentlyCreated && !$user->password) {
                $user->update(['password' => bcrypt(str()->random(16))]);
            } elseif ($user->wasRecentlyCreated) {
                $user->update(['password' => bcrypt(str()->random(16))]);
            }

            if (!$user->is_active) {
                $this->error = 'Account is suspended.';
                $audit->log('user.sso_login_failed', 'IdentityHub', null, [
                    'email' => $payload->email,
                    'reason' => 'Account suspended',
                    'ip' => request()->ip()
                ]);
                return;
            }

            // Sync Role from Token
            if (isset($payload->role)) {
                $user->syncSsoRole($payload->role);
            }

            // 3. Login
            Auth::login($user);

            // 4. Audit
            $audit->log('user.sso_login', 'IdentityHub', $user, [
                'jti' => $payload->jti ?? 'unknown',
                'ip' => request()->ip()
            ]);

            // 5. Redirect
            return redirect()->intended('/dashboard');

        } catch (RuntimeException $e) {
            \Illuminate\Support\Facades\Log::error('SSO Login Failed - Runtime Exception', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            $this->error = $e->getMessage();
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('SSO Login Failed - Unexpected Error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            $this->error = 'SSO authentication failed. Please try again or contact support.';
        }
    }

    public function render()
    {
        return view('livewire.sso-login-handler')->layout('layouts.guest');
    }
}
