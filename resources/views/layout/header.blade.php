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
                <button id="theme-toggle" class="font-medium hover:text-accent transition p-2">
                    <span id="theme-icon" class="text-lg">🌙</span>
                </button>
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
                <!-- Theme Toggle -->
                <button id="theme-toggle" class="p-2 text-gray-600 hover:text-accent transition rounded-full hover:bg-gray-100">
                    {{-- <span id="theme-icon" class="text-base leading-none">🌙</span> --}}
                </button>
                <!-- Login / Dashboard Button -->
                @auth
                    <a href="{{ url('/dashboard') }}" class="px-5 py-2 rounded-full text-xs font-bold text-white uppercase tracking-wide transition hover:opacity-90" style="background-color: var(--sage-green)">
                        Dashboard
                    </a>
                @else
                    <a href="{{ route('login') }}" class="px-5 py-2 rounded-full text-xs font-bold text-white uppercase tracking-wide transition hover:opacity-90" style="background-color: #D94F4F">
                        Login
                    </a>
                @endauth
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
</script>
