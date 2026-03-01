<?php

use App\Livewire\Auth\LoginForm;
use Illuminate\Support\Facades\Auth;
use App\Livewire\AppSelector;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Cache\RateLimiting\Limit;

Route::get('/', function () {
    return view('welcome');
});

// Apply rate limiting to login route
Route::get('/login', LoginForm::class)->name('login')->middleware(['guest', 'throttle:login']);

Route::match(['get', 'post'], '/logout', function (Illuminate\Http\Request $request) {
    Auth::logout();
    session()->invalidate();
    session()->regenerateToken();

    $redirect = $request->query('redirect');

    // Only allow redirects to whitelisted domains to prevent open redirect attacks
    if ($redirect) {
        $allowedDomains = config('app.allowed_redirect_domains', []);
        $parsedUrl = parse_url($redirect);
        $redirectDomain = $parsedUrl['host'] ?? null;

        // Validate redirect is to an allowed domain or relative path
        if ($redirectDomain) {
            if (in_array($redirectDomain, $allowedDomains)) {
                return redirect()->away($redirect);
            }
        } elseif (str_starts_with($redirect, '/')) {
            // Allow relative paths
            return redirect($redirect);
        }
    }

    return redirect('/');
})->name('logout');

Route::get('/dashboard', \App\Livewire\Dashboard::class)->name('dashboard')->middleware('auth');

Route::get('/apps', AppSelector::class)->name('apps.index')->middleware('auth');

Route::prefix('profile')->middleware(['auth'])->name('profile.')->group(function () {
    Route::get('/api-tokens', \App\Livewire\Profile\ApiTokens::class)->name('api-tokens');
    Route::get('/security', \App\Livewire\Profile\Security::class)->name('security');
    Route::get('/devices', \App\Livewire\Profile\Devices::class)->name('devices');
});

Route::prefix('admin')->middleware(['auth', \App\Http\Middleware\AdminMiddleware::class])->name('admin.')->group(function () {
    Route::get('/users', \App\Livewire\Admin\UserList::class)->name('users');
    Route::get('/users/{user}/apps', \App\Livewire\Admin\UserAppAccessManager::class)->name('user.apps');
    Route::get('/audit-logs', \App\Livewire\Admin\AuditLogViewer::class)->name('audit-logs');
    Route::get('/roles', \App\Livewire\Admin\RoleManager::class)->name('roles');
    Route::get('/permissions', \App\Livewire\Admin\PermissionManager::class)->name('permissions');
    Route::get('/apps', \App\Livewire\Admin\AppManager::class)->name('apps');
    Route::get('/sso-sessions', \App\Livewire\Admin\SsoSessions::class)->name('sso-sessions');
    Route::get('/settings', \App\Livewire\Admin\SettingsManager::class)->name('settings');
});

Route::get('/sso/callback', \App\Livewire\SsoLoginHandler::class)->name('sso.callback');