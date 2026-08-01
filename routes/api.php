<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth.api.token')->group(function () {
    Route::get('/user', function (Request $request) {
        return response()->json($request->user());
    });
});

// Mobile SSO Endpoints
use App\Http\Controllers\Api\MobileAuthController;

Route::prefix('mobile/v1/auth')->group(function () {
    Route::post('/login', [MobileAuthController::class, 'login']);
    Route::post('/refresh', [MobileAuthController::class, 'refresh']);
    Route::post('/logout', [MobileAuthController::class, 'logout']); // In production, add auth middleware
    Route::post('/revoke-session', [MobileAuthController::class, 'revokeSession']); // Admin / authenticated route
});

use App\Http\Controllers\Api\MobileWorkspaceController;
use App\Http\Controllers\Api\MobileUiConfigController;
use App\Http\Middleware\MobilePolicyMiddleware;

// The mobile token middleware is assumed to be auth.api.token based on existing implementation,
// but for the sake of completeness we apply it. If they use Sanctum/Passport, use auth:api or auth:sanctum.
// Using `auth.api.token` as seen in line 63.
Route::prefix('mobile/v1')->middleware(['auth.api.token', MobilePolicyMiddleware::class])->group(function () {
    
    Route::prefix('workspaces')->group(function () {
        Route::get('/', [MobileWorkspaceController::class, 'index']);
        Route::get('/{id}', [MobileWorkspaceController::class, 'show']);
        Route::post('/switch', [MobileWorkspaceController::class, 'switch']);
    });

    // Dynamic UI & App Registry (Phase 31-40)
    Route::get('/app-registry', [MobileUiConfigController::class, 'registry']);
    Route::get('/navigation', [MobileUiConfigController::class, 'navigation']);
    Route::get('/dashboard/widgets', [MobileUiConfigController::class, 'dashboard']);
    Route::post('/sync', [MobileUiConfigController::class, 'sync']);
    Route::get('/settings', [MobileUiConfigController::class, 'settings']);
});
