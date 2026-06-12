<?php

namespace Tests\Feature\Api;

use Tests\TestCase;
use App\Models\User;
use App\Models\App;
use App\Models\UserAppAccess;
use App\Models\Permission;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class MobileWorkspaceTest extends TestCase
{
    use RefreshDatabase;

    private $user;
    private $app;
    private $token;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create([
            'is_active' => true,
        ]);

        $this->app = App::create([
            'name' => 'C2D CRM',
            'slug' => 'c2d-crm',
            'domain' => 'c2d.example.com',
            'status' => 'active',
        ]);

        UserAppAccess::create([
            'user_id' => $this->user->id,
            'app_id' => $this->app->id,
            'role' => 'editor',
            'status' => 'active',
        ]);

        $tokenString = Str::random(60);
        $this->user->apiTokens()->create([
            'name' => 'Test Device',
            'token' => hash('sha256', $tokenString),
            'expires_at' => now()->addDays(30),
        ]);
        
        $this->token = $tokenString;
    }

    public function test_user_can_list_assigned_workspaces()
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->getJson('/api/mobile/v1/workspaces');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'workspaces' => [
                '*' => ['id', 'name', 'default']
            ]
        ]);
        
        $this->assertCount(1, $response->json('workspaces'));
        $this->assertEquals('C2D CRM Workspace', $response->json('workspaces.0.name'));
    }

    public function test_user_cannot_access_unassigned_workspace()
    {
        $unassignedApp = App::create([
            'name' => 'Secret App',
            'slug' => 'secret-app',
            'domain' => 'secret.example.com',
            'status' => 'active',
        ]);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->getJson('/api/mobile/v1/workspaces/' . $unassignedApp->id);

        $response->assertStatus(403);
    }

    public function test_workspace_switch_validates_access()
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->postJson('/api/mobile/v1/workspaces/switch', [
            'workspace_id' => $this->app->id
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'message' => 'Workspace switched successfully',
            'workspace' => [
                'id' => $this->app->id,
                'name' => 'C2D CRM Workspace',
            ]
        ]);
    }

    public function test_inactive_user_is_rejected()
    {
        $this->user->update(['is_active' => false]);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->getJson('/api/mobile/v1/workspaces');

        $response->assertStatus(403);
        $response->assertJson(['status' => 'inactive']);
    }
}
