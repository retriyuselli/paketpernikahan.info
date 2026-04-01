<!-- Sticky Header Wrapper -->
<div class="sticky top-0 z-40 bg-white border-b border-gray-200">
    <!-- Collapsible: Announcement Banner + Top Contact Bar -->
    <div id="collapsible-bar" class="overflow-hidden transition-all duration-300 max-h-[200px]">
        <!-- Top Announcement Banner -->
        <div id="announcement-banner" class="text-white py-2 px-4 text-sm font-medium bg-accent-gradient">
            <div class="max-w-7xl mx-auto flex items-center justify-between gap-3">
                <div class="text-center flex-1">
                    Coming soon, Sumatera Selatan Wedding Expo 2026 Season 1
                </div>
                <button type="button" onclick="dismissAnnouncement()" class="p-1 rounded-lg hover:bg-white/10 transition" aria-label="Tutup pengumuman">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </div>
        <!-- Top Contact Bar -->
        <x-ui.container>
        <div class="hidden md:flex items-center justify-between py-3 text-xs border-b border-gray-100">
            <div class="flex items-center gap-6 text-gray-600">
                <span>office@makruwedding.id</span>
                <span>+62 812-7893-2624</span>
                <div class="flex gap-3">
                    <a href="#" class="hover:text-accent transition" title="Facebook">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                        </svg>
                    </a>
                    <a href="#" class="hover:text-accent transition" title="Instagram">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                            <rect x="2" y="2" width="20" height="20" rx="5" ry="5" fill="none" stroke="currentColor" stroke-width="2"/>
                            <path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z" fill="none" stroke="currentColor" stroke-width="2"/>
                            <circle cx="17.5" cy="6.5" r="1.5" fill="currentColor"/>
                        </svg>
                    </a>
                </div>
            </div>
            <div class="flex items-center gap-6 text-xs">
                @auth
                    <a href="{{ url('/dashboard') }}" class="font-medium hover:text-accent">Dashboard</a>
                @else
                    <button onclick="openLoginModal()" class="font-medium hover:text-accent">Login</button>
                @endauth
                <a href="#" class="font-medium hover:text-accent">Rp 0</a>
            </div>
        </div>
        </x-ui.container>
    </div><!-- end collapsible-bar -->

    <!-- Main Header (always sticky) -->
    <x-ui.container>
        <div class="flex items-center justify-between py-3 lg:grid lg:grid-cols-3 lg:items-center">

            <!-- Logo (left) -->
            <div class="flex items-center gap-2 flex-shrink-0 h-10">
                <a href="/" class="flex items-center gap-2">
                    <span class="text-2xl lg:text-3xl font-extrabold tracking-tight leading-none">
                        <span class="text-accent">M</span><span class="text-dark">W</span>
                    </span>
                </a>
            </div>

            <!-- Main Navigation (center) -->
            <nav class="hidden lg:flex items-center justify-center gap-6 whitespace-nowrap relative z-20">
                <a href="{{ route('home') }}" class="text-xs font-bold tracking-wide text-gray-800 hover:text-accent transition uppercase">Home</a>
                <div class="relative group">
                    <button type="button" onclick="toggleHeaderDropdown('wedding')"
                            class="flex items-center gap-1 text-xs font-bold tracking-wide text-gray-800 hover:text-accent transition uppercase"
                            aria-expanded="false" aria-controls="dropdown-wedding">
                        Wedding Package
                        <svg class="w-3 h-3 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    <div id="dropdown-wedding" class="hidden absolute left-0 mt-2 w-56 bg-white rounded-xl shadow-lg border border-gray-100 py-1 z-50">
                        <a href="{{ route('vendor') }}" class="flex items-center gap-2.5 px-4 py-2.5 text-xs text-gray-600 hover:bg-gray-50 transition">
                            Semua Vendor
                        </a>
                        <a href="{{ route('vendor') }}?q=paket" class="flex items-center gap-2.5 px-4 py-2.5 text-xs text-gray-600 hover:bg-gray-50 transition">
                            Cari Paket
                        </a>
                    </div>
                </div>
                <a href="{{ route('vendor') }}" class="text-xs font-bold tracking-wide text-gray-800 hover:text-accent transition uppercase">Vendor</a>
                <a href="{{ route('store') }}" class="text-xs font-bold tracking-wide text-gray-800 hover:text-accent transition uppercase">Store</a>
                <a href="#" class="text-xs font-bold tracking-wide text-gray-800 hover:text-accent transition uppercase">Promo</a>
                <a href="#" class="text-xs font-bold tracking-wide text-gray-800 hover:text-accent transition uppercase">Blog Makna</a>
                <div class="relative group">
                    <button type="button" onclick="toggleHeaderDropdown('lain')"
                            class="flex items-center gap-1 text-xs font-bold tracking-wide text-gray-800 hover:text-accent transition uppercase"
                            aria-expanded="false" aria-controls="dropdown-lain">
                        Lain lain
                        <svg class="w-3 h-3 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    <div id="dropdown-lain" class="hidden absolute left-0 mt-2 w-56 bg-white rounded-xl shadow-lg border border-gray-100 py-1 z-50">
                        <a href="#" class="flex items-center gap-2.5 px-4 py-2.5 text-xs text-gray-600 hover:bg-gray-50 transition">
                            Tentang Makna
                        </a>
                        <a href="#" class="flex items-center gap-2.5 px-4 py-2.5 text-xs text-gray-600 hover:bg-gray-50 transition">
                            Kontak
                        </a>
                    </div>
                </div>
                @auth
                    @if(($headerShowJoinVendorForAuth ?? false))
                        <x-ui.button href="{{ route('join.vendor') }}" size="xs" class="tracking-wide uppercase">
                            Join Vendor
                        </x-ui.button>
                    @endif
                @else
                    <x-ui.button href="{{ route('join.vendor.signup') }}" size="xs" class="tracking-wide uppercase">
                        Join Vendor
                    </x-ui.button>
                @endauth
            </nav>

            <!-- Right Actions -->
            <div class="flex items-center justify-end gap-2 relative z-10" id="header-actions">
                <!-- Search Icon -->
                <div class="relative" id="header-search-wrapper">
                    <button type="button" onclick="toggleHeaderSearch()"
                            class="p-2 text-gray-600 hover:text-accent transition rounded-full hover:bg-gray-100"
                            aria-label="Cari" aria-expanded="false" aria-controls="header-search-panel">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    </button>
                    <div id="header-search-panel" class="hidden absolute right-0 mt-2 w-80 bg-white rounded-xl shadow-lg border border-gray-100 p-3 z-50">
                        <form method="GET" action="{{ route('vendor') }}" class="flex items-center gap-2">
                            <input type="text" name="q" placeholder="Cari vendor atau paket..."
                                   class="w-full border border-gray-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:border-gray-400 transition">
                            <x-ui.button type="submit" size="sm" class="font-bold">
                                Cari
                            </x-ui.button>
                        </form>
                    </div>
                </div>

                <!-- Theme Toggle (iPhone style) -->
                <button id="theme-toggle" onclick="toggleTheme()"
                        class="relative inline-flex items-center w-11 h-6 rounded-full transition-colors duration-300 focus:outline-none bg-accent"
                        aria-label="Toggle dark mode">
                    <span id="theme-knob"
                          class="inline-block w-4 h-4 bg-white rounded-full shadow-md transform transition-transform duration-300 translate-x-1">
                    </span>
                </button>

                <!-- Profile Dropdown -->
                <div class="relative" id="main-profile-wrapper">
                    <button onclick="toggleMainDropdown()"
                            class="flex items-center justify-center w-9 h-9 rounded-full overflow-hidden border-2 border-gray-200 hover:border-gray-400 transition focus:outline-none">
                        @auth
                            @if(auth()->user()->avatar_url)
                                <img src="{{ auth()->user()->avatarUrl() }}"
                                     alt="{{ auth()->user()->name }}" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-sm font-bold text-white bg-accent">
                                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                                </div>
                            @endif
                        @else
                            <div class="w-full h-full flex items-center justify-center bg-gray-100">
                                <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                            </div>
                        @endauth
                    </button>

                    <!-- Dropdown -->
                    <div id="main-profile-dropdown"
                         class="hidden absolute right-0 mt-2 w-56 bg-white rounded-xl shadow-lg border border-gray-100 py-1 z-50">
                        @auth
                            <div class="px-4 py-2.5 border-b border-gray-100">
                                <p class="text-xs font-semibold truncate text-dark">{{ auth()->user()->name }}</p>
                                <p class="text-[10px] text-gray-400 truncate">{{ auth()->user()->email }}</p>
                            </div>
                            <a href="{{ url('/dashboard') }}"
                               class="flex items-center gap-2.5 px-4 py-2.5 text-xs text-gray-600 hover:bg-gray-50 transition">
                                <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <rect x="3" y="3" width="7" height="7" rx="1" stroke-width="2"/>
                                    <rect x="14" y="3" width="7" height="7" rx="1" stroke-width="2"/>
                                    <rect x="14" y="14" width="7" height="7" rx="1" stroke-width="2"/>
                                    <rect x="3" y="14" width="7" height="7" rx="1" stroke-width="2"/>
                                </svg>
                                Dashboard
                            </a>
                            @if(auth()->user()->hasRole(['super_admin', 'admin']))
                            <a href="/admin"
                               class="flex items-center gap-2.5 px-4 py-2.5 text-xs text-gray-600 hover:bg-gray-50 transition">
                                <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><circle cx="12" cy="12" r="3"/>
                                </svg>
                                Panel Admin
                            </a>
                            @endif
                            <div class="border-t border-gray-100 mt-1">
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit"
                                            class="w-full text-left flex items-center gap-2.5 px-4 py-2.5 text-xs text-red-500 hover:bg-red-50 transition">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                                        </svg>
                                        Keluar
                                    </button>
                                </form>
                            </div>
                        @else
                            <a href="{{ route('login') }}"
                               class="flex items-center gap-2.5 px-4 py-2.5 text-xs text-gray-600 hover:bg-gray-50 transition">
                                <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
                                </svg>
                                Login / Daftar
                            </a>
                        @endauth
                    </div>
                </div>

                <button type="button" onclick="openMobileMenu()"
                        class="lg:hidden p-2 text-gray-600 hover:text-accent transition rounded-full hover:bg-gray-100"
                        aria-label="Menu" aria-expanded="false" aria-controls="mobile-menu">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
            </div>

        </div>
    </x-ui.container>

        <!-- Category Navigation -->
        {{-- <div class="border-t border-gray-100 -mx-4 sm:-mx-6 lg:-mx-8 px-4 sm:px-6 lg:px-8">
            <div class="flex items-center overflow-x-auto py-4 gap-8">
                <div class="flex items-center gap-3 cursor-pointer hover:opacity-75 transition flex-shrink-0">
                    <span class="text-xl">🏛️</span>
                    <div>
                        <div class="text-gray-900 text-[12px]">Gedung</div>
                        <div class="text-[10px] text-gray-500">Sutan, Golden +</div>
                    </div>
                </div>
                <div class="flex items-center gap-3 cursor-pointer hover:opacity-75 transition flex-shrink-0">
                    <span class="text-xl">🏨</span>
                    <div>
                        <div class="text-gray-900 text-[12px]">Hotel</div>
                        <div class="text-[10px] text-gray-500">Aceh, Novatel +</div>
                    </div>
                </div>
                <div class="flex items-center gap-3 cursor-pointer hover:opacity-75 transition flex-shrink-0">
                    <span class="text-xl">🏠</span>
                    <div>
                        <div class="text-gray-900 text-[12px]">Rumah</div>
                        <div class="text-[10px] text-gray-500">Aceh, Teaspoon +</div>
                    </div>
                </div>
                <div class="flex items-center gap-3 cursor-pointer hover:opacity-75 transition flex-shrink-0">
                    <span class="text-xl">💼</span>
                    <div>
                        <div class="text-gray-900 text-[12px]">WO Only</div>
                        <div class="text-[10px] text-gray-500">Hanya Jasa WO</div>
                    </div>
                </div>
            </div>
        </div> --}}

