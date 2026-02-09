<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\DocumentLoan;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $applyAccessControl = function ($query) use ($user) {
            if (! $user->hasRole('Admin')) {
                $query->where(function ($q) use ($user) {

                    $userDivId = $user->division_id ?? -1;
                    $q->where('division_id', $userDivId);

                    $q->orWhereHas('category', function ($c) {
                        $c->where('code', 'CERTIFICATION');
                    });
                });
            }
        };

        $stats = [
            [
                'label' => 'Total all docs',
                'value' => Document::where(function ($q) use ($applyAccessControl) {
                    $applyAccessControl($q);
                })->count(),
            ],
            [
                'label' => 'Total QA docs',
                'value' => Document::whereHas('category', fn ($q) => $q->where('code', 'QUALITY'))
                    ->where(function ($q) use ($applyAccessControl) {
                        $applyAccessControl($q);
                    })->count(),
            ],
            [
                'label' => 'Total Engineering docs',
                'value' => Document::whereHas('category', fn ($q) => $q->where('code', 'ENGINEERING'))
                    ->where(function ($q) use ($applyAccessControl) {
                        $applyAccessControl($q);
                    })->count(),
            ],
            [
                'label' => 'Total certificate docs',
                'value' => Document::whereHas('category', fn ($q) => $q->where('code', 'CERTIFICATION'))
                    ->where(function ($q) use ($applyAccessControl) {
                        $applyAccessControl($q);
                    })->count(),
            ],
            [
                'label' => 'Total active loans',
                'value' => DocumentLoan::where('status', 'Accepted')
                    ->whereHas('document', function ($q) use ($applyAccessControl) {
                        $applyAccessControl($q);
                    })->count(),
            ],
        ];

        $recentDocs = Document::with(['category', 'uploader'])
            ->where(function ($q) use ($applyAccessControl) {
                $applyAccessControl($q);
            })
            ->latest()
            ->take(10)
            ->get();

        return view('dashboard.index', compact('stats', 'recentDocs'));
    }
}
