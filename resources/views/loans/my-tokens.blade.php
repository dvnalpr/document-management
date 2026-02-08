@extends('layouts.app')

@section('content')
    <div class="space-y-6">

        <div>
            <h2 class="text-3xl font-bold text-dark">My Tokens</h2>
            <p class="text-gray-500 mt-1">List of your approved document loans</p>
        </div>

        <div class="bg-white rounded-lg border border-gray-200 overflow-hidden shadow-sm mt-8">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50/50 border-b border-gray-200 text-sm font-bold text-dark">
                            <th class="p-4 pl-6 whitespace-nowrap">Document</th>
                            <th class="p-4 whitespace-nowrap">Token</th>
                            <th class="p-4 whitespace-nowrap">Request Date</th>
                            <th class="p-4 whitespace-nowrap">Duration</th>
                            <th class="p-4 whitespace-nowrap">Status</th>
                            <th class="p-4 whitespace-nowrap">Admin Note</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 text-sm">
                        @forelse($tokens as $token)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="p-4 pl-6 font-medium text-dark">
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="w-8 h-8 rounded-full bg-gray-50 border border-gray-200 flex items-center justify-center text-gray-500">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                                </path>
                                            </svg>
                                        </div>
                                        <span>{{ $token->document->title ?? 'Deleted Document' }}</span>
                                    </div>
                                </td>

                                <td class="p-4 font-mono font-bold text-primary">
                                    {{ $token->token ?? '-' }}
                                </td>

                                <td class="p-4 text-gray-500">
                                    {{ $token->request_date ? $token->request_date->format('d M Y, H:i') : '-' }}
                                </td>

                                <td class="p-4 text-gray-500">{{ $token->duration }}</td>

                                <td class="p-4">
                                    @if($token->status == 'Accepted')
                                        <span
                                            class="px-3 py-1 rounded-full text-xs font-bold border border-green-200 text-green-600 bg-green-50">Accepted</span>
                                    @elseif($token->status == 'Pending')
                                        <span
                                            class="px-3 py-1 rounded-full text-xs font-bold border border-yellow-200 text-yellow-600 bg-yellow-50">Pending</span>
                                    @elseif($token->status == 'Rejected')
                                        <span
                                            class="px-3 py-1 rounded-full text-xs font-bold border border-red-200 text-red-600 bg-red-50">Rejected</span>
                                    @else
                                        <span
                                            class="px-3 py-1 rounded-full text-xs font-bold border border-gray-200 text-gray-600 bg-gray-50">{{ $token->status }}</span>
                                    @endif
                                </td>

                                <td class="p-4 text-gray-500 italic">
                                    {{ $token->admin_note ?? '-' }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="p-8 text-center text-gray-500">
                                    You haven't requested any documents yet.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="p-4 border-t border-gray-200 bg-gray-50/50">
                {{ $tokens->links() }}
            </div>
        </div>
    </div>
@endsection