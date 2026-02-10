<?php

namespace Tests\Feature;

use App\Models\App;
use App\Models\User;
use App\Models\UserAppAccess;
use App\Models\Role;
use App\Models\UsedSsoToken;
use App\Services\JwtService;
use App\Services\SsoTokenService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class SsoFlowTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        // Ensure keys exist for testing
        if (!file_exists(storage_path('jwt/private.pem'))) {
            $this->artisan('jwt:setup');
        }

        // Mock ENV for JWT paths if needed, though default service uses storage_path

        // Set expected audience to valid app url for decoding
        Config::set('app.url', 'https://localhost');
        Config::set('jwt.app_url', 'https://localhost');
    }

    public function test_can_generate_sso_token_for_authorized_user()
    {
        // 1. Setup User and App
        $user = User::factory()->create();
        $app = App::create([
            'name' => 'Client App',
            'slug' => 'client-app',
            'domain' => 'https://localhost',
            'status' => 'active'
        ]);

        // 2. Grant Access
        $role = Role::create(['key' => 'editor', 'name' => 'Editor', 'app_id' => $app->id]);
        UserAppAccess::create([
            'user_id' => $user->id,
            'app_id' => $app->id,
            'role_id' => $role->id
        ]);

        // 3. Generate Token
        $ssoService = app(SsoTokenService::class);
        $url = $ssoService->generateSsoUrl($user, $app);

        $this->assertStringContainsString('/sso/callback?token=', $url);

        return $url; // Pass to next test if possible, but PHPUnit doesn't chain easy like that.
    }

    public function test_cannot_generate_token_without_access()
    {
        $user = User::factory()->create();
        $app = App::create([
            'name' => 'Client App',
            'slug' => 'client-app',
            'domain' => 'https://localhost',
            'status' => 'active'
        ]);

        $this->expectException(\Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException::class);

        $ssoService = app(SsoTokenService::class);
        $ssoService->generateSsoUrl($user, $app);
    }

    public function test_sso_callback_logs_user_in()
    {
        // 1. Setup Data
        $user = User::factory()->create(['email' => 'sso@test.com']);
        $app = App::create([
            'name' => 'Self',
            'slug' => 'self',
            'domain' => 'https://localhost', // Matches APP_URL
            'status' => 'active'
        ]);

        Config::set('jwt.app_slug', 'self'); // Match the app slug expectation

        $role = Role::create(['key' => 'admin', 'name' => 'Admin', 'app_id' => $app->id]);
        UserAppAccess::create([
            'user_id' => $user->id,
            'app_id' => $app->id,
            'role_id' => $role->id
        ]);

        // 2. Generate Real Token
        $ssoService = app(SsoTokenService::class);
        $url = $ssoService->generateSsoUrl($user, $app);

        // Extract token
        parse_str(parse_url($url, PHP_URL_QUERY), $query);
        $token = $query['token'];

        // 3. Hit Callback
        $response = $this->get('/sso/callback?token=' . $token);

        // 4. Verification
        $response->assertRedirect('/dashboard');
        $this->assertAuthenticatedAs($user);

        // Verify used token recorded
        $this->assertEquals(1, UsedSsoToken::count());
    }

    public function test_sso_callback_replay_protection()
    {
        // 1. Setup
        $user = User::factory()->create();
        $app = App::create(['name' => 'Test', 'slug' => 'test', 'domain' => 'https://localhost', 'status' => 'active']);
        Config::set('jwt.app_slug', 'test'); // Match the app slug expectation
        $role = Role::create(['key' => 'viewer', 'name' => 'Viewer', 'app_id' => $app->id]);
        UserAppAccess::create(['user_id' => $user->id, 'app_id' => $app->id, 'role_id' => $role->id]);

        $ssoService = app(SsoTokenService::class);
        $url = $ssoService->generateSsoUrl($user, $app);
        parse_str(parse_url($url, PHP_URL_QUERY), $query);
        $token = $query['token'];

        // 2. First Login (Success)
        $this->get('/sso/callback?token=' . $token)->assertRedirect('/dashboard');
        Auth::logout();

        // 3. Second Login (Fail)
        // Note: Livewire component error handling usually stays on page, it doesn't 500.
        // We check if we see "Login Failed" or "Token has already been used" in the view.
        $response = $this->get('/sso/callback?token=' . $token);

        $response->assertSee('Token has already been used');
        $this->assertGuest();
    }
}
