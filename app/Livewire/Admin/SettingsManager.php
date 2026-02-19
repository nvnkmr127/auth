<?php

namespace App\Livewire\Admin;

use App\Models\Setting;
use Livewire\Component;
use Illuminate\Support\Facades\Http;

class SettingsManager extends Component
{
    public string $webhookUrl = '';
    public string $apiKey = '';
    public string $testPhone = '';

    public string $appName = '';
    public bool $mascotEnabled = true;

    public function mount()
    {
        // Load from Database first, fallback to Config (which reads from .env)
        $this->webhookUrl = Setting::get('whatsapp_webhook_url', config('services.whatsapp.webhook_url') ?? '');
        $this->apiKey = Setting::get('whatsapp_api_key', config('services.whatsapp.api_key') ?? '');
        $this->appName = Setting::get('app_name', config('app.name'));
        $this->mascotEnabled = (bool) Setting::get('mascot_enabled', true);
    }

    public function save()
    {
        Setting::set('whatsapp_webhook_url', $this->webhookUrl);
        Setting::set('whatsapp_api_key', $this->apiKey);

        $this->dispatch('notify', message: 'Integration settings saved and persisted.');
    }

    public function saveBranding()
    {
        Setting::set('app_name', $this->appName);
        Setting::set('mascot_enabled', $this->mascotEnabled);

        $this->dispatch('notify', message: 'Branding settings saved and persisted.');
    }

    public function testWebhook()
    {
        if (empty($this->webhookUrl)) {
            $this->dispatch('notify', message: 'Please provide a Webhook URL first.', type: 'error');
            return;
        }

        $phone = $this->testPhone ?: '919876543210';

        try {
            $response = Http::withHeaders([
                'X-API-KEY' => $this->apiKey,
            ])->post($this->webhookUrl, [
                        'phone' => $phone,
                        'message' => 'This is a test OTP from OneStudio Portal: 123456',
                        'template_name' => 'test_otp',
                        'variables' => ['123456']
                    ]);

            if ($response->successful()) {
                $this->dispatch('notify', message: 'Test webhook dispatched successfully to ' . $phone);
            } else {
                $status = $response->status();
                $this->dispatch('notify', message: "Webhook failed with status [{$status}].", type: 'error');
            }
        } catch (\Exception $e) {
            $this->dispatch('notify', message: 'Endpoint unreachable: ' . $e->getMessage(), type: 'error');
        }
    }

    public function render()
    {
        return view('livewire.admin.settings-manager')
            ->layout('layouts.app');
    }
}
