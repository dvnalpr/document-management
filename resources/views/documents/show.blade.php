@extends('layouts.app')

@section('content')
    <div class="max-w-5xl mx-auto space-y-8" x-data="{
        isEditModalOpen: {{ $errors->has('title') || $errors->has('document_file') ? 'true' : 'false' }},
        isBorrowModalOpen: {{ $errors->has('note') || $errors->has('duration') ? 'true' : 'false' }}
    }">

        <div class="flex flex-col md:flex-row md:items-start justify-between gap-4">
            <div>
                <div class="mb-2">
                    <a href="{{ route('documents.index') }}"
                        class="inline-flex items-center text-gray-500 hover:text-primary transition gap-1 text-sm font-medium">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Back to Documents
                    </a>
                </div>
                <h2 class="text-3xl font-bold text-dark">Preview</h2>
                <p class="text-gray-500 mt-1">Preview of the document</p>
            </div>
        </div>

        <div class="bg-white rounded-xl border border-gray-200 p-6 md:p-8 shadow-sm">
            <div class="space-y-6">
                <div class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-[200px_1fr] gap-4">
                        <div>
                            <label class="block text-sm font-bold text-dark mb-1">Document name :</label>
                            <div class="text-gray-700 text-lg font-medium">{{ $document->title }}</div>
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-dark mb-1">Category :</label>
                            <div class="text-gray-700">{{ $document->category->name ?? '-' }}</div>
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-dark mb-1">Current Version :</label>
                            <span class="bg-blue-100 text-primary text-xs font-bold px-2 py-1 rounded">
                                v{{ $document->current_version }}
                            </span>
                        </div>
                    </div>
                </div>

                <div class="flex items-center gap-4 pt-4 border-t border-gray-100">
                    <button @click="isEditModalOpen = true"
                        class="bg-primary hover:bg-primary/90 text-white font-bold px-6 py-2.5 rounded-lg shadow-md transition transform active:scale-95">
                        Update
                    </button>

                    <button @click="isBorrowModalOpen = true" type="button"
                        class="bg-gray-100 hover:bg-gray-200 text-dark font-bold px-6 py-2.5 rounded-lg border border-gray-200 shadow-sm transition transform active:scale-95">
                        Borrow
                    </button>

                    <a href="{{ Storage::url($document->file_path) }}" target="_blank"
                        class="text-gray-500 hover:text-primary font-medium px-4 py-2.5 transition flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                        </svg>
                        Download PDF
                    </a>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl border border-gray-200 overflow-hidden shadow-sm">
            <div class="p-6 border-b border-gray-200">
                <h3 class="text-lg font-bold text-dark">Version History</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50 text-xs uppercase text-gray-500 font-semibold">
                            <th class="p-4 pl-6">Version</th>
                            <th class="p-4">Date Modified</th>
                            <th class="p-4">Updated By</th>
                            <th class="p-4">Change Note</th>
                            <th class="p-4 text-right pr-6">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 text-sm">
                        @forelse($versions as $ver)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="p-4 pl-6 font-medium text-dark">v{{ $ver->version }}</td>
                                <td class="p-4 text-gray-500">{{ $ver->created_at->format('d M Y, H:i') }}</td>
                                <td class="p-4 text-gray-700">{{ $ver->updater->name ?? 'Unknown' }}</td>
                                <td class="p-4 text-gray-500 italic">"{{ $ver->change_note }}"</td>
                                <td class="p-4 pr-6 text-right">
                                    <a href="{{ Storage::url($ver->file_path) }}" target="_blank"
                                        class="text-primary hover:underline text-xs font-bold">
                                        View File
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="p-6 text-center text-gray-400">No history available.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <x-drawer name="isEditModalOpen" title="Update Documents">
            <form :action="'{{ url('documents') }}/' + editingDocument?.id" method="POST" enctype="multipart/form-data"
                id="editDocForm" class="space-y-6">
                @csrf @method('PUT')

                <div class="space-y-2">
                    <label class="block text-sm font-bold text-dark">
                        Document name <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="title" :value="editingDocument?.title"
                        class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:ring-1 focus:ring-primary focus:border-primary outline-none transition"
                        required>
                </div>

                <div class="space-y-2" x-data="{ fileName: null, fileSize: null }"
                    x-effect="if(!isEditModalOpen){fileName=null; fileSize=null; if($refs.editInput) $refs.editInput.value = '';}">
                    <label class="block text-sm font-bold text-dark">Upload New File</label>
                    <div
                        class="relative border-2 border-dashed border-gray-300 rounded-lg bg-gray-50 hover:bg-gray-100 transition p-6 text-center group">

                        <input type="file" name="document_file"
                            class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10"
                            @change="
                                                                    const file = $el.files[0];
                                                                    if(file){
                                                                        fileName = file.name;
                                                                        fileSize = (file.size < 1024 * 1024) 
                                                                            ? (file.size / 1024).toFixed(2) + ' KB' 
                                                                            : (file.size / (1024 * 1024)).toFixed(2) + ' MB';
                                                                    }
                                                                ">

                        <div x-show="!fileName">
                            <p class="text-sm text-gray-500 mb-4">Upload new version here.</p>
                            <button type="button"
                                class="inline-flex items-center px-4 py-2 bg-white border border-dashed border-gray-400 rounded-md text-sm font-medium text-dark shadow-sm group-hover:border-primary group-hover:text-primary transition">
                                Add document
                            </button>
                        </div>

                        <div x-show="fileName" class="flex flex-col items-center justify-center" style="display: none;">
                            <div
                                class="w-12 h-12 bg-blue-100 text-primary rounded-full flex items-center justify-center mb-2">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                    </path>
                                </svg>
                            </div>
                            <p class="font-bold text-sm text-dark break-all" x-text="fileName"></p>
                            <p class="text-xs text-gray-500 mt-1" x-text="fileSize"></p>
                            <p class="text-xs text-primary mt-3 font-medium">Click to change file</p>
                        </div>
                    </div>
                </div>

                <div class="space-y-2">
                    <div class="flex items-center gap-3 p-4 border border-gray-200 rounded-lg bg-gray-50/50">
                        <div class="flex items-center h-5">
                            <input id="is_certification_edit" name="is_certification" type="checkbox" value="1"
                                :checked="editingDocument?.category?.code === 'CERTIFICATION'"
                                class="w-5 h-5 text-primary border-gray-300 rounded focus:ring-primary cursor-pointer">
                        </div>
                        <div class="ml-2 text-sm">
                            <label for="is_certification_edit" class="font-bold text-dark cursor-pointer">
                                Is this a Certification Document?
                            </label>
                            <p class="text-xs text-gray-500">Check to make this document public as Certification.</p>
                        </div>
                    </div>
                </div>

                <div class="space-y-2">
                    <label class="block text-sm font-bold text-dark">
                        Change type <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <select name="change_type"
                            class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:ring-1 focus:ring-primary focus:border-primary outline-none transition appearance-none bg-white text-dark">
                            <option value="major">Major (1.0 -> 2.0)</option>
                            <option value="minor">Minor (1.0 -> 1.1)</option>
                        </select>
                        <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none text-gray-500">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7">
                                </path>
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="space-y-2">
                    <label class="block text-sm font-bold text-dark">Change note</label>
                    <textarea name="change_note" rows="3" placeholder="Enter reason for update..."
                        class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:ring-1 focus:ring-primary focus:border-primary outline-none transition"></textarea>
                </div>
            </form>
            <x-slot:footer>
                <div class="flex items-center justify-end gap-3 w-full">
                    <button @click="isEditModalOpen = false" type="button"
                        class="px-6 py-2.5 rounded-lg bg-gray-200 text-gray-700 font-bold hover:bg-gray-300 transition">
                        Cancel
                    </button>
                    <button type="submit" form="editDocForm"
                        class="px-6 py-2.5 rounded-lg bg-primary text-white font-bold hover:bg-primary/90 shadow-md transition transform active:scale-95">
                        Update Document
                    </button>
                </div>
            </x-slot:footer>
        </x-drawer>

        <x-drawer name="isBorrowModalOpen" title="Borrow document">
            <form action="{{ route('loans.store') }}" method="POST" id="borrowDocForm" class="space-y-6">
                @csrf
                <input type="hidden" name="document_id" value="{{ $document->id }}">

                <div class="space-y-3 pb-4 border-b border-gray-100">
                    <h4 class="font-bold text-dark">Metadata</h4>
                    <div class="grid grid-cols-[140px_1fr] gap-y-2 text-sm">
                        <span class="font-medium text-dark">Document name :</span>
                        <span class="text-gray-600">{{ $document->title }}</span>
                        <span class="font-medium text-dark">Category :</span>
                        <span class="text-gray-600">{{ $document->category->name ?? '-' }}</span>
                        <span class="font-medium text-dark">Current Version :</span>
                        <span class="text-gray-600">v{{ $document->current_version }}</span>
                    </div>
                </div>

                <div class="space-y-2">
                    <label class="block text-sm font-bold text-dark">Duration</label>
                    <div class="relative">
                        <select name="duration" required
                            class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:ring-1 focus:ring-primary focus:border-primary outline-none transition appearance-none bg-white text-dark">
                            <option value="" disabled selected>Select Duration</option>
                            <option value="1 Week">1 Week</option>
                            <option value="2 Weeks">2 Weeks</option>
                            <option value="1 Month">1 Month</option>
                        </select>
                        <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none text-gray-500">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7">
                                </path>
                            </svg>
                        </div>
                    </div>
                    @error('duration')
                        <span class="text-red-500 text-xs">{{ $message }}</span>
                    @enderror
                </div>

                <div class="space-y-2">
                    <label class="block text-sm font-bold text-dark">Note</label>
                    <textarea name="note" rows="4" placeholder="Reason for borrowing..." required
                        class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:ring-1 focus:ring-primary focus:border-primary outline-none transition"></textarea>
                    @error('note')
                        <span class="text-red-500 text-xs">{{ $message }}</span>
                    @enderror
                </div>
            </form>
            <x-slot:footer>
                <button @click="isBorrowModalOpen = false" type="button"
                    class="px-6 py-2.5 rounded-lg bg-gray-200 text-gray-700 font-bold hover:bg-gray-300 transition">Cancel</button>
                <button type="submit" form="borrowDocForm"
                    class="px-6 py-2.5 rounded-lg bg-primary text-white font-bold hover:bg-primary/90 shadow-md transition transform active:scale-95">Request</button>
            </x-slot:footer>
        </x-drawer>

    </div>
@endsection
