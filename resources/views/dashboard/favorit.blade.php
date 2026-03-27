@extends('layout.dashboard')

@section('title', 'Vendor Favorit Saya — Makna Wedding')
@section('page-title', 'Vendor Favorit')

@section('content')
<div class="mb-8">
    <h1 class="text-2xl font-bold mb-2" style="color: var(--dark-gray)">Vendor Favorit</h1>
    <p class="text-sm text-gray-500">Daftar vendor yang telah Anda simpan dan sukai.</p>
</div>

@if($likedVendors->isEmpty())
    <div class="bg-white rounded-2xl border border-gray-100 p-10 text-center flex flex-col items-center">
        <div class="w-16 h-16 rounded-full bg-gray-50 flex items-center justify-center mb-4">
            <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
            </svg>
        </div>
        <h3 class="text-base font-bold mb-1" style="color: var(--dark-gray)">Belum ada vendor favorit</h3>
        <p class="text-sm text-gray-500 max-w-sm mb-6">Anda belum menyukai vendor apa pun. Jelajahi berbagai vendor terbaik kami dan temukan pilihan yang pas untuk pernikahan Anda.</p>
        <a href="{{ route('vendor') }}" class="inline-flex items-center justify-center px-6 py-2.5 rounded-xl text-sm font-bold text-white transition hover:opacity-90" style="background-color: var(--sage-green)">
            Jelajahi Vendor
        </a>
    </div>
@else
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        @foreach($likedVendors as $vendor)
            <div class="bg-white rounded-2xl border border-gray-100 overflow-hidden flex flex-col group hover:shadow-md transition-shadow">
                <a href="{{ route('vendor.detail', $vendor->slug) }}" class="relative h-40 overflow-hidden block">
                    <img src="{{ $vendor->cover_image_url ?: ('https://picsum.photos/seed/vendor-' . $vendor->slug . '/800/600') }}"
                         alt="{{ $vendor->name }}"
                         class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
                    <div class="absolute top-3 right-3">
                        <form action="{{ route('vendor.like', $vendor) }}" method="POST">
                            @csrf
                            <button type="submit" class="w-8 h-8 rounded-full bg-white/90 backdrop-blur shadow-sm flex items-center justify-center text-red-500 hover:scale-110 transition-transform">
                                <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24">
                                    <path d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                                </svg>
                            </button>
                        </form>
                    </div>
                </a>
                    <div class="p-2.5 sm:p-4 flex-1 flex flex-col">
                    <div class="flex items-center justify-between mb-1">
                        <span class="text-[8px] sm:text-[10px] font-bold uppercase tracking-widest text-gray-400">{{ $vendor->type }}</span>
                        <div class="flex items-center gap-1 text-[10px] sm:text-xs font-medium" style="color: #f59e0b">
                            <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                            {{ number_format($vendor->rating, 1) }}
                        </div>
                    </div>
                    <a href="{{ route('vendor.detail', $vendor->slug) }}" class="text-[13px] sm:text-base font-bold mb-2 group-hover:text-accent transition leading-snug" style="color: var(--dark-gray)">
                        {{ $vendor->name }}
                    </a>
                    <div class="flex items-center gap-1.5 text-[10px] sm:text-xs text-gray-500 mb-3">
                        <svg class="w-3.5 h-3.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        <span class="truncate">{{ $vendor->location }}</span>
                    </div>
                    
                    <div class="mt-1 pt-2 border-t border-gray-100 flex items-center justify-between">
                        <div>
                            <p class="text-[8px] sm:text-[10px] text-gray-400 mb-0.5">Harga Mulai</p>
                            @php $cheapPkg = $vendor->cheapestPackage; @endphp
                            @if ($cheapPkg)
                                @if ($cheapPkg->discount > 0)
                                    <p class="text-[8px] sm:text-[10px] line-through text-gray-400 leading-none mb-0.5">{{ $cheapPkg->price }}</p>
                                    <p class="text-[11px] sm:text-sm font-bold leading-none" style="color: var(--sage-green)">
                                        Rp {{ number_format($cheapPkg->price_raw - $cheapPkg->discount, 0, ',', '.') }}
                                    </p>
                                @else
                                    <p class="text-[11px] sm:text-sm font-bold" style="color: var(--sage-green)">{{ $cheapPkg->price }}</p>
                                @endif
                            @else
                                <p class="text-[11px] sm:text-sm font-bold" style="color: var(--sage-green)">
                                    {{ $vendor->price_start ? 'Rp ' . number_format($vendor->price_start, 0, ',', '.') : '—' }}
                                </p>
                            @endif
                        </div>
                        <a href="{{ route('vendor.detail', $vendor->slug) }}" class="text-[10px] sm:text-xs font-bold px-2.5 sm:px-4 py-2 rounded-lg bg-gray-50 hover:bg-gray-100 transition" style="color: var(--dark-gray)">
                            Detail
                        </a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endif
@endsection
