<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ClearOldSessions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sessions:partition';
    protected $description = 'Purge old sessions to optimize database size';

    public function handle()
    {
        $this->info('Starting session maintenance...');

        // 1. Clear Guest Sessions older than 48 hours
        $guestCount = \App\Models\Session::whereNull('user_id')
            ->where('last_activity', '<', now()->subHours(48)->timestamp)
            ->delete();
        $this->line("- Purged {$guestCount} expired guest sessions.");

        // 2. Clear All Sessions older than 30 days
        $totalCount = \App\Models\Session::where('last_activity', '<', now()->subDays(30)->timestamp)
            ->delete();
        $this->line("- Purged {$totalCount} sessions older than 30 days.");

        $this->info('Session maintenance completed.');
    }
}
