<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\DocumentLoan;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            [
                'label' => 'Total all docs',
                'value' => Document::count(),
            ],
            [
                'label' => 'Total QA docs',
                'value' => Document::whereHas('category', fn ($q) => $q->where('code', 'QUALITY'))->count(),
            ],
            [
                'label' => 'Total Engineering docs',
                'value' => Document::whereHas('category', fn ($q) => $q->where('code', 'ENGINEERING'))->count(),
            ],
            [
                'label' => 'Total certificate docs',
                'value' => Document::whereHas('category', fn ($q) => $q->where('code', 'CERTIFICATION'))->count(),
            ],
            [
                'label' => 'Total loan requests',
                'value' => DocumentLoan::where('status', 'Accepted')->count(),
            ],
        ];

        $recentDocs = Document::with(['category', 'uploader'])->latest()->take(10)->get();

        return view('dashboard.index', compact('stats', 'recentDocs'));
    }
}
