<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Role;
use App\Models\UserRole;
use App\Livewire\Admin\UserList;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class UserRoleManagementTest extends TestCase
{
    use RefreshDatabase;

    protected $adminUser;
    protected $superAdminRole;
    protected $orgAdminRole;

    protected function setUp(): void
    {
        parent::setUp();

        // Create basic roles
        $this->superAdminRole = Role::create([
            'name' => 'Super Admin',
            'key' => 'super_admin',
            'is_global' => true,
        ]);

        $this->orgAdminRole = Role::create([
            'name' => 'Organization Admin',
            'key' => 'org_admin',
            'is_global' => true,
        ]);

        // Create Admin user and log them in
        $this->adminUser = User::factory()->create();
        UserRole::create([
            'user_id' => $this->adminUser->id,
            'role_id' => $this->superAdminRole->id,
            'app_id' => null,
        ]);

        $this->actingAs($this->adminUser);
    }

    public function test_user_list_renders_with_roles()
    {
        $user = User::factory()->create();
        UserRole::create([
            'user_id' => $user->id,
            'role_id' => $this->orgAdminRole->id,
            'app_id' => null,
        ]);

        $response = $this->get('/admin/users');
        $response->assertStatus(200);
        $response->assertSee('Organization Admin');
    }

    public function test_can_assign_global_roles_when_creating_user()
    {
        Livewire::test(UserList::class)
            ->set('name', 'Test User')
            ->set('email', 'test@example.com')
            ->set('password', 'password123')
            ->set('password_confirmation', 'password123')
            ->set('selectedRoles', [$this->orgAdminRole->id])
            ->call('save');

        $user = User::where('email', 'test@example.com')->first();
        $this->assertNotNull($user);
        $this->assertTrue($user->roles()->where('key', 'org_admin')->exists());
    }

    public function test_can_reassign_roles_when_editing_user()
    {
        $user = User::factory()->create();
        UserRole::create([
            'user_id' => $user->id,
            'role_id' => $this->orgAdminRole->id,
            'app_id' => null,
        ]);

        Livewire::test(UserList::class)
            ->call('edit', $user->id)
            ->assertSet('selectedRoles', [(string) $this->orgAdminRole->id])
            ->set('selectedRoles', [(string) $this->superAdminRole->id])
            ->call('save');

        $this->assertFalse($user->roles()->where('key', 'org_admin')->exists());
        $this->assertTrue($user->roles()->where('key', 'super_admin')->exists());
    }
}
