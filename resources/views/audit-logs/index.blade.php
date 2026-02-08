@extends('layouts.app')

@section('content')
    <div class="space-y-6">

        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
            <div>
                <h2 class="text-3xl font-bold text-dark">Audit Log</h2>
                <p class="text-gray-500 mt-1">Track all system activities and history</p>
            </div>

            <a href="{{ route('audit-logs.export', request()->all()) }}"
                class="bg-primary hover:bg-primary/90 text-white font-medium px-6 py-2.5 rounded-lg shadow-lg shadow-primary/30 transition transform active:scale-95 flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                </svg>
                Export CSV
            </a>
        </div>

        <div class="mb-8">
            <form method="GET" action="{{ route('audit-logs.index') }}"
                class="flex flex-col md:flex-row gap-4 justify-start items-center w-full">

                <div class="relative w-full md:w-auto" x-data="{ open: false, sort: '{{ request('sort', 'latest') }}' }">

                    <button type="button" @click="open = !open" @click.outside="open = false"
                        class="flex items-center justify-between gap-2 px-4 h-10 border border-gray-300 rounded-lg bg-white hover:bg-gray-50 text-dark transition shadow-sm min-w-35">
                        <div class="flex items-center gap-2">
                            <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 4h13M3 8h9m-9 4h6m4 0l4-4m0 0l4 4m-4-4v12"></path>
                            </svg>
                            <span class="font-medium text-sm"
                                x-text="sort === 'latest' ? 'Latest First' : 'Oldest First'"></span>
                        </div>
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>

                    <input type="hidden" name="sort" x-model="sort">

                    <div x-show="open" x-transition
                        class="absolute z-20 mt-1 w-40 bg-white rounded-lg shadow-xl border border-gray-100 ring-1 ring-black ring-opacity-5"
                        style="display: none;">
                        <div class="py-1">
                            <button type="button" @click="sort = 'latest'; $nextTick(() => $el.closest('form').submit())"
                                class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-primary"
                                :class="sort === 'latest' ? 'bg-gray-50 text-primary font-bold' : ''">
                                Latest First
                            </button>
                            <button type="button" @click="sort = 'oldest'; $nextTick(() => $el.closest('form').submit())"
                                class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-primary"
                                :class="sort === 'oldest' ? 'bg-gray-50 text-primary font-bold' : ''">
                                Oldest First
                            </button>
                        </div>
                    </div>
                </div>

                <div class="relative w-full md:w-96">
                    <input type="text" name="search" placeholder="Search activity, user, or target..."
                        value="{{ request('search') }}"
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

        <div class="bg-white rounded-lg border border-gray-200 overflow-hidden shadow-sm flex flex-col">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50/50 border-b border-gray-200 text-sm font-semibold text-gray-600">
                            <th class="p-4 pl-6 whitespace-nowrap w-1/5">Time</th>
                            <th class="p-4 whitespace-nowrap w-1/5">User</th>
                            <th class="p-4 whitespace-nowrap w-1/4">Activity</th>
                            <th class="p-4 whitespace-nowrap">Target Document/Model</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 text-sm">
                        @forelse($logs as $log)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="p-4 pl-6 text-gray-500 font-mono">
                                    {{ $log->created_at->format('d/m/Y H:i') }}
                                </td>
                                <td class="p-4 font-medium text-dark">
                                    <div class="flex items-center gap-2">
                                        <div
                                            class="w-6 h-6 rounded-full bg-gray-200 flex items-center justify-center text-xs font-bold text-gray-600">
                                            {{ substr($log->user->name ?? 'S', 0, 1) }}
                                        </div>
                                        {{ $log->user->name ?? 'System/Deleted User' }}
                                    </div>
                                </td>
                                <td class="p-4 text-gray-600">
                                    <span
                                        class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-xs font-medium bg-gray-100 text-gray-700 border border-gray-200">
                                        {{ $log->activity }}
                                    </span>
                                </td>
                                <td class="p-4">
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="w-8 h-8 rounded-full bg-gray-50 border border-gray-200 flex items-center justify-center text-gray-500">
                                            @if($log->target_type == 'Document')
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                                    </path>
                                                </svg>
                                            @elseif($log->target_type == 'User')
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z">
                                                    </path>
                                                </svg>
                                            @elseif($log->target_type == 'Loan')
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                                    </path>
                                                </svg>
                                            @else
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                            @endif
                                        </div>
                                        <span class="font-medium text-dark truncate max-w-xs" title="{{ $log->target }}">
                                            {{ $log->target }}
                                        </span>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="p-8 text-center text-gray-500">No audit logs found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="p-4 border-t border-gray-200 bg-gray-50/50">
                {{ $logs->links() }}
            </div>
        </div>
    </div>
@endsection