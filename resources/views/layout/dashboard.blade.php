<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Dashboard — Makna Wedding')</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=poppins:400,500,600,700" rel="stylesheet" />

    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <style>* { font-family: 'Poppins', ui-sans-serif, system-ui, sans-serif; }</style>
    @endif

    <style>
        :root {
            --soft-pink:  #F9D5E5;
            --sage-green: #9CAF88;
            --light-sage: #C8D5B9;
            --cream:      #FAF3E7;
            --dark-gray:  #444444;
        }
        body { background-color: var(--cream); color: var(--dark-gray); }
        .text-accent            { color: var(--sage-green); }
        .hover\:text-accent:hover { color: var(--sage-green); }

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
        .sidebar-link.active { background: var(--light-sage); color: var(--dark-gray); font-weight: 600; }
    </style>
</head>
<body class="bg-cream text-dark">

@include('layout.header')

<div class="flex" style="min-height: calc(100vh - 130px)">

    {{-- ─── Sidebar ────────────────────────────────────────────── --}}
    <aside class="hidden lg:flex w-64 flex-shrink-0 bg-white border-r border-gray-100 flex-col"
           style="min-height: 100%;">

        {{-- Logo --}}
        <div class="hidden px-5 py-5 border-b border-gray-100 flex items-center justify-between">
            <a href="{{ route('home') }}" class="flex items-center gap-2">
                <span class="text-xl font-bold tracking-tight" style="color: var(--dark-gray)">Makna</span>
                <span class="text-[10px] font-semibold px-1.5 py-0.5 rounded-full"
                      style="background: var(--light-sage); color: var(--dark-gray)">WO</span>
            </a>
            <button class="lg:hidden p-1 rounded-lg hover:bg-gray-100" onclick="closeSidebar()">
                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        {{-- User Card --}}
        <div class="px-4 py-4 mt-5 border-b border-gray-100">
            <div class="flex items-center gap-3">
                @if($user->avatar_url)
                <img src="{{ $user->avatarUrl() }}"
                     alt="{{ $user->name }}"
                     onerror="this.style.display='none';this.nextElementSibling.style.display='flex'"
                     class="w-10 h-10 rounded-full object-cover flex-shrink-0 border-2 border-white shadow-sm">
                <div class="w-10 h-10 rounded-full items-center justify-center text-sm font-bold text-white flex-shrink-0"
                     style="background: var(--sage-green); display:none">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                </div>
                @else
                <div class="w-10 h-10 rounded-full flex items-center justify-center text-sm font-bold text-white flex-shrink-0"
                     style="background: var(--sage-green)">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                </div>
                @endif
                <div class="min-w-0">
                    <p class="text-sm font-semibold truncate" style="color: var(--dark-gray)">{{ $user->name }}</p>
                    <p class="text-[10px] text-gray-400 truncate">{{ $user->email }}</p>
                </div>
            </div>
            @if($user->hasRole(['super_admin', 'admin']))
            <div class="mt-2.5">
                <span class="text-[10px] font-semibold px-2 py-0.5 rounded-full"
                      style="background: var(--soft-pink); color: var(--dark-gray)">
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
                <span class="ml-auto text-[10px] font-bold px-1.5 py-0.5 rounded-full flex-shrink-0"
                      style="background: var(--light-sage); color: var(--dark-gray)">{{ $favoriteCount }}</span>
                @endif
            </a>

            <a href="{{ route('dashboard.booking') }}" class="sidebar-link {{ request()->routeIs('dashboard.booking') ? 'active' : '' }}">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                Booking
                @if(($bookingCount ?? 0) > 0)
                <span class="ml-auto text-[10px] font-bold px-1.5 py-0.5 rounded-full flex-shrink-0"
                      style="background: var(--light-sage); color: var(--dark-gray)">{{ $bookingCount }}</span>
                @endif
            </a>

            {{-- <a href="{{ route('vendor') }}" class="sidebar-link">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>
                </svg>
                Jelajahi Vendor
            </a> --}}

            <a href="{{ route('dashboard.ulasan') }}" class="sidebar-link {{ request()->routeIs('dashboard.ulasan') ? 'active' : '' }}">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                </svg>
                Ulasan Saya
                @if($reviewCount > 0)
                <span class="ml-auto text-[10px] font-bold px-1.5 py-0.5 rounded-full flex-shrink-0"
                      style="background: var(--light-sage); color: var(--dark-gray)">{{ $reviewCount }}</span>
                @endif
            </a>

            <a href="{{ route('dashboard.pengaturan') }}" class="sidebar-link {{ request()->routeIs('dashboard.pengaturan') ? 'active' : '' }}">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
                Pengaturan Akun
            </a>

            @if($user->hasRole(['super_admin', 'admin']))
            <div class="pt-3">
                <p class="text-[10px] uppercase tracking-widest text-gray-400 px-2 mb-2">Admin</p>
            </div>
            <a href="/admin" class="sidebar-link">
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
        <header class="lg:hidden bg-white border-b border-gray-100 px-4 py-3 flex items-center gap-3">
            <p class="text-sm font-semibold" style="color: var(--dark-gray)">@yield('page-title', 'Dashboard')</p>
        </header>

        <main class="flex-1 px-6 py-8 w-full max-w-3xl mx-auto">
            @yield('content')
        </main>
    </div>

</div>

@include('layout.footer')



@yield('scripts')

</body>
</html>
