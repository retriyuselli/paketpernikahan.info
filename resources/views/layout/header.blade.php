<!-- Top Contact Bar (scrolls away naturally, NOT sticky) -->
<div class="bg-white border-b border-gray-100">
    <x-ui.container>
        <div class="hidden md:flex items-center justify-between py-3 text-xs">
            <div class="flex items-center gap-6 text-gray-600">
                <span>maknawedding@gmail.com</span>
                <span>+62 822-9796-2600</span>
                <div class="flex gap-3">
                    <a href="https://wa.me/6282297962600" target="_blank" rel="noopener noreferrer" class="hover:text-accent transition" title="WhatsApp">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z"/>
                            <path d="M12 0C5.373 0 0 5.373 0 12c0 2.127.558 4.126 1.535 5.857L.057 23.43l5.752-1.507A11.953 11.953 0 0012 24c6.627 0 12-5.373 12-12S18.627 0 12 0zm0 21.818a9.81 9.81 0 01-5.007-1.373l-.36-.214-3.715.974.99-3.618-.234-.372A9.818 9.818 0 012.182 12C2.182 6.57 6.57 2.182 12 2.182S21.818 6.57 21.818 12 17.43 21.818 12 21.818z"/>
                        </svg>
                    </a>
                    <a href="https://www.instagram.com/makna.wedding/" target="_blank" rel="noopener noreferrer" class="hover:text-accent transition" title="Instagram">
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
                    @if(($headerShowJoinVendorForAuth ?? false))
                        <a href="{{ route('join.vendor') }}" class="font-medium text-accent hover:underline">Join Vendor</a>
                    @endif
                    <a href="{{ url('/dashboard') }}" class="font-medium text-accent hover:underline">Dashboard</a>
                @else
                    <a href="{{ route('join.vendor.signup') }}" class="font-medium text-accent hover:underline">Join Vendor</a>
                    <button type="button" data-open-login-modal class="font-medium text-accent hover:underline">Login</button>
                @endauth
            </div>
        </div>
    </x-ui.container>
</div>

