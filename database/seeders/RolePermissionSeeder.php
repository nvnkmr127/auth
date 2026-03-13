<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Fetch all roles
        $superAdmin = Role::where('key', 'super_admin')->firstOrFail();
        $orgAdmin = Role::where('key', 'org_admin')->firstOrFail();
        $user = Role::where('key', 'user')->firstOrFail();

        // Fetch all permissions
        $allPermissions = Permission::all();

        // 1. Super Admin: Everything
        // The Super Admin has unrestricted access to the entire platform.
        $superAdmin->permissions()->sync($allPermissions->pluck('id'));

        // 2. Organization Admin: Management Focus
        // Can manage users, roles, and access applications, but might be restricted from foundational system settings.
        $orgAdminPermissions = $allPermissions->filter(function ($permission) {
            return str_starts_with($permission->key, 'users.')
                || str_starts_with($permission->key, 'roles.')
                || str_starts_with($permission->key, 'permissions.')
                || in_array($permission->key, [
                    'apps.view',
                    'apps.access',
                    'estimates.view',
                    'estimates.create',
                    'estimates.edit',
                    'estimates.approve',
                    'estimates.send',
                    'audit.logs.view',
                ]);
        });
        $orgAdmin->permissions()->sync($orgAdminPermissions->pluck('id'));

        // 3. Standard User: Basic Access
        // primarily consumes the applications and manages their own work (estimates).
        $userPermissions = $allPermissions->filter(function ($permission) {
            return in_array($permission->key, [
                'apps.view',
                'apps.access',
                'estimates.view',
                'estimates.create',
                'estimates.edit',
                // Users typically can't delete or approve unless specified, sticking to basic flow
            ]);
        });
        $user->permissions()->sync($userPermissions->pluck('id'));

        $this->command->info('Role permissions assigned successfully.');
        $this->command->info("Super Admin: {$superAdmin->permissions()->count()} permissions");
        $this->command->info("Org Admin: {$orgAdmin->permissions()->count()} permissions");
        $this->command->info("User: {$user->permissions()->count()} permissions");
    }
}
