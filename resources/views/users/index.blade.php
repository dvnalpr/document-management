@extends('layouts.app')

@section('content')
    <div class="space-y-6" x-data="{
        isAddUserModalOpen: false,
        isEditUserModalOpen: false,
        isDeleteModalOpen: false,
        editingUser: null,
        deletingId: null,
        deletingName: '',
    
        openEditModal(user) {
            this.editingUser = user;
            this.isEditUserModalOpen = true;
        },
    
        openDeleteModal(id, name) {
            this.deletingId = id;
            this.deletingName = name;
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
                <h2 class="text-3xl font-bold text-dark">Manage User</h2>
                <p class="text-gray-500 mt-1">Manage list of all user</p>
            </div>

            <button @click="isAddUserModalOpen = true"
                class="bg-primary hover:bg-primary/90 text-white font-medium px-6 py-2.5 rounded-lg shadow-lg shadow-primary/30 transition transform active:scale-95 flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Add User
            </button>
        </div>

        <div class="mb-8">
            <form method="GET" action="{{ route('users.index') }}"
                class="flex flex-col md:flex-row gap-3 justify-start items-center w-full">

                <input type="hidden" name="per_page" value="{{ request('per_page', 10) }}">

                <div class="relative w-full md:w-auto" x-data="{ open: false, selected: '{{ request('division_id') }}' }">

                    <button type="button" @click="open = !open" @click.outside="open = false"
                        class="flex items-center justify-between gap-2 px-4 h-10 border border-gray-300 rounded-lg bg-white hover:bg-gray-50 text-dark transition shadow-sm min-w-35">

                        <div class="flex items-center gap-2">
                            <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4">
                                </path>
                            </svg>

                            <span class="font-medium text-sm truncate max-w-37.5">
                                {{ $divisions->firstWhere('id', request('division_id'))->name ?? 'Filter' }}
                            </span>
                        </div>

                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>

                    <input type="hidden" name="division_id" x-model="selected">

                    <div x-show="open" x-transition
                        class="absolute z-20 mt-1 w-56 bg-white rounded-lg shadow-xl border border-gray-100 ring-1 ring-black ring-opacity-5"
                        style="display: none;">
                        <div class="py-1">
                            <button type="button" @click="selected = ''; $nextTick(() => $el.closest('form').submit())"
                                class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-primary">
                                All Departments
                            </button>
                            @foreach ($divisions as $division)
                                <button type="button"
                                    @click="selected = '{{ $division->id }}'; $nextTick(() => $el.closest('form').submit())"
                                    class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-primary">
                                    {{ $division->name }}
                                </button>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="relative w-full md:w-96">
                    <input type="text" name="search" placeholder="Search" value="{{ request('search') }}"
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

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-10">
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
                            <th class="p-4 pl-6 whitespace-nowrap">Username</th>
                            <th class="p-4 whitespace-nowrap">Email</th>
                            <th class="p-4 whitespace-nowrap">Date added</th>
                            <th class="p-4 whitespace-nowrap">Division</th>
                            <th class="p-4 whitespace-nowrap">Status</th>
                            <th class="p-4 text-right pr-6">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 text-sm">
                        @forelse($users as $user)
                            <tr class="hover:bg-gray-50 transition group">
                                <td class="p-4 pl-6 font-medium text-dark">
                                    <div class="flex items-center gap-3">
                                        <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=random&color=fff&rounded=true"
                                            alt="{{ $user->name }}" class="w-8 h-8 rounded-full">
                                        {{ $user->name }}
                                    </div>
                                </td>
                                <td class="p-4 text-gray-500">{{ $user->email }}</td>
                                <td class="p-4 text-gray-500">{{ $user->created_at->format('d/m/y') }}</td>
                                <td class="p-4">
                                    @php $divCode = $user->division ? $user->division->code : '-'; @endphp
                                    @if ($divCode == 'QA')
                                        <span
                                            class="px-3 py-1 rounded-full text-xs font-bold border border-blue-200 text-blue-600 bg-blue-50">QA</span>
                                    @elseif($divCode == 'ME')
                                        <span
                                            class="px-3 py-1 rounded-full text-xs font-bold border border-yellow-200 text-yellow-700 bg-yellow-50">ME</span>
                                    @elseif($divCode == 'HR')
                                        <span
                                            class="px-3 py-1 rounded-full text-xs font-bold border border-purple-200 text-purple-700 bg-purple-50">HR</span>
                                    @else
                                        <span
                                            class="px-3 py-1 rounded-full text-xs font-bold border border-gray-200 text-gray-600 bg-gray-50">{{ $user->division->name ?? 'No Dept' }}</span>
                                    @endif
                                </td>
                                <td class="p-4">
                                    @if ($user->is_active)
                                        <span
                                            class="px-3 py-1 rounded-full text-xs font-bold border border-green-200 text-green-600 bg-green-50">Active</span>
                                    @else
                                        <span
                                            class="px-3 py-1 rounded-full text-xs font-bold border border-red-200 text-red-600 bg-red-50">Inactive</span>
                                    @endif
                                </td>
                                <td class="p-4 pr-6 text-right">
                                    <div class="flex items-center justify-end gap-3">
                                        <button @click="openEditModal({{ $user }})"
                                            class="text-gray-400 hover:text-blue-600 transition" title="Edit">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z">
                                                </path>
                                            </svg>
                                        </button>
                                        <button type="button"
                                            @click="openDeleteModal({{ $user->id }}, '{{ $user->name }}')"
                                            class="text-gray-400 hover:text-red-600 transition" title="Delete">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                </path>
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="p-8 text-center text-gray-500">
                                    No users found matching your criteria.
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

                @if ($users->hasPages())
                    <div class="flex items-center gap-1">
                        {{-- First Page --}}
                        @if ($users->onFirstPage())
                            <button disabled
                                class="w-8 h-8 flex items-center justify-center rounded border border-gray-200 text-gray-300 bg-white cursor-not-allowed"><span
                                    class="font-mono font-bold text-xs">|&lt;</span></button>
                        @else
                            <a href="{{ $users->url(1) }}"
                                class="w-8 h-8 flex items-center justify-center rounded border border-gray-300 text-gray-500 bg-white hover:bg-gray-50 hover:text-primary transition"><span
                                    class="font-mono font-bold text-xs">|&lt;</span></a>
                        @endif

                        {{-- Previous Page --}}
                        @if ($users->onFirstPage())
                            <button disabled
                                class="w-8 h-8 flex items-center justify-center rounded border border-gray-200 text-gray-300 bg-white cursor-not-allowed"><span
                                    class="font-mono font-bold">&lt;</span></button>
                        @else
                            <a href="{{ $users->previousPageUrl() }}"
                                class="w-8 h-8 flex items-center justify-center rounded border border-gray-300 text-gray-500 bg-white hover:bg-gray-50 hover:text-primary transition"><span
                                    class="font-mono font-bold">&lt;</span></a>
                        @endif

                        {{-- Page Numbers --}}
                        @foreach ($users->getUrlRange(max(1, $users->currentPage() - 2), min($users->lastPage(), $users->currentPage() + 2)) as $page => $url)
                            @if ($page == $users->currentPage())
                                <button
                                    class="w-8 h-8 flex items-center justify-center rounded bg-primary text-white font-bold shadow-sm border border-primary pointer-events-none">{{ $page }}</button>
                            @else
                                <a href="{{ $url }}"
                                    class="w-8 h-8 flex items-center justify-center rounded border border-gray-300 text-gray-600 bg-white hover:bg-gray-50 hover:text-primary transition">{{ $page }}</a>
                            @endif
                        @endforeach

                        {{-- Next Page --}}
                        @if ($users->hasMorePages())
                            <a href="{{ $users->nextPageUrl() }}"
                                class="w-8 h-8 flex items-center justify-center rounded border border-gray-300 text-gray-500 bg-white hover:bg-gray-50 hover:text-primary transition"><span
                                    class="font-mono font-bold">&gt;</span></a>
                        @else
                            <button disabled
                                class="w-8 h-8 flex items-center justify-center rounded border border-gray-200 text-gray-300 bg-white cursor-not-allowed"><span
                                    class="font-mono font-bold">&gt;</span></button>
                        @endif

                        {{-- Last Page --}}
                        @if ($users->hasMorePages())
                            <a href="{{ $users->url($users->lastPage()) }}"
                                class="w-8 h-8 flex items-center justify-center rounded border border-gray-300 text-gray-500 bg-white hover:bg-gray-50 hover:text-primary transition"><span
                                    class="font-mono font-bold text-xs">&gt;|</span></a>
                        @else
                            <button disabled
                                class="w-8 h-8 flex items-center justify-center rounded border border-gray-200 text-gray-300 bg-white cursor-not-allowed"><span
                                    class="font-mono font-bold text-xs">&gt;|</span></button>
                        @endif
                    </div>
                @endif
            </div>
        </div>

        <x-drawer name="isAddUserModalOpen" title="Add User">
            <form action="{{ route('users.store') }}" method="POST" id="addUserForm" class="space-y-6">
                @csrf
                <div class="space-y-2">
                    <label class="block text-sm font-bold text-dark">Username</label>
                    <input type="text" name="name"
                        class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:ring-1 focus:ring-primary focus:border-primary outline-none transition"
                        required>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div class="space-y-2">
                        <label class="block text-sm font-bold text-dark">Division</label>
                        <div class="relative">
                            <select name="division_id" required
                                class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:ring-1 focus:ring-primary focus:border-primary outline-none transition appearance-none bg-white text-dark">
                                <option value="" disabled selected>Select Division</option>
                                @foreach ($divisions as $division)
                                    <option value="{{ $division->id }}">{{ $division->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="space-y-2">
                        <label class="block text-sm font-bold text-dark">Role</label>
                        <select name="role"
                            class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:ring-1 focus:ring-primary focus:border-primary outline-none transition bg-white text-dark">
                            <option value="Staff">Staff</option>
                            <option value="Manager">Manager</option>
                            <option value="Admin">Admin</option>
                        </select>
                    </div>
                </div>
                <div class="space-y-2">
                    <label class="block text-sm font-bold text-dark">Email</label>
                    <input type="email" name="email"
                        class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:ring-1 focus:ring-primary focus:border-primary outline-none transition"
                        required>
                </div>
                <div class="space-y-2">
                    <label class="block text-sm font-bold text-dark">Password</label>
                    <input type="password" name="password"
                        class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:ring-1 focus:ring-primary focus:border-primary outline-none transition"
                        required>
                </div>
            </form>
            <x-slot:footer>
                <button @click="isAddUserModalOpen = false"
                    class="px-6 py-2.5 rounded-lg bg-gray-200 text-gray-700 font-bold hover:bg-gray-300 transition">Cancel</button>
                <button onclick="document.getElementById('addUserForm').submit()"
                    class="px-6 py-2.5 rounded-lg bg-primary text-white font-bold hover:bg-primary/90 shadow-md transition transform active:scale-95">Add
                    user</button>
            </x-slot:footer>
        </x-drawer>

        <x-drawer name="isEditUserModalOpen" title="Update User">
            <form :action="'{{ url('users') }}/' + editingUser?.id" method="POST" id="editUserForm" class="space-y-6">
                @csrf @method('PUT')
                <div class="space-y-2">
                    <label class="block text-sm font-bold text-dark">Username</label>
                    <input type="text" name="name" :value="editingUser?.name"
                        class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:ring-1 focus:ring-primary focus:border-primary outline-none transition"
                        required>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div class="space-y-2">
                        <label class="block text-sm font-bold text-dark">Division / Department</label>
                        <div class="relative">
                            <select name="division_id" required
                                class="w-full px-4 py-2.5 rounded-lg border border-gray-300 ...">
                                @foreach ($divisions as $division)
                                    <option value="{{ $division->id }}"
                                        :selected="editingUser?.division_id == {{ $division->id }}">
                                        {{ $division->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="space-y-2">
                        <label class="block text-sm font-bold text-dark">Role</label>
                        <select name="role"
                            class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:ring-1 focus:ring-primary focus:border-primary outline-none transition bg-white text-dark">
                            <option value="Staff" :selected="editingUser?.roles?.[0]?.name == 'Staff'">Staff</option>
                            <option value="Manager" :selected="editingUser?.roles?.[0]?.name == 'Manager'">Manager</option>
                            <option value="Admin" :selected="editingUser?.roles?.[0]?.name == 'Admin'">Admin</option>
                        </select>
                    </div>
                </div>
                <div class="space-y-2">
                    <label class="block text-sm font-bold text-dark">Email</label>
                    <input type="email" name="email" :value="editingUser?.email"
                        class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:ring-1 focus:ring-primary focus:border-primary outline-none transition"
                        required>
                </div>
                <div class="space-y-2">
                    <label class="block text-sm font-bold text-dark">Password (Leave blank to keep current)</label>
                    <input type="password" name="password"
                        class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:ring-1 focus:ring-primary focus:border-primary outline-none transition">
                </div>
                <div class="space-y-2">
                    <label class="block text-sm font-bold text-dark">Status</label>
                    <div class="flex gap-4 mt-2">
                        <label class="flex items-center cursor-pointer">
                            <input type="radio" name="status" value="Active" class="mr-2"
                                :checked="editingUser?.is_active == 1">Active
                        </label>
                        <label class="flex items-center cursor-pointer">
                            <input type="radio" name="status" value="Inactive" class="mr-2"
                                :checked="editingUser?.is_active == 0">Inactive
                        </label>
                    </div>
                </div>
            </form>
            <x-slot:footer>
                <button @click="isEditUserModalOpen = false"
                    class="px-6 py-2.5 rounded-lg bg-gray-200 text-gray-700 font-bold hover:bg-gray-300 transition">Cancel</button>
                <button onclick="document.getElementById('editUserForm').submit()"
                    class="px-6 py-2.5 rounded-lg bg-primary text-white font-bold hover:bg-primary/90 shadow-md transition transform active:scale-95">Update</button>
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

                <h3 class="text-xl font-bold text-dark mb-2">Delete User?</h3>
                <p class="text-gray-500 mb-6 text-sm">
                    Are you sure you want to delete user <br>
                    "<span class="font-bold text-dark" x-text="deletingName"></span>"? <br>
                    This action cannot be undone.
                </p>

                <div class="flex gap-3 justify-center">
                    <button @click="isDeleteModalOpen = false"
                        class="px-5 py-2.5 rounded-lg border border-gray-300 text-gray-700 font-bold text-sm hover:bg-gray-50 transition">
                        Cancel
                    </button>

                    <form :action="'{{ url('users') }}/' + deletingId" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                            class="px-5 py-2.5 rounded-lg bg-red-600 text-white font-bold text-sm hover:bg-red-700 shadow-md transition transform active:scale-95">
                            Yes, Delete User
                        </button>
                    </form>
                </div>
            </div>
        </div>

    </div>
@endsection
