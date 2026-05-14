@extends('layout.app')

@section('title', 'Makna Wedding - Wedding Organizer & Paket Pernikahan')
@section('meta-description', 'Temukan vendor pernikahan, wedding organizer, paket pernikahan, promo vendor, dan inspirasi real wedding di Indonesia bersama Makna Wedding.')
@section('meta-image', url(config('app.logo_url')))

@section('extra-head')
    @php
        $homeSchema = [
            '@context' => 'https://schema.org',
            '@graph' => [
                [
                    '@type' => 'Organization',
                    '@id' => url('/#organization'),
                    'name' => 'Makna Wedding',
                    'alternateName' => 'PT Makna Kreatif Indonesia',
                    'url' => url('/'),
                    'description' => 'Platform wedding organizer dan vendor pernikahan di Indonesia.',
                    'logo' => url(config('app.logo_url')),
                    'image' => url(config('app.logo_url')),
                    'email' => 'mailto:maknawedding@gmail.com',
                    'telephone' => '+62 822-9796-2600',
                    'sameAs' => ['https://www.instagram.com/makna.wedding/'],
                    'address' => [
                        '@type' => 'PostalAddress',
                        'addressRegion' => 'Sumatera Selatan',
                        'addressCountry' => 'ID',
                    ],
                ],
                [
                    '@type' => 'WebSite',
                    '@id' => url('/#website'),
                    'url' => url('/'),
                    'name' => 'Makna Wedding',
                    'alternateName' => 'PT Makna Kreatif Indonesia',
                    'description' => 'Temukan vendor, paket pernikahan, promo, dan inspirasi wedding di Indonesia.',
                    'inLanguage' => 'id-ID',
                    'publisher' => ['@id' => url('/#organization')],
                    'potentialAction' => [
                        '@type' => 'SearchAction',
                        'target' => url('/search?q={search_term_string}'),
                        'query-input' => 'required name=search_term_string',
                    ],
                ],
                [
                    '@type' => 'WebPage',
                    '@id' => url('/#webpage'),
                    'url' => url('/'),
                    'name' => 'Makna Wedding - Wedding Organizer & Paket Pernikahan',
                    'isPartOf' => ['@id' => url('/#website')],
                    'about' => ['@id' => url('/#organization')],
                    'description' => 'Temukan vendor pernikahan, wedding organizer, paket pernikahan, promo vendor, dan inspirasi real wedding di Indonesia bersama Makna Wedding.',
                    'inLanguage' => 'id-ID',
                ],
            ],
        ];
    @endphp
    <script type="application/ld+json">@json($homeSchema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)</script>
@endsection

@section('body-class', 'bg-cream text-dark')

