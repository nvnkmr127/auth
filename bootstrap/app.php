<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Support\Facades\RateLimiter;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->redirectUsersTo('/apps');
        $middleware->append(\App\Http\Middleware\CheckAppAccess::class);
        
        // Configure rate limiters
        RateLimiter::for('login', function (Illuminate\Http\Request $request) {
            return Limit::perMinute(5)->by($request->ip())
                ->response(function () {
                    return response()->json(['message' => 'Too many login attempts. Please try again later.'], 429);
                });
        });
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