</div><!-- end sticky wrapper -->

<div id="mobile-menu" class="hidden fixed inset-0 z-50 lg:hidden" onclick="if(event.target===this) closeMobileMenu()">
    <div class="absolute inset-0 bg-black/40 backdrop-blur-sm"></div>
    <div class="absolute right-0 top-0 h-full w-full max-w-sm bg-white shadow-2xl flex flex-col">
        <div class="px-4 py-4 border-b border-gray-100 flex items-center justify-between">
            <div class="flex items-center gap-2">
                <span class="text-lg font-extrabold tracking-tight">
                    <span class="text-accent">M</span><span class="text-dark">W</span>
                </span>
                <span class="text-xs font-semibold px-2 py-1 rounded-full bg-light-sage text-dark">Menu</span>
            </div>
            <button type="button" onclick="closeMobileMenu()" class="p-2 rounded-xl hover:bg-gray-50 transition" aria-label="Tutup menu">
                <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        <div class="p-4 border-b border-gray-100">
            <form method="GET" action="{{ route('vendor') }}" class="flex items-center gap-2">
                <input type="text" name="q" placeholder="Cari vendor atau paket..."
                       class="w-full border border-gray-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:border-gray-400 transition">
                <x-ui.button type="submit" size="sm" class="font-bold">
                    Cari
                </x-ui.button>
            </form>
        </div>

        <nav class="flex-1 overflow-y-auto p-2">
            <a href="{{ route('home') }}" class="flex items-center gap-3 px-3 py-3 rounded-xl text-sm font-semibold text-gray-700 hover:bg-gray-50 transition">
                <svg class="w-5 h-5 flex-shrink-0 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 9.75L12 4l9 5.75V20a1 1 0 01-1 1h-5v-6H9v6H4a1 1 0 01-1-1V9.75z"/>
                </svg>
                Home
            </a>

            <details class="rounded-xl hover:bg-gray-50 transition">
                <summary class="list-none flex items-center gap-3 px-3 py-3 text-sm font-semibold text-gray-700 cursor-pointer">
                    <svg class="w-5 h-5 flex-shrink-0 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 7h18M3 12h18M3 17h18"/>
                    </svg>
                    Wedding Package
                    <svg class="ml-auto w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </summary>
                <div class="px-3 pb-3 space-y-1">
                    <a href="{{ route('vendor') }}" class="flex items-center gap-2 px-3 py-2 rounded-xl text-sm text-gray-600 hover:bg-white transition border border-gray-100">
                        Semua Vendor
                    </a>
                    <a href="{{ route('vendor') }}?q=paket" class="flex items-center gap-2 px-3 py-2 rounded-xl text-sm text-gray-600 hover:bg-white transition border border-gray-100">
                        Cari Paket
                    </a>
                </div>
            </details>

            <a href="{{ route('vendor') }}" class="flex items-center gap-3 px-3 py-3 rounded-xl text-sm font-semibold text-gray-700 hover:bg-gray-50 transition">
                <svg class="w-5 h-5 flex-shrink-0 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>
                </svg>
                Vendor
            </a>

            <a href="{{ route('store') }}" class="flex items-center gap-3 px-3 py-3 rounded-xl text-sm font-semibold text-gray-700 hover:bg-gray-50 transition">
                <svg class="w-5 h-5 flex-shrink-0 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 7l1 13a1 1 0 001 1h14a1 1 0 001-1l1-13"/>
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 7h18"/>
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 7a4 4 0 018 0"/>
                </svg>
                Store
            </a>

            @auth
                @if(($headerShowJoinVendorForAuth ?? false))
                    <a href="{{ route('join.vendor') }}" class="flex items-center justify-between gap-3 px-3 py-3 rounded-xl text-sm font-semibold text-gray-700 hover:bg-gray-50 transition">
                        <span class="flex items-center gap-3">
                            <svg class="w-5 h-5 flex-shrink-0 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4.5 4.5 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                            Join Vendor
                        </span>
                        <span class="text-[10px] font-bold px-2 py-1 rounded-full bg-light-sage text-dark">Daftar</span>
                    </a>
                @endif
            @else
                <a href="{{ route('join.vendor.signup') }}" class="flex items-center justify-between gap-3 px-3 py-3 rounded-xl text-sm font-semibold text-gray-700 hover:bg-gray-50 transition">
                    <span class="flex items-center gap-3">
                        <svg class="w-5 h-5 flex-shrink-0 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4.5 4.5 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                        Join Vendor
                    </span>
                    <span class="text-[10px] font-bold px-2 py-1 rounded-full bg-light-sage text-dark">Daftar</span>
                </a>
            @endauth

            <a href="#" class="flex items-center gap-3 px-3 py-3 rounded-xl text-sm font-semibold text-gray-700 hover:bg-gray-50 transition">
                <svg class="w-5 h-5 flex-shrink-0 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 1.343-3 3v2h6v-2c0-1.657-1.343-3-3-3z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 11a7 7 0 0114 0v7a1 1 0 01-1 1H6a1 1 0 01-1-1v-7z"/>
                </svg>
                Promo
            </a>

            <a href="#" class="flex items-center gap-3 px-3 py-3 rounded-xl text-sm font-semibold text-gray-700 hover:bg-gray-50 transition">
                <svg class="w-5 h-5 flex-shrink-0 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 20h9"/>
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 3.5a2.121 2.121 0 013 3L8 18l-4 1 1-4 11.5-11.5z"/>
                </svg>
                Blog Makna
            </a>

            <details class="rounded-xl hover:bg-gray-50 transition">
                <summary class="list-none flex items-center gap-3 px-3 py-3 text-sm font-semibold text-gray-700 cursor-pointer">
                    <svg class="w-5 h-5 flex-shrink-0 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M12 2a10 10 0 100 20 10 10 0 000-20z"/>
                    </svg>
                    Lain lain
                    <svg class="ml-auto w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </summary>
                <div class="px-3 pb-3 space-y-1">
                    <a href="#" class="flex items-center gap-2 px-3 py-2 rounded-xl text-sm text-gray-600 hover:bg-white transition border border-gray-100">
                        Tentang Makna
                    </a>
                    <a href="#" class="flex items-center gap-2 px-3 py-2 rounded-xl text-sm text-gray-600 hover:bg-white transition border border-gray-100">
                        Kontak
                    </a>
                </div>
            </details>

            <div class="my-2 border-t border-gray-100"></div>

            @auth
                <a href="{{ url('/dashboard') }}" class="flex items-center gap-3 px-3 py-3 rounded-xl text-sm font-semibold text-gray-700 hover:bg-gray-50 transition">
                    <svg class="w-5 h-5 flex-shrink-0 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/>
                        <rect x="14" y="14" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/>
                    </svg>
                    Dashboard
                </a>
                @if(auth()->user()->hasRole(['super_admin', 'admin']))
                    <a href="/admin" class="flex items-center gap-3 px-3 py-3 rounded-xl text-sm font-semibold text-gray-700 hover:bg-gray-50 transition">
                        <svg class="w-5 h-5 flex-shrink-0 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><circle cx="12" cy="12" r="3"/>
                        </svg>
                        Panel Admin
                    </a>
                @endif
            @else
                <button type="button" onclick="closeMobileMenu(); openLoginModal();" class="w-full flex items-center gap-3 px-3 py-3 rounded-xl text-sm font-semibold text-gray-700 hover:bg-gray-50 transition">
                    <svg class="w-5 h-5 flex-shrink-0 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                    Login / Daftar
                </button>
            @endauth
        </nav>

        <div class="p-4 border-t border-gray-100 text-xs text-gray-600">
            <div class="flex items-center justify-between">
                <span>office@makruwedding.id</span>
                <span>+62 812-7893-2624</span>
            </div>
        </div>
    </div>