<!-- Sticky Header Wrapper (main nav only, no collapsible) -->
<div class="safe-area-header fixed top-0 left-0 right-0 z-40 border-b border-gray-200 bg-white lg:backdrop-blur-none">
    <x-ui.container>
        <div class="flex items-center justify-between gap-2 py-2 lg:gap-8 lg:py-3">
            @php
                $showMobileBackButton = request()->routeIs('search')
                    || request()->routeIs('store')
                    || request()->routeIs('store.*')
                    || request()->routeIs('vendor')
                    || request()->routeIs('vendor.*');
            @endphp

            <!-- Logo (left) -->
            <div class="hidden items-center gap-2 h-10 flex-shrink-0 lg:flex">
                <a href="/" class="flex items-center gap-2">
                    <span class="text-2xl lg:text-3xl font-extrabold tracking-tight leading-none">
                        <span class="text-accent">M</span><span class="text-dark">W</span>
                    </span>
                </a>
            </div>

            @if($showMobileBackButton)
                <button type="button"
                        onclick="if (window.history.length > 1) { window.history.back(); } else { window.location.href='{{ route('home') }}'; }"
                        class="inline-flex h-9 w-9 flex-shrink-0 items-center justify-center rounded-full border border-gray-200 bg-white text-dark transition hover:border-gray-300 lg:hidden"
                        aria-label="Kembali">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                </button>
            @endif

            <form id="tour-search-mobile" method="GET" action="{{ route('search') }}"
                  class="ml-0 mr-3 flex min-w-0 flex-1 items-center gap-2 rounded-none border border-gray-200 bg-gray-50 px-3 py-1.5 transition hover:border-gray-300 focus-within:border-accent focus-within:bg-white lg:hidden">
                <input type="text" name="q" value="{{ request('q') }}" placeholder="Temukan paket pernikahan impian Anda"
                       class="min-w-0 flex-1 bg-transparent text-sm text-dark placeholder-gray-400 focus:outline-none">
                <button type="submit" class="hidden" aria-label="Cari"></button>
            </form>

            <!-- Main Navigation (center) -->
            <nav class="hidden lg:flex items-center justify-start gap-6 whitespace-nowrap relative z-20">
                @php
                    $navIsHome       = request()->routeIs('home');
                    $navIsVendor     = request()->routeIs('vendor') || request()->is('vendor*');
                    $navIsStore      = request()->routeIs('store') || request()->is('store*');
                    $navIsPromo      = request()->routeIs('store.promo');
                    $navIsRealWed    = request()->routeIs('real-wedding.*');
                    $navIsBlog       = request()->routeIs('blog.*');
                @endphp
                <a href="{{ route('home') }}"
                   class="text-xs font-bold tracking-wide transition uppercase @if($navIsHome) text-accent @else text-gray-400 hover:text-accent @endif">
                    Home
                </a>
                <span id="tour-nav-vendor-store" class="flex items-center gap-6">
                    <a href="{{ route('vendor') }}"
                       class="text-xs font-bold tracking-wide transition uppercase @if($navIsVendor) text-accent @else text-gray-400 hover:text-accent @endif">
                        Vendor
                    </a>
                    <a href="{{ route('store') }}"
                       class="text-xs font-bold tracking-wide transition uppercase @if($navIsStore) text-accent @else text-gray-400 hover:text-accent @endif">
                        Store
                    </a>
                </span>
                <a id="tour-nav-promo" href="{{ route('store.promo') }}"
                   class="text-xs font-bold tracking-wide transition uppercase @if($navIsPromo) text-accent @else text-gray-400 hover:text-accent @endif">Promo</a>
                <a id="tour-nav-real-wedding" href="{{ route('real-wedding.index') }}"
                   class="text-xs font-bold tracking-wide transition uppercase @if($navIsRealWed) text-accent @else text-gray-400 hover:text-accent @endif">Real Wedding</a>
                <a href="{{ route('blog.index') }}"
                   class="text-xs font-bold tracking-wide transition uppercase @if($navIsBlog) text-accent @else text-gray-400 hover:text-accent @endif">Blog</a>
                <div class="relative group">
                    <button type="button" data-toggle-header-dropdown="lain"
                            class="flex items-center gap-1 text-xs font-bold tracking-wide text-gray-800 hover:text-accent transition uppercase"
                            aria-expanded="false" aria-controls="dropdown-lain">
                        Lain lain
                        <svg class="w-3 h-3 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    <div id="dropdown-lain" class="hidden absolute left-0 mt-2 w-56 bg-white rounded-xl shadow-lg border border-gray-100 py-1 z-50">
                        <a href="{{ route('tentang') }}" class="flex items-center gap-2.5 px-4 py-2.5 text-xs text-gray-600 hover:bg-gray-50 transition">
                            Tentang Makna
                        </a>
                        <a href="{{ route('kontak') }}" class="flex items-center gap-2.5 px-4 py-2.5 text-xs text-gray-600 hover:bg-gray-50 transition">
                            Kontak
                        </a>
                    </div>
                </div>
            </nav>

            <!-- Search Bar -->
            <form id="tour-search" method="GET" action="{{ route('search') }}"
                  class="hidden lg:flex flex-1 items-center gap-1.5 bg-gray-100 border border-gray-200 rounded-full px-3 py-1.5 ml-4 hover:border-gray-300 focus-within:border-accent focus-within:bg-white transition">
                <svg class="w-4 h-4 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari vendor atau paket..."
                       class="flex-1 bg-transparent text-xs text-dark placeholder-gray-400 focus:outline-none min-w-0">
            </form>

            <!-- Right Actions -->
            <div class="flex items-center justify-end gap-2 relative z-10" id="header-actions">

                @auth
                @php
                    $hdrUser = auth()->user();
                    $hdrIsAdmin = $hdrUser->hasRole(['super_admin', 'admin']);
                    $hdrIsVendor = !$hdrIsAdmin && \App\Models\Vendor::where('owner_user_id', $hdrUser->id)->exists();
                @endphp
                @if($hdrIsAdmin || $hdrIsVendor)
                <a href="{{ $hdrIsAdmin ? route('chat.admin') : route('chat.vendor.index') }}"
                   class="relative items-center justify-center w-9 h-9 rounded-full hover:bg-gray-100 transition"
                   style="display:none"
                   id="header-public-chat-btn">
                    <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                    </svg>
                    <span id="header-public-chat-count"
                          class="absolute -top-0.5 -right-0.5 min-w-[1.1rem] h-[1.1rem] rounded-full bg-red-500 ring-2 ring-white text-white text-[9px] font-bold items-center justify-center px-0.5"
                          style="display:none">
                        0
                    </span>
                </a>
                @else
                <a href="{{ route('dashboard.my-chats') }}"
                   class="relative flex items-center justify-center w-9 h-9 rounded-full hover:bg-gray-100 transition"
                   id="header-user-chat-btn"
                   title="Chat saya">
                    <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                    </svg>
                    <span id="header-user-chat-count"
                          class="absolute -top-0.5 -right-0.5 min-w-[1.1rem] h-[1.1rem] rounded-full bg-red-500 ring-2 ring-white text-white text-[9px] font-bold items-center justify-center px-0.5"
                          style="display:none">0</span>
                </a>
                @endif
                @endauth

                <!-- Profile Dropdown -->
                <div class="relative" id="main-profile-wrapper">
                    <button type="button" data-toggle-main-dropdown
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

                <button type="button" id="tour-hamburger" data-open-mobile-menu
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

