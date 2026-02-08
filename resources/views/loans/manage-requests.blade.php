@extends('layouts.app')

@section('content')
    <div class="space-y-6" x-data="{
        isModalOpen: false,
        selectedLoan: null,
        actionType: '', // 'approve' or 'reject'
    
        // Fungsi untuk membuka modal & set data ID dan Action
        openConfirmModal(loanId, type) {
            this.selectedLoan = loanId;
            this.actionType = type;
            this.isModalOpen = true;
        }
    }">

        <div>
            <h2 class="text-3xl font-bold text-dark">Manage Request Borrow</h2>
            <p class="text-gray-500 mt-1">Manage of all document borrow request</p>
        </div>

        <div class="flex flex-col md:flex-row gap-4 mb-8 pt-4 border-t border-gray-200/50">
            <button
                class="flex items-center gap-2 px-4 py-2 border border-gray-300 rounded-md bg-white hover:bg-gray-50 text-dark transition shadow-sm h-10">
                <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4">
                    </path>
                </svg>
                <span class="font-medium">Filter</span>
            </button>

            <div class="relative w-full max-w-md">
                <input type="text" placeholder="Search request..."
                    class="w-full pl-4 pr-10 border border-gray-300 rounded-md focus:ring-1 focus:ring-primary focus:border-primary outline-none shadow-sm transition h-10">
                <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg border border-gray-200 overflow-hidden shadow-sm flex flex-col">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50/50 border-b border-gray-200 text-sm font-bold text-dark">
                            <th class="p-4 pl-6 whitespace-nowrap">Document</th>
                            <th class="p-4 whitespace-nowrap">Borrower</th>
                            <th class="p-4 whitespace-nowrap">Request Date</th>
                            <th class="p-4 whitespace-nowrap">Duration</th>
                            <th class="p-4 whitespace-nowrap">Note</th>
                            <th class="p-4 whitespace-nowrap">Status</th>
                            <th class="p-4 whitespace-nowrap text-right pr-6">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 text-sm">
                        @forelse($requests as $req)
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
                                        <span class="truncate max-w-50"
                                            title="{{ $req->document->title ?? 'Document Deleted' }}">
                                            {{ $req->document->title ?? 'Document Deleted' }}
                                        </span>
                                    </div>
                                </td>

                                <td class="p-4 font-bold text-dark">{{ $req->user->name ?? 'Unknown User' }}</td>

                                <td class="p-4 text-gray-500">{{ $req->request_date->format('d/m/Y') }}</td>
                                <td class="p-4 text-gray-500">{{ $req->duration }}</td>

                                <td class="p-4 text-gray-500 italic truncate max-w-xs" title="{{ $req->note }}">
                                    "{{ Str::limit($req->note, 30) }}"</td>

                                <td class="p-4">
                                    @if ($req->status == 'Accepted')
                                        <span
                                            class="px-2.5 py-1 rounded-full text-xs font-bold bg-green-100 text-green-700 border border-green-200">Accepted</span>
                                    @elseif($req->status == 'Rejected')
                                        <span
                                            class="px-2.5 py-1 rounded-full text-xs font-bold bg-red-100 text-red-700 border border-red-200">Rejected</span>
                                    @elseif($req->status == 'Canceled')
                                        <span
                                            class="px-2.5 py-1 rounded-full text-xs font-bold bg-gray-100 text-gray-600 border border-gray-300">Canceled</span>
                                    @else
                                        <span
                                            class="px-2.5 py-1 rounded-full text-xs font-bold bg-yellow-100 text-yellow-700 border border-yellow-200">Pending</span>
                                    @endif
                                </td>

                                <td class="p-4 pr-6 text-right">
                                    @if ($req->status == 'Pending')
                                        <div class="flex items-center justify-end gap-2">
                                            <button @click="openConfirmModal({{ $req->id }}, 'approve')"
                                                title="Approve"
                                                class="w-9 h-9 rounded-md bg-green-50 text-green-600 border border-green-200 flex items-center justify-center hover:bg-green-600 hover:text-white transition transform active:scale-95 shadow-sm">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M5 13l4 4L19 7"></path>
                                                </svg>
                                            </button>

                                            <button @click="openConfirmModal({{ $req->id }}, 'reject')" title="Reject"
                                                class="w-9 h-9 rounded-md bg-red-50 text-red-600 border border-red-200 flex items-center justify-center hover:bg-red-600 hover:text-white transition transform active:scale-95 shadow-sm">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M6 18L18 6M6 6l12 12"></path>
                                                </svg>
                                            </button>
                                        </div>
                                    @else
                                        <span class="text-xs text-gray-400">Processed</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="p-8 text-center text-gray-500">No requests found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="p-4 border-t border-gray-200 bg-gray-50/50">
                {{ $requests->links() }}
            </div>
        </div>

        <div x-show="isModalOpen" style="display: none;"
            class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm p-4 transition-opacity">

            <div x-show="isModalOpen" x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 scale-100"
                x-transition:leave-end="opacity-0 scale-95" @click.outside="isModalOpen = false"
                class="bg-white rounded-xl shadow-2xl w-full max-w-md overflow-hidden border border-gray-100">

                <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
                    <h3 class="text-lg font-bold text-dark flex items-center gap-2">
                        <template x-if="actionType === 'approve'">
                            <span class="text-green-600 flex items-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Approve Request
                            </span>
                        </template>
                        <template x-if="actionType === 'reject'">
                            <span class="text-red-600 flex items-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Reject Request
                            </span>
                        </template>
                    </h3>
                    <button @click="isModalOpen = false" class="text-gray-400 hover:text-gray-600 transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12">
                            </path>
                        </svg>
                    </button>
                </div>

                <form :action="'{{ url('loans') }}/' + selectedLoan + '/status'" method="POST" class="p-6">
                    @csrf
                    @method('PATCH')

                    <input type="hidden" name="action" :value="actionType">

                    <p class="text-gray-600 mb-4 text-sm">
                        Are you sure you want to <span x-text="actionType" class="font-bold"></span> this request?
                        Please provide a note below.
                    </p>

                    <div class="space-y-2">
                        <label class="block text-sm font-bold text-dark">
                            Note <span class="font-normal text-gray-500">(Optional but recommended)</span>
                        </label>
                        <textarea name="admin_note" rows="3"
                            class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-1 focus:ring-primary focus:border-primary outline-none transition resize-none text-sm placeholder-gray-400"
                            :placeholder="actionType === 'approve' ? 'E.g. Approved, please return on time.' :
                                'E.g. Rejected, document is currently under maintenance.'"></textarea>
                    </div>

                    <div class="mt-6 flex gap-3 justify-end">
                        <button type="button" @click="isModalOpen = false"
                            class="px-5 py-2.5 rounded-lg border border-gray-300 text-gray-700 font-bold text-sm hover:bg-gray-50 transition">
                            Cancel
                        </button>

                        <button type="submit"
                            class="px-5 py-2.5 rounded-lg text-white font-bold text-sm shadow-md transition transform active:scale-95"
                            :class="actionType === 'approve' ? 'bg-green-600 hover:bg-green-700' :
                                'bg-red-600 hover:bg-red-700'">
                            Confirm <span class="capitalize" x-text="actionType"></span>
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>
@endsection