</div>

<!-- Login Modal -->
@guest
<div id="login-modal"
     class="hidden fixed inset-0 z-[9999] bg-backdrop-45 items-center justify-center p-4"
     onclick="if(event.target===this) closeLoginModal()">
    <div class="bg-white rounded-2xl w-full max-w-[26rem] shadow-2xl overflow-hidden font-sans">

        <!-- Header -->
        <div class="px-7 pt-6 flex items-center justify-between">
            <h2 class="text-xl font-bold text-dark m-0">Masuk</h2>
            <button type="button" onclick="closeLoginModal()" class="text-gray-400 hover:text-gray-600 text-xl leading-none p-1 flex items-center justify-center">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
                </svg>
            </button>
        </div>

        <!-- Body -->
        <div class="px-7 pt-5 pb-7">
            <p class="text-[13px] text-gray-500 font-light mb-5 leading-snug">
                Mulai persiapan pernikahan Anda dengan penawaran terbaik &amp; fitur eksklusif di Paket Pernikahan
            </p>

            <!-- Google Button -->
            <a href="{{ route('auth.google') }}"
                class="w-full flex items-center justify-center gap-2 py-2.5 px-4 border border-gray-300 rounded-md bg-white cursor-pointer text-sm text-dark font-normal transition hover:bg-gray-50 no-underline box-border mb-4">
                <svg width="17" height="17" viewBox="0 0 24 24">
                    <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                    <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                    <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                    <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
                </svg>
                Login dengan Google
            </a>

            <!-- Divider -->
            <div class="flex items-center gap-3 mb-4">
                <div class="flex-1 h-px bg-gray-200"></div>
                <span class="text-[11px] text-gray-400 font-light tracking-wider">ATAU</span>
                <div class="flex-1 h-px bg-gray-200"></div>
            </div>

            <!-- Form -->
            <form method="POST" action="{{ route('login.post') }}">
                @csrf

                <div class="mb-3">
                    <input type="email" name="email" placeholder="Alamat email" required
                        class="w-full px-4 py-2.5 border-0 border-b border-gray-300 text-sm text-dark font-light outline-none box-border bg-transparent transition focus:border-accent"
                        value="{{ old('email') }}">
                </div>

                <div class="mb-5">
                    <input type="password" name="password" placeholder="Kata Sandi" required
                        class="w-full px-4 py-2.5 border-0 border-b border-gray-300 text-sm text-dark font-light outline-none box-border bg-transparent transition focus:border-accent">
                </div>

                <button type="submit"
                    class="w-full bg-accent hover:bg-accent-dark text-white text-[15px] font-normal py-2.5 px-4 rounded-md border-0 cursor-pointer transition mb-3.5">
                    Lanjutkan
                </button>
            </form>

            <p class="text-center mb-3">
                <a href="{{ route('password.request') }}"
                    class="text-[13px] text-accent no-underline font-light hover:text-dark">
                    Saya lupa kata sandi
                </a>
            </p>

            <p class="text-center text-[13px] text-gray-500 font-light">
                Belum punya akun?
                <a href="{{ route('register') }}" onclick="closeLoginModal()"
                    class="text-accent no-underline font-medium hover:text-dark">
                    Daftar
                </a>
            </p>
        </div>
    </div>
