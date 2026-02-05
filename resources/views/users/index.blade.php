@extends('layouts.app')

@section('content')
<div class="space-y-6">
    
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
        <div>
            <h2 class="text-3xl font-bold text-dark">Manage User</h2>
            <p class="text-gray-500 mt-1">Manage list of all user</p>
        </div>
        
        <a href="{{ route('users.create') }}" class="bg-primary hover:bg-primary/90 text-white font-medium px-6 py-2.5 rounded-lg shadow-lg shadow-primary/30 transition transform active:scale-95">
            Add User
        </a>
    </div>

    <div class="flex flex-col md:flex-row gap-4 mb-8">
        <button class="flex items-center gap-2 px-4 py-2 border border-gray-300 rounded-md bg-white hover:bg-gray-50 text-dark transition shadow-sm">
            <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"></path></svg>
            <span class="font-medium">Filter</span>
            <svg class="w-4 h-4 text-gray-400 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
        </button>

        <div class="relative w-full max-w-md">
            <input type="text" placeholder="Search" class="w-full pl-4 pr-10 py-2 border border-gray-300 rounded-md focus:ring-1 focus:ring-primary focus:border-primary outline-none shadow-sm transition">
            <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-10">
        @foreach($stats as $stat)
        <div class="bg-white p-5 border border-gray-100 rounded-xl shadow-sm hover:shadow-md transition group">
            <div class="text-sm text-gray-500 mb-1 group-hover:text-primary transition-colors">{{ $stat['label'] }}</div>
            <div class="text-3xl font-bold text-dark">{{ $stat['value'] }}</div>
        </div>
        @endforeach
    </div>

    <div class="bg-white rounded-lg border border-gray-200 overflow-hidden shadow-sm">
        
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50/50 border-b border-gray-200 text-sm font-semibold text-gray-600">
                        <th class="p-4 pl-6 whitespace-nowrap">Username</th>
                        <th class="p-4 whitespace-nowrap">Email</th>
                        <th class="p-4 whitespace-nowrap">Date added</th>
                        <th class="p-4 whitespace-nowrap">Departement</th>
                        <th class="p-4 whitespace-nowrap">Status</th>
                        <th class="p-4 text-right pr-6">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-sm">
                    @foreach($users as $user)
                    <tr class="hover:bg-gray-50 transition group">
                        
                        <td class="p-4 pl-6 font-medium text-dark">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full border border-gray-300 flex items-center justify-center text-gray-600 bg-white">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                </div>
                                {{ $user['name'] }}
                            </div>
                        </td>

                        <td class="p-4 text-gray-500">{{ $user['email'] }}</td>

                        <td class="p-4 text-gray-500">{{ $user['date_added'] }}</td>
                        
                        <td class="p-4">
                            @if($user['department'] == 'QA')
                                <span class="px-3 py-1 rounded-full text-xs font-bold border border-blue-200 text-blue-600 bg-blue-50">QA</span>
                            @elseif($user['department'] == 'ME')
                                <span class="px-3 py-1 rounded-full text-xs font-bold border border-yellow-200 text-yellow-700 bg-yellow-50">ME</span>
                            @else
                                <span class="px-3 py-1 rounded-full text-xs font-bold border border-gray-200 text-gray-600 bg-gray-50">{{ $user['department'] }}</span>
                            @endif
                        </td>

                        <td class="p-4">
                            @if($user['status'] == 'Active')
                                <span class="px-3 py-1 rounded-full text-xs font-bold border border-green-200 text-green-600 bg-green-50">Active</span>
                            @else
                                <span class="px-3 py-1 rounded-full text-xs font-bold border border-red-200 text-red-600 bg-red-50">Inactive</span>
                            @endif
                        </td>

                        <td class="p-4 pr-6 text-right">
                            <div class="flex items-center justify-end gap-3">
                                <a href="#" class="text-gray-400 hover:text-blue-600 transition" title="Edit">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                </a>

                                <button type="button" class="text-gray-400 hover:text-red-600 transition" title="Delete" onclick="return confirm('Are you sure?')">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t border-gray-200 flex justify-end gap-2 bg-gray-50/50">
            <button class="w-8 h-8 flex items-center justify-center rounded border border-gray-300 text-gray-400 hover:bg-white hover:text-primary transition disabled:opacity-50">
                &laquo;
            </button>
            <button class="w-8 h-8 flex items-center justify-center rounded border border-gray-300 text-gray-400 hover:bg-white hover:text-primary transition">
                &lsaquo;
            </button>
            
            <button class="w-8 h-8 flex items-center justify-center rounded bg-primary text-white font-bold shadow-sm">1</button>
            <button class="w-8 h-8 flex items-center justify-center rounded border border-gray-300 text-gray-600 hover:bg-white hover:text-primary transition">2</button>
            
            <button class="w-8 h-8 flex items-center justify-center rounded border border-gray-300 text-gray-400 hover:bg-white hover:text-primary transition">
                &rsaquo;
            </button>
            <button class="w-8 h-8 flex items-center justify-center rounded border border-gray-300 text-gray-400 hover:bg-white hover:text-primary transition">
                &raquo;
            </button>
        </div>
    </div>
</div>
@endsection