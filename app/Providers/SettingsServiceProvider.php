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
        // Prevent issues during migrations
        if (!Schema::hasTable('settings')) {
            return;
        }

        // Override App Name
        if ($appName = Setting::get('app_name')) {
            Config::set('app.name', $appName);
        }

        // Override WhatsApp Config
        if ($webhookUrl = Setting::get('whatsapp_webhook_url')) {
            Config::set('services.whatsapp.webhook_url', $webhookUrl);
        }

        if ($apiKey = Setting::get('whatsapp_api_key')) {
            Config::set('services.whatsapp.api_key', $apiKey);
        }
    }
}
