<!-- Top Announcement Banner -->
<div class="bg-gradient-to-r from-red-600 to-rose-500 text-white text-center py-2 px-4 text-sm font-medium">
    Coming soon, Sumatera Selatan Wedding Expo 2026 Season 1
</div>

<!-- Header -->
<header class="border-b border-gray-200 sticky top-0 bg-white z-40">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Top Contact Bar -->
        <div class="flex items-center justify-between py-3 text-xs border-b border-gray-100">
            <div class="flex items-center gap-6 text-gray-600">
                <span>office@makruwedding.id</span>
                <span>+62 812-7893-2624</span>
                <div class="flex gap-3">
                    <a href="#" class="hover:text-red-600 transition" title="Facebook">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                        </svg>
                    </a>
                    <a href="#" class="hover:text-red-600 transition" title="Instagram">
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
                    <a href="{{ url('/dashboard') }}" class="font-medium hover:text-red-600">Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="font-medium hover:text-red-600">My account</a>
                @endauth
                <button id="theme-toggle" class="font-medium hover:text-red-600 transition p-2">
                    <span id="theme-icon" class="text-lg">🌙</span>
                </button>
                <a href="#" class="font-medium hover:text-red-600">Rp 0</a>
            </div>
        </div>

        <!-- Main Header -->
        <div class="flex items-center justify-between gap-8 py-4">
            <!-- Logo -->
            <div class="flex-shrink-0">
                <a href="/" class="text-3xl font-bold">
                    <span class="text-red-600">M</span><span class="text-red-500">W</span>
                </a>
            </div>

            <!-- Search Bar -->
            <div class="hidden lg:flex flex-1 max-w-md">
                <div class="relative w-full">
                    <input type="text" placeholder="Temukan paket/produk di sini" class="w-full px-4 py-2 border border-gray-300 rounded-lg text-xs focus:outline-none focus:ring-2 focus:ring-red-400 focus:border-transparent">
                    <button class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                        <span class="text-xs">🔍</span>
                    </button>
                </div>
            </div>

            <!-- Main Navigation -->
            <nav class="hidden lg:flex items-center gap-8">
                <a href="#" class="text-gray-900 text-sm hover:text-red-600 transition">Home</a>
                <a href="#packages" class="text-gray-900 text-sm hover:text-red-600 transition">Wedding Package</a>
                <a href="#" class="text-gray-900 text-sm hover:text-red-600 transition">Promo</a>
                <a href="#" class="text-gray-900 text-sm hover:text-red-600 transition">Blog Makna</a>
                <a href="#" class="text-gray-900 text-sm hover:text-red-600 transition">Lain lain</a>
            </nav>
        </div>

        <!-- Category Navigation -->
        <div class="border-t border-gray-100 -mx-4 sm:-mx-6 lg:-mx-8 px-4 sm:px-6 lg:px-8">
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
        </div>
    </div>
</header>
