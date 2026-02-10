<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * This seeder is idempotent and allows granular control over
     * platform permissions.
     */
    public function run(): void
    {
        // Define permissions grouped by logical domain
        $permissionGroups = [
            'System' => [
                'system.access' => 'Access system dashboard',
                'system.manage' => 'Full administrative access',
            ],
            'User Management' => [
                'users.view' => 'View list of users and their profiles',
                'users.create' => 'Create new user accounts',
                'users.update' => 'Update user details',
                'users.delete' => 'Delete users from the system',
                'users.impersonate' => 'Impersonate other users for support',
            ],
            'Role & Permission Management' => [
                'roles.view' => 'View defined roles',
                'roles.create' => 'Create new custom roles',
                'roles.update' => 'Modify role details and permissions',
                'roles.delete' => 'Remove roles',
                'permissions.view' => 'View available system permissions',
            ],
            'Application Access' => [
                'apps.view' => 'View list of available applications',
                'apps.access' => 'Access specific applications',
                'apps.manage' => 'Configure application settings',
            ],
            'Estimates' => [
                'estimates.view' => 'View estimates',
                'estimates.create' => 'Create new estimates',
                'estimates.edit' => 'Edit existing estimates',
                'estimates.delete' => 'Delete estimates',
                'estimates.approve' => 'Approve estimates for sending',
                'estimates.send' => 'Send estimates to clients',
            ],
            'Audit & Logs' => [
                'audit.logs.view' => 'View system activity logs',
                'audit.settings.view' => 'View audit configurations',
            ],
        ];

        DB::transaction(function () use ($permissionGroups) {
            foreach ($permissionGroups as $group => $permissions) {
                foreach ($permissions as $key => $description) {
                    // Update existing permissions or create new ones
                    // to ensure names/groups are synchronized with code.
                    Permission::updateOrCreate(
                        ['key' => $key],
                        [
                            'name' => $description, // Use description as the human-readable name
                            'group' => $group,
                        ]
                    );
                }
            }
        });

        $this->command->info('Permissions seeded successfully.');
    }
}