<div id="mobile-menu" class="hidden fixed inset-0 z-50 lg:hidden">
    <div class="absolute inset-0 bg-black/40 backdrop-blur-sm"></div>
    <div class="absolute right-0 top-0 h-full w-full max-w-sm bg-white shadow-2xl flex flex-col">
        <div class="px-4 py-4 border-b border-gray-100 flex items-center justify-between" style="padding-top: calc(1rem + env(safe-area-inset-top))">
            <div class="flex items-center gap-2">
                <span class="text-lg font-extrabold tracking-tight">
                    <span class="text-accent">M</span><span class="text-dark">W</span>
                </span>
                <span class="text-xs font-semibold px-2 py-1 rounded-full bg-light-sage text-dark">Menu</span>
            </div>
            <button type="button" data-close-mobile-menu class="p-2 rounded-xl hover:bg-gray-50 transition" aria-label="Tutup menu">
                <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        <div class="p-4 border-b border-gray-100">
            <form method="GET" action="{{ route('search') }}" class="flex items-center gap-2">
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

            <a href="{{ route('store.promo') }}" class="flex items-center gap-3 px-3 py-3 rounded-xl text-sm font-semibold text-gray-700 hover:bg-gray-50 transition">
                <svg class="w-5 h-5 flex-shrink-0 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 1.343-3 3v2h6v-2c0-1.657-1.343-3-3-3z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 11a7 7 0 0114 0v7a1 1 0 01-1 1H6a1 1 0 01-1-1v-7z"/>
                </svg>
                Promo
            </a>

            <a href="{{ route('real-wedding.index') }}" class="flex items-center gap-3 px-3 py-3 rounded-xl text-sm font-semibold text-gray-700 hover:bg-gray-50 transition">
                <svg class="w-5 h-5 flex-shrink-0 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                </svg>
                Real Wedding
            </a>

            <a href="{{ route('blog.index') }}" class="flex items-center gap-3 px-3 py-3 rounded-xl text-sm font-semibold text-gray-700 hover:bg-gray-50 transition">
                <svg class="w-5 h-5 flex-shrink-0 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 20h9"/>
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 3.5a2.121 2.121 0 013 3L8 18l-4 1 1-4 11.5-11.5z"/>
                </svg>
                Blog
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
                    <a href="{{ route('tentang') }}" class="flex items-center gap-2 px-3 py-2 rounded-xl text-sm text-gray-600 hover:bg-white transition border border-gray-100">
                        Tentang Makna
                    </a>
                    <a href="{{ route('kontak') }}" class="flex items-center gap-2 px-3 py-2 rounded-xl text-sm text-gray-600 hover:bg-white transition border border-gray-100">
                        Kontak
                    </a>
                </div>
            </details>

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
                <button type="button" data-mobile-login class="w-full flex items-center gap-3 px-3 py-3 rounded-xl text-sm font-semibold text-gray-700 hover:bg-gray-50 transition">
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
    >
    <div class="bg-white rounded-2xl w-full max-w-[26rem] shadow-2xl overflow-hidden font-sans">

        <!-- Header -->
        <div class="px-7 pt-6 flex items-center justify-between">
            <h2 class="text-xl font-bold text-dark m-0">Masuk</h2>
            <button type="button" data-close-login-modal class="text-gray-400 hover:text-gray-600 text-xl leading-none p-1 flex items-center justify-center">
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
                <a href="{{ route('register') }}" data-close-login-modal
                    class="text-accent no-underline font-medium hover:text-dark">
                    Daftar
                </a>
            </p>
        </div>
    </div>
