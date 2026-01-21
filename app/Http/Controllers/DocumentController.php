<?php

namespace App\Http\Controllers;

use App\Models\Division;
use App\Models\Document;
use App\Models\DocumentCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class DocumentController extends Controller
{
    /**
     * Display a listing of documents
     */
    public function index(Request $request)
    {
        $this->authorize('viewAny', Document::class);

        $query = Document::with(['category', 'division', 'uploader'])
            ->accessibleBy(auth()->user());

        // Search
        if ($request->filled('search')) {
            $query->search($request->search);
        }

        // Filter by category
        if ($request->filled('category')) {
            $query->byCategory($request->category);
        }

        // Filter by division
        if ($request->filled('division')) {
            $query->byDivision($request->division);
        }

        // Filter by date range
        if ($request->filled('from_date')) {
            $query->whereDate('created_at', '>=', $request->from_date);
        }

        if ($request->filled('to_date')) {
            $query->whereDate('created_at', '<=', $request->to_date);
        }

        // Sort
        $sortBy = $request->get('sort', 'created_at');
        $sortOrder = $request->get('order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        $documents = $query->paginate(15);

        // For filters
        $categories = DocumentCategory::all();
        $divisions = Division::all();

        return view('documents.index', compact('documents', 'categories', 'divisions'));
    }

    /**
     * Show the form for creating a new document
     */
    public function create()
    {
        $this->authorize('create', Document::class);

        $categories = DocumentCategory::all();
        $divisions = Division::all();

        return view('documents.create', compact('categories', 'divisions'));
    }

    /**
     * Store a newly created document
     */
    public function store(Request $request)
    {
        $this->authorize('create', Document::class);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category_id' => 'required|exists:document_categories,id',
            'division_id' => 'nullable|exists:divisions,id',
            'file' => 'required|file|max:10240|mimes:pdf,doc,docx,xls,xlsx,jpg,jpeg,png',
        ]);

        // Handle file upload
        $file = $request->file('file');
        $fileName = time().'_'.Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)).'.'.$file->getClientOriginalExtension();
        $filePath = $file->storeAs('documents/originals', $fileName, 'private');

        // Create document
        $document = Document::create([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'category_id' => $validated['category_id'],
            'division_id' => $validated['division_id'] ?? auth()->user()->division_id,
            'current_version' => '1.0',
            'file_path' => $filePath,
            'file_name' => $file->getClientOriginalName(),
            'file_size' => $file->getSize(),
            'mime_type' => $file->getMimeType(),
            'uploaded_by' => auth()->id(),
        ]);

        // Log activity (optional)
        // AuditLog::create([...]);

        return redirect()->route('documents.show', $document)
            ->with('success', 'Document uploaded successfully!');
    }

    /**
     * Display the specified document
     */
    public function show(Document $document)
    {
        $this->authorize('view', $document);

        $document->load(['category', 'division', 'uploader', 'versions', 'loans']);

        return view('documents.show', compact('document'));
    }

    /**
     * Show the form for editing the document
     */
    public function edit(Document $document)
    {
        $this->authorize('update', $document);

        $categories = DocumentCategory::all();
        $divisions = Division::all();

        return view('documents.edit', compact('document', 'categories', 'divisions'));
    }

    /**
     * Update the specified document
     */
    public function update(Request $request, Document $document)
    {
        $this->authorize('update', $document);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category_id' => 'required|exists:document_categories,id',
            'division_id' => 'nullable|exists:divisions,id',
            'file' => 'nullable|file|max:10240|mimes:pdf,doc,docx,xls,xlsx,jpg,jpeg,png',
            'change_notes' => 'required_with:file|string',
        ]);

        // Update metadata
        $document->update([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'category_id' => $validated['category_id'],
            'division_id' => $validated['division_id'],
        ]);

        // If new file uploaded, create new version
        if ($request->hasFile('file')) {
            $this->createNewVersion($document, $request->file('file'), $validated['change_notes']);
        }

        return redirect()->route('documents.show', $document)
            ->with('success', 'Document updated successfully!');
    }

    /**
     * Remove the specified document
     */
    public function destroy(Document $document)
    {
        $this->authorize('delete', $document);

        // Soft delete
        $document->delete();

        return redirect()->route('documents.index')
            ->with('success', 'Document deleted successfully!');
    }

    /**
     * Download document
     */
    public function download(Document $document)
    {
        $this->authorize('download', $document);

        // Log download (optional)
        // AuditLog::create([...]);

        return Storage::disk('private')->download($document->file_path, $document->file_name);
    }

    /**
     * Preview document
     */
    public function preview(Document $document)
    {
        $this->authorize('view', $document);

        if (! $document->isPreviewable()) {
            abort(400, 'This document type cannot be previewed');
        }

        return response()->file(Storage::disk('private')->path($document->file_path));
    }

    /**
     * Create new version
     */
    private function createNewVersion(Document $document, $file, $changeNotes)
    {
        // Save current version to history
        $document->versions()->create([
            'version_number' => $document->current_version,
            'file_path' => $document->file_path,
            'file_name' => $document->file_name,
            'change_notes' => $changeNotes,
            'uploaded_by' => auth()->id(),
        ]);

        // Increment version number
        $versionParts = explode('.', $document->current_version);
        $versionParts[1]++; // Minor version
        $newVersion = implode('.', $versionParts);

        // Upload new file
        $fileName = time().'_'.Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)).'.'.$file->getClientOriginalExtension();
        $filePath = $file->storeAs('documents/originals', $fileName, 'private');

        // Update document
        $document->update([
            'current_version' => $newVersion,
            'file_path' => $filePath,
            'file_name' => $file->getClientOriginalName(),
            'file_size' => $file->getSize(),
            'mime_type' => $file->getMimeType(),
        ]);
    }
}
