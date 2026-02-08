@extends('layouts.app')

@section('content')
    <div class="space-y-6">

        <div class="mb-8">
            <h2 class="text-3xl font-bold text-dark">Dashboard</h2>
            <p class="text-gray-500">Overview of the documents</p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4 mb-10">
            @foreach($stats as $stat)
                <div class="bg-white p-5 border border-gray-100 rounded-xl shadow-sm hover:shadow-md transition group">
                    <div class="text-sm text-gray-500 mb-1 group-hover:text-primary transition-colors">{{ $stat['label'] }}
                    </div>
                    <div class="text-3xl font-bold text-dark">{{ number_format($stat['value']) }}</div>
                </div>
            @endforeach
        </div>

        <div class="bg-white rounded-lg border border-gray-200 overflow-hidden shadow-sm">
            <div class="p-6 border-b border-gray-200 flex justify-between items-center">
                <h3 class="text-xl font-bold text-dark">Recently added</h3>
                <a href="{{ route('documents.index') }}" class="text-sm text-primary font-bold hover:underline">View All</a>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50/50 border-b border-gray-200 text-sm font-semibold text-gray-600">
                            <th class="p-4 pl-6">Document Name</th>
                            <th class="p-4">Version</th>
                            <th class="p-4">Date modified</th>
                            <th class="p-4">Category</th>
                            <th class="p-4">Issued by</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 text-sm">
                        @forelse($recentDocs as $doc)
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
                                        <a href="{{ route('documents.show', $doc->id) }}" class="hover:text-primary transition">
                                            {{ $doc->title }}
                                        </a>
                                    </div>
                                </td>

                                <td class="p-4 text-gray-500">v{{ $doc->current_version }}</td>

                                <td class="p-4 text-gray-500">{{ $doc->updated_at->format('d/m/Y') }}</td>

                                <td class="p-4">
                                    @php $catCode = $doc->category ? $doc->category->code : ''; @endphp
                                    @if($catCode == 'QUALITY')
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

                                <td class="p-4 font-medium text-dark">
                                    {{ $doc->uploader->name ?? 'Unknown' }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="p-8 text-center text-gray-500">
                                    No recent documents found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection