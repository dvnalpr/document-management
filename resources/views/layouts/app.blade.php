<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Docmanager') }}</title>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        [x-cloak] { display: none !important; }
        /* Hide scrollbar for Chrome, Safari and Opera */
        .no-scrollbar::-webkit-scrollbar {
            display: none;
        }
        /* Hide scrollbar for IE, Edge and Firefox */
        .no-scrollbar {
            -ms-overflow-style: none;  /* IE and Edge */
            scrollbar-width: none;  /* Firefox */
        }
    </style>
</head>
<body class="font-sans text-dark antialiased bg-gray-50" 
      x-data="{ 
          isSidebarOpen: true, 
          isDocMenuOpen: false,
          toggleSidebar() {
              this.isSidebarOpen = !this.isSidebarOpen;
              if(!this.isSidebarOpen) this.isDocMenuOpen = false;
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

    <header class="h-16 bg-white flex items-center justify-between px-6 fixed w-full top-0 z-40 transition-all duration-300">
        
        <div class="flex items-center gap-4">
            <img src="{{ asset('assets/img/Logo normal.png') }}" 
                 alt="Docmanager Logo" 
                 class="h-12 w-auto object-contain">
        </div>

        <div class="flex items-center gap-3">
            <div class="text-right leading-tight hidden md:block">
                <div class="text-sm font-bold">Username</div>
                <div class="text-xs text-gray-500">Manager</div>
            </div>
            <div class="w-10 h-10 rounded-full bg-primary text-white flex items-center justify-center">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
            </div>
        </div>
    </header>

    <div class="flex pt-16 min-h-screen">
        
        <aside class="bg-primary text-white fixed top-20 left-4 bottom-4 rounded-3xl shadow-2xl hidden md:flex flex-col z-50 overflow-hidden transition-all duration-300"
       :class="isSidebarOpen ? 'w-58' : 'w-20'">
    
    <nav class="px-3 py-4 space-y-2 overflow-y-auto no-scrollbar flex-1">
        
        <a href="{{ route('dashboard') }}" 
                   class="flex items-center px-3 py-3 rounded-xl transition group relative overflow-hidden {{ request()->routeIs('dashboard') ? 'bg-white text-primary font-bold shadow-sm' : 'text-white/80 hover:bg-white/10 hover:text-white' }}"
                   title="Dashboard">
                    <svg class="w-6 h-6 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                    <span class="ml-3 whitespace-nowrap transition-opacity duration-200"
                          x-show="isSidebarOpen" x-transition:enter="delay-100">Dashboard</span>
                </a>

        <div>
            <button @click="toggleDocMenu()" 
                            class="w-full flex items-center justify-between px-3 py-3 rounded-xl transition font-medium cursor-pointer {{ request()->routeIs('documents.*') ? 'bg-white text-primary font-bold shadow-sm' : 'text-white/80 hover:bg-white/10 hover:text-white' }}"
                            title="Documents">
                        <div class="flex items-center">
                            <svg class="w-6 h-6 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                            <span class="ml-3 whitespace-nowrap" x-show="isSidebarOpen">Documents</span>
                        </div>
                        <svg class="w-4 h-4 transition-transform duration-200" :class="isDocMenuOpen ? 'rotate-180' : ''" x-show="isSidebarOpen" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </button>

                    <div x-show="isDocMenuOpen && isSidebarOpen" x-collapse class="mt-1 ml-4 border-l border-white/20 pl-2 space-y-1">
                        <a href="{{ route('documents.index') }}" 
                           class="flex items-center px-3 py-2 text-sm rounded-lg transition {{ request()->routeIs('documents.index') ? 'text-white font-bold bg-white/10' : 'text-white/70 hover:text-white hover:bg-white/5' }}">
                           All Documents
                        </a>
                        <a href="#" class="flex items-center px-3 py-2 text-sm text-white/70 hover:text-white hover:bg-white/5 rounded-lg transition">Manage Request</a>
                        <a href="#" class="flex items-center px-3 py-2 text-sm text-white/70 hover:text-white hover:bg-white/5 rounded-lg transition">My Token</a>
                    </div>
                </div>

                <a href="{{ route('users.index') }}" 
                   class="flex items-center px-3 py-3 rounded-xl transition font-medium mt-6 {{ request()->routeIs('users.*') ? 'bg-white text-primary font-bold shadow-sm' : 'text-white/80 hover:bg-white/10 hover:text-white' }}"
                   title="Manage User">
                    <svg class="w-6 h-6 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                    <span class="ml-3 whitespace-nowrap" x-show="isSidebarOpen">Manage User</span>
                </a>

                <a href="#" 
                   class="flex items-center px-3 py-3 rounded-xl transition font-medium {{ request()->routeIs('audit.*') ? 'bg-white text-primary font-bold shadow-sm' : 'text-white/80 hover:bg-white/10 hover:text-white' }}"
                   title="Audit Log">
                    <svg class="w-6 h-6 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                    <span class="ml-3 whitespace-nowrap" x-show="isSidebarOpen">Audit Log</span>
                </a>
    </nav>

    <div class="p-4 border-t border-white/10">
        <button @click="toggleSidebar()" 
                class="w-full flex items-center px-3 py-2 text-white/80 hover:text-white hover:bg-white/10 rounded-xl transition-colors duration-200"
                :class="isSidebarOpen ? 'justify-end' : 'justify-center'">
            
            <span class="text-sm font-medium mr-3" x-show="isSidebarOpen">Collapse</span>

            <svg class="w-6 h-6 transition-transform duration-300" 
                 :class="!isSidebarOpen ? 'rotate-180' : ''"
                 fill="none" stroke="currentColor" viewBox="0 0 24 24">
                 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 19l-7-7 7-7m8 14l-7-7 7-7"></path>
            </svg>
        </button>
    </div>

</aside>

        <main class="flex-1 p-8 bg-gray-50 h-full min-h-screen transition-all duration-300"
              :class="isSidebarOpen ? 'md:ml-58' : 'md:ml-20'">
            @yield('content')
        </main>
    </div>

</body>
</html>