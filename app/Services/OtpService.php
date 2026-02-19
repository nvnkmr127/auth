<?php

namespace App\Services;

use App\Models\User;
use App\Models\UserOtp;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use App\Notifications\SendOtpNotification;

class OtpService
{
    /**
     * Generate and send a new OTP to the user.
     */
    public function sendOtp(User $user, string $type = 'login'): bool
    {
        // Invalidate old OTPs
        UserOtp::where('user_id', $user->id)
            ->where('type', $type)
            ->where('used', false)
            ->update(['used' => true]);

        // Generate 6-digit code
        $code = (string) rand(100000, 999999);

        // Create record
        UserOtp::create([
            'user_id' => $user->id,
            'code' => $code,
            'type' => $type,
            'expires_at' => now()->addMinutes(10),
        ]);

        // Send via Email
        $this->sendEmail($user, $code);

        // Send via WhatsApp if phone exists
        if ($user->phone) {
            $this->sendWhatsApp($user, $code);
        }

        return true;
    }

    /**
     * Verify the OTP code.
     */
    public function verifyOtp(User $user, string $code, string $type = 'login'): bool
    {
        $otp = UserOtp::where('user_id', $user->id)
            ->where('code', $code)
            ->where('type', $type)
            ->where('used', false)
            ->where('expires_at', '>', now())
            ->first();

        if ($otp) {
            $otp->update(['used' => true]);
            return true;
        }

        return false;
    }

    protected function sendEmail(User $user, string $code)
    {
        Mail::to($user->email)->send(new \App\Mail\OtpMail($code));
    }

    protected function sendWhatsApp(User $user, string $code)
    {
        $webhookUrl = config('services.whatsapp.webhook_url');
        $apiKey = config('services.whatsapp.api_key');

        if (!$webhookUrl)
            return;

        try {
            Http::withHeaders([
                'X-API-KEY' => $apiKey,
                'Content-Type' => 'application/json',
            ])->post($webhookUrl, [
                        'phone' => $user->phone,
                        'message' => "Your Auth Portal login code is: *{$code}*. Valid for 10 minutes.",
                        'template_name' => 'login_otp', // Assuming a template is required
                        'variables' => [$code]
                    ]);
        } catch (\Exception $e) {
            // Log error but don't block login
            \Illuminate\Support\Facades\Log::error("WhatsApp OTP failed for User {$user->id}: " . $e->getMessage());
        }
    }
}
