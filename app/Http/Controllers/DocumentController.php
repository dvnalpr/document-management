<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\Division;
use App\Models\Document;
use App\Models\DocumentCategory;
use App\Models\DocumentVersion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DocumentController extends Controller
{
    /**
     * Helper Private untuk mapping Divisi ke Kategori
     */
    private function getCategoryByDivision($divisionId)
    {
        // 1. Ambil Code Kategori Certification
        $certCat = DocumentCategory::where('code', 'CERTIFICATION')->first();

        // 2. Ambil Divisi Target
        $division = Division::find($divisionId);

        if (! $division) {
            // Fallback jika divisi tidak valid
            return $certCat->id;
        }

        // 3. Logic Mapping (Sesuaikan string ini dengan nama Divisi di Database Anda)
        // Gunakan str_contains atau exact match sesuai kebutuhan
        $divName = strtoupper($division->name);

        if (str_contains($divName, 'QA') || str_contains($divName, 'QUALITY')) {
            return DocumentCategory::where('code', 'QUALITY')->first()->id;
        }

        if (str_contains($divName, 'ENGINEERING')) {
            return DocumentCategory::where('code', 'ENGINEERING')->first()->id;
        }

        // Default jika tidak match (misal HRD), kembalikan null atau default category
        // Disini saya defaultkan ke Certification atau bisa buat kategori 'General'
        return $certCat->id;
    }

    public function index(Request $request)
    {
        $user = Auth::user();
        $categories = DocumentCategory::all();
        $division = Division::all(); // Untuk dropdown Admin

        $query = Document::with(['category', 'division', 'uploader']);

        // --- FIX BUG AKSES ---
        // Masalah sebelumnya: Jika user punya division_id NULL (misal salah setup), query menjadi bocor.
        // Kita perketat logikanya.

        if (! $user->hasRole('Admin')) {
            $query->where(function ($q) use ($user) {

                // Kondisi 1: Dokumen milik divisi user
                // Gunakan where strict. Jika user->division_id null, kita pakai -1 agar tidak match apapun.
                $userDivId = $user->division_id ?? -1;
                $q->where('division_id', $userDivId);

                // Kondisi 2: ATAU Dokumen Sertifikasi (Public)
                $q->orWhereHas('category', function ($c) {
                    $c->where('code', 'CERTIFICATION');
                });
            });
        }
        // ---------------------

        if ($request->has('search')) {
            $query->where('title', 'like', "%{$request->search}%");
        }

        // Filter kategori (tetap dipertahankan untuk dropdown filter di atas tabel)
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        $documents = $query->latest()->paginate($request->input('per_page', 10))->withQueryString();

        // Stats Logic
        $stats = [
            ['label' => 'Total Docs', 'value' => Document::count()],
            ['label' => 'My Division', 'value' => $user->division_id ? Document::where('division_id', $user->division_id)->count() : 0],
            ['label' => 'Certification', 'value' => Document::whereHas('category', fn ($q) => $q->where('code', 'CERTIFICATION'))->count()],
        ];

        return view('documents.index', compact('documents', 'stats', 'categories', 'division'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            // category_id tidak lagi wajib dari input, karena otomatis
            'document_file' => 'required|file|mimes:pdf,doc,docx|max:10240',
            'division_id' => Auth::user()->hasRole('Admin') ? 'required|exists:divisions,id' : 'nullable',
        ]);

        DB::transaction(function () use ($request) {
            $path = $request->file('document_file')->store('documents/originals', 'public');

            // 1. Tentukan Divisi
            // Jika Admin, pakai input. Jika Staff, pakai divisi user sendiri.
            $targetDivisionId = $request->division_id ?? Auth::user()->division_id;

            // 2. Tentukan Kategori Otomatis
            if ($request->has('is_certification')) {
                // Jika dicentang, cari kategori CERTIFICATION
                $categoryId = DocumentCategory::where('code', 'CERTIFICATION')->first()->id;
            } else {
                // Jika tidak, cari kategori berdasarkan Divisi Target
                $categoryId = $this->getCategoryByDivision($targetDivisionId);
            }

            $document = Document::create([
                'title' => $request->title,
                'category_id' => $categoryId,       // <--- Hasil logic otomatis
                'division_id' => $targetDivisionId, // <--- Penting untuk filtering
                'uploaded_by' => Auth::id(),
                'file_path' => $path,
                'current_version' => '1.0',
            ]);

            AuditLog::create([
                'user_id' => Auth::id(),
                'activity' => 'Create Document',
                'target' => $document->title,
                'target_type' => 'Document',
            ]);
        });

        return redirect()->back()->with('success', 'Document uploaded successfully!');
    }

    public function update(Request $request, Document $document)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'change_type' => 'required|in:major,minor',
            'document_file' => 'nullable|file|mimes:pdf,doc,docx|max:10240',
        ]);

        DB::transaction(function () use ($request, $document) {

            // Simpan Versi Lama
            DocumentVersion::create([
                'document_id' => $document->id,
                'version' => $document->current_version,
                'file_path' => $document->file_path,
                'change_note' => $request->change_note ?? 'Metadata update',
                'updated_by' => Auth::id(),
            ]);

            // Hitung Versi Baru
            $oldVersion = (float) $document->current_version;
            $newVersion = $request->change_type === 'major'
                ? floor($oldVersion) + 1 .'.0'
                : $oldVersion + 0.1;

            $newPath = $document->file_path;
            if ($request->hasFile('document_file')) {
                $newPath = $request->file('document_file')->store('documents/versions', 'public');
            }

            // Logic Update Kategori (Jika User mengubah checkbox)
            $newCategoryId = $document->category_id;

            // Cek apakah user mengubah status sertifikasi di edit modal
            if ($request->has('is_certification')) {
                $newCategoryId = DocumentCategory::where('code', 'CERTIFICATION')->first()->id;
            } else {
                // Jika di-uncheck, kembalikan ke kategori sesuai divisi dokumen tersebut
                $newCategoryId = $this->getCategoryByDivision($document->division_id);
            }

            $document->update([
                'title' => $request->title,
                'category_id' => $newCategoryId, // Update kategori jika berubah
                'current_version' => (string) $newVersion,
                'file_path' => $newPath,
            ]);

            AuditLog::create([
                'user_id' => Auth::id(),
                'activity' => 'Update Document to v'.$newVersion,
                'target' => $document->title,
                'target_type' => 'Document',
            ]);
        });

        return redirect()->back()->with('success', 'Document updated successfully!');
    }

    // ... Method show & destroy tetap sama ...
    public function show(Document $document)
    {
        $versions = $document->versions()->latest()->get();

        return view('documents.show', compact('document', 'versions'));
    }

    public function destroy(Document $document)
    {
        $pendingLoans = \App\Models\DocumentLoan::where('document_id', $document->id)
            ->where('status', 'Pending')->get();

        foreach ($pendingLoans as $loan) {
            $loan->update(['status' => 'Canceled', 'admin_note' => 'System: Document deleted.']);
        }
        $document->delete();

        AuditLog::create([
            'user_id' => Auth::id(),
            'activity' => 'Delete Document',
            'target' => $document->title,
            'target_type' => 'Document',
        ]);

        return redirect()->route('documents.index')->with('success', 'Document deleted.');
    }
}
