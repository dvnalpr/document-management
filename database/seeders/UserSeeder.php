<?php

namespace Database\Seeders;

use App\Models\Division;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        $password = Hash::make('password');

        $qaDiv = Division::where('code', 'QA')->first();
        $meDiv = Division::where('code', 'ME')->first();
        $itDiv = Division::where('code', 'IT')->first();

        $admin = User::create([
            'name' => 'Super Admin',
            'email' => 'admin@docmanager.com',
            'password' => $password,
            'division_id' => $itDiv->id,
            'is_active' => true,
        ]);
        $admin->assignRole('Admin');

        $managerQA = User::create([
            'name' => 'Bradley William',
            'email' => 'bradley@docmanager.com',
            'password' => $password,
            'division_id' => $qaDiv->id,
            'is_active' => true,
        ]);
        $managerQA->assignRole('Manager');

        $staffME = User::create([
            'name' => 'Jonas McDuff',
            'email' => 'jonas@docmanager.com',
            'password' => $password,
            'division_id' => $meDiv->id,
            'is_active' => true,
        ]);
        $staffME->assignRole('Staff');
    }
}
