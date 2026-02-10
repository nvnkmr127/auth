<?php

namespace App\Services;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use App\Models\UsedSsoToken;
use Carbon\Carbon;
use RuntimeException;
use stdClass;

class JwtVerificationService
{
    protected string $publicKeyPath;
    protected string $algorithm = 'RS256';

    public function __construct()
    {
        $this->publicKeyPath = config('jwt.public_key');
    }

    /**
     * Verify and decode a JWT.
     *
     * @param string $token
     * @return object
     * @throws RuntimeException
     */
    public function verifyToken(string $token): stdClass
    {
        if (!file_exists($this->publicKeyPath)) {
            throw new RuntimeException("JWT Public Key not found. Cannot verify tokens.");
        }

        $publicKey = file_get_contents($this->publicKeyPath);

        try {
            // 1. Decode and Verify Signature & Expiry
            $decoded = JWT::decode($token, new Key($publicKey, $this->algorithm));
        } catch (\Exception $e) {
            throw new RuntimeException("Invalid Token: " . $e->getMessage());
        }

        // 2. Validate Audience and App Slug (Authorization)
        $this->validateClaims($decoded);

        // 3. Replay Protection
        $this->checkReplay($decoded);

        return $decoded;
    }

    protected function validateClaims(stdClass $decoded)
    {
        $expectedAudience = config('jwt.app_url');
        $expectedAppSlug = config('jwt.app_slug');

        // Validate Audience if configured
        if ($expectedAudience && $decoded->aud !== $expectedAudience) {
             throw new RuntimeException("Invalid Audience: Expected $expectedAudience, got {$decoded->aud}");
        }

        // Validate App Slug if configured
        if ($expectedAppSlug && isset($decoded->app_slug) && $decoded->app_slug !== $expectedAppSlug) {
            throw new RuntimeException("Invalid App Slug: Expected $expectedAppSlug, got {$decoded->app_slug}");
        }
    }

    protected function checkReplay(stdClass $decoded)
    {
        if (!isset($decoded->jti)) {
            throw new RuntimeException("Token missing JTI claim.");
        }

        if (UsedSsoToken::where('jti', $decoded->jti)->exists()) {
            throw new RuntimeException("Token has already been used.");
        }

        // Mark as used
        UsedSsoToken::create([
            'jti' => $decoded->jti,
            'expires_at' => Carbon::createFromTimestamp($decoded->exp)
        ]);
    }
}
