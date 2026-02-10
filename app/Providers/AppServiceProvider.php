<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        \Illuminate\Support\Facades\Gate::before(function ($user, $ability) {
            if ($user->isAdmin()) {
                return true;
            }
        });

        \Illuminate\Support\Facades\Gate::define('access-admin', function ($user) {
            return $user->isAdmin();
        });

        // Use hasPermission for all other string-based abilities
        \Illuminate\Support\Facades\Gate::after(function ($user, $ability) {
            return $user->hasPermission($ability);
        });
    }
}
