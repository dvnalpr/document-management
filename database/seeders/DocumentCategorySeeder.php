<?php

namespace Database\Seeders;

use App\Models\DocumentCategory;
use Illuminate\Database\Seeder;

class DocumentCategorySeeder extends Seeder
{
    public function run()
    {
        $categories = [
            ['name' => 'Dokumen Quality', 'code' => 'QUALITY'],
            ['name' => 'Dokumen Engineering', 'code' => 'ENGINEERING'],
            ['name' => 'Sertifikasi Personil', 'code' => 'CERTIFICATION'],
        ];

        foreach ($categories as $cat) {
            DocumentCategory::create($cat);
        }
    }
}