</div>
@endguest

<script>

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

document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('[data-open-login-modal]').forEach(function (btn) {
        btn.addEventListener('click', function () {
            openLoginModal();
        });
    });

    document.querySelectorAll('[data-close-login-modal]').forEach(function (btn) {
        btn.addEventListener('click', function () {
            closeLoginModal();
        });
    });

    document.querySelectorAll('[data-toggle-header-dropdown]').forEach(function (btn) {
        btn.addEventListener('click', function () {
            toggleHeaderDropdown(btn.getAttribute('data-toggle-header-dropdown'));
        });
    });

    document.querySelectorAll('[data-toggle-header-search]').forEach(function (btn) {
        btn.addEventListener('click', function () {
            toggleHeaderSearch();
        });
    });

    document.querySelectorAll('[data-toggle-theme]').forEach(function (btn) {
        btn.addEventListener('click', function () {
            toggleTheme();
        });
    });

    document.querySelectorAll('[data-toggle-main-dropdown]').forEach(function (btn) {
        btn.addEventListener('click', function () {
            toggleMainDropdown();
        });
    });

    document.querySelectorAll('[data-open-mobile-menu]').forEach(function (btn) {
        btn.addEventListener('click', function () {
            openMobileMenu();
        });
    });

    document.querySelectorAll('[data-close-mobile-menu]').forEach(function (btn) {
        btn.addEventListener('click', function () {
            closeMobileMenu();
        });
    });

    document.querySelectorAll('[data-mobile-login]').forEach(function (btn) {
        btn.addEventListener('click', function () {
            closeMobileMenu();
            openLoginModal();
        });
    });

    var mobileMenu = document.getElementById('mobile-menu');
    if (mobileMenu) {
        mobileMenu.addEventListener('click', function (e) {
            if (e.target === mobileMenu) closeMobileMenu();
        });
    }

    var loginModal = document.getElementById('login-modal');
    if (loginModal) {
        loginModal.addEventListener('click', function (e) {
            if (e.target === loginModal) closeLoginModal();
        });
    }
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

@auth
@php
    $hdrNotifyUrl = isset($hdrIsAdmin) && $hdrIsAdmin
        ? route('chat.admin.notify')
        : (isset($hdrIsVendor) && $hdrIsVendor ? route('chat.vendor.notify') : '');
@endphp
@if($hdrNotifyUrl)
(function () {
    var btn   = document.getElementById('header-public-chat-btn');
    var count = document.getElementById('header-public-chat-count');
    if (!btn) return;
    var url = '{{ $hdrNotifyUrl }}';
    function poll() {
        fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
            .then(function (r) { return r.json(); })
            .then(function (d) {
                var n = parseInt(d.open_guest_sessions_count || 0, 10);
                btn.style.display   = n > 0 ? 'flex' : 'none';
                if (count) {
                    count.style.display = n > 0 ? 'flex' : 'none';
                    count.textContent   = n > 9 ? '9+' : String(n);
                }
            })
            .catch(function () {});
    }
    poll();
    setInterval(poll, 10000);
})();
@endif
@if(!$hdrIsAdmin && !$hdrIsVendor)
(function () {
    var btn   = document.getElementById('header-user-chat-btn');
    var count = document.getElementById('header-user-chat-count');
    if (!btn) return;
    var url = '{{ route('chat.user.notify') }}';
    function poll() {
        fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
            .then(function (r) { return r.json(); })
            .then(function (d) {
                var n = parseInt(d.open_sessions_count || 0, 10);
                if (count) {
                    count.style.display = n > 0 ? 'flex' : 'none';
                    count.textContent   = n > 9 ? '9+' : String(n);
                }
            })
            .catch(function () {});
    }
    poll();
    setInterval(poll, 10000);
})();
@endif
@endauth
</script>
