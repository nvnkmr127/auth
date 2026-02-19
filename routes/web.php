<?php

use App\Livewire\Auth\LoginForm;
use Illuminate\Support\Facades\Auth;
use App\Livewire\AppSelector;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/login', LoginForm::class)->name('login')->middleware('guest');

Route::match(['get', 'post'], '/logout', function (Illuminate\Http\Request $request) {
    Auth::logout();
    session()->invalidate();
    session()->regenerateToken();

    $redirect = $request->query('redirect');
    if ($redirect && filter_var($redirect, FILTER_VALIDATE_URL)) {
        return redirect()->away($redirect);
    }

    return redirect('/');
})->name('logout');

Route::get('/dashboard', \App\Livewire\Dashboard::class)->name('dashboard')->middleware('auth');

Route::get('/apps', AppSelector::class)->name('apps.index')->middleware('auth');

Route::prefix('profile')->middleware(['auth'])->name('profile.')->group(function () {
    Route::get('/api-tokens', \App\Livewire\Profile\ApiTokens::class)->name('api-tokens');
    Route::get('/security', \App\Livewire\Profile\Security::class)->name('security');
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