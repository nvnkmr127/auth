<?php

namespace App\Livewire\Admin;

use App\Models\App;
use App\Models\AuditLog;
use App\Models\Session;
use App\Models\User;
use App\Models\UserOtp;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class SystemHealth extends Component
{
    public function render()
    {
        // 1. JWT Key Rotation Status
        $privateKeyPath = config('jwt.private_key');
        $publicKeyPath = config('jwt.public_key');
        
        $jwtKeysStatus = 'Missing';
        $keyAgeDays = null;
        if (file_exists($privateKeyPath) && file_exists($publicKeyPath)) {
            $jwtKeysStatus = 'Healthy';
            $keyAgeDays = round((time() - filemtime($privateKeyPath)) / (60 * 60 * 24));
            
            // Flag if older than 90 days
            if ($keyAgeDays > 90) {
                $jwtKeysStatus = 'Needs Rotation';
            }
        }

        // 2. Failed Login Count (Last 24h)
        $failedLogins24h = AuditLog::whereIn('action', ['user.login_failed', 'user.sso_login_failed'])
            ->where('created_at', '>=', now()->subDay())
            ->count();

        // 3. Active SSO Sessions
        // Uses the Session model which filters authenticated sessions
        $activeSessions = Session::whereNotNull('user_id')
            ->where('last_activity', '>=', now()->subHours(2)->timestamp)
            ->count();

        // 4. OTP Delivery Success Rate (Last 7 Days)
        $totalOtps = UserOtp::where('created_at', '>=', now()->subDays(7))->count();
        $usedOtps = UserOtp::where('used', true)->where('created_at', '>=', now()->subDays(7))->count();
        $otpSuccessRate = $totalOtps > 0 ? round(($usedOtps / $totalOtps) * 100, 1) : 100;

        // 5. General System Stats
        $totalUsers = User::count();
        $totalApps = App::count();
        $activeApps = App::where('status', 'active')->count();
        
        // 6. Database Connection Status
        $dbStatus = 'Disconnected';
        try {
            DB::connection()->getPdo();
            $dbStatus = 'Connected';
        } catch (\Exception $e) {
            $dbStatus = 'Error';
        }

        return view('livewire.admin.system-health', [
            'jwtKeysStatus' => $jwtKeysStatus,
            'keyAgeDays' => $keyAgeDays,
            'failedLogins24h' => $failedLogins24h,
            'activeSessions' => $activeSessions,
            'otpSuccessRate' => $otpSuccessRate,
            'totalUsers' => $totalUsers,
            'totalApps' => $totalApps,
            'activeApps' => $activeApps,
            'dbStatus' => $dbStatus,
            'phpVersion' => phpversion(),
            'laravelVersion' => app()->version(),
        ])->layout('layouts.app');
    }
}
