<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>@yield('title', 'Makna Wedding - Wedding Organizer & Paket Pernikahan')</title>

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
                    animation: marquee 30s linear infinite;
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
                <button onclick="document.getElementById('error-modal').remove()"
                        class="w-full py-2.5 rounded-xl text-sm font-semibold text-white transition hover:opacity-90 bg-accent">
                    Mengerti
                </button>
            </div>
        </div>
        @endif

    </body>
</html>
