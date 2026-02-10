<?php

namespace App\Services;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use RuntimeException;
use Carbon\Carbon;

class JwtService
{
    protected string $privateKeyPath;
    protected string $publicKeyPath;
    protected string $algorithm = 'RS256';

    public function __construct()
    {
        // Use configured paths or defaults in storage/jwt
        $this->privateKeyPath = config('jwt.private_key');
        $this->publicKeyPath = config('jwt.public_key');
    }

    /**
     * Generate a signed JWT for cross-domain SSO.
     *
     * @param string $userId
     * @param string $email
     * @param string $appSlug
     * @param string $role
     * @param string $audience
     * @return string
     * @throws RuntimeException
     */
    public function generateToken(string $userId, string $email, string $appSlug, string $role, string $audience): string
    {
        if (!file_exists($this->privateKeyPath)) {
            throw new RuntimeException("JWT Private Key not found at: {$this->privateKeyPath}. Please generate keys.");
        }

        $privateKey = file_get_contents($this->privateKeyPath);

        $issuedAt = Carbon::now()->timestamp;
        $expiry = Carbon::now()->addSeconds(60)->timestamp;

        $payload = [
            'iss' => config('jwt.app_url'), // Issuer
            'sub' => $userId,              // Subject (user_id)
            'email' => $email,             // User Email
            'app_slug' => $appSlug,        // Application Slug
            'role' => $role,               // User Role in that App
            'aud' => $audience,            // Audience (usually the destination domain/app)
            'iat' => $issuedAt,            // Issued At
            'exp' => $expiry,              // Expiration Time (60s)
            'jti' => bin2hex(random_bytes(16)) // Unique Token ID
        ];

        // Encode the payload using the private key and RS256 algorithm
        return JWT::encode($payload, $privateKey, $this->algorithm);
    }

    /**
     * Get the Public Key content.
     * Useful for exposing an endpoint (JWKS) or sharing with the client app manually.
     *
     * @return string
     * @throws RuntimeException
     */
    public function getPublicKey(): string
    {
        if (!file_exists($this->publicKeyPath)) {
            throw new RuntimeException("JWT Public Key not found at: {$this->publicKeyPath}. Please generate keys.");
        }

        return file_get_contents($this->publicKeyPath);
    }
}
