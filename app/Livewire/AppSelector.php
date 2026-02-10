<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\App;
use Illuminate\Support\Facades\Auth;

class AppSelector extends Component
{
    public function selectApp($appId, \App\Services\SsoTokenService $ssoService)
    {
        try {
            $user = Auth::user();
            $app = App::findOrFail($appId);

            // Generate SSO URL (validates access internally)
            $url = $ssoService->generateSsoUrl($user, $app);

            // Redirect user
            return redirect()->away($url);

        } catch (\Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException $e) {
            $this->addError('access', $e->getMessage());
        } catch (\Exception $e) {
            $user = Auth::user();
            $isAdmin = $user ? ($user->isAdmin() ? 'YES' : 'NO') : 'N/A';
            $debug = "User: " . ($user->email ?? 'Guest') . ", Admin: " . $isAdmin;
            $errorMessage = $e->getMessage();
            \Illuminate\Support\Facades\Log::channel('single')->error("SSO Error: $errorMessage | Debug: $debug");
            file_put_contents(storage_path('logs/sso_debug.log'), date('Y-m-d H:i:s') . " - Error: $errorMessage | Debug: $debug\n", FILE_APPEND);

            $this->addError('access', 'Error: ' . $errorMessage . " | Debug: " . $debug);
        }
    }

    public function render()
    {
        $user = Auth::user();

        // Fetch all active apps
        $allApps = App::where('status', 'active')->get();

        // Fetch user's access
        $userAccessIds = $user->appAccesses()->pluck('app_id')->toArray();

        $isAdmin = $user->isAdmin();

        // Map apps to include 'has_access' flag
        $apps = $allApps->map(function ($app) use ($userAccessIds, $isAdmin) {
            $app->has_access = $isAdmin || in_array($app->id, $userAccessIds);
            return $app;
        });

        return view('livewire.app-selector', [
            'apps' => $apps
        ])->layout('layouts.app');
    }
}
