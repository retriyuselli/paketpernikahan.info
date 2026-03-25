@extends('layout.dashboard')

@section('title', 'Dashboard — Makna Wedding')
@section('page-title', 'Dashboard')

@section('content')

    {{-- Welcome --}}
    <div class="mb-8">
        <h1 class="text-2xl font-bold" style="color: var(--dark-gray)">
            Selamat datang, {{ explode(' ', $user->name)[0] }} 👋
        </h1>
        <p class="text-sm text-gray-400 mt-1">Kelola aktivitas dan ulasan pernikahanmu di sini.</p>
    </div>

    {{-- Stats --}}
    <div class="grid grid-cols-2 sm:grid-cols-3 gap-4 mb-8">
        <div class="bg-white rounded-2xl border border-gray-100 p-5">
            <p class="text-[10px] uppercase tracking-widest text-gray-400 mb-1">Ulasan Dikirim</p>
            <p class="text-3xl font-bold" style="color: var(--dark-gray)">{{ $reviewCount }}</p>
            <p class="text-xs text-gray-400 mt-0.5">total ulasan</p>
        </div>
        <div class="bg-white rounded-2xl border border-gray-100 p-5">
            <p class="text-[10px] uppercase tracking-widest text-gray-400 mb-1">Bergabung</p>
            <p class="text-3xl font-bold" style="color: var(--dark-gray)">{{ (int) $user->created_at->diffInDays(now()) }}</p>
            <p class="text-xs text-gray-400 mt-0.5">hari lalu</p>
        </div>
        <div class="bg-white rounded-2xl border border-gray-100 p-5 col-span-2 sm:col-span-1">
            <p class="text-[10px] uppercase tracking-widest text-gray-400 mb-1">Akun Sejak</p>
            <p class="text-lg font-bold leading-tight" style="color: var(--dark-gray)">{{ $user->created_at->translatedFormat('M Y') }}</p>
            <p class="text-xs mt-0.5 {{ $user->email_verified_at ? 'text-green-500' : 'text-amber-500' }}">
                {{ $user->email_verified_at ? '✓ Terverifikasi' : '⚠ Belum verifikasi' }}
            </p>
        </div>
    </div>

    {{-- Quick Links --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-8">
        <a href="{{ route('vendor') }}"
           class="bg-white rounded-2xl border border-gray-100 p-5 flex items-center gap-4 hover:border-gray-200 hover:shadow-sm transition group">
            <div class="w-11 h-11 rounded-xl flex items-center justify-center flex-shrink-0"
                 style="background: var(--light-sage)">
                <svg class="w-5 h-5" style="color: var(--sage-green)" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
            </div>
            <div class="flex-1">
                <p class="text-sm font-bold" style="color: var(--dark-gray)">Jelajahi Vendor</p>
                <p class="text-xs text-gray-400">Temukan WO, venue &amp; fotografer</p>
            </div>
            <svg class="w-4 h-4 text-gray-300 group-hover:text-gray-400 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
        </a>

        <a href="{{ route('home') }}"
           class="bg-white rounded-2xl border border-gray-100 p-5 flex items-center gap-4 hover:border-gray-200 hover:shadow-sm transition group">
            <div class="w-11 h-11 rounded-xl flex items-center justify-center flex-shrink-0"
                 style="background: var(--soft-pink)">
                <svg class="w-5 h-5" style="color: #c87ba0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                </svg>
            </div>
            <div class="flex-1">
                <p class="text-sm font-bold" style="color: var(--dark-gray)">Beranda</p>
                <p class="text-xs text-gray-400">Kembali ke halaman utama</p>
            </div>
            <svg class="w-4 h-4 text-gray-300 group-hover:text-gray-400 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
        </a>
    </div>

@endsection
