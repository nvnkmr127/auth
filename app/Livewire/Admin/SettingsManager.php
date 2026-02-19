<?php

namespace App\Livewire\Admin;

use Livewire\Component;

class SettingsManager extends Component
{
    public string $webhookUrl = '';
    public string $apiKey = '';
    public string $testPhone = '';

    // Frontend Branding Settings (Simulated for "No Backend" request)
    public string $appName = '';
    public string $mascotName = 'StudioBot';
    public bool $mascotEnabled = true;

    public function mount()
    {
        $this->webhookUrl = config('services.whatsapp.webhook_url') ?? '';
        $this->apiKey = config('services.whatsapp.api_key') ?? '';
        $this->appName = config('app.name');
    }

    public function saveBranding()
    {
        $this->dispatch('notify', message: 'Branding updated successfully in frontend.');
    }

    public function save()
    {
        $this->dispatch('notify', message: 'Integration settings saved (Update .env for persistence).');
    }

    public function testWebhook()
    {
        if (empty($this->webhookUrl)) {
            $this->dispatch('notify', message: 'Please provide a Webhook URL first.', type: 'error');
            return;
        }

        $phone = $this->testPhone ?: '919876543210'; // Default if none provided

        try {
            $response = \Illuminate\Support\Facades\Http::withHeaders([
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
                $this->dispatch('notify', message: "Webhook failed with status [{$status}]. Check configuration.", type: 'error');
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
