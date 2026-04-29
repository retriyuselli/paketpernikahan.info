@php $stats = $this->getStats(); @endphp

<div class="grid grid-cols-2 md:grid-cols-4 gap-4 p-2">

    {{-- Vendors --}}
    <div class="rounded-2xl border border-gray-200 bg-white p-4 shadow-sm">
        <div class="flex items-center justify-between mb-3">
            <p class="text-xs font-semibold uppercase tracking-widest text-gray-400">Vendor</p>
            <div class="w-8 h-8 rounded-xl flex items-center justify-center bg-amber-50">
                <x-heroicon-o-building-storefront class="w-4 h-4 text-amber-500"/>
            </div>
        </div>
        <p class="text-2xl font-extrabold text-gray-800">{{ number_format($stats['totalVendors']) }}</p>
        <p class="text-xs text-gray-400 mt-1">{{ $stats['activeVendors'] }} aktif</p>
    </div>

    {{-- Packages --}}
    <div class="rounded-2xl border border-gray-200 bg-white p-4 shadow-sm">
        <div class="flex items-center justify-between mb-3">
            <p class="text-xs font-semibold uppercase tracking-widest text-gray-400">Paket</p>
            <div class="w-8 h-8 rounded-xl flex items-center justify-center bg-emerald-50">
                <x-heroicon-o-archive-box class="w-4 h-4 text-emerald-500"/>
            </div>
        </div>
        <p class="text-2xl font-extrabold text-gray-800">{{ number_format($stats['totalPackages']) }}</p>
        <p class="text-xs text-gray-400 mt-1">{{ $stats['activePackages'] }} aktif</p>
    </div>

    {{-- Bookings --}}
    <div class="rounded-2xl border border-gray-200 bg-white p-4 shadow-sm">
        <div class="flex items-center justify-between mb-3">
            <p class="text-xs font-semibold uppercase tracking-widest text-gray-400">Booking</p>
            <div class="w-8 h-8 rounded-xl flex items-center justify-center bg-blue-50">
                <x-heroicon-o-calendar-days class="w-4 h-4 text-blue-500"/>
            </div>
        </div>
        <p class="text-2xl font-extrabold text-gray-800">{{ number_format($stats['totalBookings']) }}</p>
        <div class="flex gap-2 mt-1 flex-wrap">
            <span class="text-[10px] font-semibold px-1.5 py-0.5 rounded-full bg-yellow-50 text-yellow-600">{{ $stats['pendingBookings'] }} pending</span>
            <span class="text-[10px] font-semibold px-1.5 py-0.5 rounded-full bg-green-50 text-green-600">{{ $stats['confirmedBookings'] }} confirmed</span>
        </div>
    </div>

    {{-- Users --}}
    <div class="rounded-2xl border border-gray-200 bg-white p-4 shadow-sm">
        <div class="flex items-center justify-between mb-3">
            <p class="text-xs font-semibold uppercase tracking-widest text-gray-400">Pengguna</p>
            <div class="w-8 h-8 rounded-xl flex items-center justify-center bg-violet-50">
                <x-heroicon-o-users class="w-4 h-4 text-violet-500"/>
            </div>
        </div>
        <p class="text-2xl font-extrabold text-gray-800">{{ number_format($stats['totalUsers']) }}</p>
        <p class="text-xs text-gray-400 mt-1">terdaftar</p>
    </div>

    {{-- Reviews --}}
    <div class="rounded-2xl border border-gray-200 bg-white p-4 shadow-sm">
        <div class="flex items-center justify-between mb-3">
            <p class="text-xs font-semibold uppercase tracking-widest text-gray-400">Ulasan</p>
            <div class="w-8 h-8 rounded-xl flex items-center justify-center bg-rose-50">
                <x-heroicon-o-star class="w-4 h-4 text-rose-500"/>
            </div>
        </div>
        <p class="text-2xl font-extrabold text-gray-800">{{ number_format($stats['totalReviews']) }}</p>
        @if($stats['pendingReviews'] > 0)
        <span class="text-[10px] font-semibold px-1.5 py-0.5 rounded-full bg-yellow-50 text-yellow-600 mt-1 inline-block">{{ $stats['pendingReviews'] }} perlu disetujui</span>
        @else
        <p class="text-xs text-gray-400 mt-1">semua disetujui</p>
        @endif
    </div>

    {{-- Blog --}}
    <div class="rounded-2xl border border-gray-200 bg-white p-4 shadow-sm">
        <div class="flex items-center justify-between mb-3">
            <p class="text-xs font-semibold uppercase tracking-widest text-gray-400">Blog</p>
            <div class="w-8 h-8 rounded-xl flex items-center justify-center bg-sky-50">
                <x-heroicon-o-newspaper class="w-4 h-4 text-sky-500"/>
            </div>
        </div>
        <p class="text-2xl font-extrabold text-gray-800">{{ number_format($stats['totalBlogs']) }}</p>
        <p class="text-xs text-gray-400 mt-1">artikel</p>
    </div>

</div>
