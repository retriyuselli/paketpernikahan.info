<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

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
            </style>
        @endif

        <!-- Custom CSS Variables -->
        <style>
            :root {
                --soft-pink:   #F9D5E5;
                --sage-green:  #9CAF88;
                --light-sage:  #C8D5B9;
                --cream:       #FAF3E7;
                --dark-gray:   #444444;
            }

            /* Global body background */
            body { background-color: var(--cream); color: var(--dark-gray); }

            /* Color Utilities */
            .text-accent            { color: var(--sage-green); }
            .text-accent-pink       { color: var(--soft-pink); }
            .text-dark              { color: var(--dark-gray); }
            .bg-accent              { background-color: var(--sage-green); }
            .bg-accent-pink         { background-color: var(--soft-pink); }
            .bg-light-sage          { background-color: var(--light-sage); }
            .bg-cream               { background-color: var(--cream); }
            .border-accent          { border-color: var(--sage-green); }
            .from-accent            { --tw-gradient-from: var(--sage-green); }
            .to-accent              { --tw-gradient-to: var(--sage-green); }
            .to-accent-dark         { --tw-gradient-to: #7d9469; }
            .from-soft-pink         { --tw-gradient-from: var(--soft-pink); }
            .hover\:text-accent:hover { color: var(--sage-green); }
            .focus\:ring-accent:focus { --tw-ring-color: rgba(156,175,136,.4); box-shadow: 0 0 0 3px rgba(156,175,136,.4); }

            @keyframes marquee {
                0%   { transform: translateX(0); }
                100% { transform: translateX(-50%); }
            }
            .animate-marquee {
                animation: marquee 30s linear infinite;
            }

            .scrollbar-hide::-webkit-scrollbar { display: none; }
            .scrollbar-hide { -ms-overflow-style: none; scrollbar-width: none; }
        </style>

        @yield('extra-head')
    </head>
    
    <body class="@yield('body-class', 'bg-cream text-dark')">
        @yield('content')
    </body>
</html>
