<!-- Sticky Header Wrapper -->
<div class="sticky top-0 z-40 bg-white border-b border-gray-200">

    <!-- Collapsible: Announcement Banner + Top Contact Bar -->
    <div id="collapsible-bar" class="overflow-hidden transition-all duration-300" style="max-height: 200px;">
        <!-- Top Announcement Banner -->
        <div class="text-white text-center py-2 px-4 text-sm font-medium" style="background: linear-gradient(to right, var(--sage-green), var(--light-sage))">
            Coming soon, Sumatera Selatan Wedding Expo 2026 Season 1
        </div>
        <!-- Top Contact Bar -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between py-3 text-xs border-b border-gray-100">
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
                    <a href="{{ route('login') }}" class="font-medium hover:text-accent">My account</a>
                @endauth
                <a href="#" class="font-medium hover:text-accent">Rp 0</a>
            </div>
        </div>
        </div><!-- end max-w-7xl for contact bar -->
    </div><!-- end collapsible-bar -->

    <!-- Main Header (always sticky) -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-3 items-center py-3">

            <!-- Logo (left) -->
            <div class="flex items-center gap-2 flex-shrink-0">
                <a href="/" class="flex items-center gap-2">
                    <span class="text-3xl font-extrabold tracking-tight">
                        <span style="color:var(--sage-green)">M</span><span style="color:var(--dark-gray)">W</span>
                    </span>
                </a>
            </div>

            <!-- Main Navigation (center) -->
            <nav class="hidden lg:flex items-center justify-center gap-6 whitespace-nowrap">
                <a href="#" class="text-xs font-bold tracking-wide text-gray-800 hover:text-accent transition uppercase">Home</a>
                <div class="relative group">
                    <button class="flex items-center gap-1 text-xs font-bold tracking-wide text-gray-800 hover:text-accent transition uppercase">
                        Wedding Package
                        <svg class="w-3 h-3 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                </div>
                <a href="{{ route('vendor') }}" class="text-xs font-bold tracking-wide text-gray-800 hover:text-accent transition uppercase">Vendor</a>
                <a href="#" class="text-xs font-bold tracking-wide text-gray-800 hover:text-accent transition uppercase">Promo</a>
                <a href="#" class="text-xs font-bold tracking-wide text-gray-800 hover:text-accent transition uppercase">Blog Makna</a>
                <div class="relative group">
                    <button class="flex items-center gap-1 text-xs font-bold tracking-wide text-gray-800 hover:text-accent transition uppercase">
                        Lain lain
                        <svg class="w-3 h-3 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                </div>
            </nav>

            <!-- Right Actions -->
            <div class="flex items-center justify-end gap-2">
                <!-- Search Icon -->
                <button class="p-2 text-gray-600 hover:text-accent transition rounded-full hover:bg-gray-100">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </button>

                <!-- Theme Toggle (iPhone style) -->
                <button id="theme-toggle" onclick="toggleTheme()"
                        class="relative inline-flex items-center w-11 h-6 rounded-full transition-colors duration-300 focus:outline-none"
                        style="background: var(--sage-green)"
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
                                <img src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url(auth()->user()->avatar_url) }}"
                                     alt="{{ auth()->user()->name }}" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-sm font-bold text-white"
                                     style="background: var(--sage-green)">
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
                                <p class="text-xs font-semibold truncate" style="color: var(--dark-gray)">{{ auth()->user()->name }}</p>
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
            </div>

        </div>
    </div><!-- end max-w-7xl main header -->

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

<script>
(function () {
    var bar = document.getElementById('collapsible-bar');
    var lastScrollY = window.scrollY;

    window.addEventListener('scroll', function () {
        var currentScrollY = window.scrollY;
        if (currentScrollY > lastScrollY && currentScrollY > 60) {
            bar.style.maxHeight = '0';
        } else {
            bar.style.maxHeight = '200px';
        }
        lastScrollY = currentScrollY;
    }, { passive: true });
})();

function toggleMainDropdown() {
    document.getElementById('main-profile-dropdown').classList.toggle('hidden');
}

document.addEventListener('click', function (e) {
    var wrapper = document.getElementById('main-profile-wrapper');
    if (wrapper && !wrapper.contains(e.target)) {
        var d = document.getElementById('main-profile-dropdown');
        if (d) d.classList.add('hidden');
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
        if (toggle) toggle.style.background = '#374151';
        if (knob)   knob.classList.replace('translate-x-1', 'translate-x-6');
    } else {
        document.documentElement.classList.remove('dark');
        if (toggle) toggle.style.background = 'var(--sage-green)';
        if (knob)   knob.classList.replace('translate-x-6', 'translate-x-1');
    }
}

function toggleTheme() {
    var isDark = document.documentElement.classList.contains('dark');
    localStorage.setItem('theme', isDark ? 'light' : 'dark');
    applyTheme(!isDark);
}
</script>
