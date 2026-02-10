<?php

namespace App\Services;

use App\Models\App;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use InvalidArgumentException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class SsoTokenService
{
    protected JwtService $jwtService;

    public function __construct(JwtService $jwtService)
    {
        $this->jwtService = $jwtService;
    }

    /**
     * Generate an SSO redirect URL for the given user and app.
     *
     * @param User $user
     * @param App $app
     * @return string
     * @throws AccessDeniedHttpException
     */
    public function generateSsoUrl(User $user, App $app): string
    {
        // 1. Validate App Status
        if ($app->status !== 'active') {
            throw new AccessDeniedHttpException("Application '{$app->name}' is currently inactive.");
        }

        // 2. Refresh relationships to ensure we have latest data
        $access = $user->appAccesses()->where('app_id', $app->id)->first();

        // 3. Verify User Access
        $isAdmin = $user->isAdmin();

        if (!$access && !$isAdmin) {
            throw new AccessDeniedHttpException("User does not have access to '{$app->name}'.");
        }

        // Determine Role
        $role = 'user';
        if ($isAdmin) {
            $role = 'super_admin';
        } elseif ($access && $access->role) {
            $role = $access->role->key;
        }

        // 4. Generate JWT - use the role key (e.g. 'super_admin') as the claim
        $token = $this->jwtService->generateToken(
            userId: (string) $user->id,
            email: $user->email,
            appSlug: $app->slug,
            role: $role, // Extract the string key
            audience: $this->getAudienceFromDomain($app->domain)
        );

        // 5. Construct Redirect URL
        return $this->buildCallbackUrl($app->domain, $token);
    }

    /**
     * Clean and format the audience from the domain.
     *
     * @param string|null $domain
     * @return string
     */
    protected function getAudienceFromDomain(?string $domain): string
    {
        if (empty($domain)) {
            throw new InvalidArgumentException("App domain is not configured.");
        }

        // Ensure scheme for consistency
        $url = $domain;
        if (!preg_match("~^(?:f|ht)tps?://~i", $url)) {
            $url = "https://" . $url;
        }

        // Parse and return only protocol + host (the origin)
        $parsed = parse_url($url);
        $scheme = $parsed['scheme'] ?? 'https';
        $host = $parsed['host'] ?? '';
        $port = isset($parsed['port']) ? ':' . $parsed['port'] : '';

        return "{$scheme}://{$host}{$port}";
    }

    /**
     * Build the callback URL with the token.
     *
     * @param string $domain
     * @param string $token
     * @return string
     */
    protected function buildCallbackUrl(string $domain, string $token): string
    {
        // Normalize domain to remove trailing slashes
        $baseUrl = rtrim($this->getAudienceFromDomain($domain), '/');

        // Prevent double /sso/callback if it's already in the domain field
        if (str_ends_with($baseUrl, '/sso/callback')) {
            return "{$baseUrl}?token={$token}";
        }

        // Append callback path
        return "{$baseUrl}/sso/callback?token={$token}";
    }
}
