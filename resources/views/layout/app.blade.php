<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        @php
            $defaultTitle = 'Makna Wedding - Wedding Organizer & Paket Pernikahan';
            $pageTitle = trim($__env->yieldContent('title', $defaultTitle)) ?: $defaultTitle;
            $defaultDescription = 'Makna Wedding membantu calon pengantin menemukan vendor, paket pernikahan, inspirasi, dan layanan wedding organizer di Indonesia.';
            $metaDescription = trim($__env->yieldContent('meta-description', $defaultDescription)) ?: $defaultDescription;
            $metaImage = trim($__env->yieldContent('meta-image', url(config('app.logo_url'))));
            $canonicalUrl = trim($__env->yieldContent('canonical-url', url()->current())) ?: url()->current();
            $metaType = trim($__env->yieldContent('meta-type', 'website')) ?: 'website';
        @endphp
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover, maximum-scale=1">
        <style>
            html, body { overscroll-behavior: none; }
            body { padding-bottom: env(safe-area-inset-bottom); }
            .safe-area-header { padding-top: env(safe-area-inset-top); }
            a, button { -webkit-tap-highlight-color: transparent; touch-action: manipulation; }
        </style>
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="description" content="{{ $metaDescription }}">
        <meta name="robots" content="index,follow,max-image-preview:large,max-snippet:-1,max-video-preview:-1">

        <link rel="canonical" href="{{ $canonicalUrl }}">

        <title>{!! $pageTitle !!}</title>
        <link rel="icon" href="{{ config('app.favicon_url') }}">
        <meta property="og:locale" content="{{ str_replace('_', '-', app()->getLocale()) }}">
        <meta property="og:type" content="{{ $metaType }}">
        <meta property="og:title" content="{{ $pageTitle }}">
        <meta property="og:description" content="{{ $metaDescription }}">
        <meta property="og:url" content="{{ $canonicalUrl }}">
        <meta property="og:site_name" content="{{ config('app.name', 'Makna Wedding') }}">
        <meta property="og:image" content="{{ $metaImage }}">
        <meta name="twitter:card" content="summary_large_image">
        <meta name="twitter:title" content="{{ $pageTitle }}">
        <meta name="twitter:description" content="{{ $metaDescription }}">
        <meta name="twitter:image" content="{{ $metaImage }}">
        @if(filled(config('services.ga4.measurement_id')))
            <script async src="https://www.googletagmanager.com/gtag/js?id={{ config('services.ga4.measurement_id') }}"></script>
            <script>
                window.dataLayer = window.dataLayer || [];
                function gtag(){dataLayer.push(arguments);}
                gtag('js', new Date());
                gtag('config', "{{ config('services.ga4.measurement_id') }}");
            </script>
        @endif

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=poppins:400,500,600,700" rel="stylesheet" />

        <!-- Styles / Scripts -->
        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @else
            <style>
                * {
                    font-family: 'Poppins', ui-sans-serif, system-ui, sans-serif;
                }

                :root {
                    --soft-pink: #F9D5E5;
                    --sage-green: #9CAF88;
                    --light-sage: #C8D5B9;
                    --cream: #FAF3E7;
                    --dark-gray: #444444;
                }

                body {
                    background-color: var(--cream);
                    color: var(--dark-gray);
                }

                .text-accent {
                    color: var(--sage-green);
                }

                .text-accent-pink {
                    color: var(--soft-pink);
                }

                .text-dark {
                    color: var(--dark-gray);
                }

                .bg-accent {
                    background-color: var(--sage-green);
                }

                .bg-accent-pink {
                    background-color: var(--soft-pink);
                }

                .bg-light-sage {
                    background-color: var(--light-sage);
                }

                .bg-cream {
                    background-color: var(--cream);
                }

                .border-accent {
                    border-color: var(--sage-green);
                }

                .from-accent {
                    --tw-gradient-from: var(--sage-green);
                }

                .to-accent {
                    --tw-gradient-to: var(--sage-green);
                }

                .to-accent-dark {
                    --tw-gradient-to: #7d9469;
                }

                .from-soft-pink {
                    --tw-gradient-from: var(--soft-pink);
                }

                .hover\:text-accent:hover {
                    color: var(--sage-green);
                }

                .focus\:ring-accent:focus {
                    --tw-ring-color: rgba(156, 175, 136, 0.4);
                    box-shadow: 0 0 0 3px rgba(156, 175, 136, 0.4);
                }

                .bg-accent-gradient {
                    background: linear-gradient(to right, var(--sage-green), var(--light-sage));
                }

                @keyframes marquee {
                    0% {
                        transform: translateX(0);
                    }
                    100% {
                        transform: translateX(-50%);
                    }
                }

                .animate-marquee {
                    animation: marquee 18s linear infinite;
                }

                .scrollbar-hide::-webkit-scrollbar {
                    display: none;
                }

                .scrollbar-hide {
                    -ms-overflow-style: none;
                    scrollbar-width: none;
                }
            </style>
        @endif

        @include('layout.theme-vars')

        @yield('extra-head')
    </head>
    
    <body class="@yield('body-class', 'bg-cream text-dark')">
        @yield('content')

        {{-- ── Error Modal (PostTooLarge / upload errors) ── --}}
        @if(session('error_modal'))
        <div id="error-modal"
             class="fixed inset-0 z-[9999] flex items-center justify-center p-4 bg-backdrop-45">
            <div class="bg-white rounded-2xl shadow-2xl max-w-sm w-full p-6 flex flex-col items-center gap-4 text-center">
                <div class="w-14 h-14 rounded-full flex items-center justify-center bg-red-50">
                    <svg class="w-7 h-7 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
                    </svg>
                </div>
                <h3 class="text-base font-bold text-dark">File Terlalu Besar</h3>
                <p class="text-sm text-gray-500">{{ session('error_modal') }}</p>
                <button type="button" data-close-error-modal class="w-full py-2.5 rounded-xl text-sm font-semibold text-white transition hover:opacity-90 bg-accent">
                    Mengerti
                </button>
            </div>
        </div>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                document.querySelectorAll('[data-close-error-modal]').forEach(function (btn) {
                    btn.addEventListener('click', function () {
                        var modal = document.getElementById('error-modal');
                        if (modal) modal.remove();
                    });
                });
            });
        </script>
        @endif

        @include('layout.mobile-nav')

        {{-- Compare Bar --}}
        <x-compare-bar />

        {{-- Alpine.js Compare Store --}}
        <script>
        document.addEventListener('alpine:init', () => {
            Alpine.store('compare', {
                items: JSON.parse(localStorage.getItem('mw_compare') || '[]'),
                max: 3,
                add(pkg) {
                    if (this.has(pkg.id) || this.items.length >= this.max) return;
                    this.items.push(pkg);
                    this.save();
                },
                remove(id) {
                    this.items = this.items.filter(i => i.id !== id);
                    this.save();
                },
                toggle(pkg) {
                    this.has(pkg.id) ? this.remove(pkg.id) : this.add(pkg);
                },
                has(id) {
                    return this.items.some(i => i.id === id);
                },
                clear() {
                    this.items = [];
                    this.save();
                },
                save() {
                    localStorage.setItem('mw_compare', JSON.stringify(this.items));
                },
                compareUrl() {
                    const ids = this.items.map(i => i.id).join(',');
                    return '/bandingkan?ids=' + ids;
                }
            });
        });
        </script>

        {{-- Posisi compare bar: di atas mobile nav kalau mobile, di bawah kalau desktop --}}
        <script>
        (function() {
            function adjustCompareBar() {
                var bar = document.getElementById('compare-bar');
                if (!bar) return;
                var nav = document.getElementById('app-bottom-nav');
                var navVisible = nav && window.getComputedStyle(nav).display !== 'none';
                if (navVisible) {
                    bar.style.bottom = 'calc(60px + env(safe-area-inset-bottom))';
                } else {
                    bar.style.bottom = '0px';
                }
            }
            document.addEventListener('DOMContentLoaded', adjustCompareBar);
            window.addEventListener('resize', adjustCompareBar);
        })();
        </script>

        @yield('extra-scripts')
    </body>
</html>
