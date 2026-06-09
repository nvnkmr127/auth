<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\ApiToken;

class ApiTokenMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $token = $request->bearerToken();

        if (!$token) {
            return response()->json(['message' => 'Unauthenticated.'], 401);
        }

        $apiToken = ApiToken::where('token', hash('sha256', $token))
            ->where(function ($query) {
                $query->whereNull('expires_at')
                      ->orWhere('expires_at', '>', now());
            })
            ->with('user')
            ->first();

        if (!$apiToken || !$apiToken->user) {
            return response()->json(['message' => 'Unauthenticated.'], 401);
        }

        // Update last used at
        $apiToken->update(['last_used_at' => now()]);

        // Authenticate the user for the current request
        auth()->login($apiToken->user);

        return $next($request);
    }
}
