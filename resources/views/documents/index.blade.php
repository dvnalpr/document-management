@extends('layouts.app')

@section('content')
    <div class="space-y-6" x-data="{
        isAddModalOpen: false,
        isEditModalOpen: false,
        isDeleteModalOpen: false,
        editingDocument: null,
        deletingId: null,
        deletingTitle: '',
    
        openEditModal(doc) {
            this.editingDocument = doc;
            this.isEditModalOpen = true;
        },
    
    
        openDeleteModal(id, title) {
            this.deletingId = id;
            this.deletingTitle = title;
            this.isDeleteModalOpen = true;
        },
    
        updatePerPage(value) {
            const url = new URL(window.location.href);
            url.searchParams.set('per_page', value);
            url.searchParams.set('page', 1);
            window.location.href = url.toString();
        }
    }">

        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
            <div>
                <h2 class="text-3xl font-bold text-dark">Documents</h2>
                <p class="text-gray-500 mt-1">Manage list of all the documents</p>
            </div>

            <button @click="isAddModalOpen = true"
                class="bg-primary hover:bg-primary/90 text-white font-medium px-6 py-2.5 rounded-lg shadow-lg shadow-primary/30 transition transform active:scale-95 flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Add Document
            </button>
        </div>

        <div class="mb-8">
            <form method="GET" action="{{ route('documents.index') }}"
                class="flex flex-col md:flex-row gap-3 justify-start items-center w-full">

                <input type="hidden" name="per_page" value="{{ request('per_page', 10) }}">

                <div class="relative w-full md:w-auto" x-data="{ open: false, selected: '{{ request('category_id') }}' }">

                    <button type="button" @click="open = !open" @click.outside="open = false"
                        class="flex items-center justify-between gap-2 px-4 h-10 border border-gray-300 rounded-lg bg-white hover:bg-gray-50 text-dark transition shadow-sm min-w-40">

                        <div class="flex items-center gap-2">
                            <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4">
                                </path>
                            </svg>

                            <span class="font-medium text-sm truncate max-w-37.5">
                                {{ $categories->firstWhere('id', request('category_id'))->name ?? 'All Categories' }}
                            </span>
                        </div>

                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>

                    <input type="hidden" name="category_id" x-model="selected">

                    <div x-show="open" x-transition
                        class="absolute z-20 mt-1 w-64 bg-white rounded-lg shadow-xl border border-gray-100 ring-1 ring-black ring-opacity-5"
                        style="display: none;">
                        <div class="py-1">
                            <button type="button" @click="selected = ''; $nextTick(() => $el.closest('form').submit())"
                                class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-primary">
                                All Categories
                            </button>
                            @foreach ($categories as $category)
                                <button type="button"
                                    @click="selected = '{{ $category->id }}'; $nextTick(() => $el.closest('form').submit())"
                                    class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-primary">
                                    {{ $category->name }}
                                </button>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="relative w-full md:w-96">
                    <input type="text" name="search" placeholder="Search document..." value="{{ request('search') }}"
                        class="w-full h-10 pl-4 pr-10 border border-gray-300 rounded-lg focus:ring-1 focus:ring-primary focus:border-primary outline-none shadow-sm transition placeholder-gray-400 text-sm">

                    <button type="submit"
                        class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400 hover:text-primary">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </button>
                </div>
            </form>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 mb-10">
            @foreach ($stats as $stat)
                <div class="bg-white p-5 border border-gray-100 rounded-xl shadow-sm hover:shadow-md transition group">
                    <div class="text-sm text-gray-500 mb-1 group-hover:text-primary transition-colors">{{ $stat['label'] }}
                    </div>
                    <div class="text-3xl font-bold text-dark">{{ $stat['value'] }}</div>
                </div>
            @endforeach
        </div>

        <div class="bg-white rounded-lg border border-gray-200 overflow-hidden shadow-sm flex flex-col">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50/50 border-b border-gray-200 text-sm font-semibold text-gray-600">
                            <th class="p-4 pl-6 whitespace-nowrap">Document Name</th>
                            <th class="p-4 whitespace-nowrap">Version</th>
                            <th class="p-4 whitespace-nowrap">Date modified</th>
                            <th class="p-4 whitespace-nowrap">Category</th>
                            <th class="p-4 whitespace-nowrap">Issued by</th>
                            <th class="p-4 text-right pr-6">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 text-sm">
                        @forelse($documents as $doc)
                            <tr class="hover:bg-gray-50 transition group">

                                <td class="p-4 pl-6 font-medium text-dark">
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="w-8 h-8 rounded-full bg-gray-100 flex items-center justify-center text-gray-500 group-hover:bg-white group-hover:shadow-sm transition">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                                </path>
                                            </svg>
                                        </div>
                                        <a href="{{ route('documents.show', $doc->id) }}"
                                            class="hover:text-primary transition">
                                            {{ $doc->title }}
                                        </a>
                                    </div>
                                </td>

                                <td class="p-4 text-gray-500">v{{ $doc->current_version }}</td>

                                <td class="p-4 text-gray-500">{{ $doc->updated_at->format('d/m/Y') }}</td>

                                <td class="p-4">
                                    @php $catCode = $doc->category ? $doc->category->code : ''; @endphp

                                    @if ($catCode == 'QUALITY')
                                        <span
                                            class="px-3 py-1 rounded-full text-xs font-bold border border-blue-200 text-blue-600 bg-blue-50">Quality</span>
                                    @elseif($catCode == 'ENGINEERING')
                                        <span
                                            class="px-3 py-1 rounded-full text-xs font-bold border border-yellow-200 text-yellow-700 bg-yellow-50">Engineering</span>
                                    @elseif($catCode == 'CERTIFICATION')
                                        <span
                                            class="px-3 py-1 rounded-full text-xs font-bold border border-green-200 text-green-700 bg-green-50">Certification</span>
                                    @else
                                        <span
                                            class="px-3 py-1 rounded-full text-xs font-bold border border-gray-200 text-gray-600 bg-gray-50">{{ $doc->category->name ?? '-' }}</span>
                                    @endif
                                </td>

                                <td class="p-4 font-medium text-dark">{{ $doc->uploader->name ?? 'Unknown' }}</td>

                                <td class="p-4 pr-6 text-right">
                                    <div class="flex items-center justify-end gap-3">

                                        @hasanyrole('Manager|Admin')
                                            <button @click="openEditModal({{ $doc }})"
                                                class="text-gray-400 hover:text-blue-600 transition p-1 rounded-md hover:bg-blue-50"
                                                title="Edit Document">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z">
                                                    </path>
                                                </svg>
                                            </button>
                                        @endhasanyrole

                                        @hasanyrole('Manager|Admin')
                                            <button type="button"
                                                @click="openDeleteModal({{ $doc->id }}, '{{ $doc->title }}')"
                                                class="text-gray-400 hover:text-red-600 transition p-1 rounded-md hover:bg-red-50"
                                                title="Delete Document">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                    </path>
                                                </svg>
                                            </button>
                                        @endhasanyrole

                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="p-8 text-center text-gray-500">No documents found matching your
                                    criteria.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div
                class="p-4 border-t border-gray-200 bg-gray-50/50 flex flex-col md:flex-row justify-between items-center gap-4">

                <div class="flex items-center gap-2 text-sm text-gray-600">
                    <span>Show</span>
                    <select @change="updatePerPage($event.target.value)"
                        class="border border-gray-300 rounded px-2 py-1 bg-white focus:ring-1 focus:ring-primary focus:border-primary outline-none cursor-pointer">
                        <option value="10" {{ request('per_page') == 10 ? 'selected' : '' }}>10</option>
                        <option value="20" {{ request('per_page') == 20 ? 'selected' : '' }}>20</option>
                        <option value="30" {{ request('per_page') == 30 ? 'selected' : '' }}>30</option>
                    </select>
                    <span>entries</span>
                </div>

                @if ($documents->hasPages())
                    <div class="flex items-center gap-1">
                        {{-- First & Prev --}}
                        @if ($documents->onFirstPage())
                            <button disabled
                                class="w-8 h-8 flex items-center justify-center rounded border border-gray-200 text-gray-300 bg-white cursor-not-allowed"><span
                                    class="font-mono font-bold text-xs">|&lt;</span></button>
                            <button disabled
                                class="w-8 h-8 flex items-center justify-center rounded border border-gray-200 text-gray-300 bg-white cursor-not-allowed"><span
                                    class="font-mono font-bold">&lt;</span></button>
                        @else
                            <a href="{{ $documents->url(1) }}"
                                class="w-8 h-8 flex items-center justify-center rounded border border-gray-300 text-gray-500 bg-white hover:bg-gray-50 hover:text-primary transition"><span
                                    class="font-mono font-bold text-xs">|&lt;</span></a>
                            <a href="{{ $documents->previousPageUrl() }}"
                                class="w-8 h-8 flex items-center justify-center rounded border border-gray-300 text-gray-500 bg-white hover:bg-gray-50 hover:text-primary transition"><span
                                    class="font-mono font-bold">&lt;</span></a>
                        @endif

                        {{-- Page Numbers --}}
                        @foreach ($documents->getUrlRange(max(1, $documents->currentPage() - 2), min($documents->lastPage(), $documents->currentPage() + 2)) as $page => $url)
                            @if ($page == $documents->currentPage())
                                <button
                                    class="w-8 h-8 flex items-center justify-center rounded bg-primary text-white font-bold shadow-sm border border-primary pointer-events-none">{{ $page }}</button>
                            @else
                                <a href="{{ $url }}"
                                    class="w-8 h-8 flex items-center justify-center rounded border border-gray-300 text-gray-600 bg-white hover:bg-gray-50 hover:text-primary transition">{{ $page }}</a>
                            @endif
                        @endforeach

                        {{-- Next & Last --}}
                        @if ($documents->hasMorePages())
                            <a href="{{ $documents->nextPageUrl() }}"
                                class="w-8 h-8 flex items-center justify-center rounded border border-gray-300 text-gray-500 bg-white hover:bg-gray-50 hover:text-primary transition"><span
                                    class="font-mono font-bold">&gt;</span></a>
                            <a href="{{ $documents->url($documents->lastPage()) }}"
                                class="w-8 h-8 flex items-center justify-center rounded border border-gray-300 text-gray-500 bg-white hover:bg-gray-50 hover:text-primary transition"><span
                                    class="font-mono font-bold text-xs">&gt;|</span></a>
                        @else
                            <button disabled
                                class="w-8 h-8 flex items-center justify-center rounded border border-gray-200 text-gray-300 bg-white cursor-not-allowed"><span
                                    class="font-mono font-bold">&gt;</span></button>
                            <button disabled
                                class="w-8 h-8 flex items-center justify-center rounded border border-gray-200 text-gray-300 bg-white cursor-not-allowed"><span
                                    class="font-mono font-bold text-xs">&gt;|</span></button>
                        @endif
                    </div>
                @endif
            </div>
        </div>

        <x-drawer name="isAddModalOpen" title="Add Documents">
            <form action="{{ route('documents.store') }}" method="POST" enctype="multipart/form-data" id="addDocForm"
                class="space-y-6">
                @csrf
                <div class="space-y-2">
                    <label class="block text-sm font-bold text-dark">Document name</label>
                    <input type="text" name="title" placeholder="Enter here"
                        class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:ring-1 focus:ring-primary focus:border-primary outline-none transition"
                        required>
                </div>
                <div class="space-y-2" x-data="{ fileName: null, fileSize: null }"
                    x-effect="if(!isAddModalOpen){fileName=null; fileSize=null; if($refs.addInput) $refs.addInput.value = '';}">
                    <label class="block text-sm font-bold text-dark">Upload</label>
                    <div
                        class="relative border-2 border-dashed border-gray-300 rounded-lg bg-gray-50 hover:bg-gray-100 transition p-6 text-center group">

                        <input type="file" name="document_file"
                            class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10" required
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
                            <p class="text-sm text-gray-500 mb-4">Upload relevant document. Max 10MB.</p>
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
                            <input id="is_certification_add" name="is_certification" type="checkbox" value="1"
                                class="w-5 h-5 text-primary border-gray-300 rounded focus:ring-primary cursor-pointer">
                        </div>
                        <div class="ml-2 text-sm">
                            <label for="is_certification_add" class="font-bold text-dark cursor-pointer">
                                Is this a Certification Document?
                            </label>
                            <p class="text-xs text-gray-500">
                                If checked, this document will be visible to <b>All Divisions</b>. <br>
                                If unchecked, it will be automatically assigned to your Division's category.
                            </p>
                        </div>
                    </div>
                </div>

                @role('Admin')
                    <div class="space-y-2 bg-yellow-50 p-3 rounded-lg border border-yellow-100">
                        <label class="block text-sm font-bold text-dark">Assign to Division <span
                                class="text-red-500">*</span></label>
                        <p class="text-xs text-gray-500 mb-2">Since you are Admin, please specify which division owns this
                            document.</p>
                        <div class="relative">
                            <select name="division_id"
                                class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:ring-1 focus:ring-primary focus:border-primary outline-none transition appearance-none bg-white text-dark"
                                required>
                                <option value="" disabled selected>Select Target Division</option>
                                @foreach ($division as $div)
                                    <option value="{{ $div->id }}">{{ $div->name }}</option>
                                @endforeach
                            </select>
                            <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none text-gray-500">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7">
                                    </path>
                                </svg>
                            </div>
                        </div>
                    </div>
                @endrole
            </form>
            <x-slot:footer>
                <button @click="isAddModalOpen = false" type="button"
                    class="px-6 py-2.5 rounded-lg bg-gray-200 text-gray-700 font-bold hover:bg-gray-300 transition">Cancel</button>
                <button onclick="document.getElementById('addDocForm').submit()"
                    class="px-6 py-2.5 rounded-lg bg-primary text-white font-bold hover:bg-primary/90 shadow-md transition transform active:scale-95">Upload
                    Document</button>
            </x-slot:footer>
        </x-drawer>

        <x-drawer name="isEditModalOpen" title="Update Documents">
            <form :action="'{{ url('documents') }}/' + editingDocument?.id" method="POST" enctype="multipart/form-data"
                id="editDocForm" class="space-y-6">
                @csrf @method('PUT')

                <div class="space-y-2">
                    <label class="block text-sm font-bold text-dark">Document name</label>
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
                    <label class="block text-sm font-bold text-dark">Change type</label>
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
                <button @click="isEditModalOpen = false" type="button"
                    class="px-6 py-2.5 rounded-lg bg-gray-200 text-gray-700 font-bold hover:bg-gray-300 transition">Cancel</button>
                <button onclick="document.getElementById('editDocForm').submit()"
                    class="px-6 py-2.5 rounded-lg bg-primary text-white font-bold hover:bg-primary/90 shadow-md transition transform active:scale-95">Update
                    Document</button>
            </x-slot:footer>
        </x-drawer>

        <div x-show="isDeleteModalOpen" style="display: none;"
            class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm p-4 transition-opacity">

            <div x-show="isDeleteModalOpen" x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 scale-100"
                x-transition:leave-end="opacity-0 scale-95" @click.outside="isDeleteModalOpen = false"
                class="bg-white rounded-xl shadow-2xl w-full max-w-sm overflow-hidden border border-gray-100 text-center p-6">

                <div class="mx-auto w-16 h-16 bg-red-100 text-red-600 rounded-full flex items-center justify-center mb-4">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                        </path>
                    </svg>
                </div>

                <h3 class="text-xl font-bold text-dark mb-2">Delete Document?</h3>
                <p class="text-gray-500 mb-6 text-sm">
                    Are you sure you want to delete <br>
                    "<span class="font-bold text-dark" x-text="deletingTitle"></span>"? <br>
                    This action cannot be undone.
                </p>

                <div class="flex gap-3 justify-center">
                    <button @click="isDeleteModalOpen = false"
                        class="px-5 py-2.5 rounded-lg border border-gray-300 text-gray-700 font-bold text-sm hover:bg-gray-50 transition">
                        Cancel
                    </button>

                    <form :action="'{{ url('documents') }}/' + deletingId" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                            class="px-5 py-2.5 rounded-lg bg-red-600 text-white font-bold text-sm hover:bg-red-700 shadow-md transition transform active:scale-95">
                            Yes, Delete it
                        </button>
                    </form>
                </div>
            </div>
        </div>

    </div>
@endsection
