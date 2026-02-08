<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Docmanager') }}</title>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        [x-cloak] {
            display: none !important;
        }

        .no-scrollbar::-webkit-scrollbar {
            display: none;
        }

        .no-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
    </style>
</head>

<body class="font-sans text-dark antialiased bg-gray-50" x-data="{
    isSidebarOpen: true,
    isDocMenuOpen: {{ request()->routeIs('documents.*') || request()->routeIs('loans.*') ? 'true' : 'false' }},
    isLogoutModalOpen: false,

    toggleSidebar() {
        this.isSidebarOpen = !this.isSidebarOpen;
        if (!this.isSidebarOpen) this.isDocMenuOpen = false;
    },
    toggleDocMenu() {
        if (!this.isSidebarOpen) {
            this.isSidebarOpen = true;
            setTimeout(() => this.isDocMenuOpen = true, 150);
        } else {
            this.isDocMenuOpen = !this.isDocMenuOpen;
        }
    }
}">

    <header
        class="h-16 bg-white flex items-center justify-between px-6 fixed w-full top-0 z-40 transition-all duration-300">

        <div class="flex items-center gap-4">
            <img src="{{ asset('assets/img/Logo normal.png') }}" alt="Docmanager Logo" class="h-12 w-auto object-contain">
        </div>

        <div class="relative" x-data="{ isProfileMenuOpen: false }">

            <button @click="isProfileMenuOpen = !isProfileMenuOpen" @click.outside="isProfileMenuOpen = false"
                class="flex items-center gap-3 cursor-pointer hover:bg-gray-50 p-2 rounded-xl transition duration-200 outline-none focus:ring-2 focus:ring-primary/20">

                <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=0D8ABC&color=fff&rounded=true&bold=true"
                    alt="{{ Auth::user()->name }}"
                    class="w-10 h-10 rounded-lg object-cover border border-gray-100 shadow-sm">

                <div class="text-left leading-tight hidden md:block">
                    <div class="text-sm font-bold text-dark">{{ Auth::user()->name }}</div>
                    <div class="text-xs text-gray-500">{{ Auth::user()->getRoleNames()->first() ?? 'User' }}</div>
                </div>

                <svg class="w-4 h-4 text-gray-400 transition-transform duration-200"
                    :class="isProfileMenuOpen ? 'rotate-180' : ''" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                </svg>
            </button>

            <div x-show="isProfileMenuOpen" x-transition:enter="transition ease-out duration-100"
                x-transition:enter-start="transform opacity-0 scale-95"
                x-transition:enter-end="transform opacity-100 scale-100"
                x-transition:leave="transition ease-in duration-75"
                x-transition:leave-start="transform opacity-100 scale-100"
                x-transition:leave-end="transform opacity-0 scale-95"
                class="absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-lg border border-gray-100 py-1 z-50"
                style="display: none;">

                <div class="px-4 py-3 border-b border-gray-100">
                    <p class="text-sm text-gray-500">Signed in as</p>
                    <p class="text-sm font-bold text-dark truncate">{{ Auth::user()->email }}</p>
                </div>

                <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 transition">
                    Your Profile
                </a>

                <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 transition">
                    Settings
                </a>

                <div class="border-t border-gray-100 my-1"></div>

                <form method="POST" action="{{ route('logout') }}" id="logout-form">
                    @csrf
                    <button type="button" @click="isLogoutModalOpen = true; isProfileMenuOpen = false"
                        class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50 hover:text-red-700 transition font-medium flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1">
                            </path>
                        </svg>
                        Sign out
                    </button>
                </form>
            </div>

        </div>
    </header>

    <div class="flex pt-16 min-h-screen">

        <aside
            class="bg-primary text-white fixed top-20 left-4 bottom-4 rounded-3xl shadow-2xl hidden md:flex flex-col z-50 overflow-hidden transition-all duration-300"
            :class="isSidebarOpen ? 'w-58' : 'w-20'">

            <nav class="px-3 py-4 space-y-2 overflow-y-auto no-scrollbar flex-1">

                <a href="{{ route('dashboard') }}"
                    class="flex items-center px-3 py-3 rounded-xl transition-all group relative overflow-hidden font-medium {{ request()->routeIs('dashboard') ? 'bg-white text-primary font-bold shadow-sm' : 'text-white/80 hover:bg-white/10 hover:text-white' }}"
                    :class="isSidebarOpen ? 'w-full ml-0' : 'w-12 ml-1'" title="Dashboard">
                    <svg class="w-6 h-6 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z">
                        </path>
                    </svg>
                    <span class="ml-3 whitespace-nowrap transition-opacity duration-200" x-show="isSidebarOpen"
                        x-transition:enter="delay-100">Dashboard</span>
                </a>

                <div>
                    <button @click="toggleDocMenu()"
                        class="flex items-center justify-between px-3 py-3 rounded-xl transition-all font-medium cursor-pointer {{ request()->routeIs('documents.*') || request()->routeIs('loans.*') ? 'bg-white text-primary font-bold shadow-sm' : 'text-white/80 hover:bg-white/10 hover:text-white' }}"
                        :class="isSidebarOpen ? 'w-full ml-0' : 'w-12 ml-1'" title="Documents">
                        <div class="flex items-center">
                            <svg class="w-6 h-6 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                </path>
                            </svg>
                            <span class="ml-3 whitespace-nowrap" x-show="isSidebarOpen">Documents</span>
                        </div>
                        <svg class="w-4 h-4 transition-transform duration-200"
                            :class="isDocMenuOpen ? 'rotate-180' : ''" x-show="isSidebarOpen" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7">
                            </path>
                        </svg>
                    </button>

                    <div x-show="isDocMenuOpen && isSidebarOpen" x-collapse
                        class="mt-1 ml-4 border-l border-white/20 pl-2 space-y-1">

                        <a href="{{ route('documents.index') }}"
                            class="flex items-center px-3 py-2 text-sm rounded-lg transition {{ request()->routeIs('documents.index') ? 'text-white font-bold bg-white/10' : 'text-white/70 hover:text-white hover:bg-white/5' }}">
                            All Documents
                        </a>

                        @hasanyrole('Manager|Admin')
                            <a href="{{ route('loans.manage') }}"
                                class="flex items-center px-3 py-2 text-sm rounded-lg transition {{ request()->routeIs('loans.manage') ? 'text-white font-bold bg-white/10' : 'text-white/70 hover:text-white hover:bg-white/5' }}">
                                Manage Request
                            </a>
                        @endhasanyrole

                        <a href="{{ route('loans.my-tokens') }}"
                            class="flex items-center px-3 py-2 text-sm rounded-lg transition {{ request()->routeIs('loans.my-tokens') ? 'text-white font-bold bg-white/10' : 'text-white/70 hover:text-white hover:bg-white/5' }}">
                            My Token
                        </a>
                    </div>
                </div>

                @role('Admin')
                    <a href="{{ route('users.index') }}"
                        class="flex items-center px-3 py-3 rounded-xl transition-all font-medium mt-6 {{ request()->routeIs('users.*') ? 'bg-white text-primary font-bold shadow-sm' : 'text-white/80 hover:bg-white/10 hover:text-white' }}"
                        :class="isSidebarOpen ? 'w-full ml-0' : 'w-12 ml-1'" title="Manage User">
                        <svg class="w-6 h-6 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                        <span class="ml-3 whitespace-nowrap" x-show="isSidebarOpen">Manage User</span>
                    </a>
                @endrole

                @role('Admin')
                    <a href="{{ route('audit-logs.index') }}"
                        class="flex items-center px-3 py-3 rounded-xl transition-all font-medium {{ request()->routeIs('audit-logs.*') ? 'bg-white text-primary font-bold shadow-sm' : 'text-white/80 hover:bg-white/10 hover:text-white' }}"
                        :class="isSidebarOpen ? 'w-full ml-0' : 'w-12 ml-1'" title="Audit Log">
                        <svg class="w-6 h-6 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
                            </path>
                        </svg>
                        <span class="ml-3 whitespace-nowrap" x-show="isSidebarOpen">Audit Log</span>
                    </a>
                @endrole

            </nav>

            <div class="p-4 border-t border-white/10">
                <button @click="toggleSidebar()"
                    class="w-full flex items-center px-3 py-2 text-white/80 hover:text-white hover:bg-white/10 rounded-xl transition-colors duration-200"
                    :class="isSidebarOpen ? 'justify-end' : 'justify-center'">

                    <span class="text-sm font-medium mr-3" x-show="isSidebarOpen">Collapse</span>

                    <svg class="w-6 h-6 transition-transform duration-300" :class="!isSidebarOpen ? 'rotate-180' : ''"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M11 19l-7-7 7-7m8 14l-7-7 7-7"></path>
                    </svg>
                </button>
            </div>

        </aside>

        <main class="flex-1 p-8 bg-gray-50 h-full min-h-screen transition-all duration-300"
            :class="isSidebarOpen ? 'md:ml-58' : 'md:ml-20'">
            @yield('content')
        </main>
    </div>

    <div x-data="{
        show: false,
        message: '',
        type: 'success',
        init() {
            @if (session('success')) this.show = true;
                    this.message = '{{ session('success') }}';
                    this.type = 'success';
                    setTimeout(() => this.show = false, 4000); // Hilang setelah 4 detik @endif
            @if (session('error')) this.show = true;
                    this.message = '{{ session('error') }}';
                    this.type = 'error';
                    setTimeout(() => this.show = false, 4000); @endif
        }
    }" x-show="show" x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0"
        x-transition:leave-end="opacity-0 translate-y-2"
        class="fixed bottom-5 right-5 z-50 flex items-center gap-3 px-6 py-4 rounded-lg shadow-xl border border-gray-100 bg-white min-w-75"
        style="display: none;">

        <template x-if="type === 'success'">
            <div class="w-8 h-8 rounded-full bg-green-100 text-green-600 flex items-center justify-center shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
            </div>
        </template>

        <template x-if="type === 'error'">
            <div class="w-8 h-8 rounded-full bg-red-100 text-red-600 flex items-center justify-center shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                    </path>
                </svg>
            </div>
        </template>

        <div>
            <h4 class="font-bold text-sm text-dark" x-text="type === 'success' ? 'Success!' : 'Error!'"></h4>
            <p class="text-sm text-gray-500" x-text="message"></p>
        </div>

        <button @click="show = false" class="ml-auto text-gray-400 hover:text-gray-600">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                </path>
            </svg>
        </button>
    </div>

    <div x-show="isLogoutModalOpen" style="display: none;"
        class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm p-4 transition-opacity">

        <div x-show="isLogoutModalOpen" x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
            x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-95" @click.outside="isLogoutModalOpen = false"
            class="bg-white rounded-xl shadow-2xl w-full max-w-sm overflow-hidden border border-gray-100 text-center p-6">

            <div
                class="mx-auto w-16 h-16 bg-red-50 text-red-500 rounded-full flex items-center justify-center mb-4 border border-red-100">
                <svg class="w-8 h-8 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1">
                    </path>
                </svg>
            </div>

            <h3 class="text-xl font-bold text-dark mb-2">Sign Out?</h3>
            <p class="text-gray-500 mb-6 text-sm">
                Are you sure you want to end your session? <br>
                You will need to login again to access the system.
            </p>

            <div class="flex gap-3 justify-center">
                <button @click="isLogoutModalOpen = false"
                    class="px-5 py-2.5 rounded-lg border border-gray-300 text-gray-700 font-bold text-sm hover:bg-gray-50 transition">
                    Cancel
                </button>

                <button onclick="document.getElementById('logout-form').submit()"
                    class="px-5 py-2.5 rounded-lg bg-red-600 text-white font-bold text-sm hover:bg-red-700 shadow-md transition transform active:scale-95">
                    Yes, Sign Out
                </button>
            </div>
        </div>
    </div>
</body>

</html>
