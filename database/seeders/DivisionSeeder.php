<?php

namespace Database\Seeders;

use App\Models\Division;
use Illuminate\Database\Seeder;

class DivisionSeeder extends Seeder
{
    public function run()
    {
        $divisions = [
            ['name' => 'Information Technology', 'code' => 'IT'],
            ['name' => 'Quality Assurance', 'code' => 'QA'],
            ['name' => 'Manufacturing Engineering', 'code' => 'ME'],
            ['name' => 'Human Resources', 'code' => 'HR'],
            ['name' => 'Production', 'code' => 'PROD'],
        ];

        foreach ($divisions as $div) {
            Division::create($div);
        }
    }
}
