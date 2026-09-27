<?php

namespace Database\Seeders;

use App\Enums\SystemRole;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        app(PermissionRegistrar::class)->forgetCachedPermissions();
        $permissions = [
            SystemRole::Admin->value => [
                'manage users', 'verify identities', 'manage opportunities',
                'manage master data', 'manage government users', 'moderate users',
                'moderate communities', 'view analytics', 'view audit logs',
            ],
            SystemRole::Verifier->value => [
                'review communities', 'review activities', 'review proposals',
                'review logbooks', 'review financial reports', 'complete programs',
            ],
            SystemRole::Youth->value => [],
        ];

        foreach ($permissions as $roleName => $names) {
            $role = Role::findOrCreate($roleName, 'web');
            foreach ($names as $name) {
                Permission::findOrCreate($name, 'web');
            }
            app(PermissionRegistrar::class)->forgetCachedPermissions();
            $role->syncPermissions($names);
        }
        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }
}
