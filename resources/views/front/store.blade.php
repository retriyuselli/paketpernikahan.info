@extends('layout.app')

@section('title', 'Store - Makna Wedding')

@section('body-class', 'bg-cream text-dark')

@section('content')
    @php $isNavyGold = \App\Http\Controllers\ThemeController::active()['name'] === 'navy-gold'; @endphp
    @include('layout.header')

    <x-highlight-section
        :real-weddings="$realWeddings ?? collect()"
        :featured-blogs="$homeFeaturedBlogs ?? collect()"
        :popular-blogs="$homePopularBlogs ?? collect()"
        :home-ad="$homeAd ?? null"
    />

    <div class="px-4 sm:px-6 lg:px-8">
        <x-banner-ad />
    </div>

    {{-- ── Search Bar ───────────────────────────────────── --}}
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-8 pb-2">
        <form method="GET" action="{{ route('store') }}" class="mb-6 flex flex-wrap items-center gap-2">
                <div class="relative flex-1 min-w-55">
                    <span class="absolute inset-y-0 left-4 flex items-center pointer-events-none">
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </span>
                    <input type="text"
                            name="q"
                            value="{{ $search }}"
                            placeholder="Cari paket, vendor, atau kota…"
                            class="w-full h-11 pl-10 pr-4 rounded-xl border border-gray-200 bg-white text-sm focus:outline-none focus:border-accent transition text-dark placeholder-gray-400">
                </div>
                @if($categories->isNotEmpty())
                    <select name="kategori"
                            class="h-11 flex-1 min-w-40 px-4 rounded-xl border border-gray-200 bg-white text-sm focus:outline-none focus:border-accent transition text-dark">
                        <option value="">Semua Kategori</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->slug }}" @selected($kategori === $cat->slug)>{{ $cat->name }}</option>
                        @endforeach
                    </select>
                @endif
                <button type="submit"
                        class="h-11 px-5 rounded-xl text-sm font-bold transition hover:opacity-90 bg-accent text-white shrink-0">
                    Cari
                </button>
        </form>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        <div id="store-sections"></div>

        @if(!empty($search) || !empty($kategori))
            <div class="flex items-center justify-between gap-4 mb-6 p-4 bg-white rounded-2xl border border-gray-100">
                <div class="flex items-center gap-3">
                        <svg class="w-4 h-4 text-accent shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    <p class="text-sm text-gray-700">
                        Hasil pencarian untuk:
                        @if(!empty($search))
                            <strong>"{{ $search }}"</strong>
                        @endif
                        @if(!empty($kategori))
                            <span class="ml-1 inline-block bg-accent/10 text-accent text-xs font-semibold px-2 py-0.5 rounded-full">{{ $kategoriCat?->name ?? ucfirst($kategori) }}</span>
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
                            {{-- <p class="text-xs text-gray-400">{{ $category->description ?: 'Paket pilihan berdasarkan kategori.' }}</p> --}}
                        </div>
                        <a href="{{ route('store.category', $category) }}" class="text-xs font-bold hover:opacity-80 transition text-dark">
                            Lihat
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
                                    $discountPercent = $price > 0 && $discount > 0 ? (int) round(($discount / $price) * 100) : 0;
                                    $cover = $pkg->image_url;
                                    if (!$cover && $vendor) {
                                        $cover = $vendor->cover_image_url ?: null;
                                        if (!$cover && is_array($vendor->cover_image ?? null) && count($vendor->cover_image) > 0) {
                                            $cover = $vendor->cover_image[0];
                                        }
                                    }
                                    $cover = $cover ?: ('data:image/svg+xml;utf8,' . rawurlencode('<svg xmlns="http://www.w3.org/2000/svg" width="640" height="480" viewBox="0 0 640 480"><defs><linearGradient id="g" x1="0" x2="0" y1="0" y2="1"><stop offset="0" stop-color="#f3f4f6"/><stop offset="1" stop-color="#e5e7eb"/></linearGradient></defs><rect width="640" height="480" fill="url(#g)"/><text x="50%" y="50%" dominant-baseline="middle" text-anchor="middle" fill="#9ca3af" font-family="Arial, sans-serif" font-size="20">No Image</text></svg>'));
                                    $items = $pkg->items;
                                    $primaryBenefit = $discount > 0 ? 'Harga Diskon' : 'Paket Pilihan';
                                    $secondaryBenefit = !empty($items[0]) ? \Illuminate\Support\Str::limit($items[0], 16) : 'Gratis Konsultasi';
                                    $rating = $vendor && $vendor->rating ? number_format((float) $vendor->rating, 1) : null;
                                @endphp
                                <a href="{{ route('store.package.show', $pkg) }}"
                                    class="flex-none snap-start w-[46.5vw] sm:w-[44vw] lg:w-52 bg-white rounded-[18px] border border-gray-200 overflow-hidden shadow-[0_2px_10px_rgba(15,23,42,0.08)] hover:border-gray-200 hover:shadow-sm transition">
                                    <div class="relative aspect-square">
                                        <img src="{{ $cover }}" alt="{{ $pkg->name }}" class="w-full h-full object-cover">
                                        @if($discountPercent > 0)
                                            <span class="absolute right-0 top-0 rounded-bl-2xl bg-accent px-2.5 py-1.5 text-[11px] font-extrabold leading-none text-cream">
                                                {{ $discountPercent }}%
                                            </span>
                                        @endif
                                        <div class="absolute inset-x-0 bottom-0 flex items-center gap-1.5 px-2 py-1.5 text-dark" style="background: linear-gradient(90deg, var(--soft-pink), var(--light-sage), var(--sage-green));">
                                            <span class="rounded-md bg-white/55 px-1.5 py-0.5 text-[9px] font-bold leading-none">{{ $primaryBenefit }}</span>
                                            <span class="rounded-md bg-white/55 px-1.5 py-0.5 text-[9px] font-bold leading-none">{{ $secondaryBenefit }}</span>
                                        </div>
                                    </div>
                                    <div class="space-y-1 p-3">
                                        <p class="text-[13px] font-medium leading-tight text-gray-900 sm:min-h-11">{{ $pkg->name }}</p>
                                        @if($discount > 0)
                                            <p class="text-[11px] text-gray-400 line-through">Rp{{ number_format($price, 0, ',', '.') }}</p>
                                            <p class="font-extrabold leading-none text-accent"><span class="text-[11px]">Rp</span><span class="text-[15px]">{{ number_format($final, 0, ',', '.') }}</span></p>
                                        @else
                                            <p class="font-extrabold leading-none text-accent"><span class="text-[11px]">Rp</span><span class="text-[15px]">{{ number_format($price, 0, ',', '.') }}</span></p>
                                        @endif
                                        @if($discount > 0)
                                            <div class="flex flex-wrap gap-1">
                                                <span class="rounded-lg border border-transparent bg-accent-pink px-1.5 py-0.5 text-[10px] font-medium text-dark">Harga Diskon</span>
                                            </div>
                                        @endif
                                        <div class="flex items-center gap-1.5 text-[11px] text-gray-500">
                                            @if($rating)
                                                <span class="flex items-center gap-1 text-accent">
                                                    <svg class="h-3.5 w-3.5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                                    <span>{{ $rating }}</span>
                                                </span>
                                                <span class="text-gray-300">|</span>
                                            @endif
                                            <span>{{ $vendor->city ?? 'Indonesia' }}</span>
                                        </div>
                                        <p class="truncate text-[11px] text-gray-500">{{ $vendor->name }}</p>
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
                                $discountPercent = $price > 0 && $discount > 0 ? (int) round(($discount / $price) * 100) : 0;
                                $cover = $pkg->image_url;
                                if (!$cover) {
                                    $cover = $vendor->cover_image_url ?: null;
                                    if (!$cover && is_array($vendor->cover_image ?? null) && count($vendor->cover_image) > 0) {
                                        $cover = $vendor->cover_image[0];
                                    }
                                }
                                $cover = $cover ?: ('data:image/svg+xml;utf8,' . rawurlencode('<svg xmlns="http://www.w3.org/2000/svg" width="640" height="480" viewBox="0 0 640 480"><defs><linearGradient id="g" x1="0" x2="0" y1="0" y2="1"><stop offset="0" stop-color="#f3f4f6"/><stop offset="1" stop-color="#e5e7eb"/></linearGradient></defs><rect width="640" height="480" fill="url(#g)"/><text x="50%" y="50%" dominant-baseline="middle" text-anchor="middle" fill="#9ca3af" font-family="Arial, sans-serif" font-size="20">No Image</text></svg>'));
                                $items = $pkg->items;
                                $primaryBenefit = $discount > 0 ? 'Harga Diskon' : 'Paket Pilihan';
                                $secondaryBenefit = !empty($items[0]) ? \Illuminate\Support\Str::limit($items[0], 16) : 'Gratis Konsultasi';
                                $rating = $vendor->rating ? number_format((float) $vendor->rating, 1) : null;
                            @endphp
                            <a href="{{ route('store.package.show', $pkg) }}"
                                class="flex-none snap-start w-[46.5vw] sm:w-[44vw] lg:w-60 bg-white rounded-[18px] border border-gray-200 overflow-hidden shadow-[0_2px_10px_rgba(15,23,42,0.08)] hover:border-gray-200 hover:shadow-sm transition">
                                <div class="relative aspect-square">
                                    <img src="{{ $cover }}" alt="{{ $pkg->name }}" class="w-full h-full object-cover">
                                    @if($discountPercent > 0)
                                        <span class="absolute right-0 top-0 rounded-bl-2xl bg-accent px-2.5 py-1.5 text-[11px] font-extrabold leading-none text-cream">
                                            {{ $discountPercent }}%
                                        </span>
                                    @endif
                                    <div class="absolute inset-x-0 bottom-0 flex items-center gap-1.5 px-2 py-1.5 text-dark" style="background: linear-gradient(90deg, var(--soft-pink), var(--light-sage), var(--sage-green));">
                                        <span class="rounded-md bg-white/55 px-1.5 py-0.5 text-[9px] font-bold leading-none">{{ $primaryBenefit }}</span>
                                        <span class="rounded-md bg-white/55 px-1.5 py-0.5 text-[9px] font-bold leading-none">{{ $secondaryBenefit }}</span>
                                    </div>
                                </div>
                                <div class="space-y-1 p-3">
                                    <p class="text-[13px] font-medium leading-tight text-gray-900 sm:min-h-11">{{ $pkg->name }}</p>
                                    @if($discount > 0)
                                        <p class="text-[11px] text-gray-400 line-through">Rp{{ number_format($price, 0, ',', '.') }}</p>
                                        <p class="font-extrabold leading-none text-accent"><span class="text-[11px]">Rp</span><span class="text-[15px]">{{ number_format($final, 0, ',', '.') }}</span></p>
                                    @else
                                        <p class="font-extrabold leading-none text-accent"><span class="text-[11px]">Rp</span><span class="text-[15px]">{{ number_format($price, 0, ',', '.') }}</span></p>
                                    @endif
                                    @if($discount > 0)
                                        <div class="flex flex-wrap gap-1">
                                            <span class="rounded-lg border border-transparent bg-accent-pink px-1.5 py-0.5 text-[10px] font-medium text-dark">Harga Diskon</span>
                                        </div>
                                    @endif
                                    <div class="flex items-center gap-1.5 text-[11px] text-gray-500">
                                        @if($rating)
                                            <span class="flex items-center gap-1 text-accent">
                                                <svg class="h-3.5 w-3.5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                                <span>{{ $rating }}</span>
                                            </span>
                                            <span class="text-gray-300">|</span>
                                        @endif
                                        <span>{{ $vendor->city ?? 'Indonesia' }}</span>
                                    </div>
                                    <p class="truncate text-[11px] text-gray-500">{{ $vendor->name }}</p>
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
