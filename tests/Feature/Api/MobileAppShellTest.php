<?php

namespace Tests\Feature\Api;

use Tests\TestCase;
use App\Models\User;
use App\Models\App;
use App\Models\UserAppAccess;
use App\Models\ApplicationRegistry;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;

class MobileAppShellTest extends TestCase
{
    use RefreshDatabase;

    private $user;
    private $workspace;
    private $token;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create(['is_active' => true]);

        $this->workspace = App::create([
            'name' => 'HQ Workspace',
            'slug' => 'hq-workspace',
            'status' => 'active',
        ]);

        UserAppAccess::create([
            'user_id' => $this->user->id,
            'app_id' => $this->workspace->id,
            'role' => 'editor',
            'status' => 'active',
        ]);

        ApplicationRegistry::create([
            'app_name' => 'Central CRM',
            'app_slug' => 'central-crm',
            'mobile_enabled' => true,
        ]);

        $tokenString = Str::random(60);
        $this->user->apiTokens()->create([
            'name' => 'Mobile App',
            'token' => hash('sha256', $tokenString),
            'expires_at' => now()->addDays(30),
        ]);
        
        $this->token = $tokenString;
    }

    public function test_can_fetch_app_registry()
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->getJson('/api/mobile/v1/app-registry');

        $response->assertStatus(200);
        $this->assertCount(1, $response->json('apps'));
        $this->assertEquals('Central CRM', $response->json('apps.0.app_name'));
    }

    public function test_navigation_is_dynamically_built()
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->getJson('/api/mobile/v1/navigation?workspace_id=' . $this->workspace->id);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'bottom_nav', 'sidebar'
        ]);
        // Central CRM should be in sidebar
        $this->assertEquals('Central CRM', $response->json('sidebar.0.label'));
    }

    public function test_dashboard_is_dynamically_built()
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->getJson('/api/mobile/v1/dashboard/widgets?workspace_id=' . $this->workspace->id);

        $response->assertStatus(200);
        $this->assertNotEmpty($response->json('widgets'));
        $this->assertEquals('welcome_banner', $response->json('widgets.0.type'));
    }

    public function test_sync_returns_updated_context()
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->postJson('/api/mobile/v1/sync', [
            'workspace_id' => $this->workspace->id
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'status' => 'active',
            'action' => 'update_context',
        ]);
        $this->assertNotEmpty($response->json('context.sync_hash'));
    }

    public function test_policy_engine_revokes_access_for_inactive_user()
    {
        $this->user->update(['is_active' => false]);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->postJson('/api/mobile/v1/sync', [
            'workspace_id' => $this->workspace->id
        ]);

        $response->assertStatus(403);
        $response->assertJson([
            'status' => 'inactive',
            'action' => 'force_logout',
        ]);
    }
}
