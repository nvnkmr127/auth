<?php

namespace App\Providers;

use App\Models\Setting;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class SettingsServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Prevent issues during migrations and CLI operations where DB is not ready
        try {
            if (!Schema::hasTable('settings')) {
                return;
            }

            // Optimization: Load all settings in one query
            $settings = Setting::all()->pluck('value', 'key');

            if ($settings->isEmpty()) {
                return;
            }

            // Override App Name
            if ($appName = $settings->get('app_name')) {
                Config::set('app.name', $appName);
            }

            // Override WhatsApp Config
            if ($webhookUrl = $settings->get('whatsapp_webhook_url')) {
                Config::set('services.whatsapp.webhook_url', $webhookUrl);
            }

            if ($apiKey = $settings->get('whatsapp_api_key')) {
                Config::set('services.whatsapp.api_key', $apiKey);
            }

            // Override Mail Config if exists
            if ($mailFrom = $settings->get('mail_from_address')) {
                Config::set('mail.from.address', $mailFrom);
            }

        } catch (\Exception $e) {
            // Fail gracefully if DB connection is not yet available
            return;
        }
    }
}
