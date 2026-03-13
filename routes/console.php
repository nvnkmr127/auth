<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;
use App\Models\UsedSsoToken;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::call(function () {
    try {
        $deletedCount = UsedSsoToken::where('expires_at', '<', now())->delete();
        \Illuminate\Support\Facades\Log::debug("Cleaned up {$deletedCount} expired SSO tokens.");
    } catch (\Exception $e) {
        \Illuminate\Support\Facades\Log::error('Failed to cleanup expired SSO tokens: ' . $e->getMessage());
    }
})->hourly();

Schedule::command('sessions:partition')->daily();
