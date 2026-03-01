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

            // Open in new tab via browser event
            $this->dispatch('open-url', url: $url);
            return;

        } catch (\Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException $e) {
            $this->addError('access', $e->getMessage());
        } catch (\Exception $e) {
            $errorMessage = $e->getMessage();
            \Illuminate\Support\Facades\Log::channel('single')->error("SSO Error: $errorMessage", [
                'user_id' => Auth::id(),
                'ip' => request()->ip(),
                'trace' => $e->getTraceAsString()
            ]);

            // Don't expose debug info or user email to the client
            $this->addError('access', 'Unable to access the application. Please try again or contact support if the issue persists.');
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
