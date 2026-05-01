@extends('layout.app')

@section('title', 'PT. Makna kreatif Indonesia - Wedding Organizer & Paket Pernikahan')

@section('body-class', 'bg-cream text-dark')

@section('content')
        @include('layout.header')

        @include('front.sections.hero')

        <!-- Highlight -->
        <section class="py-10 bg-cream">
            <x-ui.container>
                <h2 class="text-xl font-bold mb-5 text-dark">Highlights</h2>
            </x-ui.container>

                <!-- Slider Container -->
                <div class="relative">
                    <!-- Left Arrow -->
                    <button type="button" data-scroll-target="highlights-scroll" data-scroll-by="-400"
                            class="absolute left-4 top-1/2 -translate-y-1/2 w-10 h-10 bg-white rounded-full shadow-md flex items-center justify-center hover:shadow-lg transition z-10">
                        <svg class="w-5 h-5 text-dark" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                    </button>

                    <!-- Scrollable Track -->
                    <div id="highlights-scroll" class="flex gap-4 overflow-x-auto scroll-smooth pb-2 scrollbar-hide px-4 sm:px-6 lg:px-8">

                        @php
                            $hlRealWeddings = $realWeddings ?? collect();
                            $hlBlogs = (($homeFeaturedBlogs ?? collect())->merge($homePopularBlogs ?? collect()))->unique('id');
                            $adInserted = false;
                        @endphp

                        @foreach($hlRealWeddings->take(3) as $hlIdx => $rw)
                            @php $rwCover = $rw->cover_image_url ?: 'https://picsum.photos/seed/rw-' . $rw->id . '/640/480'; @endphp
                            {{-- Wedding Story Card --}}
                            <a href="{{ route('real-wedding.show', $rw) }}"
                               class="flex-none w-72 rounded-2xl overflow-hidden relative group cursor-pointer shadow-sm hover:shadow-md transition ar-4x3">
                                <img src="{{ $rwCover }}" alt="{{ $rw->couple_names }}" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
                                <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/10 to-transparent"></div>
                                <div class="absolute bottom-0 left-0 right-0 p-4">
                                    <p class="text-white text-xs mb-2 opacity-80">Wedding Story of <span class="font-bold">{{ $rw->couple_names }}</span></p>
                                    <span class="inline-block text-xs font-bold uppercase tracking-widest px-4 py-1.5 rounded-full bg-accent text-cream">More Info</span>
                                </div>
                            </a>

                            @if($hlIdx === 0 && $homeAd && !$adInserted)
                                @php $adInserted = true; @endphp
                                {{-- HomeAd Card --}}
                                <a href="{{ $homeAd->link_url ?: '#' }}" class="flex-none w-52 rounded-2xl overflow-hidden relative group cursor-pointer shadow-sm hover:shadow-md transition bg-accent-pink ar-4x3">
                                    @if($homeAd->image_url)
                                        <img src="{{ $homeAd->image_url }}" alt="{{ $homeAd->title }}" class="w-full h-full object-cover opacity-30 transition-transform duration-500 group-hover:scale-105">
                                    @endif
                                    <div class="absolute inset-0 flex flex-col items-center justify-center text-center px-6 gap-3">
                                        <p class="text-2xl font-bold leading-tight text-dark">{{ $homeAd->title }}</p>
                                        @if($homeAd->caption)
                                            <p class="text-xs text-dark">{{ $homeAd->caption }}</p>
                                        @endif
                                        <span class="inline-block text-[10px] font-bold uppercase tracking-wider px-3 py-1.5 rounded-full bg-dark text-cream mt-2">
                                            {{ $homeAd->link_label ?: 'More Info' }}
                                        </span>
                                    </div>
                                </a>
                            @endif
                        @endforeach

                        @if(!$adInserted && $homeAd)
                            <a href="{{ $homeAd->link_url ?: '#' }}" class="flex-none w-52 rounded-2xl overflow-hidden relative group cursor-pointer shadow-sm hover:shadow-md transition bg-accent-pink ar-4x3">
                                @if($homeAd->image_url)
                                    <img src="{{ $homeAd->image_url }}" alt="{{ $homeAd->title }}" class="w-full h-full object-cover opacity-30 transition-transform duration-500 group-hover:scale-105">
                                @endif
                                <div class="absolute inset-0 flex flex-col items-center justify-center text-center px-6 gap-3">
                                    <p class="text-2xl font-bold leading-tight text-dark">{{ $homeAd->title }}</p>
                                    @if($homeAd->caption)
                                        <p class="text-xs text-dark">{{ $homeAd->caption }}</p>
                                    @endif
                                    <span class="inline-block text-[10px] font-bold uppercase tracking-wider px-3 py-1.5 rounded-full bg-dark text-cream mt-2">
                                        {{ $homeAd->link_label ?: 'More Info' }}
                                    </span>
                                </div>
                            </a>
                        @endif

                        @foreach($hlBlogs->take(4) as $blog)
                            @php $blogCover = $blog->cover_image_url ?: 'https://picsum.photos/seed/blog-' . $blog->id . '/640/480'; @endphp
                            {{-- Blog Article Card --}}
                            <a href="{{ route('blog.show', $blog) }}"
                               class="flex-none w-72 rounded-2xl overflow-hidden relative group cursor-pointer shadow-sm hover:shadow-md transition ar-4x3">
                                <img src="{{ $blogCover }}" alt="{{ $blog->title }}" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
                                <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/20 to-transparent"></div>
                                <div class="absolute bottom-0 left-0 right-0 p-4">
                                    @if($blog->category)
                                        <p class="text-[10px] font-bold uppercase tracking-widest mb-1 text-accent-pink">{{ $blog->category }}</p>
                                    @endif
                                    <p class="text-white text-sm font-bold leading-snug line-clamp-3">{{ $blog->title }}</p>
                                </div>
                            </a>
                        @endforeach

                        @if($hlRealWeddings->isEmpty() && !$homeAd && $hlBlogs->isEmpty())
                            <p class="text-sm text-gray-400 py-10">Belum ada konten highlights.</p>
                        @endif

                    </div>

                    <!-- Right Arrow -->
                    <button type="button" data-scroll-target="highlights-scroll" data-scroll-by="400"
                            class="absolute right-4 top-1/2 -translate-y-1/2 w-10 h-10 bg-white rounded-full shadow-md flex items-center justify-center hover:shadow-lg transition z-10">
                        <svg class="w-5 h-5 text-dark" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </button>
                </div>

        </section>


        <section class="py-5 sm:py-8 overflow-hidden">
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
                                 class="h-10 sm:h-22 w-auto object-contain">
                        </div>
                    @endforeach
                    @foreach($logos as $l)
                        <div class="inline-flex items-center justify-center rounded-2xl px-3 py-2 sm:px-5 sm:py-3 min-w-max">
                            <img src="{{ $l['src'] }}"
                                 alt="{{ $l['alt'] }}"
                                 class="h-10 sm:h-22 w-auto object-contain">
                        </div>
                    @endforeach
                </div>
            </div>
        </section>

        <!-- Package Section -->
        <section class="py-16 bg-cream" id="packages">
            <x-ui.container>

                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-2xl font-bold text-dark">Wedding Package</h2>
                    <!-- <a href="{{ route('store') }}" class="text-sm font-medium hover:underline text-accent">Lihat Semua</a> -->
                </div>

                @php $catIndex = 0; @endphp
                @foreach($homeCategories as $category)
                    @php $group = $homePackagesByCategory->get($category->slug, collect()); @endphp
                    @if($group->isNotEmpty())
                        @php $catIndex++; @endphp
                        <div class="mb-10 pkg-category-block" @if($catIndex > 3) style="display:none" @endif>
                            <div class="flex items-center justify-between mb-3">
                                <div class="flex items-center gap-2">
                                    <p class="text-base font-bold text-dark">{{ $category->name }}</p>
                                </div>
                                <a href="{{ route('store.category', $category) }}" class="text-xs font-medium hover:underline text-accent">Lihat Semua</a>
                            </div>

                            <div class="flex gap-5 overflow-x-auto pb-4 scrollbar-hide">
                                @foreach($group->take(7) as $pkg)
                                    @php
                                        $vendor = $pkg->vendor;
                                        $price = (int) ($pkg->price ?? 0);
                                        $discount = (int) ($pkg->discount ?? 0);
                                        $final = max($price - $discount, 0);
                                        $cover = $pkg->image_url;
                                        if (!$cover && $vendor) {
                                            $cover = $vendor->cover_image_url ?? null;
                                        }
                                        $cover = $cover ?: 'data:image/svg+xml;utf8,' . rawurlencode('<svg xmlns="http://www.w3.org/2000/svg" width="400" height="280" viewBox="0 0 400 280"><rect width="400" height="280" fill="#f3f4f6"/><text x="50%" y="50%" dominant-baseline="middle" text-anchor="middle" fill="#9ca3af" font-family="Arial" font-size="16">No Image</text></svg>');
                                        $items = $pkg->items;
                                        $maxTags = 2;
                                        $shownTags = array_slice($items, 0, $maxTags);
                                        $extraTags = max(0, count($items) - $maxTags);
                                    @endphp
                                    <a href="{{ route('store.package.show', $pkg) }}"
                                       class="flex-none w-[44vw] lg:w-60 bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden cursor-pointer hover:shadow-md transition">
                                        <div class="relative aspect-[4/5]">
                                            <img src="{{ $cover }}" alt="{{ $pkg->name }}" class="w-full h-full object-cover">
                                            @if($discount > 0)
                                                <span class="absolute top-2 left-2 bg-accent text-white text-xs font-bold px-2.5 py-1 rounded-full leading-tight text-center">
                                                    Promo
                                                </span>
                                            @endif
                                            <span class="absolute bottom-2 left-2 bg-black/50 text-white text-[10px] px-2 py-0.5 rounded-full flex items-center gap-1">
                                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/></svg>
                                                {{ $vendor->city ?? 'Indonesia' }}
                                            </span>
                                        </div>
                                        <div class="p-4">
                                            <p class="font-bold text-gray-900 text-sm leading-snug mb-0.5">{{ $pkg->name }}</p>
                                            <p class="text-[10px] text-gray-500 mb-3">by <span class="font-medium text-gray-700">{{ $vendor->name }}</span> — {{ $category->name }}</p>
                                            @if($discount > 0)
                                                <p class="text-xs text-gray-400 line-through mb-0.5">IDR {{ number_format($price, 0, ',', '.') }}</p>
                                            @endif
                                            <p class="font-bold text-sm text-accent">IDR {{ number_format($final ?: $price, 0, ',', '.') }}</p>
                                        </div>
                                    </a>
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
        <section class="py-16 bg-light-sage" id="venues">
            <x-ui.container>

                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-2xl font-bold text-dark">Paket Pernikahan per Kota</h2>
                    <!-- <a href="{{ route('store') }}" class="text-sm font-medium hover:underline text-accent">Lihat Semua</a> -->
                </div>

                @php $cityIndex = 0; @endphp
                @foreach($homePackagesByCity as $city => $cityPackages)
                    @php $cityIndex++; @endphp
                    <div class="mb-10 venue-city-block" @if($cityIndex > 3) style="display:none" @endif>
                        <div class="flex items-center justify-between mb-3">
                            <p class="text-base font-bold text-dark">{{ $city }}</p>
                            <a href="{{ route('store.city', $city) }}" class="text-xs text-accent font-medium hover:underline">Lihat semua</a>
                        </div>

                        <div class="flex gap-5 overflow-x-auto pb-4 scrollbar-hide">
                            @foreach($cityPackages->take(7) as $pkg)
                                @php
                                    $vendor = $pkg->vendor;
                                    $price = (int) ($pkg->price ?? 0);
                                    $discount = (int) ($pkg->discount ?? 0);
                                    $final = max($price - $discount, 0);
                                    $cover = $pkg->image_url;
                                    if (!$cover && $vendor) {
                                        $cover = $vendor->cover_image_url ?? null;
                                    }
                                    $cover = $cover ?: 'data:image/svg+xml;utf8,' . rawurlencode('<svg xmlns="http://www.w3.org/2000/svg" width="400" height="280" viewBox="0 0 400 280"><rect width="400" height="280" fill="#f3f4f6"/><text x="50%" y="50%" dominant-baseline="middle" text-anchor="middle" fill="#9ca3af" font-family="Arial" font-size="16">No Image</text></svg>');
                                    $items = $pkg->items;
                                    $maxTags = 2;
                                    $shownTags = array_slice($items, 0, $maxTags);
                                    $extraTags = max(0, count($items) - $maxTags);
                                @endphp
                                <a href="{{ route('store.package.show', $pkg) }}"
                                   class="flex-none w-[44vw] lg:w-60 bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden cursor-pointer hover:shadow-md transition">
                                    <div class="relative aspect-[4/5]">
                                        <img src="{{ $cover }}" alt="{{ $pkg->name }}" class="w-full h-full object-cover">
                                        @if($discount > 0)
                                            <span class="absolute top-2 left-2 bg-accent text-white text-xs font-bold px-2.5 py-1 rounded-full leading-tight text-center">
                                                Promo
                                            </span>
                                        @endif
                                        <span class="absolute bottom-2 left-2 bg-black/50 text-white text-xs px-2 py-0.5 rounded-full flex items-center gap-1">
                                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/></svg>
                                            {{ $city }}
                                        </span>
                                    </div>
                                    <div class="p-4">
                                        <p class="font-bold text-gray-900 text-sm leading-snug mb-0.5">{{ $pkg->name }}</p>
                                        <p class="text-xs text-gray-500 mb-3">by <span class="font-medium text-gray-700">{{ $vendor->name }}</span></p>
                                        @if($discount > 0)
                                            <p class="text-xs text-gray-400 line-through mb-0.5">IDR {{ number_format($price, 0, ',', '.') }}</p>
                                        @endif
                                        <p class="font-bold text-sm text-accent">IDR {{ number_format($final ?: $price, 0, ',', '.') }}</p>
                                    </div>
                                </a>
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

                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-2xl font-bold text-dark">Review Videos</h2>
                    <a href="#" class="text-sm font-medium hover:underline text-accent">Lihat Semua</a>
                </div>

                <div class="flex gap-4 overflow-x-auto pb-4 scrollbar-hide">

                    @forelse($venueReviewVideos as $video)
                        @php
                            $thumb = $video->thumbnail_url ?: 'https://picsum.photos/seed/vrvideo-' . $video->id . '/300/533';
                            $hasVideo = !empty($video->video_url);
                        @endphp
                        <div class="flex-none rounded-2xl overflow-hidden cursor-pointer relative group w-[calc((100%-4rem)/5)] min-w-[140px] ar-9x16"
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
                    <div class="relative w-full max-w-3xl">
                        <button type="button" id="venue-video-close"
                                class="absolute -top-10 right-0 text-white hover:text-gray-300 transition flex items-center gap-1 text-sm">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                            Tutup
                        </button>
                        <div class="relative w-full aspect-video bg-black rounded-2xl overflow-hidden">
                            <iframe id="venue-video-iframe"
                                    src=""
                                    class="w-full h-full"
                                    frameborder="0"
                                    allow="autoplay; encrypted-media; picture-in-picture"
                                    allowfullscreen>
                            </iframe>
                        </div>
                    </div>
                </div>

                <script>
                (function () {
                    function toEmbedUrl(url) {
                        if (!url) return '';
                        // youtu.be/ID
                        var m = url.match(/youtu\.be\/([^?&]+)/);
                        if (m) return 'https://www.youtube.com/embed/' + m[1] + '?autoplay=1';
                        // youtube.com/watch?v=ID
                        m = url.match(/[?&]v=([^&]+)/);
                        if (m) return 'https://www.youtube.com/embed/' + m[1] + '?autoplay=1';
                        // youtube.com/embed/...
                        if (url.includes('/embed/')) return url + (url.includes('?') ? '&' : '?') + 'autoplay=1';
                        return url;
                    }

                    var modal = document.getElementById('venue-video-modal');
                    var iframe = document.getElementById('venue-video-iframe');
                    var closeBtn = document.getElementById('venue-video-close');

                    function openModal(url) {
                        iframe.src = toEmbedUrl(url);
                        modal.classList.remove('hidden');
                        modal.classList.add('flex');
                        document.body.style.overflow = 'hidden';
                    }

                    function closeModal() {
                        iframe.src = '';
                        modal.classList.add('hidden');
                        modal.classList.remove('flex');
                        document.body.style.overflow = '';
                    }

                    document.querySelectorAll('[data-video-popup]').forEach(function (el) {
                        el.addEventListener('click', function () {
                            openModal(el.getAttribute('data-video-popup'));
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

                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-2xl font-bold text-dark">Vendor Event dan Promo</h2>
                    <a href="{{ route('store.promo') }}" class="text-sm font-medium hover:underline text-accent">Lihat Semua</a>
                </div>

                @if($homePromoPackages->isNotEmpty())
                <div class="relative">
                    <div class="flex gap-4 overflow-x-auto pb-2 scrollbar-hide" id="vendor-promo-scroll">

                        @foreach($homePromoPackages as $pkg)
                            @php
                                $vendor = $pkg->vendor;
                                $price = (int) ($pkg->price ?? 0);
                                $discount = (int) ($pkg->discount ?? 0);
                                $final = max($price - $discount, 0);
                                $logo = $vendor->logo_vendor
                                    ? (str_starts_with($vendor->logo_vendor, 'http') ? $vendor->logo_vendor : \Illuminate\Support\Facades\Storage::url($vendor->logo_vendor))
                                    : null;
                                $logo = $logo ?: $pkg->image_url ?: ($vendor->cover_image_url ?? null);
                            @endphp
                            <a href="{{ route('store.package.show', $pkg) }}"
                               class="flex-none w-56 bg-white border border-gray-200 rounded-2xl overflow-hidden cursor-pointer hover:shadow-md transition relative">
                                <div class="absolute top-3 left-3 z-10">
                                    <span class="text-[10px] font-bold uppercase tracking-wide px-2.5 py-1 rounded-full bg-accent-pink text-dark">PROMO</span>
                                </div>
                                <div class="flex flex-col items-center px-6 pt-10 pb-5">
                                    <div class="w-24 h-24 rounded-full overflow-hidden border-2 border-gray-100 mb-4 bg-gray-50">
                                        @if($logo)
                                            <img src="{{ $logo }}" alt="{{ $vendor->name }}" class="w-full h-full object-cover">
                                        @else
                                            <div class="w-full h-full flex items-center justify-center text-gray-300">
                                                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                            </div>
                                        @endif
                                    </div>
                                    <p class="font-bold text-sm text-center text-gray-900 leading-snug mb-1">{{ $pkg->name }}</p>
                                    @php
                                        $catNames = collect($pkg->category_vendor_id ?? [])
                                            ->map(fn($cid) => $homeCategories->firstWhere('id', (int)$cid)?->name)
                                            ->filter()
                                            ->implode(', ');
                                    @endphp
                                    <p class="text-xs text-gray-500 text-center mb-2">{{ $vendor->name }}{{ $catNames ? ' — ' . $catNames : '' }}</p>
                                    <p class="text-[11px] text-gray-400 line-through">IDR {{ number_format($price, 0, ',', '.') }}</p>
                                    <p class="text-sm font-bold text-accent">IDR {{ number_format($final, 0, ',', '.') }}</p>
                                    @if($vendor->city)
                                        <p class="text-xs text-gray-500 flex items-center gap-1 mt-2">
                                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/></svg>
                                            {{ $vendor->city }}
                                        </p>
                                    @endif
                                </div>
                            </a>
                        @endforeach

                        <!-- View All Card -->
                        <a href="{{ route('store.promo') }}" class="flex-none w-56 bg-white border border-gray-200 rounded-2xl cursor-pointer hover:shadow-md transition flex flex-col items-center justify-center gap-3 py-10">
                            <div class="w-14 h-14 rounded-full border-2 border-gray-400 flex items-center justify-center">
                                <svg class="w-6 h-6 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                            </div>
                            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide text-center">LIHAT SEMUA PROMO</p>
                        </a>

                    </div>

                    <!-- Arrow prev -->
                    <button type="button" data-scroll-target="vendor-promo-scroll" data-scroll-by="-300"
                            class="absolute left-0 top-1/2 -translate-y-1/2 -translate-x-4 w-10 h-10 bg-white rounded-full shadow-md flex items-center justify-center hover:shadow-lg transition z-10">
                        <svg class="w-5 h-5 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                    </button>
                    <!-- Arrow next -->
                    <button type="button" data-scroll-target="vendor-promo-scroll" data-scroll-by="300"
                            class="absolute right-0 top-1/2 -translate-y-1/2 translate-x-4 w-10 h-10 bg-white rounded-full shadow-md flex items-center justify-center hover:shadow-lg transition z-10">
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

                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-2xl font-bold text-dark">Real Wedding</h2>
                    <a href="{{ route('real-wedding.index') }}" class="text-sm font-medium hover:underline text-accent">Lihat Semua</a>
                </div>

                <div class="relative">
                    <div class="flex gap-4 overflow-x-auto pb-2 scrollbar-hide" id="real-wedding-scroll">

                        @forelse($realWeddings as $rw)
                            @php
                                $rwImage = $rw->cover_image_url ?: 'https://picsum.photos/seed/rw-' . $rw->id . '/400/533';
                            @endphp
                            <a href="{{ route('real-wedding.show', $rw->slug) }}"
                               class="flex-none rounded-2xl overflow-hidden cursor-pointer relative group w-[calc((100%-4rem)/5)] min-w-[160px] aspect-[3/4] block">
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
                            class="absolute right-0 top-1/2 -translate-y-1/2 translate-x-4 w-10 h-10 bg-white rounded-full shadow-md flex items-center justify-center hover:shadow-lg transition z-10">
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

            <x-ui.container class="relative flex flex-col lg:flex-row items-center gap-8">

                <!-- Left: Title -->
                <div class="flex-shrink-0 lg:w-64">
                    <p class="font-bold text-lg leading-snug text-cream">Persiapkan Pernikahan dengan Beragam Kemudahan &amp; Penawaran Ekslusif</p>
                </div>

                <!-- Middle: Features -->
                <div class="flex flex-wrap lg:flex-nowrap gap-6 flex-1 justify-center">
                    <div class="flex items-center gap-2 text-cream">
                        <div class="w-8 h-8 rounded-full flex items-center justify-center flex-shrink-0 bg-cream-25">
                            <svg class="w-4 h-4 text-cream" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                        </div>
                        <p class="text-xs font-medium leading-snug">Vendor &amp; Produk<br>Pernikahan Terlengkap</p>
                    </div>
                    <div class="flex items-center gap-2 text-cream">
                        <div class="w-8 h-8 rounded-full flex items-center justify-center flex-shrink-0 bg-cream-25">
                            <svg class="w-4 h-4 text-cream" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd"/></svg>
                        </div>
                        <p class="text-xs font-medium leading-snug">Sesuaikan Pesanan<br>dengan Impian Anda</p>
                    </div>
                    <div class="flex items-center gap-2 text-cream">
                        <div class="w-8 h-8 rounded-full flex items-center justify-center flex-shrink-0 bg-cream-25">
                            <svg class="w-4 h-4 text-cream" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5 2a2 2 0 00-2 2v14l3.5-2 3.5 2 3.5-2 3.5 2V4a2 2 0 00-2-2H5zm2.5 3a1.5 1.5 0 100 3 1.5 1.5 0 000-3zm6.207.293a1 1 0 00-1.414 0l-6 6a1 1 0 101.414 1.414l6-6a1 1 0 000-1.414zM12.5 10a1.5 1.5 0 100 3 1.5 1.5 0 000-3z" clip-rule="evenodd"/></svg>
                        </div>
                        <p class="text-xs font-medium leading-snug">Promo Eksklusif &amp;<br>Hadiah Menarik</p>
                    </div>
                    <div class="flex items-center gap-2 text-cream">
                        <div class="w-8 h-8 rounded-full flex items-center justify-center flex-shrink-0 bg-cream-25">
                            <svg class="w-4 h-4 text-cream" fill="currentColor" viewBox="0 0 20 20"><path d="M8.433 7.418c.155-.103.346-.196.567-.267v1.698a2.305 2.305 0 01-.567-.267C8.07 8.34 8 8.114 8 8c0-.114.07-.34.433-.582zM11 12.849v-1.698c.22.071.412.164.567.267.364.243.433.468.433.582 0 .114-.07.34-.433.582a2.305 2.305 0 01-.567.267z"/><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-13a1 1 0 10-2 0v.092a4.535 4.535 0 00-1.676.662C6.602 6.234 6 7.009 6 8c0 .99.602 1.765 1.324 2.246.48.32 1.054.545 1.676.662v1.941c-.391-.127-.68-.317-.843-.504a1 1 0 10-1.51 1.31c.562.649 1.413 1.077 2.353 1.253V15a1 1 0 102 0v-.092a4.535 4.535 0 001.676-.662C13.398 13.766 14 12.991 14 12c0-.99-.602-1.765-1.324-2.246A4.535 4.535 0 0011 9.092V7.151c.391.127.68.317.843.504a1 1 0 101.511-1.31c-.563-.649-1.413-1.077-2.354-1.253V5z" clip-rule="evenodd"/></svg>
                        </div>
                        <p class="text-xs font-medium leading-snug">Cicilan 0% hingga 24<br>bulan</p>
                    </div>
                </div>

                <!-- Right: CTA Button -->
                <div class="flex-shrink-0">
                    <button type="button" onclick="document.getElementById('cta-coming-soon-modal').classList.remove('hidden');document.getElementById('cta-coming-soon-modal').classList.add('flex');document.body.style.overflow='hidden';"
                            class="inline-block px-6 py-3 rounded-xl font-semibold text-sm transition hover:opacity-90 bg-cream text-dark cursor-pointer">
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

                <div class="flex items-center justify-between mb-8">
                    <h2 class="text-2xl font-bold text-dark">Jangan Lewatkan Blog Post Ini</h2>
                    <a href="{{ route('blog.index') }}" class="text-sm font-medium hover:underline text-accent">Lihat Semua</a>
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

        @if($homeAd)
        @php
            $adImage = $homeAd->image_url ?: 'https://picsum.photos/seed/makna-ad/800/800';
            $adDelay = (int) ($homeAd->delay_seconds ?? 5) * 1000;
            $adKey   = 'home_ad_dismissed_v' . $homeAd->id;
        @endphp
        <div id="home-ad-modal"
             data-ad-key="{{ $adKey ?? 'home_ad_dismissed_v1' }}"
             data-ad-delay="{{ (int) ($adDelay ?? 5000) }}"
             class="fixed inset-0 z-[9998] hidden items-center justify-center p-4"
             style="background:rgba(35, 34, 34, 0.92)">
            <div class="bg-white rounded-2xl shadow-2xl overflow-hidden" style="max-width:min(90vw,400px);max-height:90vh;">
                <div class="relative">
                    @if($homeAd->link_url)
                        <a href="{{ $homeAd->link_url }}" class="block">
                    @endif
                    <img src="{{ $adImage }}" alt="{{ $homeAd->title ?: 'Iklan' }}" class="block w-auto h-auto" style="max-width:min(90vw,400px);max-height:85vh;display:block;">
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
