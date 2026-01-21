<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DivisionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $divisions = [
            [
                'name' => 'Quality Assurance',
                'code' => 'QA',
                'description' => 'Divisi Quality Assurance - Mengelola dokumen quality',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Manufacturing Engineering',
                'code' => 'ME',
                'description' => 'Divisi Manufacturing Engineering - Mengelola dokumen engineering',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Human Resources',
                'code' => 'HR',
                'description' => 'Divisi Human Resources - Mengelola sertifikasi personil',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Production',
                'code' => 'PROD',
                'description' => 'Divisi Production',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'IT',
                'code' => 'IT',
                'description' => 'Divisi Information Technology',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ];

        DB::table('divisions')->insert($divisions);
    }
}
