<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create Permissions
        $permissions = [
            // Document permissions
            'view documents',
            'create documents',
            'edit documents',
            'delete documents',
            'download documents',
            'upload documents',

            // Loan permissions
            'request loan',
            'approve loan',
            'reject loan',
            'view loans',

            // User management permissions
            'view users',
            'create users',
            'edit users',
            'delete users',

            // Audit log permissions
            'view audit logs',

            // Dashboard permissions
            'view dashboard',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // Create Roles and assign permissions

        // 1. Admin Role - Full access
        $adminRole = Role::create(['name' => 'admin']);
        $adminRole->givePermissionTo(Permission::all());

        // 2. QA Staff Role
        $qaRole = Role::create(['name' => 'qa_staff']);
        $qaRole->givePermissionTo([
            'view documents',
            'create documents',
            'edit documents',
            'download documents',
            'upload documents',
            'request loan',
            'approve loan',
            'reject loan',
            'view loans',
            'view dashboard',
        ]);

        // 3. Engineering Staff Role
        $engineeringRole = Role::create(['name' => 'engineering_staff']);
        $engineeringRole->givePermissionTo([
            'view documents',
            'create documents',
            'edit documents',
            'download documents',
            'upload documents',
            'request loan',
            'approve loan',
            'reject loan',
            'view loans',
            'view dashboard',
        ]);

        // 4. Regular User Role
        $userRole = Role::create(['name' => 'user']);
        $userRole->givePermissionTo([
            'view documents',
            'download documents',
            'request loan',
            'view loans',
            'view dashboard',
        ]);
    }
}
