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

    <div class="max-w-7xl mx-auto px-4 pt-4 pb-10 sm:px-6 lg:px-8 lg:py-10">
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
                            <span class="ml-1 inline-block bg-accent/10 text-accent text-sm font-bold px-2 py-0.5 rounded-full">{{ $kategoriCat?->name ?? ucfirst($kategori) }}</span>
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
                <div class="mb-[10px] sm:mb-10" id="cat-{{ $category->slug }}">
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

                        <div id="store-cat-{{ $category->slug }}" class="flex gap-1.5 sm:gap-2 overflow-x-auto scroll-smooth pb-2 scrollbar-hide">
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
                                <x-package-card
                                    :href="route('store.package.show', $pkg)"
                                    :name="$pkg->name"
                                    :image="$cover"
                                    :price="$price"
                                    :discount="$discount"
                                    :vendor-name="$vendor->name"
                                    :location="$vendor->city ?? 'Indonesia'"
                                    :rating="$vendor?->rating"
                                    :benefit-primary="$primaryBenefit"
                                    :benefit-secondary="$secondaryBenefit"
                                    width-class="w-[46.5vw] sm:w-[44vw] lg:w-52"
                                />
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
            <div class="mb-[10px] sm:mb-10" id="cat-lainnya">
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

                    <div id="store-cat-lainnya" class="flex gap-1.5 sm:gap-2 overflow-x-auto scroll-smooth pb-2 scrollbar-hide">
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
                            <x-package-card
                                :href="route('store.package.show', $pkg)"
                                :name="$pkg->name"
                                :image="$cover"
                                :price="$price"
                                :discount="$discount"
                                :vendor-name="$vendor->name"
                                :location="$vendor->city ?? 'Indonesia'"
                                :rating="$vendor?->rating"
                                :benefit-primary="$primaryBenefit"
                                :benefit-secondary="$secondaryBenefit"
                                width-class="w-[calc((100%-0.375rem)/2)] sm:w-[44vw] lg:w-60"
                            />
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
