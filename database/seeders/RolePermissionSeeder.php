<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionSeeder extends Seeder
{
    public function run()
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $permissions = [
            'view_dashboard',
            'manage_users',
            'view_audit_logs',
            'view_documents',
            'create_documents',
            'edit_documents',
            'delete_documents',
            'request_loan',
            'approve_loan',
            'view_all_loans',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        $staff = Role::create(['name' => 'Staff']);
        $staff->givePermissionTo(['view_dashboard', 'view_documents', 'request_loan']);

        $manager = Role::create(['name' => 'Manager']);
        $manager->givePermissionTo([
            'view_dashboard', 'view_documents', 'create_documents',
            'edit_documents', 'delete_documents', 'approve_loan', 'view_all_loans',
        ]);

        $admin = Role::create(['name' => 'Admin']);
        $admin->givePermissionTo(Permission::all());
    }
}
