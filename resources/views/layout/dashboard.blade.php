<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Dashboard — Makna Wedding')</title>
    <link rel="icon" href="{{ config('app.favicon_url') }}">

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=poppins:400,500,600,700" rel="stylesheet" />

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

            .hover\:text-accent:hover {
                color: var(--sage-green);
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
        </style>
    @endif

    @include('layout.theme-vars')

    <style>
        /* Sidebar link */
        .sidebar-link {
            display: flex;
            align-items: center;
            gap: 0.625rem;
            padding: 0.5rem 0.625rem;
            border-radius: 0.75rem;
            font-size: 0.8125rem;
            font-weight: 500;
            color: #6b7280;
            transition: background .15s, color .15s;
            text-decoration: none;
        }
        .sidebar-link svg { width: 1rem; height: 1rem; flex-shrink: 0; }
        .sidebar-link:hover { background: #f3f4f6; color: #374151; }
        .sidebar-link.active { background: var(--light-sage); color: var(--sidebar-active-text); font-weight: 600; }
        .sidebar-link.active .ml-auto { background: rgba(255,255,255,0.22) !important; color: var(--sidebar-active-text) !important; }
    </style>
</head>
<body class="bg-cream text-dark"
      data-chat-can-notify="{{ $user->hasRole(['super_admin', 'admin']) ? '1' : '0' }}"
      data-chat-notify-url="{{ route('chat.admin.notify') }}">

@include('layout.header')

@php
    $isPemilikPaket = $user->hasRole(['super_admin', 'admin'])
        || \App\Models\Vendor::where('owner_user_id', $user->id)->whereHas('packages')->exists();
    $menuPaketCount = $isPemilikPaket
        ? ($user->hasRole(['super_admin', 'admin'])
            ? \App\Models\VendorPackage::count()
            : \App\Models\VendorPackage::whereHas('vendor', fn($q) => $q->where('owner_user_id', $user->id))->count())
        : 0;
@endphp

<div class="flex min-h-[calc(100vh-130px)]">

    {{-- ─── Sidebar ────────────────────────────────────────────── --}}
    <aside class="hidden lg:flex w-64 flex-shrink-0 bg-white border-r border-gray-100 flex-col min-h-full">

        {{-- Logo --}}
        <div class="hidden px-5 py-5 border-b border-gray-100 flex items-center justify-between">
            <a href="{{ route('home') }}" class="flex items-center gap-2">
                <span class="text-xl font-bold tracking-tight text-dark">Makna</span>
                <span class="text-[10px] font-semibold px-1.5 py-0.5 rounded-full bg-light-sage text-dark">WO</span>
            </a>
            <button type="button" class="lg:hidden p-1 rounded-lg hover:bg-gray-100" data-close-sidebar>
                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        {{-- User Card --}}
        <div class="px-4 py-4 mt-5 border-b border-gray-100">
            <div class="flex items-center gap-3">
                @if($user->avatar_url)
                <img id="dashboard-sidebar-avatar"
                     src="{{ $user->avatarUrl() }}"
                     alt="{{ $user->name }}"
                     class="w-10 h-10 rounded-full object-cover flex-shrink-0 border-2 border-white shadow-sm">
                <div id="dashboard-sidebar-avatar-fallback" class="w-10 h-10 rounded-full items-center justify-center text-sm font-bold text-white flex-shrink-0 bg-accent hidden">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                </div>
                @else
                <div class="w-10 h-10 rounded-full flex items-center justify-center text-sm font-bold text-white flex-shrink-0 bg-accent">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                </div>
                @endif
                <div class="min-w-0">
                    <p class="text-sm font-semibold truncate text-dark">{{ $user->name }}</p>
                    <p class="text-[10px] text-gray-400 truncate">{{ $user->email }}</p>
                </div>
            </div>
            @if($user->hasRole(['super_admin', 'admin']))
            <div class="mt-2.5">
                <span class="text-[10px] font-semibold px-2 py-0.5 rounded-full bg-accent-pink text-dark">
                    {{ $user->hasRole('super_admin') ? 'Super Admin' : 'Admin' }}
                </span>
            </div>
            @endif
        </div>

        {{-- Navigation --}}
        <nav class="flex-1 px-3 py-4 space-y-0.5 overflow-y-auto">
            <p class="text-[10px] uppercase tracking-widest text-gray-400 px-2 mb-2">Menu</p>

            <a href="{{ route('dashboard') }}" class="sidebar-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/>
                    <rect x="14" y="14" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/>
                </svg>
                Dashboard
            </a>

            <a href="{{ route('dashboard.favorit') }}" class="sidebar-link {{ request()->routeIs('dashboard.favorit') ? 'active' : '' }}">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                </svg>
                Favorit
                @if(($favoriteCount ?? 0) > 0)
                <span class="ml-auto text-[10px] font-bold px-1.5 py-0.5 rounded-full flex-shrink-0 bg-soft-pink text-dark">{{$favoriteCount }}</span>
                @endif
            </a>

            <a href="{{ route('dashboard.booking') }}" class="sidebar-link {{ request()->routeIs('dashboard.booking*') ? 'active' : '' }}">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                Booking
                @if(($bookingCount ?? 0) > 0)
                <span class="ml-auto text-[10px] font-bold px-1.5 py-0.5 rounded-full flex-shrink-0 bg-soft-pink text-dark">{{$bookingCount }}</span>
                @endif
            </a>

            @if($user->hasRole(['super_admin', 'admin']))
            <a href="{{ route('dashboard.booking.user') }}" class="sidebar-link {{ request()->routeIs('dashboard.booking.user') ? 'active' : '' }}">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                Booking User
                @if(($bookingUserCount ?? 0) > 0)
                <span class="ml-auto text-[10px] font-bold px-1.5 py-0.5 rounded-full flex-shrink-0 bg-soft-pink text-dark">{{$bookingUserCount }}</span>
                @endif
            </a>
            @endif

            <a href="{{ route('dashboard.ulasan') }}" class="sidebar-link {{ request()->routeIs('dashboard.ulasan') ? 'active' : '' }}">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                </svg>
                Ulasan Saya
                @if($reviewCount > 0)
                <span class="ml-auto text-[10px] font-bold px-1.5 py-0.5 rounded-full flex-shrink-0 bg-soft-pink text-dark">{{$reviewCount }}</span>
                @endif
            </a>

            <a href="{{ route('dashboard.pengaturan') }}" class="sidebar-link {{ request()->routeIs('dashboard.pengaturan') ? 'active' : '' }}">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
                Pengaturan Akun
            </a>

            @if($user->hasRole(['super_admin', 'admin', 'vendor']) || $isPemilikPaket)
            <div class="pt-3">
                <p class="text-[10px] uppercase tracking-widest text-gray-400 px-2 mb-2">Vendor</p>
            </div>
            @if($user->hasRole(['vendor']))
                <a href="{{ route('dashboard.vendor.vendors') }}" class="sidebar-link {{ request()->routeIs('dashboard.vendor.vendors') ? 'active' : '' }}">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 10l9-6 9 6v10a1 1 0 01-1 1h-5v-6H9v6H4a1 1 0 01-1-1V10z"/>
                    </svg>
                    Vendor Saya
                    @if(($menuVendorCount ?? 0) > 0)
                    <span class="ml-auto text-[10px] font-bold px-1.5 py-0.5 rounded-full flex-shrink-0 bg-soft-pink text-dark">{{$menuVendorCount }}</span>
                    @endif
                </a>
            @endif
            @if($isPemilikPaket)
            <a href="{{ route('dashboard.paket') }}" class="sidebar-link {{ request()->routeIs('dashboard.paket') ? 'active' : '' }}">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10"/>
                </svg>
                Paket
                @if($menuPaketCount > 0)
                <span class="ml-auto text-[10px] font-bold px-1.5 py-0.5 rounded-full flex-shrink-0 bg-soft-pink text-dark">{{$menuPaketCount }}</span>
                @endif
            </a>
            @endif
            <a href="{{ route('dashboard.vendor.bookings') }}" class="sidebar-link {{ request()->routeIs('dashboard.vendor.bookings*') ? 'active' : '' }}">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                Booking Masuk
                @if(($menuVendorBookingPendingCount ?? 0) > 0)
                <span class="ml-auto text-[10px] font-bold px-1.5 py-0.5 rounded-full flex-shrink-0 bg-soft-pink text-dark">{{$menuVendorBookingPendingCount }}</span>
                @endif
            </a>
            <a href="{{ route('dashboard.vendor.payments') }}" class="sidebar-link {{ request()->routeIs('dashboard.vendor.payments*') ? 'active' : '' }}">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 1.343-3 3v2h6v-2c0-1.657-1.343-3-3-3z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 11a7 7 0 0114 0v7a1 1 0 01-1 1H6a1 1 0 01-1-1v-7z"/>
                </svg>
                Pembayaran Masuk
                <span class="ml-auto text-[10px] font-bold px-1.5 py-0.5 rounded-full flex-shrink-0 {{ ($menuVendorPaymentPendingCount ?? 0) > 0 ? 'bg-light-sage text-dark' : 'bg-gray-100 text-gray-500' }}">{{ $menuVendorPaymentPendingCount ?? 0 }}</span>
            </a>
            @endif

            @if($user->hasRole(['super_admin', 'admin']))
            <div class="pt-3">
                <p class="text-[10px] uppercase tracking-widest text-gray-400 px-2 mb-2">Admin</p>
            </div>
            <a href="{{ route('dashboard.admin.vendors') }}" class="sidebar-link {{ request()->routeIs('dashboard.admin.vendors*') ? 'active' : '' }}">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 10l9-6 9 6v10a1 1 0 01-1 1h-5v-6H9v6H4a1 1 0 01-1-1V10z"/>
                </svg>
                All Vendor
                @if(($menuAllVendorCount ?? 0) > 0)
                <span class="ml-auto text-[10px] font-bold px-1.5 py-0.5 rounded-full flex-shrink-0 bg-soft-pink text-dark">{{$menuAllVendorCount }}</span>
                @endif
            </a>
            <a href="{{ route('dashboard.vendor.applications') }}" class="sidebar-link {{ request()->routeIs('dashboard.vendor.applications*') ? 'active' : '' }}">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4.5 4.5 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
                Pengajuan Vendor
                @if(($menuVendorApplicationPendingCount ?? 0) > 0)
                <span class="ml-auto text-[10px] font-bold px-1.5 py-0.5 rounded-full flex-shrink-0 bg-soft-pink text-dark">{{$menuVendorApplicationPendingCount }}</span>
                @endif
            </a>
            <a href="{{ route('dashboard.payment.user') }}" class="sidebar-link {{ request()->routeIs('dashboard.payment.user*') ? 'active' : '' }}">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 1.343-3 3v2h6v-2c0-1.657-1.343-3-3-3z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 11a7 7 0 0114 0v7a1 1 0 01-1 1H6a1 1 0 01-1-1v-7z"/>
                </svg>
                Pembayaran User
                @if(($menuPaymentUserPendingCount ?? 0) > 0)
                <span class="ml-auto text-[10px] font-bold px-1.5 py-0.5 rounded-full flex-shrink-0 bg-soft-pink text-dark">{{$menuPaymentUserPendingCount }}</span>
                @endif
            </a>
            <a href="{{ route('chat.admin') }}" class="sidebar-link {{ request()->routeIs('chat.*') ? 'active' : '' }}">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                </svg>
                Live Chat
                <span id="menu-chat-badge" class="ml-auto text-[10px] font-bold px-1.5 py-0.5 rounded-full flex-shrink-0 bg-light-sage text-dark hidden">0</span>
            </a>
            <a href="/admin" class="sidebar-link {{ request()->is('admin*') ? 'active' : '' }}">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><circle cx="12" cy="12" r="3"/>
                </svg>
                Panel Admin
            </a>
            @endif
        </nav>

        {{-- Logout --}}
        <div class="px-3 py-4 border-t border-gray-100">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="sidebar-link group w-full hover:bg-red-50 hover:!text-red-500">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"
                         class="group-hover:stroke-red-400">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                    </svg>
                    Keluar
                </button>
            </form>
        </div>
    </aside>

    {{-- ─── Main Content ───────────────────────────────────────── --}}
    <div class="flex-1 flex flex-col min-w-0">

        {{-- Mobile top bar --}}
        <header class="lg:hidden bg-white border-b border-gray-100 px-4 py-3 flex items-center justify-between gap-3">
            <button type="button" data-open-dashboard-mobile-menu class="p-2 rounded-xl hover:bg-gray-50 transition" aria-label="Menu">
                <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>
            <p class="text-sm font-semibold truncate text-dark">@yield('page-title', 'Dashboard')</p>
            <a href="{{ url('/dashboard') }}" class="w-9 h-9 rounded-full overflow-hidden border border-gray-200 flex items-center justify-center bg-white">
                @if($user->avatar_url)
                    <img src="{{ $user->avatarUrl() }}" alt="{{ $user->name }}" class="w-full h-full object-cover">
                @else
                    <span class="w-full h-full flex items-center justify-center text-xs font-bold text-white bg-accent">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    </span>
                @endif
            </a>
        </header>

        <div id="dashboard-mobile-menu" class="hidden fixed inset-0 z-50 lg:hidden">
            <div class="absolute inset-0 bg-black/40 backdrop-blur-sm"></div>
            <div class="absolute left-0 top-0 h-full w-full max-w-sm bg-white shadow-2xl flex flex-col">
                <div class="px-4 py-4 border-b border-gray-100 flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <span class="text-lg font-extrabold tracking-tight">
                            <span class="text-accent">M</span><span class="text-dark">W</span>
                        </span>
                        <span class="text-xs font-semibold px-2 py-1 rounded-full bg-light-sage text-dark">Dashboard</span>
                    </div>
                    <button type="button" data-close-dashboard-mobile-menu class="p-2 rounded-xl hover:bg-gray-50 transition" aria-label="Tutup">
                        <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                <div class="px-4 py-4 border-b border-gray-100">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full overflow-hidden border border-gray-100 flex-shrink-0">
                            @if($user->avatar_url)
                                <img src="{{ $user->avatarUrl() }}" alt="{{ $user->name }}" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-sm font-bold text-white bg-accent">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </div>
                            @endif
                        </div>
                        <div class="min-w-0">
                            <p class="text-sm font-semibold truncate text-dark">{{ $user->name }}</p>
                            <p class="text-[10px] text-gray-400 truncate">{{ $user->email }}</p>
                        </div>
                    </div>
                </div>

                <nav class="flex-1 px-3 py-4 space-y-0.5 overflow-y-auto">
                    <p class="text-[10px] uppercase tracking-widest text-gray-400 px-2 mb-2">Menu</p>

                    <a href="{{ route('dashboard') }}" class="sidebar-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/>
                            <rect x="14" y="14" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/>
                        </svg>
                        Dashboard
                    </a>

                    <a href="{{ route('dashboard.favorit') }}" class="sidebar-link {{ request()->routeIs('dashboard.favorit') ? 'active' : '' }}">
                        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                        </svg>
                        Favorit
                        @if(($favoriteCount ?? 0) > 0)
                        <span class="ml-auto text-[10px] font-bold px-1.5 py-0.5 rounded-full flex-shrink-0 bg-soft-pink text-dark">{{$favoriteCount }}</span>
                        @endif
                    </a>

                    <a href="{{ route('dashboard.booking') }}" class="sidebar-link {{ request()->routeIs('dashboard.booking*') ? 'active' : '' }}">
                        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        Booking
                        @if(($bookingCount ?? 0) > 0)
                        <span class="ml-auto text-[10px] font-bold px-1.5 py-0.5 rounded-full flex-shrink-0 bg-soft-pink text-dark">{{$bookingCount }}</span>
                        @endif
                    </a>

                    @if($user->hasRole(['super_admin', 'admin']))
                    <a href="{{ route('dashboard.booking.user') }}" class="sidebar-link {{ request()->routeIs('dashboard.booking.user') ? 'active' : '' }}">
                        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        Booking User
                        @if(($bookingUserCount ?? 0) > 0)
                        <span class="ml-auto text-[10px] font-bold px-1.5 py-0.5 rounded-full flex-shrink-0 bg-soft-pink text-dark">{{$bookingUserCount }}</span>
                        @endif
                    </a>
                    @endif

                    <a href="{{ route('dashboard.ulasan') }}" class="sidebar-link {{ request()->routeIs('dashboard.ulasan') ? 'active' : '' }}">
                        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                        </svg>
                        Ulasan Saya
                        @if(($reviewCount ?? 0) > 0)
                        <span class="ml-auto text-[10px] font-bold px-1.5 py-0.5 rounded-full flex-shrink-0 bg-soft-pink text-dark">{{$reviewCount }}</span>
                        @endif
                    </a>

                    <a href="{{ route('dashboard.pengaturan') }}" class="sidebar-link {{ request()->routeIs('dashboard.pengaturan') ? 'active' : '' }}">
                        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4.5 4.5 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                        Pengaturan Akun
                    </a>

            @if($user->hasRole(['super_admin', 'admin', 'vendor']) || $isPemilikPaket)
            <div class="pt-3">
                <p class="text-[10px] uppercase tracking-widest text-gray-400 px-2 mb-2">Vendor</p>
            </div>
            @if($user->hasRole(['vendor']))
            <a href="{{ route('dashboard.vendor.vendors') }}" class="sidebar-link {{ request()->routeIs('dashboard.vendor.vendors') ? 'active' : '' }}">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 10l9-6 9 6v10a1 1 0 01-1 1h-5v-6H9v6H4a1 1 0 01-1-1V10z"/>
                </svg>
                Vendor Saya
                @if(($menuVendorCount ?? 0) > 0)
                <span class="ml-auto text-[10px] font-bold px-1.5 py-0.5 rounded-full flex-shrink-0 bg-soft-pink text-dark">{{$menuVendorCount }}</span>
                @endif
            </a>
            @endif
            @if($isPemilikPaket)
            <a href="{{ route('dashboard.paket') }}" class="sidebar-link {{ request()->routeIs('dashboard.paket') ? 'active' : '' }}">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10"/>
                </svg>
                Paket
                @if($menuPaketCount > 0)
                <span class="ml-auto text-[10px] font-bold px-1.5 py-0.5 rounded-full flex-shrink-0 bg-soft-pink text-dark">{{$menuPaketCount }}</span>
                @endif
            </a>
            @endif
            <a href="{{ route('dashboard.vendor.bookings') }}" class="sidebar-link {{ request()->routeIs('dashboard.vendor.bookings*') ? 'active' : '' }}">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                Booking Masuk
                @if(($menuVendorBookingPendingCount ?? 0) > 0)
                <span class="ml-auto text-[10px] font-bold px-1.5 py-0.5 rounded-full flex-shrink-0 bg-soft-pink text-dark">{{$menuVendorBookingPendingCount }}</span>
                @endif
            </a>
            <a href="{{ route('dashboard.vendor.payments') }}" class="sidebar-link {{ request()->routeIs('dashboard.vendor.payments*') ? 'active' : '' }}">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 1.343-3 3v2h6v-2c0-1.657-1.343-3-3-3z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 11a7 7 0 0114 0v7a1 1 0 01-1 1H6a1 1 0 01-1-1v-7z"/>
                </svg>
                Pembayaran Masuk
                <span class="ml-auto text-[10px] font-bold px-1.5 py-0.5 rounded-full flex-shrink-0 {{ ($menuVendorPaymentPendingCount ?? 0) > 0 ? 'bg-light-sage text-dark' : 'bg-gray-100 text-gray-500' }}">{{ $menuVendorPaymentPendingCount ?? 0 }}</span>
            </a>
            @endif

                    @if($user->hasRole(['super_admin', 'admin']))
                    <div class="pt-3">
                        <p class="text-[10px] uppercase tracking-widest text-gray-400 px-2 mb-2">Admin</p>
                    </div>
                    <a href="{{ route('dashboard.admin.vendors') }}" class="sidebar-link {{ request()->routeIs('dashboard.admin.vendors*') ? 'active' : '' }}">
                        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 10l9-6 9 6v10a1 1 0 01-1 1h-5v-6H9v6H4a1 1 0 01-1-1V10z"/>
                        </svg>
                        All Vendor
                        @if(($menuAllVendorCount ?? 0) > 0)
                        <span class="ml-auto text-[10px] font-bold px-1.5 py-0.5 rounded-full flex-shrink-0 bg-soft-pink text-dark">{{$menuAllVendorCount }}</span>
                        @endif
                    </a>
                    <a href="{{ route('dashboard.vendor.applications') }}" class="sidebar-link {{ request()->routeIs('dashboard.vendor.applications*') ? 'active' : '' }}">
                        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4.5 4.5 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                        Pengajuan Vendor
                        @if(($menuVendorApplicationPendingCount ?? 0) > 0)
                        <span class="ml-auto text-[10px] font-bold px-1.5 py-0.5 rounded-full flex-shrink-0 bg-soft-pink text-dark">{{$menuVendorApplicationPendingCount }}</span>
                        @endif
                    </a>
                    <a href="{{ route('dashboard.payment.user') }}" class="sidebar-link {{ request()->routeIs('dashboard.payment.user*') ? 'active' : '' }}">
                        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 1.343-3 3v2h6v-2c0-1.657-1.343-3-3-3z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 11a7 7 0 0114 0v7a1 1 0 01-1 1H6a1 1 0 01-1-1v-7z"/>
                        </svg>
                        Pembayaran User
                        @if(($menuPaymentUserPendingCount ?? 0) > 0)
                        <span class="ml-auto text-[10px] font-bold px-1.5 py-0.5 rounded-full flex-shrink-0 bg-soft-pink text-dark">{{$menuPaymentUserPendingCount }}</span>
                        @endif
                    </a>
                    <a href="{{ route('chat.admin') }}" class="sidebar-link {{ request()->routeIs('chat.*') ? 'active' : '' }}">
                        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                        </svg>
                        Live Chat
                        <span id="menu-chat-badge-mobile" class="ml-auto text-[10px] font-bold px-1.5 py-0.5 rounded-full flex-shrink-0 bg-light-sage text-dark hidden">0</span>
                    </a>
                    <a href="/admin" class="sidebar-link {{ request()->is('admin*') ? 'active' : '' }}">
                        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><circle cx="12" cy="12" r="3"/>
                        </svg>
                        Panel Admin
                    </a>
                    @endif
                </nav>

                <div class="px-3 py-4 border-t border-gray-100">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="sidebar-link group w-full hover:bg-red-50 hover:!text-red-500">
                            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" class="group-hover:stroke-red-400">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                            </svg>
                            Keluar
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <main class="flex-1 px-6 py-8 w-full max-w-6xl mx-auto">
            @if(session('status'))
                <div id="flash-status" class="mb-5 bg-emerald-50 border border-emerald-200 rounded-xl px-4 py-3 text-sm text-emerald-800 flex items-center justify-between">
                    <span>✓ {{ session('status') }}</span>
                    <button onclick="document.getElementById('flash-status').remove()" class="text-emerald-500 hover:text-emerald-800 ml-4 text-lg leading-none">&times;</button>
                </div>
            @endif
            @yield('content')
        </main>
    </div>

</div>

@include('layout.footer')

<script>
function openDashboardMobileMenu() {
    var menu = document.getElementById('dashboard-mobile-menu');
    if (!menu) return;
    menu.classList.remove('hidden');
    menu.classList.add('flex');
    document.body.style.overflow = 'hidden';
}
function closeDashboardMobileMenu() {
    var menu = document.getElementById('dashboard-mobile-menu');
    if (!menu) return;
    menu.classList.add('hidden');
    menu.classList.remove('flex');
    document.body.style.overflow = '';
}
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') closeDashboardMobileMenu();
});

