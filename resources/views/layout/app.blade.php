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
                --accent-red: #8B1538;
                --accent-gold: #D4AF37;
                --accent-light-red: #D32F2F;
            }
        </style>

        @yield('extra-head')
    </head>
    
    <body class="@yield('body-class', 'bg-white text-gray-900')">
        @yield('content')
    </body>
</html>
