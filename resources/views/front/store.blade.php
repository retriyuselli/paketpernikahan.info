@extends('layout.app')

@section('title', 'Store - Makna Wedding')

@section('body-class', 'bg-cream text-dark')

@section('content')
    @php $isNavyGold = \App\Http\Controllers\ThemeController::active()['name'] === 'navy-gold'; @endphp
    @include('layout.header')

    <section class="py-10 bg-cream">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between gap-4 mb-6">
                <div>
                    <h1 class="text-xl font-bold text-dark">Store</h1>
                    <p class="text-sm text-gray-500">Pilih paket vendor berdasarkan kategori.</p>
                </div>
                <a href="{{ route('vendor') }}" class="hidden sm:inline-flex text-xs font-bold uppercase tracking-widest px-4 py-2 rounded-full transition hover:opacity-90 bg-accent text-cream">
                    Lihat Semua Vendor
                </a>
            </div>

            <div class="relative mb-6">
                <button type="button"
                        data-scroll-target="store-highlights-scroll" data-scroll-by="-500"
                        class="hidden lg:flex absolute left-0 top-1/2 -translate-y-1/2 -translate-x-4 w-10 h-10 bg-white rounded-full shadow-md items-center justify-center hover:shadow-lg transition z-10">
                    <svg class="w-5 h-5 text-dark" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                </button>

                <div id="store-highlights-scroll" class="flex gap-3 overflow-x-auto scroll-smooth pb-2 scrollbar-hide">
                    <a href="#store-sections" class="flex-none w-[22rem] sm:w-[26rem] rounded-2xl overflow-hidden relative group shadow-sm hover:shadow-md transition ar-16x9">
                        <img src="https://picsum.photos/seed/store-hero-1/1200/675" alt="Paket Lengkap" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/10 to-transparent"></div>
                        <div class="absolute bottom-0 left-0 right-0 p-4">
                            <p class="text-white text-sm font-bold leading-snug">Paket Lengkap untuk Hari Spesial</p>
                            <p class="text-white/80 text-xs mt-1">Cari paket terbaik sesuai budget dan lokasi</p>
                        </div>
                    </a>
                    <a href="{{ route('vendor') }}?q=promo" class="flex-none w-[22rem] sm:w-[26rem] rounded-2xl overflow-hidden relative group shadow-sm hover:shadow-md transition ar-16x9">
                        <img src="https://picsum.photos/seed/store-hero-2/1200/675" alt="Promo" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/10 to-transparent"></div>
                        <div class="absolute bottom-0 left-0 right-0 p-4">
                            <p class="text-white text-sm font-bold leading-snug">Promo & Penawaran Terbaru</p>
                            <p class="text-white/80 text-xs mt-1">Diskon dan bonus dari vendor pilihan</p>
                        </div>
                    </a>
                    <a href="{{ route('vendor') }}?q=paket" class="flex-none w-[22rem] sm:w-[26rem] rounded-2xl overflow-hidden relative group shadow-sm hover:shadow-md transition ar-16x9">
                        <img src="https://picsum.photos/seed/store-hero-3/1200/675" alt="Semua Paket" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/10 to-transparent"></div>
                        <div class="absolute bottom-0 left-0 right-0 p-4">
                            <p class="text-white text-sm font-bold leading-snug">Semua Paket Vendor</p>
                            <p class="text-white/80 text-xs mt-1">Bandingkan paket dari berbagai kategori</p>
                        </div>
                    </a>
                </div>

                <button type="button"
                        data-scroll-target="store-highlights-scroll" data-scroll-by="500"
                        class="hidden lg:flex absolute right-0 top-1/2 -translate-y-1/2 translate-x-4 w-10 h-10 bg-white rounded-full shadow-md items-center justify-center hover:shadow-lg transition z-10">
                    <svg class="w-5 h-5 text-dark" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </button>
            </div>

            <div class="mb-10 flex justify-center">
                <a href="{{ route('vendor') }}" class="block w-full max-w-[728px] bg-white rounded-2xl border border-gray-100 overflow-hidden hover:border-gray-200 hover:shadow-sm transition">
                    <div class="relative aspect-[728/90]">
                        <img src="https://picsum.photos/seed/makna-leaderboard/728/90" alt="Iklan" class="w-full h-full object-cover">
                        <div class="absolute inset-0 bg-gradient-to-r from-black/55 via-black/10 to-transparent"></div>
                        <div class="absolute inset-y-0 left-0 flex flex-col justify-center p-4">
                            <p class="text-white text-xs font-extrabold leading-snug">Promo spesial untuk booking vendor pilihanmu</p>
                            <p class="text-white/80 text-[10px] mt-0.5">Klik untuk lihat daftar vendor dan paket terbaru.</p>
                        </div>
                    </div>
                </a>
            </div>

            <div id="store-sections"></div>

            @if(!empty($search) || !empty($kategori))
                <div class="flex items-center justify-between gap-4 mb-6 p-4 bg-white rounded-2xl border border-gray-100">
                    <div class="flex items-center gap-3">
                        <svg class="w-4 h-4 text-accent flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                        <p class="text-sm text-gray-700">
                            Hasil pencarian untuk:
                            @if(!empty($search))
                                <strong>"{{ $search }}"</strong>
                            @endif
                            @if(!empty($kategori))
                                <span class="ml-1 inline-block bg-accent/10 text-accent text-xs font-semibold px-2 py-0.5 rounded-full">{{ ucfirst($kategori) }}</span>
                            @endif
                        </p>
                    </div>
                    <a href="{{ route('store') }}" class="text-xs text-gray-400 hover:text-accent transition flex items-center gap-1">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                        Reset
                    </a>
                </div>
            @endif

            @foreach($categories as $category)
                @php
                    $group = $packagesByCategory->get($category->slug, collect());
                @endphp
                @if($group->isNotEmpty())
                    <div class="mb-10" id="cat-{{ $category->slug }}">
                        <div class="flex items-end justify-between gap-4 mb-3">
                            <div>
                                <p class="text-sm font-bold text-dark">{{ $category->name }}</p>
                                <p class="text-xs text-gray-400">{{ $category->description ?: 'Paket pilihan berdasarkan kategori.' }}</p>
                            </div>
                            <a href="{{ route('store.category', $category) }}" class="text-xs font-bold hover:opacity-80 transition text-dark">
                                Lihat Semua
                            </a>
                        </div>

                        <div class="relative">
                            <button type="button"
                                    data-scroll-target="store-cat-{{ $category->slug }}" data-scroll-by="-500"
                                    class="hidden lg:flex absolute left-0 top-1/2 -translate-y-1/2 -translate-x-4 w-10 h-10 bg-white rounded-full shadow-md items-center justify-center hover:shadow-lg transition z-10">
                                <svg class="w-5 h-5 text-dark" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                                </svg>
                            </button>

                            <div id="store-cat-{{ $category->slug }}" class="flex gap-3 overflow-x-auto scroll-smooth pb-2 scrollbar-hide">
                                @foreach($group->take(12) as $pkg)
                                    @php
                                        $vendor = $pkg->vendor;
                                        $price = (int) ($pkg->price ?? 0);
                                        $discount = (int) ($pkg->discount ?? 0);
                                        $final = max($price - $discount, 0);
                                        $cover = $pkg->image_url;
                                        if (!$cover && $vendor) {
                                            $cover = $vendor->cover_image_url ?: null;
                                            if (!$cover && is_array($vendor->cover_image ?? null) && count($vendor->cover_image) > 0) {
                                                $cover = $vendor->cover_image[0];
                                            }
                                        }
                                        $cover = $cover ?: ('data:image/svg+xml;utf8,' . rawurlencode('<svg xmlns="http://www.w3.org/2000/svg" width="640" height="480" viewBox="0 0 640 480"><defs><linearGradient id="g" x1="0" x2="0" y1="0" y2="1"><stop offset="0" stop-color="#f3f4f6"/><stop offset="1" stop-color="#e5e7eb"/></linearGradient></defs><rect width="640" height="480" fill="url(#g)"/><text x="50%" y="50%" dominant-baseline="middle" text-anchor="middle" fill="#9ca3af" font-family="Arial, sans-serif" font-size="20">No Image</text></svg>'));
                                    @endphp
                                    <a href="{{ route('store.package.show', $pkg) }}"
                                       class="flex-none w-64 bg-white rounded-2xl border border-gray-100 overflow-hidden hover:border-gray-200 hover:shadow-sm transition">
                                        <div class="relative ar-4x3">
                                            <img src="{{ $cover }}" alt="{{ $pkg->name }}" class="w-full h-full object-cover">
                                            <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/10 to-transparent"></div>
                                            @if($discount > 0)
                                                <span class="absolute top-3 left-3 text-[10px] font-bold px-2 py-1 rounded-full bg-white/90 border border-gray-200 text-dark">
                                                    Diskon
                                                </span>
                                            @endif
                                            <div class="absolute bottom-0 left-0 right-0 p-3">
                                                <p class="text-white text-xs font-bold leading-snug">{{ $pkg->name }}</p>
                                                <p class="text-white/80 text-[10px] mt-1">{{ $vendor->name }}</p>
                                            </div>
                                        </div>
                                        <div class="p-4">
                                            <div class="flex items-center justify-between gap-3">
                                                <div class="min-w-0">
                                                    <p class="text-[10px] text-gray-400 truncate">{{ $vendor->city }} · {{ $vendor->location }}</p>
                                                    @if($discount > 0)
                                                        <p class="text-[11px] text-gray-400 line-through mt-1">{{ number_format($price, 0, ',', '.') }}</p>
                                                        <p class="text-sm font-extrabold leading-tight text-accent">{{ number_format($final, 0, ',', '.') }}</p>
                                                    @else
                                                        <p class="text-sm font-extrabold leading-tight mt-1 text-accent">{{ number_format($price, 0, ',', '.') }}</p>
                                                    @endif
                                                    <p class="text-[10px] text-gray-400 truncate">{{ $vendor->name }}</p>
                                                </div>
                                                <span class="text-[10px] font-bold px-2 py-1 rounded-full flex-shrink-0 bg-light-sage {{ $isNavyGold ? 'text-white' : 'text-dark' }}">
                                                    {{ $vendor->city }}
                                                </span>
                                            </div>
                                        </div>
                                    </a>
                                @endforeach
                            </div>

                            <button type="button"
                                    data-scroll-target="store-cat-{{ $category->slug }}" data-scroll-by="500"
                                    class="hidden lg:flex absolute right-0 top-1/2 -translate-y-1/2 translate-x-4 w-10 h-10 bg-white rounded-full shadow-md items-center justify-center hover:shadow-lg transition z-10">
                                <svg class="w-5 h-5 text-dark" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                @endif
            @endforeach

            @if(($uncategorizedPackages ?? collect())->isNotEmpty())
                <div class="mb-10" id="cat-lainnya">
                    <div class="flex items-end justify-between gap-4 mb-3">
                        <div>
                            <p class="text-sm font-bold text-dark">Lainnya</p>
                            <p class="text-xs text-gray-400">{{ $uncategorizedPackages->count() }} paket</p>
                        </div>
                    </div>

                    <div class="relative">
                        <button type="button"
                                data-scroll-target="store-cat-lainnya" data-scroll-by="-500"
                                class="hidden lg:flex absolute left-0 top-1/2 -translate-y-1/2 -translate-x-4 w-10 h-10 bg-white rounded-full shadow-md items-center justify-center hover:shadow-lg transition z-10">
                            <svg class="w-5 h-5 text-dark" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                            </svg>
                        </button>

                        <div id="store-cat-lainnya" class="flex gap-3 overflow-x-auto scroll-smooth pb-2 scrollbar-hide">
                            @foreach($uncategorizedPackages->take(12) as $pkg)
                                @php
                                    $vendor = $pkg->vendor;
                                    if (!$vendor) {
                                        continue;
                                    }
                                    $price = (int) ($pkg->price ?? 0);
                                    $discount = (int) ($pkg->discount ?? 0);
                                    $final = max($price - $discount, 0);
                                    $cover = $pkg->image_url;
                                    if (!$cover) {
                                        $cover = $vendor->cover_image_url ?: null;
                                        if (!$cover && is_array($vendor->cover_image ?? null) && count($vendor->cover_image) > 0) {
                                            $cover = $vendor->cover_image[0];
                                        }
                                    }
                                    $cover = $cover ?: ('data:image/svg+xml;utf8,' . rawurlencode('<svg xmlns="http://www.w3.org/2000/svg" width="640" height="480" viewBox="0 0 640 480"><defs><linearGradient id="g" x1="0" x2="0" y1="0" y2="1"><stop offset="0" stop-color="#f3f4f6"/><stop offset="1" stop-color="#e5e7eb"/></linearGradient></defs><rect width="640" height="480" fill="url(#g)"/><text x="50%" y="50%" dominant-baseline="middle" text-anchor="middle" fill="#9ca3af" font-family="Arial, sans-serif" font-size="20">No Image</text></svg>'));
                                @endphp
                                <a href="{{ route('store.package.show', $pkg) }}"
                                   class="flex-none w-64 bg-white rounded-2xl border border-gray-100 overflow-hidden hover:border-gray-200 hover:shadow-sm transition">
                                    <div class="relative ar-4x3">
                                        <img src="{{ $cover }}" alt="{{ $pkg->name }}" class="w-full h-full object-cover">
                                        <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/10 to-transparent"></div>
                                        <div class="absolute bottom-0 left-0 right-0 p-3">
                                            <p class="text-white text-xs font-bold leading-snug">{{ $pkg->name }}</p>
                                            <p class="text-white/80 text-[10px] mt-1">{{ $vendor->name }}</p>
                                        </div>
                                    </div>
                                    <div class="p-4">
                                        <p class="text-[10px] text-gray-400 truncate">{{ $vendor->city }} · {{ $vendor->location }}</p>
                                        @if($discount > 0)
                                            <p class="text-[11px] text-gray-400 line-through mt-1">{{ number_format($price, 0, ',', '.') }}</p>
                                            <p class="text-sm font-extrabold leading-tight text-accent">{{ number_format($final, 0, ',', '.') }}</p>
                                        @else
                                            <p class="text-sm font-extrabold leading-tight mt-1 text-accent">{{ number_format($price, 0, ',', '.') }}</p>
                                        @endif
                                        <p class="text-[10px] text-gray-400">mulai dari</p>
                                    </div>
                                </a>
                            @endforeach
                        </div>

                        <button type="button"
                                data-scroll-target="store-cat-lainnya" data-scroll-by="500"
                                class="hidden lg:flex absolute right-0 top-1/2 -translate-y-1/2 translate-x-4 w-10 h-10 bg-white rounded-full shadow-md items-center justify-center hover:shadow-lg transition z-10">
                            <svg class="w-5 h-5 text-dark" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </button>
                    </div>
                </div>
            @endif
        </div>
    </section>

    <script>
        document.addEventListener('click', function (e) {
            var btn = e.target.closest('[data-scroll-target][data-scroll-by]');
            if (!btn) return;
            var targetId = btn.getAttribute('data-scroll-target');
            var by = parseInt(btn.getAttribute('data-scroll-by') || '0', 10);
            if (!targetId || !Number.isFinite(by) || by === 0) return;
            var el = document.getElementById(targetId);
            if (!el || typeof el.scrollBy !== 'function') return;
            el.scrollBy({ left: by, behavior: 'smooth' });
        });
    </script>

    @include('layout.footer')
@endsection