</div>
@endguest

<script>
(function () {
    var bar = document.getElementById('collapsible-bar');
    var lastScrollY = window.scrollY;
    var banner = document.getElementById('announcement-banner');
    var isAnnouncementDismissed = localStorage.getItem('announcement_dismissed') === '1';

    if (isAnnouncementDismissed) {
        if (banner) banner.style.display = 'none';
    }

    window.addEventListener('scroll', function () {
        if (localStorage.getItem('announcement_dismissed') === '1') {
            bar.style.maxHeight = '0';
            return;
        }
        var currentScrollY = window.scrollY;
        if (currentScrollY > lastScrollY && currentScrollY > 60) {
            bar.style.maxHeight = '0';
        } else {
            bar.style.maxHeight = '200px';
        }
        lastScrollY = currentScrollY;
    }, { passive: true });
})();

function dismissAnnouncement() {
    localStorage.setItem('announcement_dismissed', '1');
    var banner = document.getElementById('announcement-banner');
    if (banner) banner.style.display = 'none';
    var bar = document.getElementById('collapsible-bar');
    if (bar) bar.style.maxHeight = '0';
}

function openLoginModal() {
    var modal = document.getElementById('login-modal');
    if (modal) {
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        document.body.style.overflow = 'hidden';
    }
}
function closeLoginModal() {
    var modal = document.getElementById('login-modal');
    if (modal) {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        document.body.style.overflow = '';
    }
}
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') closeLoginModal();
});

