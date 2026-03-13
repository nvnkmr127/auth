<?php

namespace App\Console\Commands;

use App\Models\UsedSsoToken;
use Illuminate\Console\Command;
use Carbon\Carbon;

class PruneSsoTokens extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sso:prune-tokens';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Remove expired SSO tokens from the used_sso_tokens table.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $count = UsedSsoToken::where('expires_at', '<', Carbon::now())->delete();

        $this->info("Pruned {$count} expired SSO tokens.");
    }
}
