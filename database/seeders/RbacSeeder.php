<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;

class RbacSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Create Permissions
        $permissions = [
            'users.view',
            'users.create',
            'users.update',
            'users.delete',
            'roles.view',
            'roles.manage',
            'apps.view',
            'apps.manage',
        ];

        foreach ($permissions as $key) {
            Permission::firstOrCreate(
                ['key' => $key],
                ['name' => ucwords(str_replace('.', ' ', $key))]
            );
        }

        // 2. Create Roles
        $superAdmin = Role::firstOrCreate(
            ['key' => 'super_admin'],
            [
                'name' => 'Super Admin',
                'description' => 'Full access to everything globally',
                'is_global' => true,
            ]
        );

        $orgAdmin = Role::firstOrCreate(
            ['key' => 'org_admin'],
            [
                'name' => 'Organization Admin',
                'description' => 'Manage organization resources',
                'is_global' => true,
            ]
        );

        $user = Role::firstOrCreate(
            ['key' => 'user'],
            [
                'name' => 'Standard User',
                'description' => 'Default user access',
                'is_global' => true,
            ]
        );

        // 3. Assign Permissions
        // Super Admin gets all permissions
        $allPermissions = Permission::all();
        $superAdmin->permissions()->syncWithoutDetaching($allPermissions->pluck('id'));

        // Org Admin gets managing users and apps
        $orgPermissions = Permission::whereIn('key', [
            'users.view',
            'users.create',
            'users.update',
            'users.delete',
            'apps.view',
            'apps.manage'
        ])->get();
        $orgAdmin->permissions()->syncWithoutDetaching($orgPermissions->pluck('id'));

        // User gets basic view
        $userPermissions = Permission::whereIn('key', [
            'users.view',
            'apps.view',
        ])->get();
        $user->permissions()->syncWithoutDetaching($userPermissions->pluck('id'));
    }
}
