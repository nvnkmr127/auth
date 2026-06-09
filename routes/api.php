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
