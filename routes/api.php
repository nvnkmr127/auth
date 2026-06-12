<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Route::post('/verify-credentials', function (Request $request) {
    $request->validate([
        'email' => 'required|email',
        'password' => 'required',
    ]);

    $valid = Auth::validate([
        'email' => $request->email,
        'password' => $request->password,
    ]);

    return response()->json([
        'valid' => $valid,
    ]);
});

Route::post('/login', function (Request $request) {
    $request->validate([
        'email' => 'required|email',
        'password' => 'required',
        'device_name' => 'nullable|string'
    ]);

    $user = \App\Models\User::where('email', $request->email)->first();

    if (!$user || !\Illuminate\Support\Facades\Hash::check($request->password, $user->password)) {
        return response()->json([
            'message' => 'Invalid credentials'
        ], 401);
    }

    if (!$user->is_active) {
        return response()->json([
            'message' => 'Account is inactive'
        ], 403);
    }

    // Generate token
    $tokenString = \Illuminate\Support\Str::random(60);
    
    $token = $user->apiTokens()->create([
        'name' => $request->device_name ?? 'Mobile App',
        'token' => hash('sha256', $tokenString),
        'expires_at' => now()->addDays(30),
    ]);

    return response()->json([
        'token' => $tokenString,
        'user' => [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
        ]
    ]);
});

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

// Mobile Multi-CRM Switching
use App\Http\Controllers\Api\MobileCrmController;

Route::prefix('mobile/v1')->group(function () {
    Route::get('/available-crms', [MobileCrmController::class, 'availableCrms']);
    Route::post('/switch-crm', [MobileCrmController::class, 'switchCrm']);
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
