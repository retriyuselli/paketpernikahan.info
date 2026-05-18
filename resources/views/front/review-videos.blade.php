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

    <section class="pt-3 lg:py-8 bg-cream">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="pt-1 pb-4 lg:pt-4">
                <x-breadcrumb :items="$breadcrumbItems" />
            </div>
            <div class="mt-3">
                <x-banner-ad mt="0" />
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
                            <img src="{{ $thumb }}" alt="{{ $video->title }}" loading="lazy" class="w-full h-full object-cover">
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

    <x-paket-section :packages="$packages" :more-url="route('store')" />

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