@section('content')
        @include('layout.header')

        @include('front.sections.hero')

        <x-highlight-section
            :real-weddings="$realWeddings ?? collect()"
            :featured-blogs="$homeFeaturedBlogs ?? collect()"
            :popular-blogs="$homePopularBlogs ?? collect()"
            :home-ad="$homeAd ?? null"
        />

        <section class="pt-2 pb-3 sm:py-8 overflow-hidden">
            @php
                $logos = \App\Models\PartnerLogo::query()
                    ->where('is_active', true)
                    ->orderBy('sort_order')
                    ->orderByDesc('id')
                    ->limit(12)
                    ->get(['name', 'logo'])
                    ->map(function ($p) {
                        $src = $p->logo;
                        $src = str_starts_with($src, 'http') ? $src : \Illuminate\Support\Facades\Storage::url($src);
                        return ['src' => $src, 'alt' => $p->name ?: 'Partner'];
                    });

                if ($logos->isEmpty()) {
                    $logos = collect([
                        ['src' => asset('images/Makna Kreatif Indonesia.png'), 'alt' => 'Makna Kreatif Indonesia'],
                    ]);
                }
            @endphp
            <div class="relative flex">
                <div class="flex gap-3 sm:gap-4 animate-marquee whitespace-nowrap">
                    @foreach($logos as $l)
                        <div class="inline-flex items-center justify-center rounded-2xl px-3 py-2 sm:px-5 sm:py-3 min-w-max">
                            <img src="{{ $l['src'] }}"
                                 alt="{{ $l['alt'] }}"
                                 class="h-16 sm:h-22 w-auto object-contain">
                        </div>
                    @endforeach
                    @foreach($logos as $l)
                        <div class="inline-flex items-center justify-center rounded-2xl px-3 py-2 sm:px-5 sm:py-3 min-w-max">
                            <img src="{{ $l['src'] }}"
                                 alt="{{ $l['alt'] }}"
                                 class="h-16 sm:h-22 w-auto object-contain">
                        </div>
                    @endforeach
                </div>
            </div>
        </section>

        <!-- Package Section -->
        <section class="pt-6 pb-8 sm:py-16 bg-cream" id="packages">
            <x-ui.container>

                <div class="mb-2 flex items-end justify-between gap-3">
                    <h2 class="text-xl font-bold text-dark sm:text-2xl">Wedding Package</h2>
                </div>

                @php $catIndex = 0; @endphp
                @foreach($homeCategories as $category)
                    @php $group = $homePackagesByCategory->get($category->slug, collect()); @endphp
                    @if($group->isNotEmpty())
                        @php $catIndex++; @endphp
                        <div class="mb-[14px] sm:mb-10 pkg-category-block" @if($catIndex > 3) style="display:none" @endif>
                            <div class="flex items-center justify-between mb-3">
                                <div class="flex items-center gap-2">
                                    <p class="text-base font-bold text-dark">{{ $category->name }}</p>
                                </div>
                                <a href="{{ route('store.category', $category) }}" class="text-xs font-medium hover:underline text-accent">Lihat</a>
                            </div>

                            <div class="flex snap-x snap-mandatory gap-1.5 sm:gap-2 overflow-x-auto pb-4 scrollbar-hide">
                                @foreach($group->take(7) as $pkg)
                                    @php
                                        $vendor = $pkg->vendor;
                                        $price = (int) ($pkg->price ?? 0);
                                        $discount = (int) ($pkg->discount ?? 0);
                                        $final = max($price - $discount, 0);
                                        $discountPercent = $price > 0 && $discount > 0 ? (int) round(($discount / $price) * 100) : 0;
                                        $cover = $pkg->image_url;
                                        if (!$cover && $vendor) {
                                            $cover = $vendor->cover_image_url ?? null;
                                        }
                                        $cover = $cover ?: 'data:image/svg+xml;utf8,' . rawurlencode('<svg xmlns="http://www.w3.org/2000/svg" width="400" height="280" viewBox="0 0 400 280"><rect width="400" height="280" fill="#f3f4f6"/><text x="50%" y="50%" dominant-baseline="middle" text-anchor="middle" fill="#9ca3af" font-family="Arial" font-size="16">No Image</text></svg>');
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
                                        width-class="w-[calc((100%-0.375rem)/2)] sm:w-[44vw] lg:w-60"
                                    />
                                @endforeach
                            </div>
                        </div>
                    @endif
                @endforeach

                @if($catIndex > 3)
                    <div class="text-center mt-2" id="pkg-more-btn-wrap">
                        <button type="button" id="pkg-show-more"
                            class="inline-flex items-center gap-2 px-6 py-2.5 rounded-full border border-gray-300 text-sm font-medium text-gray-700 hover:border-accent hover:text-accent transition">
                            Lihat Kategori Lainnya
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>
                    </div>
                    <script>
                        (function () {
                            var hiddenBlocks = Array.from(document.querySelectorAll('.pkg-category-block')).filter(function (el) {
                                return el.style.display === 'none';
                            });
                            var btn = document.getElementById('pkg-show-more');
                            var wrap = document.getElementById('pkg-more-btn-wrap');
                            btn.addEventListener('click', function () {
                                var shown = 0;
                                while (hiddenBlocks.length > 0 && shown < 3) {
                                    hiddenBlocks.shift().style.display = '';
                                    shown++;
                                }
                                if (hiddenBlocks.length === 0) {
                                    wrap.style.display = 'none';
                                }
                            });
                        })();
                    </script>
                @endif

            </x-ui.container>
        </section>

        <!-- Venue Section -->
        <section class="pt-8 pb-12 sm:py-16 bg-light-sage" id="venues">
            <x-ui.container>

                <div class="mb-6 flex items-end justify-between gap-3">
                    <h2 class="text-xl font-bold text-dark sm:text-2xl">Paket Pernikahan per Kota</h2>
                    <!-- <a href="{{ route('store') }}" class="text-sm font-medium hover:underline text-accent">Lihat Semua</a> -->
                </div>

                @php $cityIndex = 0; @endphp
                @foreach($homePackagesByCity as $city => $cityPackages)
                    @php $cityIndex++; @endphp
                    <div class="mb-[14px] sm:mb-10 venue-city-block" @if($cityIndex > 3) style="display:none" @endif>
                        <div class="flex items-center justify-between mb-3">
                            <p class="text-base font-bold text-dark">{{ $city }}</p>
                            <a href="{{ route('store.city', $city) }}" class="text-xs text-accent font-medium hover:underline">Lihat</a>
                        </div>

                        <div class="flex snap-x snap-mandatory gap-2 sm:gap-2 overflow-x-auto pb-4 scrollbar-hide">
                            @foreach($cityPackages->take(7) as $pkg)
                                @php
                                    $vendor = $pkg->vendor;
                                    $price = (int) ($pkg->price ?? 0);
                                    $discount = (int) ($pkg->discount ?? 0);
                                    $final = max($price - $discount, 0);
                                    $discountPercent = $price > 0 && $discount > 0 ? (int) round(($discount / $price) * 100) : 0;
                                    $cover = $pkg->image_url;
                                    if (!$cover && $vendor) {
                                        $cover = $vendor->cover_image_url ?? null;
                                    }
                                    $cover = $cover ?: 'data:image/svg+xml;utf8,' . rawurlencode('<svg xmlns="http://www.w3.org/2000/svg" width="400" height="280" viewBox="0 0 400 280"><rect width="400" height="280" fill="#f3f4f6"/><text x="50%" y="50%" dominant-baseline="middle" text-anchor="middle" fill="#9ca3af" font-family="Arial" font-size="16">No Image</text></svg>');
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
                                    :location="$city"
                                    :rating="$vendor?->rating"
                                    :benefit-primary="$primaryBenefit"
                                    :benefit-secondary="$secondaryBenefit"
                                    width-class="w-[calc((100%-0.5rem)/2)] sm:w-[44vw] lg:w-60"
                                />
                            @endforeach
                        </div>
                    </div>
                @endforeach

                @if($cityIndex > 3)
                    <div class="text-center mt-2" id="venue-more-btn-wrap">
                        <button type="button" id="venue-show-more"
                            class="inline-flex items-center gap-2 px-6 py-2.5 rounded-full border border-gray-300 text-sm font-medium text-gray-700 hover:border-accent hover:text-accent transition">
                            Lihat Kota Lainnya
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>
                    </div>
                    <script>
                        (function () {
                            var hiddenBlocks = Array.from(document.querySelectorAll('.venue-city-block')).filter(function (el) {
                                return el.style.display === 'none';
                            });
                            var btn = document.getElementById('venue-show-more');
                            var wrap = document.getElementById('venue-more-btn-wrap');
                            btn.addEventListener('click', function () {
                                var shown = 0;
                                while (hiddenBlocks.length > 0 && shown < 3) {
                                    hiddenBlocks.shift().style.display = '';
                                    shown++;
                                }
                                if (hiddenBlocks.length === 0) {
                                    wrap.style.display = 'none';
                                }
                            });
                        })();
                    </script>
                @endif

            </x-ui.container>
        </section>

        <!-- Venue Review Video Section -->
        <section class="py-16 bg-cream" id="venue-reviews">
            <x-ui.container>

                <div class="mb-6 flex items-end justify-between gap-3">
                    <h2 class="text-xl font-bold text-dark sm:text-2xl">Review Videos</h2>
                    <a href="{{ route('review-videos') }}" class="text-sm font-medium hover:underline text-accent">Lihat</a>
                </div>

                <div class="flex snap-x snap-mandatory gap-4 overflow-x-auto pb-4 scrollbar-hide">

                    @forelse($venueReviewVideos as $video)
                        @php
                            $thumb = $video->thumbnail_url ?: 'https://picsum.photos/seed/vrvideo-' . $video->id . '/300/533';
                            $hasVideo = !empty($video->video_url);
                        @endphp
                        <div class="flex-none snap-start rounded-2xl overflow-hidden cursor-pointer relative group w-[56vw] min-w-[200px] sm:w-[calc((100%-4rem)/5)] sm:min-w-[140px] ar-9x16"
                             @if($hasVideo) data-video-popup="{{ $video->video_url }}" @endif>
                            <img src="{{ $thumb }}" alt="{{ $video->title }}" class="w-full h-full object-cover">
                            <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/20 to-transparent"></div>
                            <div class="absolute inset-0 flex items-center justify-center @if(!$hasVideo) opacity-0 @endif group-hover:opacity-100 transition-opacity duration-200">
                                <div class="w-14 h-14 bg-white/90 rounded-full flex items-center justify-center shadow-lg"><svg class="w-6 h-6 text-gray-800 ml-1" fill="currentColor" viewBox="0 0 20 20"><path d="M6.3 2.841A1.5 1.5 0 004 4.11V15.89a1.5 1.5 0 002.3 1.269l9.344-5.89a1.5 1.5 0 000-2.538L6.3 2.84z"/></svg></div>
                            </div>
                            <div class="absolute bottom-4 left-4 right-4 text-white">
                                <p class="text-[10px] uppercase tracking-widest opacity-80 mb-1">{{ $video->subtitle }}</p>
                                <p class="font-bold text-sm leading-tight">{{ $video->title }}</p>
                                @if($video->location)
                                    <p class="text-[10px] opacity-70 mt-1">{{ $video->location }}</p>
                                @endif
                            </div>
                        </div>
                    @empty
                        <p class="text-sm text-gray-400">Belum ada video venue.</p>
                    @endforelse

                </div>

                <!-- Video Popup Modal -->
                <div id="venue-video-modal" class="fixed inset-0 z-[9999] hidden items-center justify-center p-4 bg-black/80">
                    <div id="venue-video-wrapper" class="relative w-full max-w-3xl">
                        <button type="button" id="venue-video-close"
                                class="absolute -top-10 right-0 text-white hover:text-gray-300 transition flex items-center gap-1 text-sm">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                            Tutup
                        </button>
                        <div id="venue-video-container" class="relative w-full aspect-video bg-black rounded-2xl overflow-hidden">
                            <iframe id="venue-video-iframe"
                                    src=""
                                    class="w-full h-full"
                                    frameborder="0"
                                    referrerpolicy="strict-origin-when-cross-origin"
                                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                                    allowfullscreen>
                            </iframe>
                        </div>
                    </div>
                </div>

                <script>
                (function () {
                    var playerOrigin = encodeURIComponent(window.location.origin);

                    function toEmbedUrl(url) {
                        if (!url) return '';
                        function buildEmbedUrl(videoId) {
                            return 'https://www.youtube.com/embed/' + videoId + '?autoplay=1&playsinline=1&rel=0&origin=' + playerOrigin;
                        }

                        // youtube.com/shorts/ID
                        var m = url.match(/youtube\.com\/shorts\/([^?&/]+)/);
                        if (m) return buildEmbedUrl(m[1]);
                        // youtu.be/ID
                        m = url.match(/youtu\.be\/([^?&]+)/);
                        if (m) return buildEmbedUrl(m[1]);
                        // youtube.com/watch?v=ID
                        m = url.match(/[?&]v=([^&]+)/);
                        if (m) return buildEmbedUrl(m[1]);
                        // youtube.com/embed/...
                        if (url.includes('/embed/')) {
                            return url
                                + (url.includes('?') ? '&' : '?')
                                + 'autoplay=1&playsinline=1&rel=0&origin=' + playerOrigin;
                        }
                        return url;
                    }

                    var modal = document.getElementById('venue-video-modal');
                    var iframe = document.getElementById('venue-video-iframe');
                    var closeBtn = document.getElementById('venue-video-close');

                    function openModal(url) {
                        var isShorts = /youtube\.com\/shorts\//.test(url);
                        var wrapper = document.getElementById('venue-video-wrapper');
                        var container = document.getElementById('venue-video-container');
                        wrapper.style.maxWidth = isShorts ? '24rem' : '';
                        container.style.aspectRatio = isShorts ? '9 / 16' : '';
                        iframe.src = toEmbedUrl(url);
                        modal.classList.remove('hidden');
                        modal.classList.add('flex');
                        document.body.style.overflow = 'hidden';
                    }

                    function closeModal() {
                        var wrapper = document.getElementById('venue-video-wrapper');
                        var container = document.getElementById('venue-video-container');
                        wrapper.style.maxWidth = '';
                        container.style.aspectRatio = '';
                        iframe.src = '';
                        modal.classList.add('hidden');
                        modal.classList.remove('flex');
                        document.body.style.overflow = '';
                    }

                    document.querySelectorAll('[data-video-popup]').forEach(function (el) {
                        el.addEventListener('click', function () {
                            var url = el.getAttribute('data-video-popup');
                            if (/youtube\.com\/shorts\//.test(url)) {
                                window.open(url, '_blank');
                            } else {
                                openModal(url);
                            }
                        });
                    });

                    closeBtn.addEventListener('click', closeModal);
                    modal.addEventListener('click', function (e) {
                        if (e.target === modal) closeModal();
                    });
                    document.addEventListener('keydown', function (e) {
                        if (e.key === 'Escape' && !modal.classList.contains('hidden')) closeModal();
                    });
                })();
                </script>
            </x-ui.container>
        </section>

        <!-- Vendor Event & Promo Section -->
        <section class="py-16 bg-white" id="vendor-promo">
            <x-ui.container>

                <div class="mb-6 flex items-end justify-between gap-3">
                    <h2 class="text-xl font-bold text-dark sm:text-2xl">Vendor Event dan Promo</h2>
                    <a href="{{ route('store.promo') }}" class="text-sm font-medium hover:underline text-accent">Lihat</a>
                </div>

                @if($homePromoPackages->isNotEmpty())
                <div class="relative">
                    <div class="flex snap-x snap-mandatory gap-3 overflow-x-auto pb-2 scrollbar-hide" id="vendor-promo-scroll">

                        @foreach($homePromoPackages as $pkg)
                            @php
                                $vendor = $pkg->vendor;
                                $price = (int) ($pkg->price ?? 0);
                                $discount = (int) ($pkg->discount ?? 0);
                                $final = max($price - $discount, 0);
                                $discountPercent = $price > 0 && $discount > 0 ? (int) round(($discount / $price) * 100) : 0;
                                $logo = $vendor->logo_vendor
                                    ? (str_starts_with($vendor->logo_vendor, 'http') ? $vendor->logo_vendor : \Illuminate\Support\Facades\Storage::url($vendor->logo_vendor))
                                    : null;
                                $logo = $logo ?: $pkg->image_url ?: ($vendor->cover_image_url ?? null);
                                $rating = $vendor && $vendor->rating ? number_format((float) $vendor->rating, 1) : null;
                            @endphp
                            <a href="{{ route('store.package.show', $pkg) }}"
                               class="flex-none snap-start w-[46.5vw] sm:w-56 bg-white border border-gray-200 rounded-[18px] overflow-hidden cursor-pointer hover:shadow-md transition relative">
                                <div class="relative aspect-square bg-gray-50">
                                    @if($logo)
                                        <img src="{{ $logo }}" alt="{{ $vendor->name }}" class="w-full h-full object-cover">
                                    @else
                                        <div class="flex h-full w-full items-center justify-center text-gray-300">
                                            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                        </div>
                                    @endif
                                    @if($discountPercent > 0)
                                        <span class="absolute right-0 top-0 rounded-bl-2xl bg-accent px-2.5 py-1.5 text-[11px] font-extrabold leading-none text-cream">
                                            {{ $discountPercent }}%
                                        </span>
                                    @endif
                                    <div class="absolute inset-x-0 bottom-0 flex items-center gap-1.5 px-2 py-1.5 text-dark" style="background: linear-gradient(90deg, var(--soft-pink), var(--light-sage), var(--sage-green));">
                                        <span class="rounded-md bg-white/55 px-1.5 py-0.5 text-[9px] font-bold leading-none">XTRA Voucher</span>
                                        <span class="rounded-md bg-white/55 px-1.5 py-0.5 text-[9px] font-bold leading-none">Gratis Ongkir</span>
                                    </div>
                                </div>
                                <div class="space-y-1 p-3">
                                    <p class="text-[13px] font-medium leading-tight text-gray-900 sm:min-h-11">{{ $pkg->name }}</p>
                                    @php
                                        $catNames = collect($pkg->category_vendor_id ?? [])
                                            ->map(fn($cid) => $homeCategories->firstWhere('id', (int)$cid)?->name)
                                            ->filter()
                                            ->implode(', ');
                                    @endphp
                                    @if($discount > 0)
                                        <p class="text-[11px] text-gray-400 line-through">Rp{{ number_format($price, 0, ',', '.') }}</p>
                                    @endif
                                    <p class="font-extrabold leading-none text-accent"><span class="text-[11px]">Rp</span><span class="text-[15px]">{{ number_format($final, 0, ',', '.') }}</span></p>
                                    <div class="flex flex-wrap gap-1">
                                        <span class="rounded-lg border border-transparent bg-accent-pink px-1.5 py-0.5 text-[10px] font-medium text-dark">Harga Diskon</span>
                                    </div>
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

                        <!-- View All Card -->
                        <a href="{{ route('store.promo') }}" class="flex-none snap-start w-[46.5vw] sm:w-56 bg-white border border-gray-200 rounded-[18px] cursor-pointer hover:shadow-md transition flex flex-col items-center justify-center gap-3 py-10">
                            <div class="w-14 h-14 rounded-full border-2 border-gray-400 flex items-center justify-center">
                                <svg class="w-6 h-6 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                            </div>
                            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide text-center">LIHAT SEMUA PROMO</p>
                        </a>

                    </div>

                    <!-- Arrow prev -->
                    <button type="button" data-scroll-target="vendor-promo-scroll" data-scroll-by="-300"
                            class="absolute left-0 top-1/2 -translate-y-1/2 -translate-x-4 hidden h-10 w-10 items-center justify-center rounded-full bg-white shadow-md transition hover:shadow-lg z-10 md:flex">
                        <svg class="w-5 h-5 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                    </button>
                    <!-- Arrow next -->
                    <button type="button" data-scroll-target="vendor-promo-scroll" data-scroll-by="300"
                            class="absolute right-0 top-1/2 -translate-y-1/2 translate-x-4 hidden h-10 w-10 items-center justify-center rounded-full bg-white shadow-md transition hover:shadow-lg z-10 md:flex">
                        <svg class="w-5 h-5 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </button>
                </div>
                @else
                    <p class="text-sm text-gray-400">Belum ada promo saat ini.</p>
                @endif

            </x-ui.container>
        </section>

        <!-- Real Wedding Section -->
        <section class="py-16 bg-cream" id="real-wedding">
            <x-ui.container>

                <div class="mb-6 flex items-end justify-between gap-3">
                    <h2 class="text-xl font-bold text-dark sm:text-2xl">Real Wedding</h2>
                    <a href="{{ route('real-wedding.index') }}" class="text-sm font-medium hover:underline text-accent">Lihat</a>
                </div>

                <div class="relative">
                    <div class="flex snap-x snap-mandatory gap-4 overflow-x-auto pb-2 scrollbar-hide" id="real-wedding-scroll">

                        @forelse($realWeddings as $rw)
                            @php
                                $rwImage = $rw->cover_image_url ?: 'https://picsum.photos/seed/rw-' . $rw->id . '/400/533';
                            @endphp
                            <a href="{{ route('real-wedding.show', $rw->slug) }}"
                               class="flex-none snap-start rounded-2xl overflow-hidden cursor-pointer relative group w-[72vw] min-w-[220px] sm:w-[calc((100%-4rem)/5)] sm:min-w-[160px] aspect-[3/4] block">
                                <img src="{{ $rwImage }}" alt="{{ $rw->couple_names }}" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
                                <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/10 to-transparent"></div>
                                <div class="absolute bottom-4 left-4 right-4 text-white">
                                    @if($rw->badge)
                                        <span class="inline-block text-[9px] border border-white/70 rounded-full px-2 py-0.5 mb-2 bg-black/20 backdrop-blur-sm">{{ $rw->badge }}</span>
                                    @endif
                                    <p class="font-bold text-base leading-tight tracking-wide uppercase">{{ $rw->couple_names }}</p>
                                    @if($rw->venue_name)
                                        <p class="text-[11px] opacity-70 mt-1">{{ $rw->venue_name }}</p>
                                    @endif
                                </div>
                            </a>
                        @empty
                            <p class="text-sm text-gray-400">Belum ada Real Wedding.</p>
                        @endforelse

                    </div>

                    <!-- Arrow next -->
                    <button type="button" data-scroll-target="real-wedding-scroll" data-scroll-by="300"
                            class="absolute right-0 top-1/2 -translate-y-1/2 translate-x-4 hidden h-10 w-10 items-center justify-center rounded-full bg-white shadow-md transition hover:shadow-lg z-10 md:flex">
                        <svg class="w-5 h-5 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </button>
                </div>

            </x-ui.container>
        </section>

        <!-- CTA Banner -->
        <section class="relative overflow-hidden py-10 bg-accent">
            <!-- Decorative shapes -->
            <div class="absolute -top-10 -right-10 w-64 h-64 rounded-full opacity-20 bg-light-sage"></div>
            <div class="absolute -bottom-16 right-32 w-80 h-80 rounded-full opacity-15 bg-light-sage"></div>

            <x-ui.container class="relative flex flex-col items-start gap-6 sm:gap-8 lg:flex-row lg:items-center">

                <!-- Left: Title -->
                <div class="flex-shrink-0 max-w-sm lg:w-64">
                    <p class="font-bold text-lg leading-snug text-cream sm:text-xl">Persiapkan Pernikahan dengan Beragam Kemudahan &amp; Penawaran Ekslusif</p>
                </div>

                <!-- Middle: Features -->
                <div class="flex flex-col gap-4 self-stretch sm:flex-row sm:flex-wrap sm:gap-6 lg:flex-nowrap flex-1 lg:justify-center">
                    <div class="flex items-center gap-2 text-cream sm:max-w-[15rem]">
                        <div class="w-8 h-8 rounded-full flex items-center justify-center flex-shrink-0 bg-cream-25">
                            <svg class="w-4 h-4 text-cream" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                        </div>
                        <p class="text-xs font-medium leading-snug">Vendor &amp; Produk<br>Pernikahan Terlengkap</p>
                    </div>
                    <div class="flex items-center gap-2 text-cream sm:max-w-[15rem]">
                        <div class="w-8 h-8 rounded-full flex items-center justify-center flex-shrink-0 bg-cream-25">
                            <svg class="w-4 h-4 text-cream" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd"/></svg>
                        </div>
                        <p class="text-xs font-medium leading-snug">Sesuaikan Pesanan<br>dengan Impian Anda</p>
                    </div>
                    <div class="flex items-center gap-2 text-cream sm:max-w-[15rem]">
                        <div class="w-8 h-8 rounded-full flex items-center justify-center flex-shrink-0 bg-cream-25">
                            <svg class="w-4 h-4 text-cream" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5 2a2 2 0 00-2 2v14l3.5-2 3.5 2 3.5-2 3.5 2V4a2 2 0 00-2-2H5zm2.5 3a1.5 1.5 0 100 3 1.5 1.5 0 000-3zm6.207.293a1 1 0 00-1.414 0l-6 6a1 1 0 101.414 1.414l6-6a1 1 0 000-1.414zM12.5 10a1.5 1.5 0 100 3 1.5 1.5 0 000-3z" clip-rule="evenodd"/></svg>
                        </div>
                        <p class="text-xs font-medium leading-snug">Promo Eksklusif &amp;<br>Hadiah Menarik</p>
                    </div>
                    <div class="flex items-center gap-2 text-cream sm:max-w-[15rem]">
                        <div class="w-8 h-8 rounded-full flex items-center justify-center flex-shrink-0 bg-cream-25">
                            <svg class="w-4 h-4 text-cream" fill="currentColor" viewBox="0 0 20 20"><path d="M8.433 7.418c.155-.103.346-.196.567-.267v1.698a2.305 2.305 0 01-.567-.267C8.07 8.34 8 8.114 8 8c0-.114.07-.34.433-.582zM11 12.849v-1.698c.22.071.412.164.567.267.364.243.433.468.433.582 0 .114-.07.34-.433.582a2.305 2.305 0 01-.567.267z"/><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-13a1 1 0 10-2 0v.092a4.535 4.535 0 00-1.676.662C6.602 6.234 6 7.009 6 8c0 .99.602 1.765 1.324 2.246.48.32 1.054.545 1.676.662v1.941c-.391-.127-.68-.317-.843-.504a1 1 0 10-1.51 1.31c.562.649 1.413 1.077 2.353 1.253V15a1 1 0 102 0v-.092a4.535 4.535 0 001.676-.662C13.398 13.766 14 12.991 14 12c0-.99-.602-1.765-1.324-2.246A4.535 4.535 0 0011 9.092V7.151c.391.127.68.317.843.504a1 1 0 101.511-1.31c-.563-.649-1.413-1.077-2.354-1.253V5z" clip-rule="evenodd"/></svg>
                        </div>
                        <p class="text-xs font-medium leading-snug">Cicilan 0% hingga 24<br>bulan</p>
                    </div>
                </div>

                <!-- Right: CTA Button -->
                <div class="flex w-full flex-shrink-0 sm:w-auto">
                    <button type="button" onclick="document.getElementById('cta-coming-soon-modal').classList.remove('hidden');document.getElementById('cta-coming-soon-modal').classList.add('flex');document.body.style.overflow='hidden';"
                            class="inline-block w-full px-6 py-3 rounded-xl font-semibold text-sm transition hover:opacity-90 bg-cream text-dark cursor-pointer sm:w-auto">
                        Daftar Sekarang
                    </button>
                </div>

            </x-ui.container>
        </section>

        <!-- Coming Soon Modal: Daftar Sekarang -->
        <div id="cta-coming-soon-modal"
             class="fixed inset-0 z-[9999] hidden items-center justify-center p-4 bg-black/60"
             onclick="if(event.target===this){this.classList.add('hidden');this.classList.remove('flex');document.body.style.overflow='';}">
            <div class="bg-white rounded-3xl shadow-2xl max-w-sm w-full overflow-hidden">

                <!-- Top decorative bar -->
                <div class="h-2 bg-gradient-to-r from-accent via-light-sage to-accent"></div>

                <div class="px-8 py-8 text-center">

                    <!-- Icon -->
                    <div class="w-16 h-16 rounded-2xl bg-accent/10 flex items-center justify-center mx-auto mb-5">
                        <svg class="w-8 h-8 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>

                    <!-- Title -->
                    <p class="text-xs font-bold uppercase tracking-widest text-accent mb-2">Segera Hadir</p>
                    <h2 class="text-xl font-extrabold text-dark mb-3">Coming Soon</h2>

                    <!-- Description -->
                    <p class="text-sm text-gray-500 leading-relaxed mb-6">
                        Fitur pendaftaran member sedang dalam pengembangan. Segera hadir dengan berbagai keuntungan eksklusif untuk merencanakan pernikahan impianmu.
                    </p>

                    <!-- Benefits list -->
                    <div class="text-left space-y-2.5 mb-7">
                        <div class="flex items-center gap-2.5">
                            <div class="w-5 h-5 rounded-full bg-accent/10 flex items-center justify-center flex-shrink-0">
                                <svg class="w-3 h-3 text-accent" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                            </div>
                            <p class="text-xs text-gray-700">Akses promo &amp; diskon eksklusif member</p>
                        </div>
                        <div class="flex items-center gap-2.5">
                            <div class="w-5 h-5 rounded-full bg-accent/10 flex items-center justify-center flex-shrink-0">
                                <svg class="w-3 h-3 text-accent" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                            </div>
                            <p class="text-xs text-gray-700">Simpan vendor favorit &amp; wishlist paket</p>
                        </div>
                        <div class="flex items-center gap-2.5">
                            <div class="w-5 h-5 rounded-full bg-accent/10 flex items-center justify-center flex-shrink-0">
                                <svg class="w-3 h-3 text-accent" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                            </div>
                            <p class="text-xs text-gray-700">Kelola booking &amp; pembayaran dalam satu dashboard</p>
                        </div>
                        <div class="flex items-center gap-2.5">
                            <div class="w-5 h-5 rounded-full bg-accent/10 flex items-center justify-center flex-shrink-0">
                                <svg class="w-3 h-3 text-accent" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                            </div>
                            <p class="text-xs text-gray-700">Cicilan 0% hingga 24 bulan tanpa biaya tambahan</p>
                        </div>
                    </div>

                    <!-- Close button -->
                    <button type="button"
                            onclick="document.getElementById('cta-coming-soon-modal').classList.add('hidden');document.getElementById('cta-coming-soon-modal').classList.remove('flex');document.body.style.overflow='';"
                            class="w-full py-3 rounded-xl bg-accent text-cream font-semibold text-sm hover:opacity-90 transition">
                        Oke, Ditunggu!
                    </button>

                </div>
            </div>
        </div>

        <!-- Blog Section -->
        @if($homeFeaturedBlogs->isNotEmpty() || $homePopularBlogs->isNotEmpty())
        <section class="py-16 bg-light-sage" id="blog">
            <x-ui.container>

                <div class="mb-8 flex items-end justify-between gap-3">
                    <h2 class="text-xl font-bold text-dark sm:text-2xl">Jangan Lewatkan Blog Post Ini</h2>
                    <a href="{{ route('blog.index') }}" class="text-sm font-medium hover:underline text-accent">Lihat</a>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                    <!-- Featured Posts (2 cards side by side) -->
                    <div class="lg:col-span-2 grid grid-cols-1 sm:grid-cols-2 gap-6">
                        @forelse($homeFeaturedBlogs as $blog)
                            @php
                                $blogImage = $blog->cover_image_url ?: 'https://picsum.photos/seed/blog-' . $blog->id . '/600/400';
                            @endphp
                            <a href="{{ route('blog.show', $blog->slug) }}" class="bg-white rounded-2xl overflow-hidden hover:shadow-md transition group block">
                                <div class="overflow-hidden">
                                    <img src="{{ $blogImage }}" alt="{{ $blog->title }}" class="w-full h-48 object-cover transition-transform duration-500 group-hover:scale-105">
                                </div>
                                <div class="p-4">
                                    <div class="flex items-center gap-2 mb-2">
                                        @if($blog->category)
                                            <span class="text-xs font-semibold text-accent">{{ $blog->category }}</span>
                                            <span class="text-xs text-gray-400">·</span>
                                        @endif
                                        <span class="text-xs text-gray-400">{{ $blog->published_at?->format('M d, Y') }} | {{ number_format($blog->views_count) }} views</span>
                                    </div>
                                    <p class="font-bold text-gray-900 text-sm leading-snug group-hover:underline">{{ $blog->title }}</p>
                                </div>
                            </a>
                        @empty
                            <p class="text-sm text-gray-400 col-span-2">Belum ada blog post.</p>
                        @endforelse
                    </div>

                    <!-- Artikel Terpopuler Sidebar -->
                    @if($homePopularBlogs->isNotEmpty())
                    <div class="lg:col-span-1">
                        <h3 class="text-base font-bold mb-4 text-dark">Artikel Terpopuler</h3>
                        <div class="flex flex-col gap-4">
                            @foreach($homePopularBlogs as $loop_index => $popular)
                                @php
                                    $popularImage = $popular->cover_image_url ?: 'https://picsum.photos/seed/popular-' . $popular->id . '/160/120';
                                @endphp
                                @if($loop_index > 0)
                                    <div class="border-t border-gray-200"></div>
                                @endif
                                <a href="{{ route('blog.show', $popular->slug) }}" class="flex gap-3 group cursor-pointer">
                                    <img src="{{ $popularImage }}" alt="{{ $popular->title }}" class="w-16 h-14 rounded-xl object-cover flex-shrink-0">
                                    <div>
                                        <p class="text-[11px] font-semibold mb-0.5 text-accent">
                                            {{ $popular->category ?: '—' }}
                                            <span class="text-gray-400 font-normal">· {{ number_format($popular->views_count) }} views</span>
                                        </p>
                                        <p class="text-xs font-semibold text-gray-800 leading-snug group-hover:underline">{{ $popular->title }}</p>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    </div>
                    @endif

                </div>
            </x-ui.container>
        </section>
        @endif

        @if($homeAd && $homeAd->image_url)
        @php
            $adDelay = (int) ($homeAd->delay_seconds ?? 5) * 1000;
            $adKey   = 'home_ad_dismissed_v' . $homeAd->id;
        @endphp
        <div id="home-ad-modal"
             data-ad-key="{{ $adKey ?? 'home_ad_dismissed_v1' }}"
             data-ad-delay="{{ (int) ($adDelay ?? 5000) }}"
             class="fixed inset-0 z-[9998] hidden items-center justify-center p-4"
             style="background:rgba(35, 34, 34, 0.92)">
            <div class="bg-white rounded-2xl shadow-2xl overflow-hidden" style="max-width:min(84vw,340px);max-height:84vh;">
                <div class="relative">
                    @if($homeAd->link_url)
                        <a href="{{ $homeAd->link_url }}" class="block">
                    @endif
                    <img src="{{ $homeAd->image_url }}" alt="{{ $homeAd->title ?: 'Iklan' }}" class="block w-auto h-auto" style="max-width:min(84vw,340px);max-height:78vh;display:block;">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/20 to-transparent pointer-events-none"></div>
                    @if($homeAd->link_url)
                        </a>
                    @endif

                    <button type="button" data-home-ad-close class="absolute top-3 right-3 w-9 h-9 rounded-xl bg-white/90 border border-gray-200 flex items-center justify-center hover:bg-white transition" aria-label="Tutup">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>

                    @if($homeAd->caption || $homeAd->link_url)
                        <div class="absolute bottom-0 left-0 right-0 p-4 pointer-events-none">
                            @if($homeAd->caption)
                                <p class="text-white text-sm font-semibold leading-snug">{{ $homeAd->caption }}</p>
                            @endif
                            @if($homeAd->link_url && $homeAd->link_label)
                                <a href="{{ $homeAd->link_url }}"
                                   class="pointer-events-auto mt-2 inline-block px-4 py-1.5 rounded-xl bg-white text-dark text-xs font-bold hover:opacity-90 transition">
                                    {{ $homeAd->link_label }}
                                </a>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </div>
        @endif

        <script>
            (function () {
                var modal = document.getElementById('home-ad-modal');
                var key = (modal && modal.dataset && modal.dataset.adKey) ? modal.dataset.adKey : 'home_ad_dismissed_v1';
                var delay = (modal && modal.dataset && modal.dataset.adDelay) ? parseInt(modal.dataset.adDelay || '5000', 10) : 5000;

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

                if (!modal) return;

                function openModal() {
                    try {
                        if (localStorage.getItem(key) === '1') return;
                    } catch (e) {}
                    modal.classList.remove('hidden');
                    modal.classList.add('flex');
                    document.body.style.overflow = 'hidden';
                }

                window.closeHomeAdModal = function (persist) {
                    modal.classList.add('hidden');
                    modal.classList.remove('flex');
                    document.body.style.overflow = '';
                    if (persist) {
                        try {
                            localStorage.setItem(key, '1');
                        } catch (e) {}
                    }
                };

                modal.addEventListener('click', function (e) {
                    if (e.target === modal) window.closeHomeAdModal();
                });
                var closeBtn = modal.querySelector('[data-home-ad-close]');
                if (closeBtn) {
                    closeBtn.addEventListener('click', function () {
                        window.closeHomeAdModal();
                    });
                }

                window.setTimeout(openModal, delay);

                document.addEventListener('keydown', function (e) {
                    if (e.key === 'Escape' && !modal.classList.contains('hidden')) {
                        window.closeHomeAdModal();
                    }
                });
            })();
        </script>

        @include('layout.footer')
@endsection
