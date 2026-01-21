<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Admin User
        $admin = User::create([
            'name' => 'Super Admin',
            'email' => 'admin@company.com',
            'password' => Hash::make('password123'),
            'division_id' => 5, // IT Division
            'is_active' => true,
        ]);
        $admin->assignRole('admin');

        // Create QA Staff User
        $qaStaff = User::create([
            'name' => 'QA Manager',
            'email' => 'qa@company.com',
            'password' => Hash::make('password123'),
            'division_id' => 1, // QA Division
            'is_active' => true,
        ]);
        $qaStaff->assignRole('qa_staff');

        // Create Engineering Staff User
        $engineeringStaff = User::create([
            'name' => 'Engineering Manager',
            'email' => 'engineering@company.com',
            'password' => Hash::make('password123'),
            'division_id' => 2, // Manufacturing Engineering Division
            'is_active' => true,
        ]);
        $engineeringStaff->assignRole('engineering_staff');

        // Create Regular Users
        $user1 = User::create([
            'name' => 'John Doe',
            'email' => 'john@company.com',
            'password' => Hash::make('password123'),
            'division_id' => 4, // Production Division
            'is_active' => true,
        ]);
        $user1->assignRole('user');

        $user2 = User::create([
            'name' => 'Jane Smith',
            'email' => 'jane@company.com',
            'password' => Hash::make('password123'),
            'division_id' => 3, // HR Division
            'is_active' => true,
        ]);
        $user2->assignRole('user');

        // Create Additional QA Staff
        $qaStaff2 = User::create([
            'name' => 'QA Inspector',
            'email' => 'qa.inspector@company.com',
            'password' => Hash::make('password123'),
            'division_id' => 1,
            'is_active' => true,
        ]);
        $qaStaff2->assignRole('qa_staff');

        // Create Additional Engineering Staff
        $engStaff2 = User::create([
            'name' => 'Senior Engineer',
            'email' => 'senior.eng@company.com',
            'password' => Hash::make('password123'),
            'division_id' => 2,
            'is_active' => true,
        ]);
        $engStaff2->assignRole('engineering_staff');

        $this->command->info('Users created successfully!');
        $this->command->info('Login credentials:');
        $this->command->info('Admin: admin@company.com / password123');
        $this->command->info('QA: qa@company.com / password123');
        $this->command->info('Engineering: engineering@company.com / password123');
        $this->command->info('User: john@company.com / password123');
    }
}
