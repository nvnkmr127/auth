<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\MobileDevice;
use App\Models\MobileSession;
use App\Models\MobileAuthLog;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Carbon\Carbon;

class MobileAuthController extends Controller
{
    private $jwtSecret;

    public function __construct()
    {
        $this->jwtSecret = config('app.key'); // Use app key or dedicated JWT_SECRET
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
            'device_uuid' => 'required|string',
            'device_name' => 'nullable|string',
            'platform' => 'nullable|string',
            'app_version' => 'nullable|string',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            $this->logAuthAction($user->id ?? null, 'failed_login', $request);
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        if (!$user->is_active) {
            $this->logAuthAction($user->id, 'failed_login_inactive', $request);
            return response()->json(['status' => 'inactive', 'message' => 'User account is disabled'], 403);
        }

        // Register or update device
        $device = MobileDevice::updateOrCreate(
            ['device_uuid' => $request->device_uuid],
            [
                'user_id' => $user->id,
                'device_name' => $request->device_name,
                'platform' => $request->platform,
                'app_version' => $request->app_version,
                'last_login' => now(),
            ]
        );

        $tokens = $this->generateTokens($user, $device);

        // Track session
        MobileSession::create([
            'user_id' => $user->id,
            'device_id' => $device->device_uuid,
            'device_name' => $device->device_name,
            'platform' => $device->platform,
            'access_token_hash' => hash('sha256', $tokens['access_token']),
            'refresh_token_hash' => hash('sha256', $tokens['refresh_token']),
            'last_activity' => now(),
            'expires_at' => now()->addDays(30), // Refresh token expiry
        ]);

        $this->logAuthAction($user->id, 'login', $request);

        return response()->json([
            'access_token' => $tokens['access_token'],
            'refresh_token' => $tokens['refresh_token'],
            'expires_in' => 3600, // 1 hour
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
            ]
        ]);
    }

    public function refresh(Request $request)
    {
        $request->validate([
            'refresh_token' => 'required|string',
            'device_uuid' => 'required|string',
        ]);

        $tokenHash = hash('sha256', $request->refresh_token);
        
        $session = MobileSession::where('refresh_token_hash', $tokenHash)
            ->where('device_id', $request->device_uuid)
            ->first();

        if (!$session || $session->expires_at < now()) {
            if ($session) {
                $this->logAuthAction($session->user_id, 'expired_session', $request);
                $session->delete();
            }
            return response()->json(['message' => 'Invalid or expired refresh token'], 401);
        }

        $user = User::find($session->user_id);
        
        if (!$user || !$user->is_active) {
            if ($session) {
                $this->logAuthAction($session->user_id, 'revoked_inactive', $request);
                $session->delete();
            }
            return response()->json(['status' => 'inactive', 'message' => 'User account is disabled'], 403);
        }

        $device = MobileDevice::where('device_uuid', $request->device_uuid)->first();

        $tokens = $this->generateTokens($user, $device);

        // Rotate token
        $session->update([
            'access_token_hash' => hash('sha256', $tokens['access_token']),
            'refresh_token_hash' => hash('sha256', $tokens['refresh_token']),
            'last_activity' => now(),
            'expires_at' => now()->addDays(30),
        ]);

        $this->logAuthAction($user->id, 'refresh', $request);

        return response()->json([
            'access_token' => $tokens['access_token'],
            'refresh_token' => $tokens['refresh_token'],
            'expires_in' => 3600,
        ]);
    }

    public function logout(Request $request)
    {
        $request->validate([
            'device_uuid' => 'required|string',
        ]);

        // Assuming user is authenticated via middleware and available in request
        $userId = $request->user()->id ?? null;

        if ($userId) {
            MobileSession::where('user_id', $userId)
                ->where('device_id', $request->device_uuid)
                ->delete();
                
            $this->logAuthAction($userId, 'logout', $request);
        }

        return response()->json(['message' => 'Logged out successfully']);
    }
    
    public function revokeSession(Request $request)
    {
        $request->validate([
            'session_id' => 'required|integer',
        ]);
        
        $userId = $request->user()->id ?? null;
        
        $session = MobileSession::where('id', $request->session_id)
            ->where('user_id', $userId) // Only allow revoking own session, unless admin
            ->first();
            
        if ($session) {
            $session->delete();
            $this->logAuthAction($userId, 'revoke', $request);
            return response()->json(['message' => 'Session revoked successfully']);
        }
        
        return response()->json(['message' => 'Session not found'], 404);
    }

    private function generateTokens($user, $device)
    {
        $payload = [
            'iss' => config('app.url'),
            'sub' => $user->id,
            'iat' => now()->timestamp,
            'exp' => now()->addHours(1)->timestamp,
            'device_uuid' => $device->device_uuid,
            'roles' => [], // To be populated from permissions service
        ];

        $accessToken = JWT::encode($payload, $this->jwtSecret, 'HS256');
        $refreshToken = Str::random(64);

        return [
            'access_token' => $accessToken,
            'refresh_token' => $refreshToken,
        ];
    }

    private function logAuthAction($userId, $action, $request)
    {
        MobileAuthLog::create([
            'user_id' => $userId,
            'action' => $action,
            'device_id' => $request->device_uuid ?? $request->header('X-Device-UUID'),
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);
    }
}
