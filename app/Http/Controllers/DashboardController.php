<?php

namespace App\Http\Controllers;

class DashboardController extends Controller
{
    public function index()
    {
        // Data Dummy untuk Mockup
        $stats = [
            ['label' => 'Total all docs', 'value' => '2,500'],
            ['label' => 'Total QA docs', 'value' => '1,000'],
            ['label' => 'Total Engineering docs', 'value' => '1,000'],
            ['label' => 'Total sertificate docs', 'value' => '500'],
            ['label' => 'Total docs borrowed', 'value' => '624'],
        ];

        $recentDocs = [
            ['name' => 'Safety Guideline.pdf', 'version' => 'v1.0', 'date' => '12/12/2025', 'category' => 'QA', 'user' => 'Jonas McDuff'],
            ['name' => 'Safety Guideline.pdf', 'version' => 'v1.0', 'date' => '12/12/2025', 'category' => 'ME', 'user' => 'Jonas McDuff'],
            ['name' => 'Safety Guideline.pdf', 'version' => 'v2.0', 'date' => '12/12/2025', 'category' => 'QA', 'user' => 'Jonas McDuff'],
            ['name' => 'Safety Guideline.pdf', 'version' => 'v2.0', 'date' => '12/12/2025', 'category' => 'QA', 'user' => 'Jonas McDuff'],
            ['name' => 'Safety Guideline.pdf', 'version' => 'v1.0', 'date' => '12/12/2025', 'category' => 'QA', 'user' => 'Jonas McDuff'],
            ['name' => 'Safety Guideline.pdf', 'version' => 'v3.0', 'date' => '12/12/2025', 'category' => 'QA', 'user' => 'Jonas McDuff'],
        ];

        return view('dashboard.index', compact('stats', 'recentDocs'));
    }
}
