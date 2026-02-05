@extends('layouts.app')

@section('content')
<div class="space-y-6">
    
    <div class="mb-8">
        <h2 class="text-3xl font-bold text-dark">Dashboard</h2>
        <p class="text-gray-500">Overview of all the documents</p>
    </div>

    <div class="flex flex-col md:flex-row gap-4 mb-8">
        <button class="flex items-center gap-2 px-4 py-2 border border-gray-300 rounded-md bg-white hover:bg-gray-50 text-dark transition">
            <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"></path></svg>
            <span class="font-medium">Filter</span>
            <svg class="w-4 h-4 text-gray-400 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
        </button>

        <div class="relative w-full max-w-md">
            <input type="text" placeholder="Search" class="w-full pl-4 pr-10 py-2 border border-gray-300 rounded-md focus:ring-1 focus:ring-primary focus:border-primary outline-none">
            <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4 mb-10">
        @foreach($stats as $stat)
        <div class="bg-white p-5 border border-gray-100 rounded-xl shadow-sm hover:shadow-md transition">
            <div class="text-sm text-gray-500 mb-1">{{ $stat['label'] }}</div>
            <div class="text-3xl font-bold text-dark">{{ $stat['value'] }}</div>
        </div>
        @endforeach
    </div>

    <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
        <div class="p-6 border-b border-gray-200">
            <h3 class="text-xl font-bold text-dark">Recently added</h3>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-gray-200 text-sm font-semibold text-gray-600">
                        <th class="p-4 pl-6">Document Name</th>
                        <th class="p-4">Document Version</th>
                        <th class="p-4">Date modified</th>
                        <th class="p-4">Category</th>
                        <th class="p-4">Issued by</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-sm">
                    @foreach($recentDocs as $doc)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="p-4 pl-6 font-medium text-dark flex items-center gap-3">
                            <div class="w-8 h-8 rounded-full bg-gray-100 flex items-center justify-center text-gray-500">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                            </div>
                            {{ $doc['name'] }}
                        </td>
                        <td class="p-4 text-gray-500">{{ $doc['version'] }}</td>
                        <td class="p-4 text-gray-500">{{ $doc['date'] }}</td>
                        <td class="p-4">
                            @if($doc['category'] == 'QA')
                                <span class="px-3 py-1 rounded-full text-xs font-semibold border border-blue-300 text-blue-600 bg-blue-50">QA</span>
                            @elseif($doc['category'] == 'ME')
                                <span class="px-3 py-1 rounded-full text-xs font-semibold border border-yellow-300 text-yellow-700 bg-yellow-50">ME</span>
                            @else
                                <span class="px-3 py-1 rounded-full text-xs font-semibold border border-gray-300 text-gray-600 bg-gray-50">{{ $doc['category'] }}</span>
                            @endif
                        </td>
                        <td class="p-4 font-medium text-dark">{{ $doc['user'] }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t border-gray-200 flex justify-end gap-2">
            <button class="w-8 h-8 flex items-center justify-center rounded border border-gray-300 text-gray-400 hover:bg-gray-50 disabled:opacity-50">
                &laquo;
            </button>
            <button class="w-8 h-8 flex items-center justify-center rounded border border-gray-300 text-gray-400 hover:bg-gray-50">
                &lsaquo;
            </button>
            
            <button class="w-8 h-8 flex items-center justify-center rounded bg-primary text-white font-bold">1</button>
            <button class="w-8 h-8 flex items-center justify-center rounded border border-gray-300 text-gray-600 hover:bg-gray-50">2</button>
            
            <button class="w-8 h-8 flex items-center justify-center rounded border border-gray-300 text-gray-400 hover:bg-gray-50">
                &rsaquo;
            </button>
            <button class="w-8 h-8 flex items-center justify-center rounded border border-gray-300 text-gray-400 hover:bg-gray-50">
                &raquo;
            </button>
        </div>
    </div>
</div>
@endsection