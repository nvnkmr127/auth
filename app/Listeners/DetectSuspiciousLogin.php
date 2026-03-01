<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Login;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class DetectSuspiciousLogin
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(Login $event): void
    {
        /** @var \App\Models\User $user */
        $user = $event->user;
        $request = request();
        $ip = $request->ip();
        $ua = $request->userAgent();

        if (!$user || !$ip)
            return;

        $device = \App\Models\UserDevice::where('user_id', $user->id)
            ->where('ip_address', $ip)
            ->where('user_agent', $ua)
            ->first();

        if (!$device) {
            // New device detected
            \App\Models\UserDevice::create([
                'user_id' => $user->id,
                'ip_address' => $ip,
                'user_agent' => $ua,
                'last_active_at' => now(),
            ]);

            \Illuminate\Support\Facades\Log::warning('Suspicious Activity: New login device detected', [
                'user_id' => $user->id,
                'email' => $user->email,
                'ip' => $ip,
                'ua' => $ua
            ]);

            // Optional: Send email notification to user here
        } else {
            $device->update(['last_active_at' => now()]);
        }
    }
}
