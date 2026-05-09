@extends('layout.app')

@section('title', 'Review Videos - ' . config('app.name'))

@section('body-class', 'bg-cream text-dark')

@section('content')
    @include('layout.header')

    @php
        $breadcrumbItems = [
            ['label' => 'Home', 'url' => route('home')],
            ['label' => 'Review Videos', 'url' => null],
        ];
    @endphp

    <section class="py-8 bg-cream">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="pt-4 pb-4">
                @include('layout.breadcrumb', ['items' => $breadcrumbItems])
            </div>

            <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4 mb-5">
                <div>
                    <h1 class="text-xl sm:text-2xl font-extrabold text-dark">Review Videos</h1>
                    <p class="text-sm text-gray-500 mt-1">Video ulasan venue & paket pernikahan pilihan.</p>
                </div>
            </div>

            @if($videos->isEmpty())
                <div class="bg-white rounded-2xl border border-gray-100 p-10 text-center">
                    <p class="text-sm text-gray-500">Belum ada video tersedia.</p>
                </div>
            @else
                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4">
                    @foreach($videos as $video)
                        @php
                            $thumb    = $video->thumbnail_url ?: 'https://picsum.photos/seed/vrvideo-' . $video->id . '/300/533';
                            $hasVideo = !empty($video->video_url);
                        @endphp
                        <div class="rounded-2xl overflow-hidden cursor-pointer relative group ar-9x16"
                             @if($hasVideo) data-video-popup="{{ $video->video_url }}" @endif>
                            <img src="{{ $thumb }}" alt="{{ $video->title }}" class="w-full h-full object-cover">
                            <div class="absolute inset-0 bg-linear-to-t from-black/70 via-black/20 to-transparent"></div>
                            <div class="absolute inset-0 flex items-center justify-center @if(!$hasVideo) opacity-0 @endif group-hover:opacity-100 transition-opacity duration-200">
                                <div class="w-14 h-14 bg-white/90 rounded-full flex items-center justify-center shadow-lg">
                                    <svg class="w-6 h-6 text-gray-800 ml-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M6.3 2.841A1.5 1.5 0 004 4.11V15.89a1.5 1.5 0 002.3 1.269l9.344-5.89a1.5 1.5 0 000-2.538L6.3 2.84z"/>
                                    </svg>
                                </div>
                            </div>
                            <div class="absolute bottom-4 left-4 right-4 text-white">
                                <p class="text-[10px] uppercase tracking-widest opacity-80 mb-1">{{ $video->subtitle }}</p>
                                <p class="font-bold text-sm leading-tight">{{ $video->title }}</p>
                                @if($video->location)
                                    <p class="text-[10px] opacity-70 mt-1">{{ $video->location }}</p>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </section>

    {{-- Paket Section --}}
    <section class="py-10 bg-cream">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between mb-5">
                <h2 class="text-xl font-extrabold text-dark">Paket Pernikahan</h2>
                <a href="{{ route('store') }}" class="text-xs font-medium hover:underline text-accent">Lainnya</a>
            </div>

            @if($packages->isEmpty())
                <div class="bg-white rounded-2xl border border-gray-100 p-10 text-center">
                    <p class="text-sm text-gray-500">Belum ada paket tersedia.</p>
                </div>
            @else
                <div class="grid grid-cols-2 lg:grid-cols-6 gap-4">
                    @foreach($packages as $pkg)
                        @php
                            $vendor   = $pkg->vendor;
                            $price    = (int) ($pkg->price ?? 0);
                            $discount = (int) ($pkg->discount ?? 0);
                            $final    = max($price - $discount, 0);
                            $cover    = $pkg->image_url;
                            if (!$cover && $vendor && is_array($vendor->cover_image ?? null) && count($vendor->cover_image) > 0) {
                                $cover = $vendor->cover_image[0];
                            }
                            $cover = $cover ?: 'https://picsum.photos/seed/rv-pkg-' . $pkg->id . '/800/600';
                        @endphp
                        <a href="{{ route('store.package.show', $pkg) }}"
                           class="bg-white rounded-2xl border border-gray-100 overflow-hidden hover:border-gray-200 hover:shadow-sm transition">
                            <div class="relative ar-4x5">
                                <img src="{{ $cover }}" alt="{{ $pkg->name }}" class="w-full h-full object-cover">
                                <div class="absolute inset-0 bg-linear-to-t from-black/90 via-black/30 to-transparent"></div>
                                @if($discount > 0)
                                    <span class="absolute top-3 left-3 text-[10px] font-bold px-2 py-1 rounded-full bg-white/90 border border-gray-200 text-dark">
                                        Promo
                                    </span>
                                @endif
                                <div class="absolute bottom-0 left-0 right-0 p-3">
                                    <p class="text-white text-xs font-bold leading-snug">{{ $pkg->name }}</p>
                                    <p class="text-white/80 text-[10px] mt-1">{{ $vendor?->name }}</p>
                                </div>
                            </div>
                            <div class="p-4">
                                <p class="text-[10px] text-gray-400 truncate">{{ $vendor?->city }} · {{ $vendor?->location }}</p>
                                @if($discount > 0)
                                    <p class="text-[11px] text-gray-400 line-through mt-1">Rp {{ number_format($price, 0, ',', '.') }}</p>
                                    <p class="text-sm font-extrabold leading-tight text-accent">Rp {{ number_format($final, 0, ',', '.') }}</p>
                                @else
                                    <p class="text-sm font-extrabold leading-tight mt-1 text-accent">Rp {{ number_format($price, 0, ',', '.') }}</p>
                                @endif
                                <p class="text-[10px] text-gray-400 truncate">{{ $vendor?->name }}</p>
                            </div>
                        </a>
                    @endforeach
                </div>

            @endif
        </div>
    </section>

    {{-- Video Popup Modal --}}
    <div id="venue-video-modal" class="fixed inset-0 z-9999 hidden items-center justify-center p-4 bg-black/80">
        <div id="venue-video-wrapper" class="relative w-full max-w-3xl">
            <button type="button" id="venue-video-close"
                    class="absolute -top-10 right-0 text-white hover:text-gray-300 transition flex items-center gap-1 text-sm">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
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
            var m = url.match(/youtube\.com\/shorts\/([^?&/]+)/);
            if (m) return buildEmbedUrl(m[1]);
            m = url.match(/youtu\.be\/([^?&]+)/);
            if (m) return buildEmbedUrl(m[1]);
            m = url.match(/[?&]v=([^&]+)/);
            if (m) return buildEmbedUrl(m[1]);
            if (url.includes('/embed/')) {
                return url + (url.includes('?') ? '&' : '?') + 'autoplay=1&playsinline=1&rel=0&origin=' + playerOrigin;
            }
            return url;
        }

        var modal    = document.getElementById('venue-video-modal');
        var iframe   = document.getElementById('venue-video-iframe');
        var closeBtn = document.getElementById('venue-video-close');

        function openModal(url) {
            var isShorts  = /youtube\.com\/shorts\//.test(url);
            var wrapper   = document.getElementById('venue-video-wrapper');
            var container = document.getElementById('venue-video-container');
            wrapper.style.maxWidth      = isShorts ? '24rem' : '';
            container.style.aspectRatio = isShorts ? '9 / 16' : '';
            iframe.src = toEmbedUrl(url);
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            document.body.style.overflow = 'hidden';
        }

        function closeModal() {
            var wrapper   = document.getElementById('venue-video-wrapper');
            var container = document.getElementById('venue-video-container');
            wrapper.style.maxWidth      = '';
            container.style.aspectRatio = '';
            iframe.src = '';
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            document.body.style.overflow = '';
        }

        document.querySelectorAll('[data-video-popup]').forEach(function (el) {
            el.addEventListener('click', function () { openModal(el.dataset.videoPopup); });
        });

        closeBtn.addEventListener('click', closeModal);
        modal.addEventListener('click', function (e) { if (e.target === modal) closeModal(); });
        document.addEventListener('keydown', function (e) { if (e.key === 'Escape') closeModal(); });
    })();
    </script>

    @include('layout.footer')
@endsection