document.addEventListener('DOMContentLoaded', function () {
    var avatar = document.getElementById('dashboard-sidebar-avatar');
    if (avatar) {
        avatar.addEventListener('error', function () {
            avatar.classList.add('hidden');
            var fallback = document.getElementById('dashboard-sidebar-avatar-fallback');
            if (fallback) {
                fallback.classList.remove('hidden');
                fallback.classList.add('flex');
            }
        });
    }

    document.querySelectorAll('[data-open-dashboard-mobile-menu]').forEach(function (btn) {
        btn.addEventListener('click', function () {
            openDashboardMobileMenu();
        });
    });

    document.querySelectorAll('[data-close-dashboard-mobile-menu]').forEach(function (btn) {
        btn.addEventListener('click', function () {
            closeDashboardMobileMenu();
        });
    });

    var menu = document.getElementById('dashboard-mobile-menu');
    if (menu) {
        menu.addEventListener('click', function (e) {
            if (e.target === menu) closeDashboardMobileMenu();
        });
    }

    document.querySelectorAll('[data-close-sidebar]').forEach(function (btn) {
        btn.addEventListener('click', function () {
            closeDashboardMobileMenu();
        });
    });
    var canChatNotify = (document.body && document.body.dataset)
        ? document.body.dataset.chatCanNotify === '1'
        : false;
    if (canChatNotify) {
        var notifyUrl = (document.body && document.body.dataset)
            ? (document.body.dataset.chatNotifyUrl || '')
            : '';
        var badge = document.getElementById('menu-chat-badge');
        var badgeMobile = document.getElementById('menu-chat-badge-mobile');
        var audioCtx = null;
        var enabled = localStorage.getItem('mw_chat_notif_enabled') === '1';
        var lastId = parseInt(localStorage.getItem('mw_last_guest_msg_id') || '0', 10);

        function setBadgeCount(n) {
            var show = n > 0;
            if (badge) {
                badge.textContent = String(n);
                badge.classList.toggle('hidden', !show);
            }
            if (badgeMobile) {
                badgeMobile.textContent = String(n);
                badgeMobile.classList.toggle('hidden', !show);
            }
        }

        function ensureAudio() {
            if (!enabled) return;
            if (!window.AudioContext && !window.webkitAudioContext) return;
            if (!audioCtx) audioCtx = new (window.AudioContext || window.webkitAudioContext)();
            if (audioCtx && audioCtx.state === 'suspended' && audioCtx.resume) audioCtx.resume();
        }

        function beep() {
            if (!enabled) return;
            ensureAudio();
            if (!audioCtx) return;
            var osc = audioCtx.createOscillator();
            var gain = audioCtx.createGain();
            osc.type = 'sine';
            osc.frequency.value = 880;
            gain.gain.value = 0.0001;
            osc.connect(gain);
            gain.connect(audioCtx.destination);
            osc.start();
            gain.gain.exponentialRampToValueAtTime(0.12, audioCtx.currentTime + 0.02);
            gain.gain.exponentialRampToValueAtTime(0.0001, audioCtx.currentTime + 0.25);
            osc.stop(audioCtx.currentTime + 0.26);
        }

        function notify(title, body, token) {
            if (!enabled) return;
            if (!('Notification' in window)) return;
            if (Notification.permission !== 'granted') return;
            var n = new Notification(title, { body: body });
            n.onclick = function () {
                try { window.focus(); } catch (e) {}
                if (token) window.location.href = '/dashboard/chat/' + token;
                n.close();
            };
        }

        function enableNotifications() {
            enabled = true;
            localStorage.setItem('mw_chat_notif_enabled', '1');
            ensureAudio();
        }

        if (!enabled) {
            var enableOnce = function () {
                enableNotifications();
                document.removeEventListener('click', enableOnce);
                document.removeEventListener('keydown', enableOnce);
            };
            document.addEventListener('click', enableOnce, { once: true });
            document.addEventListener('keydown', enableOnce, { once: true });
        } else {
            ensureAudio();
            if ('Notification' in window && Notification.permission === 'default') {
                var askPermOnce = function () {
                    Notification.requestPermission().catch(function () {});
                    document.removeEventListener('click', askPermOnce);
                    document.removeEventListener('keydown', askPermOnce);
                };
                document.addEventListener('click', askPermOnce, { once: true });
                document.addEventListener('keydown', askPermOnce, { once: true });
            }
        }

        function pollNotify() {
            if (!notifyUrl) return;
            fetch(notifyUrl, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
                .then(function (r) { return r.json(); })
                .then(function (data) {
                    var count = parseInt(data.open_guest_sessions_count || 0, 10);
                    setBadgeCount(count);

                    var latest = parseInt(data.latest_guest_message_id || 0, 10);
                    if (!latest) return;

                    if (!lastId) {
                        lastId = latest;
                        localStorage.setItem('mw_last_guest_msg_id', String(lastId));
                        return;
                    }

                    if (latest > lastId) {
                        lastId = latest;
                        localStorage.setItem('mw_last_guest_msg_id', String(lastId));
                        beep();
                        var name = data.latest_guest_name || 'Pengunjung';
                        notify('Chat masuk', 'Dari ' + name, data.latest_session_token || null);
                    }
                })
                .catch(function () {});
        }

        pollNotify();
        setInterval(pollNotify, 7000);
    }
});
</script>


@yield('scripts')
</body>
</html>
