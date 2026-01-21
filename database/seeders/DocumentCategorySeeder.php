<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DocumentCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Dokumen Quality',
                'code' => 'QUALITY',
                'description' => 'Dokumen terkait Quality Assurance seperti SOP, Work Instructions, Quality Manual',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Dokumen Engineering',
                'code' => 'ENGINEERING',
                'description' => 'Dokumen terkait Manufacturing Engineering seperti Technical Drawing, Process Flow, Equipment Manual',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Sertifikasi Personil',
                'code' => 'CERTIFICATION',
                'description' => 'Dokumen sertifikasi personil seperti Training Certificate, License, Competency Card',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ];

        DB::table('document_categories')->insert($categories);
    }
}
