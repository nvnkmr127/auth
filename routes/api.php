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
