<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Process\Process;

class SetupJwtKeys extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'jwt:setup {--force : Overwrite existing keys}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate RS256 keys for JWT signing';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $directory = storage_path('jwt');
        $privateKeyPath = $directory . '/private.pem';
        $publicKeyPath = $directory . '/public.pem';

        if (!is_dir($directory)) {
            mkdir($directory, 0755, true);
        }

        if (file_exists($privateKeyPath) && !$this->option('force')) {
            $this->error('Private key already exists. Use --force to overwrite.');
            return;
        }

        $this->info('Generating RSA Private Key...');

        $process = new Process(['openssl', 'genrsa', '-out', $privateKeyPath, '4096']);
        $process->run();

        if (!$process->isSuccessful()) {
            $this->error('Failed to generate private key.');
            $this->error($process->getErrorOutput());
            return;
        }

        $this->info('Generating RSA Public Key...');

        $process = new Process(['openssl', 'rsa', '-in', $privateKeyPath, '-pubout', '-out', $publicKeyPath]);
        $process->run();

        if (!$process->isSuccessful()) {
            $this->error('Failed to generate public key.');
            $this->error($process->getErrorOutput());
            return;
        }

        // Set permissions
        chmod($privateKeyPath, 0600);
        chmod($publicKeyPath, 0644);

        $this->info('Keys generated successfully.');
        $this->info("Private Key: $privateKeyPath");
        $this->info("Public Key:  $publicKeyPath");

        $this->info('');
        $this->info('Ensure these paths are set in your .env file:');
        $this->line('JWT_PRIVATE_KEY_PATH=storage/jwt/private.pem');
        $this->line('JWT_PUBLIC_KEY_PATH=storage/jwt/public.pem');
    }
}
