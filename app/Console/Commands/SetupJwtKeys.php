<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

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

        $this->info('Generating RSA key pair...');

        $key = openssl_pkey_new([
            'private_key_bits' => 4096,
            'private_key_type' => OPENSSL_KEYTYPE_RSA,
        ]);

        if ($key === false || !openssl_pkey_export($key, $privateKey)) {
            $this->error('Failed to generate private key: ' . openssl_error_string());
            return;
        }

        $publicKey = openssl_pkey_get_details($key)['key'];

        file_put_contents($privateKeyPath, $privateKey);
        file_put_contents($publicKeyPath, $publicKey);

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
