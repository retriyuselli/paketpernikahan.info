@extends('layout.dashboard')

@section('title', 'Favorit Saya — Makna Wedding')
@section('page-title', 'Favorit')

@section('content')
<div class="mb-6">
    <h1 class="text-xl font-bold text-dark">Favorit Saya</h1>
    <p class="text-sm text-gray-400 mt-0.5">Vendor dan paket yang telah Anda simpan.</p>
</div>

{{-- Paket Wishlist --}}
<div class="mb-8">
    <h2 class="text-sm font-bold text-dark mb-3">Paket Disimpan</h2>
    @if($wishlistPackages->isEmpty())
        <div class="bg-white rounded-2xl border border-gray-100 p-8 text-center">
            <svg class="w-10 h-10 mx-auto mb-3 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"/>
            </svg>
            <p class="text-sm text-gray-500 mb-4">Belum ada paket yang disimpan.</p>
            <a href="{{ route('store') }}" class="inline-flex items-center justify-center px-5 py-2 rounded-xl text-sm font-bold bg-accent text-white transition hover:opacity-90">
                Jelajahi Paket
            </a>
        </div>
    @else
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-3">
            @foreach($wishlistPackages as $pkg)
                @php
                    $price    = (int) ($pkg->price ?? 0);
                    $discount = (int) ($pkg->discount ?? 0);
                    $final    = max($price - $discount, 0);
                    $cover    = $pkg->image_url ?: ($pkg->vendor?->cover_image_url ?? null);
                    $cover    = $cover ?: 'https://picsum.photos/seed/pkg-'.$pkg->id.'/800/600';
                @endphp
                <div class="bg-white rounded-2xl border border-gray-100 overflow-hidden flex flex-col group hover:shadow-md transition-shadow">
                    <a href="{{ route('store.package.show', $pkg) }}" class="relative h-36 overflow-hidden block">
                        <img src="{{ $cover }}" alt="{{ $pkg->name }}"
                             class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
                        <button type="button"
                                data-wishlist-remove="{{ $pkg->id }}"
                                data-toggle-url="{{ route('wishlist.toggle', $pkg) }}"
                                class="absolute top-2 right-2 w-7 h-7 rounded-full bg-white/90 backdrop-blur shadow-sm flex items-center justify-center text-accent hover:scale-110 transition-transform">
                            <svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 24 24">
                                <path d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                            </svg>
                        </button>
                    </a>
                    <div class="p-3 flex-1 flex flex-col">
                        <p class="text-xs font-semibold text-dark leading-snug mb-1 line-clamp-2">{{ $pkg->name }}</p>
                        @if($pkg->vendor)
                            <p class="text-[10px] text-gray-400 mb-2 truncate">{{ $pkg->vendor->name }}</p>
                        @endif
                        <div class="mt-auto pt-2 border-t border-gray-100 flex items-center justify-between">
                            <div>
                                @if($discount > 0)
                                    <p class="text-[9px] line-through text-gray-400 leading-none">Rp{{ number_format($price, 0, ',', '.') }}</p>
                                    <p class="text-xs font-bold text-accent">Rp{{ number_format($final, 0, ',', '.') }}</p>
                                @else
                                    <p class="text-xs font-bold text-accent">Rp{{ number_format($price, 0, ',', '.') }}</p>
                                @endif
                            </div>
                            <a href="{{ route('store.package.show', $pkg) }}"
                               class="text-[10px] font-bold px-2.5 py-1.5 rounded-lg bg-gray-50 text-dark hover:bg-gray-100 transition">
                                Detail
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>

{{-- Vendor Favorit --}}
<div>
    <h2 class="text-sm font-bold text-dark mb-3">Vendor Disukai</h2>
    @if($likedVendors->isEmpty())
        <div class="bg-white rounded-2xl border border-gray-100 p-8 text-center">
            <svg class="w-10 h-10 mx-auto mb-3 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
            </svg>
            <p class="text-sm text-gray-500 mb-4">Belum ada vendor yang disukai.</p>
            <a href="{{ route('vendor') }}" class="inline-flex items-center justify-center px-5 py-2 rounded-xl text-sm font-bold bg-accent text-white transition hover:opacity-90">
                Jelajahi Vendor
            </a>
        </div>
    @else
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-3">
            @foreach($likedVendors as $vendor)
                <div class="bg-white rounded-2xl border border-gray-100 overflow-hidden flex flex-col group hover:shadow-md transition-shadow">
                    <a href="{{ route('vendor.detail', $vendor->slug) }}" class="relative h-36 overflow-hidden block">
                        <img src="{{ $vendor->cover_image_url ?: ('https://picsum.photos/seed/vendor-' . $vendor->slug . '/800/600') }}"
                             alt="{{ $vendor->name }}"
                             class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
                        <div class="absolute top-2 right-2">
                            <form action="{{ route('vendor.like', $vendor) }}" method="POST">
                                @csrf
                                <button type="submit" class="w-7 h-7 rounded-full bg-white/90 backdrop-blur shadow-sm flex items-center justify-center text-red-500 hover:scale-110 transition-transform">
                                    <svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 24 24">
                                        <path d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </a>
                    <div class="p-3 flex-1 flex flex-col">
                        <a href="{{ route('vendor.detail', $vendor->slug) }}" class="text-xs font-bold mb-1 text-dark group-hover:text-accent transition leading-snug">
                            {{ $vendor->name }}
                        </a>
                        <p class="text-[10px] text-gray-400 mb-2 truncate">{{ $vendor->location }}</p>
                        <div class="mt-auto pt-2 border-t border-gray-100 flex items-center justify-between">
                            <div>
                                <p class="text-[9px] text-gray-400 mb-0.5">Harga Mulai</p>
                                @php $cheapPkg = $vendor->cheapestPackage; @endphp
                                @if($cheapPkg && $cheapPkg->discount > 0)
                                    <p class="text-xs font-bold text-accent">Rp{{ number_format($cheapPkg->price - $cheapPkg->discount, 0, ',', '.') }}</p>
                                @elseif($cheapPkg)
                                    <p class="text-xs font-bold text-accent">Rp{{ number_format($cheapPkg->price, 0, ',', '.') }}</p>
                                @else
                                    <p class="text-xs font-bold text-accent">{{ $vendor->price_start ? 'Rp '.number_format($vendor->price_start,0,',','.') : '—' }}</p>
                                @endif
                            </div>
                            <a href="{{ route('vendor.detail', $vendor->slug) }}"
                               class="text-[10px] font-bold px-2.5 py-1.5 rounded-lg bg-gray-50 text-dark hover:bg-gray-100 transition">
                                Detail
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>

@endsection

@section('scripts')
<script>
document.addEventListener('click', function (e) {
    var btn = e.target.closest('[data-wishlist-remove]');
    if (!btn) return;
    var url  = btn.dataset.toggleUrl;
    var card = btn.closest('.bg-white.rounded-2xl');
    fetch(url, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content ?? '',
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
        },
    }).then(r => r.json()).then(d => {
        if (!d.wishlisted && card) card.remove();
    }).catch(() => {});
});
</script>
@endsection
