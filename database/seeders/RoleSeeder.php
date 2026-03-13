<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Super Admin
        Role::firstOrCreate(
            ['key' => 'super_admin'],
            [
                'name' => 'Super Admin',
                'description' => 'Full access to everything globally',
                'is_global' => true,
            ]
        );

        // 2. Organization Admin
        Role::firstOrCreate(
            ['key' => 'org_admin'],
            [
                'name' => 'Organization Admin',
                'description' => 'Manage organization resources',
                'is_global' => true,
            ]
        );

        // 3. User
        Role::firstOrCreate(
            ['key' => 'user'],
            [
                'name' => 'Standard User',
                'description' => 'Default user access',
                'is_global' => true,
            ]
        );
    }
}
