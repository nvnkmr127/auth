<?php

namespace App\Livewire\Profile;

use App\Models\ApiToken;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Livewire\Component;

class ApiTokens extends Component
{
    public $name = '';
    public $plainTextToken = null;
    public $showTokenModal = false;

    public function createToken()
    {
        $this->validate([
            'name' => 'required|string|max:255',
        ]);

        $token = Str::random(40);

        Auth::user()->apiTokens()->create([
            'name' => $this->name,
            'token' => hash('sha256', $token),
            'expires_at' => now()->addMonths(6),
        ]);

        $this->plainTextToken = $token;
        $this->showTokenModal = true;
        $this->name = '';

        $this->dispatch('notify', message: 'API Token created successfully.');
    }

    public function revokeToken($id)
    {
        Auth::user()->apiTokens()->findOrFail($id)->delete();
        $this->dispatch('notify', message: 'API Token revoked.');
    }

    public function render()
    {
        return view('livewire.profile.api-tokens', [
            'tokens' => Auth::user()->apiTokens()->latest()->get(),
        ])->layout('layouts.app');
    }
}
