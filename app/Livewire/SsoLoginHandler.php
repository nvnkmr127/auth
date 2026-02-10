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

            // 2. Find or Create User
            $user = User::firstOrCreate(
                ['email' => $payload->email],
                [
                    'name' => $payload->name ?? 'SSO User',
                    'password' => bcrypt(str()->random(16)),
                    'is_active' => true
                ]
            );

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
            return redirect()->intended('/apps');

        } catch (RuntimeException $e) {
            $this->error = "SSO Login Failed: " . $e->getMessage();
        } catch (\Exception $e) {
            $this->error = "An unexpected error occurred.";
        }
    }

    public function render()
    {
        return view('livewire.sso-login-handler')->layout('layouts.guest');
    }
}
