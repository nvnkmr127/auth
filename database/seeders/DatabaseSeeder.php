<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Initial Apps
        $this->call(AppSeeder::class);

        // 2. RBAC (Permissions & Roles)
        $this->call(PermissionSeeder::class);
        $this->call(RolePermissionSeeder::class);

        // 3. User & Admin Account
        $admin = User::updateOrCreate(
            ['email' => 'admin@auth.core'],
            [
                'name' => 'System Administrator',
                'password' => Hash::make('password'),
            ]
        );

        // Assign Super Admin role to the admin user
        $superAdminRole = \App\Models\Role::where('key', 'super_admin')->first();
        if ($superAdminRole) {
            $admin->roles()->syncWithoutDetaching([$superAdminRole->id]);
        }
    }
}
