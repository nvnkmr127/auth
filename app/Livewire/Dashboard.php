<?php

namespace App\Livewire;

use App\Models\App;
use App\Models\AuditLog;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Dashboard extends Component
{
    public function logout()
    {
        Auth::logout();
        session()->invalidate();
        session()->regenerateToken();

        return redirect('/');
    }

    public function render()
    {
        $user = Auth::user();

        // Basic personal stats
        $stats = [
            'apps_count' => $user->appAccesses()->count(),
            'total_users' => User::count(),
            'total_apps' => App::count(),
            'recent_logs' => AuditLog::with('target')
                ->where('user_id', $user->id)
                ->latest()
                ->take(5)
                ->get()
        ];

        return view('livewire.dashboard', [
            'user' => $user,
            'stats' => $stats,
        ])->layout('layouts.app');
    }
}