function toggleMainDropdown() {
    document.getElementById('main-profile-dropdown').classList.toggle('hidden');
}

function toggleHeaderDropdown(key) {
    var wedding = document.getElementById('dropdown-wedding');
    var lain = document.getElementById('dropdown-lain');
    var target = document.getElementById('dropdown-' + key);

    if (wedding && wedding !== target) wedding.classList.add('hidden');
    if (lain && lain !== target) lain.classList.add('hidden');
    if (target) target.classList.toggle('hidden');
}

function toggleHeaderSearch() {
    var panel = document.getElementById('header-search-panel');
    var wedding = document.getElementById('dropdown-wedding');
    var lain = document.getElementById('dropdown-lain');
    if (wedding) wedding.classList.add('hidden');
    if (lain) lain.classList.add('hidden');
    if (panel) panel.classList.toggle('hidden');
    if (panel && !panel.classList.contains('hidden')) {
        var input = panel.querySelector('input[name="q"]');
        if (input) input.focus();
    }
}

function openMobileMenu() {
    var menu = document.getElementById('mobile-menu');
    if (!menu) return;
    menu.classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeMobileMenu() {
    var menu = document.getElementById('mobile-menu');
    if (!menu) return;
    menu.classList.add('hidden');
    document.body.style.overflow = '';
}

document.addEventListener('click', function (e) {
    var wrapper = document.getElementById('main-profile-wrapper');
    if (wrapper && !wrapper.contains(e.target)) {
        var d = document.getElementById('main-profile-dropdown');
        if (d) d.classList.add('hidden');
    }
    var sw = document.getElementById('header-search-wrapper');
    if (sw && !sw.contains(e.target)) {
        var sp = document.getElementById('header-search-panel');
        if (sp) sp.classList.add('hidden');
    }
    var dw = document.getElementById('dropdown-wedding');
    var dl = document.getElementById('dropdown-lain');
    if (dw && !dw.parentElement.contains(e.target)) dw.classList.add('hidden');
    if (dl && !dl.parentElement.contains(e.target)) dl.classList.add('hidden');
});

(function () {
    var isDark = localStorage.getItem('theme') === 'dark';
    applyTheme(isDark);
})();

function applyTheme(isDark) {
    var toggle = document.getElementById('theme-toggle');
    var knob   = document.getElementById('theme-knob');
    if (isDark) {
        document.documentElement.classList.add('dark');
        if (toggle) {
            toggle.classList.remove('bg-accent');
            toggle.classList.add('bg-gray-700');
        }
        if (knob)   knob.classList.replace('translate-x-1', 'translate-x-6');
    } else {
        document.documentElement.classList.remove('dark');
        if (toggle) {
            toggle.classList.remove('bg-gray-700');
            toggle.classList.add('bg-accent');
        }
        if (knob)   knob.classList.replace('translate-x-6', 'translate-x-1');
    }
}

function toggleTheme() {
    var isDark = document.documentElement.classList.contains('dark');
    localStorage.setItem('theme', isDark ? 'light' : 'dark');
    applyTheme(!isDark);
}

document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeMobileMenu();
        var sp = document.getElementById('header-search-panel');
        if (sp) sp.classList.add('hidden');
        var dw = document.getElementById('dropdown-wedding');
        var dl = document.getElementById('dropdown-lain');
        if (dw) dw.classList.add('hidden');
        if (dl) dl.classList.add('hidden');
    }
});
</script>
