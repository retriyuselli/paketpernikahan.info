@extends('layout.app')

@php
    $chatVendorName = $vendor->name ?? 'Makna Wedding';
    $chatVendorBadge = $vendor ? 'Vendor Terverifikasi' : 'Power Chat';
    $chatBackUrl = url()->previous() !== url()->current()
        ? url()->previous()
        : ($package ? route('store.package.show', $package) : route('store'));
    $chatProductImage = $package?->image_url ?: $vendor?->cover_image_url ?: url(config('app.logo_url'));
    $chatProductPriceBase = (int) ($package->price ?? 0);
    $chatProductDiscount = (int) ($package->discount ?? 0);
    $chatProductFinalPrice = max($chatProductPriceBase - $chatProductDiscount, 0);
    $chatProductPrice = $package
        ? 'Rp' . number_format($chatProductDiscount > 0 ? $chatProductFinalPrice : $chatProductPriceBase, 0, ',', '.')
        : null;
    $chatProductName = $package?->name ?? 'Konsultasi Paket Pernikahan';
    $chatVendorInitial = strtoupper(substr($chatVendorName, 0, 1));
    $chatQuickReplies = [
        'Hai, paket ini update?',
        'Bisa kirim detail paket ini?',
        'Apakah ada promo kak?',
        'Terima kasih',
    ];
    $chatCurrentPkgData = $package ? [
        'id'    => $package->id,
        'name'  => $chatProductName,
        'image' => $chatProductImage,
        'price' => $chatProductPrice,
        'url'   => route('store.package.show', $package),
    ] : null;
@endphp

@section('title', 'Chat dengan ' . $chatVendorName . ' - Makna Wedding')
@section('meta-description', 'Mulai chat langsung dengan ' . $chatVendorName . ' untuk tanya detail paket dan ketersediaan.')
@section('body-class', 'bg-white text-dark chat-public-page')

@section('extra-head')
<style>
    .chat-public-page {
        --chat-header-height: 5rem;
        --chat-safe-bottom: calc(env(safe-area-inset-bottom, 0px) + 1rem);
        background: #f6f7fb;
    }

    .public-chat-shell {
        min-height: 100dvh;
        background:
            radial-gradient(circle at top left, rgba(155, 184, 128, 0.12), transparent 30%),
            radial-gradient(circle at top right, rgba(167, 204, 225, 0.16), transparent 22%),
            linear-gradient(180deg, #ffffff 0%, #f7f8fc 44%, #fbfbfd 100%);
    }

    .public-chat-header {
        height: var(--chat-header-height);
        backdrop-filter: blur(14px);
        background: rgba(255, 255, 255, 0.92);
    }

    .public-chat-main {
        position: relative;
        min-height: 100dvh;
        padding-top: calc(var(--chat-header-height) + 0.75rem);
        padding-bottom: 34rem; /* fallback; JS will override with measured footer height */
    }

    .public-chat-wallpaper {
        position: absolute;
        inset: calc(var(--chat-header-height) + 0.75rem) 0 0;
        overflow: hidden;
        pointer-events: none;
    }

    .public-chat-wallpaper::before,
    .public-chat-wallpaper::after {
        content: '';
        position: absolute;
        border-radius: 9999px;
        filter: blur(6px);
    }

    .public-chat-wallpaper::before {
        width: 16rem;
        height: 16rem;
        left: -5rem;
        top: 12rem;
        background: rgba(226, 232, 240, 0.35);
    }

    .public-chat-wallpaper::after {
        width: 18rem;
        height: 18rem;
        right: -7rem;
        bottom: 14rem;
        background: rgba(209, 250, 229, 0.26);
    }

    .public-chat-wallpaper-ghost {
        position: absolute;
        color: rgba(148, 163, 184, 0.18);
        pointer-events: none;
    }

    .public-chat-scroll {
        position: relative;
        z-index: 1;
        display: flex;
        flex-direction: column;
        gap: 0.85rem;
        width: 100%;
        max-width: 48rem;
        margin: 0 auto;
        padding: 0 1rem;
    }

    .public-chat-empty {
        min-height: calc(100dvh - var(--chat-header-height) - 25rem);
        display: flex;
        align-items: flex-end;
        justify-content: center;
        padding-bottom: 2rem;
        text-align: center;
        color: #94a3b8;
    }

    .public-chat-footer {
        position: fixed;
        left: 0;
        right: 0;
        bottom: 0;
        z-index: 40;
        padding-bottom: var(--chat-safe-bottom);
        background: linear-gradient(180deg, rgba(246, 247, 251, 0) 0%, rgba(246, 247, 251, 0.88) 18%, #f6f7fb 42%);
    }

    .public-chat-bubble {
        max-width: min(86%, 22rem);
        border-radius: 1.5rem;
        padding: 0.85rem 1rem;
        line-height: 1.45;
        font-size: 0.94rem;
        box-shadow: 0 12px 30px rgba(15, 23, 42, 0.08);
    }

    .public-chat-bubble--admin {
        border-top-left-radius: 0.5rem;
        background: rgba(255, 255, 255, 0.96);
        color: #1f2937;
    }

    .public-chat-bubble--guest {
        border-top-right-radius: 0.5rem;
        background: #cdd8ee;
        color: #334155;
    }

    .public-chat-composer {
        min-height: 3.75rem;
        border: 1px solid #dbe4f0;
        background: rgba(255, 255, 255, 0.98);
        box-shadow: 0 16px 40px rgba(148, 163, 184, 0.16);
    }

    .public-chat-scrollbar {
        scrollbar-width: none;
    }

    .public-chat-scrollbar::-webkit-scrollbar {
        display: none;
    }

    .public-chat-name-sheet {
        background: rgba(15, 23, 42, 0.35);
        backdrop-filter: blur(6px);
    }

    #chat-promo-banner {
        transition: opacity 0.55s ease, max-height 0.55s ease, margin-top 0.55s ease,
                    padding-top 0.55s ease, padding-bottom 0.55s ease, border-width 0.55s ease;
        overflow: hidden;
        max-height: 12rem;
    }

    #chat-promo-banner.chat-banner--fadeout {
        opacity: 0;
        max-height: 0;
        margin-top: 0;
        padding-top: 0;
        padding-bottom: 0;
        border-width: 0;
    }

    @media (min-width: 768px) {
        .public-chat-main {
            padding-bottom: 28rem;
        }

        .public-chat-footer {
            padding-bottom: 1.25rem;
        }
    }
</style>
@endsection

@section('content')
    <div class="public-chat-shell">
        <header class="public-chat-header fixed inset-x-0 top-0 z-50 border-b border-slate-100">
            <div class="mx-auto flex h-full w-full max-w-3xl items-center gap-3 px-4">
                <a href="{{ $chatBackUrl }}" class="inline-flex h-10 w-10 items-center justify-center rounded-full text-slate-700 transition hover:bg-slate-100" aria-label="Kembali">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                </a>

                <div class="flex min-w-0 flex-1 items-center gap-3">
                    @if($vendor?->cover_image_url)
                        <img src="{{ $vendor->cover_image_url }}" alt="{{ $chatVendorName }}" class="h-11 w-11 rounded-full object-cover ring-1 ring-slate-200">
                    @else
                        <div class="flex h-11 w-11 items-center justify-center rounded-full bg-slate-900 text-sm font-bold text-white">{{ $chatVendorInitial }}</div>
                    @endif

                    <div class="min-w-0">
                        <div class="flex items-center gap-2">
                            <span class="inline-flex items-center gap-1 rounded-lg bg-emerald-100 px-2 py-1 text-[11px] font-bold text-emerald-700">
                                <svg class="h-3.5 w-3.5" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M10 1.667 2.5 4.583v4.375c0 4.55 3.183 8.808 7.5 9.375 4.317-.567 7.5-4.825 7.5-9.375V4.583L10 1.667Zm3.13 6.946-3.47 3.47a.833.833 0 0 1-1.178 0L6.87 10.47a.833.833 0 1 1 1.178-1.178l1.024 1.024 2.88-2.88a.833.833 0 0 1 1.178 1.178Z"/>
                                </svg>
                                {{ $chatVendorBadge }}
                            </span>
                            <span class="hidden items-center gap-1 text-[11px] font-medium text-slate-400 sm:inline-flex">
                                <span class="h-2 w-2 rounded-full bg-emerald-400"></span>
                                Online
                            </span>
                        </div>
                        <p class="truncate text-[1.05rem] font-bold text-slate-800">{{ $chatVendorName }}</p>
                    </div>
                </div>

                <button type="button" class="inline-flex h-10 w-10 items-center justify-center rounded-full text-slate-500 transition hover:bg-slate-100" aria-label="Opsi chat">
                    <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10 4.167a1.25 1.25 0 1 0 0-2.5 1.25 1.25 0 0 0 0 2.5Zm0 7.083a1.25 1.25 0 1 0 0-2.5 1.25 1.25 0 0 0 0 2.5Zm0 7.083a1.25 1.25 0 1 0 0-2.5 1.25 1.25 0 0 0 0 2.5Z"/>
                    </svg>
                </button>
            </div>
        </header>

        @if($sessionNotFound ?? false)
        <main class="flex min-h-dvh flex-col items-center justify-center px-6 pt-[var(--chat-header-height)] pb-12">
            <div class="flex flex-col items-center gap-4 text-center">
                <div class="flex h-20 w-20 items-center justify-center rounded-full bg-slate-100">
                    <svg class="h-10 w-10 text-slate-400" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 8.511c.884.284 1.5 1.128 1.5 2.097v4.286c0 1.136-.847 2.1-1.98 2.193-.34.027-.68.052-1.02.072v3.091l-3-3c-1.354 0-2.694-.055-4.02-.163a2.115 2.115 0 0 1-.825-.242m9.345-8.334a2.126 2.126 0 0 0-.476-.095 48.64 48.64 0 0 0-8.048 0c-1.131.094-1.976 1.057-1.976 2.192v4.286c0 .837.46 1.58 1.155 1.951m9.345-8.334V6.637c0-1.621-1.152-3.026-2.76-3.235A48.455 48.455 0 0 0 11.25 3c-2.115 0-4.198.137-6.24.402-1.608.209-2.76 1.614-2.76 3.235v6.226c0 1.621 1.152 3.026 2.76 3.235.577.075 1.157.14 1.74.194V21l4.155-4.155"/>
                    </svg>
                </div>
                <div>
                    <p class="text-lg font-bold text-slate-800">Percakapan tidak ditemukan</p>
                    <p class="mt-1 text-sm leading-6 text-slate-500">Percakapan ini sudah dihapus atau tidak tersedia.<br>Kamu bisa kembali dan memulai percakapan baru.</p>
                </div>
                <a href="{{ route('home') }}" class="inline-flex items-center gap-2 rounded-2xl bg-slate-900 px-6 py-3 text-sm font-semibold text-white transition hover:opacity-90">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                    Kembali ke Beranda
                </a>
            </div>
        </main>
        @else
        <main class="public-chat-main">
            <div class="public-chat-wallpaper" aria-hidden="true">
                {{-- Shape 1: wedding rings (two interlocking circles), top-left --}}
                <div class="public-chat-wallpaper-ghost" style="width: 9rem; height: 6rem; top: 2.5rem; left: 0.5rem;">
                    <svg viewBox="0 0 130 80" fill="none" stroke="currentColor" stroke-width="5" stroke-linecap="round" xmlns="http://www.w3.org/2000/svg" class="w-full h-full">
                        <circle cx="48" cy="40" r="30"/>
                        <circle cx="82" cy="40" r="30"/>
                    </svg>
                </div>
                {{-- Shape 2: 6-petal flower, top-right small --}}
                <div class="public-chat-wallpaper-ghost" style="width: 5rem; height: 5rem; top: 6.5rem; right: 1rem;">
                    <svg viewBox="0 0 100 100" fill="none" stroke="currentColor" stroke-width="3.5" xmlns="http://www.w3.org/2000/svg" class="w-full h-full">
                        <ellipse cx="50" cy="24" rx="9" ry="20" transform="rotate(0, 50, 50)"/>
                        <ellipse cx="50" cy="24" rx="9" ry="20" transform="rotate(60, 50, 50)"/>
                        <ellipse cx="50" cy="24" rx="9" ry="20" transform="rotate(120, 50, 50)"/>
                        <ellipse cx="50" cy="24" rx="9" ry="20" transform="rotate(180, 50, 50)"/>
                        <ellipse cx="50" cy="24" rx="9" ry="20" transform="rotate(240, 50, 50)"/>
                        <ellipse cx="50" cy="24" rx="9" ry="20" transform="rotate(300, 50, 50)"/>
                        <circle cx="50" cy="50" r="9"/>
                    </svg>
                </div>
                {{-- Shape 3: diamond / gem outline, center --}}
                <div class="public-chat-wallpaper-ghost" style="width: 9rem; height: 10rem; top: 15rem; left: 50%; transform: translateX(-50%);">
                    <svg viewBox="0 0 100 115" fill="none" stroke="currentColor" stroke-width="3.5" stroke-linejoin="round" xmlns="http://www.w3.org/2000/svg" class="w-full h-full">
                        <path d="M50 6 L94 44 L50 109 L6 44 Z"/>
                        <path d="M6 44 L94 44"/>
                        <path d="M50 6 L22 44 L50 109"/>
                        <path d="M50 6 L78 44 L50 109"/>
                        <path d="M22 44 L36 6"/>
                        <path d="M78 44 L64 6"/>
                    </svg>
                </div>
                {{-- Shape 4: heart outline, bottom-left --}}
                <div class="public-chat-wallpaper-ghost" style="width: 6rem; height: 5.5rem; bottom: 16rem; left: 0.5rem;">
                    <svg viewBox="0 0 100 90" fill="none" stroke="currentColor" stroke-width="4.5" stroke-linejoin="round" stroke-linecap="round" xmlns="http://www.w3.org/2000/svg" class="w-full h-full">
                        <path d="M50 80 C50 80 8 52 8 26 C8 13 18 7 30 7 C38 7 45 12 50 18 C55 12 62 7 70 7 C82 7 92 13 92 26 C92 52 50 80 50 80Z"/>
                    </svg>
                </div>
                {{-- Shape 5: infinity / eternal knot, bottom-right --}}
                <div class="public-chat-wallpaper-ghost" style="width: 9rem; height: 5rem; bottom: 20rem; right: 0.5rem;">
                    <svg viewBox="0 0 130 60" fill="none" stroke="currentColor" stroke-width="4.5" stroke-linecap="round" xmlns="http://www.w3.org/2000/svg" class="w-full h-full">
                        <path d="M65 30 C65 30 54 8 32 8 C10 8 10 52 32 52 C54 52 65 30 65 30 C65 30 76 8 98 8 C120 8 120 52 98 52 C76 52 65 30 65 30Z"/>
                    </svg>
                </div>
                {{-- Shape 6: 4-petal flower small, mid-right --}}
                <div class="public-chat-wallpaper-ghost" style="width: 4rem; height: 4rem; top: 30rem; right: 0.75rem;">
                    <svg viewBox="0 0 80 80" fill="none" stroke="currentColor" stroke-width="3.5" xmlns="http://www.w3.org/2000/svg" class="w-full h-full">
                        <ellipse cx="40" cy="19" rx="8" ry="17" transform="rotate(0, 40, 40)"/>
                        <ellipse cx="40" cy="19" rx="8" ry="17" transform="rotate(90, 40, 40)"/>
                        <ellipse cx="40" cy="19" rx="8" ry="17" transform="rotate(180, 40, 40)"/>
                        <ellipse cx="40" cy="19" rx="8" ry="17" transform="rotate(270, 40, 40)"/>
                        <circle cx="40" cy="40" r="7"/>
                    </svg>
                </div>
            </div>

            <div id="public-chat-messages" class="public-chat-scroll">

                {{-- Context block: muncul di atas chat saat percakapan dimulai --}}
                <div id="chat-context-top"
                     style="display:none; opacity:0; transition: opacity 0.5s ease;"
                     class="mb-4 w-full">
                    <p class="text-center text-[10px] leading-5 text-slate-400 px-1">
                        Hati-hati penipuan! Mohon tidak bertransaksi di luar platform resmi dan tidak memberikan data pribadi kepada penjual, seperti nomor HP dan alamat. Tetap berinteraksi melalui Makna Wedding, ya.
                        <a href="{{ route('home') }}" class="font-bold text-emerald-600">Baca Panduan Keamanan.</a>
                    </p>
                    <div class="mt-2 rounded-2xl border border-cyan-300/70 bg-cyan-50/80 px-4 py-3 shadow-sm">
                        <div class="flex items-start gap-3">
                            <div class="mt-0.5 flex h-7 w-7 shrink-0 items-center justify-center rounded-full border border-cyan-300 text-cyan-600">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M12 8v.01"/>
                                </svg>
                            </div>
                            <div class="min-w-0 flex-1">
                                <p class="text-[12px] leading-5 text-slate-700">Ada konsultasi gratis untuk 1 sesi percakapan di vendor ini.</p>
                                <p class="text-xs font-bold text-emerald-600">Cek info terbaru</p>
                            </div>
                        </div>
                    </div>
                    @if($package)
                        <div class="mt-3 rounded-2xl border border-slate-200 bg-white/95 p-3 shadow-xl shadow-slate-200/70">
                            <div class="flex items-center gap-3">
                                <a id="chat-ctx-pkg-link" href="{{ route('store.package.show', $package) }}" class="shrink-0">
                                    <img id="chat-ctx-pkg-img" src="{{ $chatProductImage }}" alt="{{ $chatProductName }}" class="h-14 w-14 rounded-2xl object-cover bg-slate-100">
                                </a>
                                <div class="min-w-0 flex-1">
                                    <p id="chat-ctx-pkg-name" class="truncate text-sm font-semibold leading-tight text-slate-800">{{ $chatProductName }}</p>
                                    <p id="chat-ctx-pkg-price" class="mt-0.5 text-base font-bold text-slate-900" style="{{ $chatProductPrice ? '' : 'display:none;' }}">{{ $chatProductPrice }}</p>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>

                <div id="public-chat-empty" class="public-chat-empty">
                    @unless($isGuest)
                    <div class="max-w-sm">
                        <p class="text-sm font-medium text-slate-400">Percakapan akan muncul di sini. Kirim pesan pertama untuk mulai tanya detail paket, jadwal, atau konsultasi.</p>
                    </div>
                    @endunless
                </div>
            </div>
        </main>

        <div class="public-chat-footer">
            <div class="mx-auto w-full max-w-3xl px-4">
                <p id="chat-footer-notice" class="mx-auto max-w-2xl text-center text-[10px] leading-5 text-slate-400 sm:text-[11px]"
                   style="transition: opacity 0.5s ease, max-height 0.5s ease; overflow:hidden; max-height:6rem;">
                    Hati-hati penipuan! Mohon tidak bertransaksi di luar platform resmi dan tidak memberikan data pribadi kepada penjual, seperti nomor HP dan alamat. Tetap berinteraksi melalui Makna Wedding, ya.
                    <a href="{{ route('home') }}" class="font-bold text-emerald-600">Baca Panduan Keamanan.</a>
                </p>


                @if($package)
                    <div id="chat-product-card" class="relative mt-4 w-full max-w-md rounded-2xl border border-slate-200 bg-white/95 p-3 shadow-xl shadow-slate-200/70">
                        <button type="button" id="chat-product-dismiss"
                                class="absolute -right-2.5 -top-2.5 flex h-6 w-6 items-center justify-center rounded-full bg-slate-200 text-slate-500 shadow-sm transition hover:bg-slate-300"
                                aria-label="Tutup kartu produk">
                            <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                        <div class="flex items-center gap-3">
                            <a href="{{ route('store.package.show', $package) }}" class="shrink-0">
                                <img src="{{ $chatProductImage }}" alt="{{ $chatProductName }}" class="h-16 w-16 rounded-2xl object-cover bg-slate-100">
                            </a>
                            <div class="min-w-0 flex-1">
                                <p class="truncate text-sm font-semibold leading-tight text-slate-800">{{ $chatProductName }}</p>
                                @if($chatProductPrice)
                                    <p class="mt-1 text-base font-bold text-slate-900">{{ $chatProductPrice }}</p>
                                @endif
                            </div>
                        </div>
                    </div>
                @endif

                @if($isGuest)
                {{-- ── Login gate (guest) ─────────────────────────────── --}}
                <div class="mt-3 rounded-2xl border border-slate-200 bg-white/98 px-5 py-5 shadow-xl shadow-slate-200/60">
                    <div class="flex items-start gap-3">
                        <div class="mt-0.5 flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-amber-50 ring-1 ring-amber-200">
                            <svg class="h-4.5 w-4.5 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z"/>
                            </svg>
                        </div>
                        <div class="min-w-0 flex-1">
                            <p class="text-sm font-semibold text-slate-800">Masuk untuk mulai chat</p>
                            <p class="mt-0.5 text-xs leading-5 text-slate-500">Login atau daftar gratis untuk berkonsultasi langsung dengan <span class="font-semibold text-slate-700">{{ $chatVendorName }}</span>.</p>
                        </div>
                    </div>
                    <div class="mt-4">
                        <a href="{{ route('login', ['redirect' => request()->getRequestUri()]) }}"
                           class="inline-flex w-full items-center justify-center rounded-2xl bg-slate-900 px-4 py-3 text-sm font-semibold text-white transition hover:opacity-90">
                            Masuk
                        </a>
                    </div>
                </div>
                @else
                {{-- ── Quick replies ───────────────────────────────────── --}}
                <div class="public-chat-scrollbar mt-4 flex gap-2 overflow-x-auto px-1 pb-1">
                    @foreach($chatQuickReplies as $quickReply)
                        <button type="button" data-quick-reply="{{ $quickReply }}" class="shrink-0 rounded-full bg-white px-4 py-3 text-xs font-medium text-slate-700 shadow-sm ring-1 ring-slate-200 transition hover:bg-slate-50">
                            {{ $quickReply }}
                        </button>
                    @endforeach
                </div>

                {{-- ── Composer ────────────────────────────────────────── --}}
                <div class="mt-3 flex items-center gap-2 sm:gap-3">
                    <button type="button" class="inline-flex h-12 w-12 items-center justify-center rounded-full bg-white text-slate-700 shadow-sm ring-1 ring-slate-200 transition hover:bg-slate-50" aria-label="Fitur lampiran segera hadir">
                        <svg class="h-7 w-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v14m7-7H5"/>
                        </svg>
                    </button>

                    <div class="public-chat-composer flex min-w-0 flex-1 items-center gap-3 rounded-full px-4">
                        <input id="public-chat-input" type="text" placeholder="Tulis pesan..." class="h-12 min-w-0 flex-1 bg-transparent text-base text-slate-700 outline-none placeholder:text-slate-400">
                        <button type="button" class="inline-flex h-9 w-9 items-center justify-center rounded-full text-slate-400 transition hover:bg-slate-100 hover:text-slate-600" aria-label="Emoji segera hadir">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 0 1-5.656 0M9 10h.01M15 10h.01M12 21a9 9 0 1 0 0-18 9 9 0 0 0 0 18Z"/>
                            </svg>
                        </button>
                    </div>

                    <button id="public-chat-send" type="button" class="inline-flex h-12 w-12 items-center justify-center rounded-full bg-slate-300 text-white transition hover:bg-slate-400 disabled:cursor-not-allowed">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 12l14-7-3 7 3 7-14-7Z"/>
                        </svg>
                    </button>
                </div>

                <p id="public-chat-closed" class="hidden pt-3 text-center text-xs font-medium text-rose-500">Sesi ini sudah ditutup admin. Silakan buka percakapan baru bila perlu bantuan lagi.</p>
                @endif
            </div>
        </div>
    </div>

        @endif

    {{-- Template kartu paket baru (di-clone oleh JS saat paket berbeda) --}}
    @if($package)
    <div id="chat-pkg-divider-tpl" style="display:none;" aria-hidden="true" class="my-4 w-full px-2">
        <div class="flex items-center gap-2 mb-3">
            <hr class="flex-1 border-slate-200">
            <span class="shrink-0 rounded-full bg-slate-100 px-3 py-1 text-[11px] font-medium text-slate-400">Menanyakan paket baru</span>
            <hr class="flex-1 border-slate-200">
        </div>
        <div class="rounded-2xl border border-slate-200 bg-white/95 p-3 shadow-sm">
            <div class="flex items-center gap-3">
                <a href="{{ route('store.package.show', $package) }}" class="shrink-0">
                    <img src="{{ $chatProductImage }}" alt="{{ $chatProductName }}" class="h-14 w-14 rounded-2xl object-cover bg-slate-100">
                </a>
                <div class="min-w-0 flex-1">
                    <p class="text-[11px] font-medium text-slate-400 mb-0.5">{{ $chatVendorName }}</p>
                    <p class="truncate text-sm font-semibold leading-tight text-slate-800">{{ $chatProductName }}</p>
                    @if($chatProductPrice)
                        <p class="mt-0.5 text-base font-bold text-slate-900">{{ $chatProductPrice }}</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @endif

    <div id="public-chat-name-sheet" class="public-chat-name-sheet fixed inset-0 z-60 hidden items-end justify-center p-4 sm:items-center">
        <div id="public-chat-name-backdrop" class="absolute inset-0"></div>
        <div class="relative z-10 w-full max-w-md rounded-4xl bg-white p-6 shadow-2xl">
            <p class="text-lg font-bold text-slate-900">Mulai chat</p>
            <p class="mt-2 text-sm leading-6 text-slate-500">Sebelum mengirim pesan, masukkan nama yang ingin ditampilkan ke admin.</p>
            <input id="public-chat-name-input" type="text" maxlength="100" placeholder="Nama kamu" class="mt-4 h-12 w-full rounded-2xl border border-slate-200 px-4 text-sm outline-none transition focus:border-slate-400">
            <p id="public-chat-name-error" class="mt-2 hidden text-xs font-medium text-rose-500">Nama tidak boleh kosong.</p>
            <div class="mt-5 flex gap-3">
                <button id="public-chat-name-cancel" type="button" class="inline-flex flex-1 items-center justify-center rounded-2xl border border-slate-200 px-4 py-3 text-sm font-semibold text-slate-600 transition hover:bg-slate-50">Nanti saja</button>
                <button id="public-chat-name-save" type="button" class="inline-flex flex-1 items-center justify-center rounded-2xl bg-slate-900 px-4 py-3 text-sm font-semibold text-white transition hover:opacity-90">Lanjutkan</button>
            </div>
        </div>
    </div>

    <script>
        (function () {
            if (@json($isGuest)) { return; }

            var CSRF = document.querySelector('meta[name="csrf-token"]')?.content ?? '';
            var messagesEl = document.getElementById('public-chat-messages');
            var emptyEl = document.getElementById('public-chat-empty');
            var inputEl = document.getElementById('public-chat-input');
            var sendBtn = document.getElementById('public-chat-send');
            var closedEl = document.getElementById('public-chat-closed');
            var nameSheet = document.getElementById('public-chat-name-sheet');
            var nameBackdrop = document.getElementById('public-chat-name-backdrop');
            var nameInput = document.getElementById('public-chat-name-input');
            var nameError = document.getElementById('public-chat-name-error');
            var nameCancelBtn = document.getElementById('public-chat-name-cancel');
            var nameSaveBtn = document.getElementById('public-chat-name-save');
            var quickReplyButtons = document.querySelectorAll('[data-quick-reply]');
            var packageId = @json($package?->id);
            var vendorId = @json($vendor?->id);
            var token = @json($session?->session_token);
            var guestName = @json($guestName);
            var currentPkgData = @json($chatCurrentPkgData);
            var pollTimer = null;
            var lastId = 0;
            var pendingAction = null;
            var tokenStorageKey = vendorId ? 'lc_token_v' + vendorId : 'lc_token';
            var lastIdStorageKey = vendorId ? 'lc_last_id_v' + vendorId : 'lc_last_id';
            var guestNameStorageKey = 'lc_guest_name';
            var packageStorageKey = vendorId ? 'lc_pkg_v' + vendorId : 'lc_package_id';
            var pkgDataStorageKey = vendorId ? 'lc_pkg_data_v' + vendorId : 'lc_pkg_data';
            var isDifferentPackage = false;
            var dividerInjected = false;
            var APP_TZ = 'Asia/Jakarta';
            var timeFormatter = null;

            try {
                timeFormatter = new Intl.DateTimeFormat('id-ID', {
                    timeZone: APP_TZ,
                    hour: '2-digit',
                    minute: '2-digit',
                    hour12: false,
                });
            } catch (error) {
                timeFormatter = null;
            }

            try {
                if (!guestName) {
                    guestName = sessionStorage.getItem(guestNameStorageKey) || '';
                }
            } catch (error) {}

            function esc(value) {
                return String(value)
                    .replace(/&/g, '&amp;')
                    .replace(/</g, '&lt;')
                    .replace(/>/g, '&gt;')
                    .replace(/"/g, '&quot;')
                    .replace(/'/g, '&#039;');
            }

            function fmtTime(iso) {
                var date = new Date(iso);
                if (timeFormatter) {
                    return timeFormatter.format(date);
                }

                return date.getHours().toString().padStart(2, '0') + ':' + date.getMinutes().toString().padStart(2, '0');
            }

            function scrollBottom() {
                window.requestAnimationFrame(function () {
                    window.scrollTo({ top: document.body.scrollHeight, behavior: 'smooth' });
                });
            }

            function syncSendState() {
                var disabled = !!closedEl && !closedEl.classList.contains('hidden');
                if (sendBtn) {
                    sendBtn.disabled = disabled;
                    sendBtn.classList.toggle('bg-slate-300', disabled || !inputEl.value.trim());
                    sendBtn.classList.toggle('bg-slate-900', !disabled && !!inputEl.value.trim());
                }
            }

            function removeMessages() {
                Array.from(messagesEl.querySelectorAll('[data-chat-message="1"]')).forEach(function (element) {
                    element.remove();
                });
            }

            function updateEmptyState() {
                var hasMessages = messagesEl.querySelector('[data-chat-message="1"]') !== null;
                if (emptyEl) {
                    emptyEl.style.display = hasMessages ? 'none' : 'flex';
                }
            }

            function appendMessage(message) {
                if (!message || messagesEl.querySelector('[data-message-id="' + message.id + '"]')) {
                    return;
                }

                var isAdmin = message.sender === 'admin';
                var wrap = document.createElement('div');
                wrap.dataset.chatMessage = '1';
                wrap.dataset.messageId = String(message.id);
                wrap.className = 'flex ' + (isAdmin ? 'justify-start' : 'justify-end');

                var adminLabel = isAdmin && message.admin_name
                    ? '<span class="mb-1 block text-[11px] font-medium text-slate-400">' + esc(message.admin_name) + '</span>'
                    : '';

                wrap.innerHTML = '<div class="public-chat-bubble ' + (isAdmin ? 'public-chat-bubble--admin' : 'public-chat-bubble--guest') + '">'
                    + adminLabel
                    + '<div class="break-words">' + esc(message.message) + '</div>'
                    + '<span class="mt-2 block text-[11px] ' + (isAdmin ? 'text-slate-400' : 'text-slate-500 text-right') + '">' + fmtTime(message.created_at) + '</span>'
                    + '</div>';

                messagesEl.appendChild(wrap);
                lastId = Math.max(lastId, parseInt(message.id, 10) || 0);

                try {
                    sessionStorage.setItem(lastIdStorageKey, String(lastId));
                } catch (error) {}

                updateEmptyState();
            }

            function setClosed(isClosed) {
                if (!closedEl || !inputEl) {
                    return;
                }

                closedEl.classList.toggle('hidden', !isClosed);
                inputEl.disabled = isClosed;
                if (isClosed) {
                    inputEl.placeholder = 'Sesi sudah ditutup';
                    stopPolling();
                } else {
                    inputEl.placeholder = 'Tulis pesan...';
                }
                syncSendState();
            }

            function buildQuery() {
                return packageId ? '?package=' + encodeURIComponent(packageId) : '';
            }

            function buildChatUrl(nextToken) {
                return nextToken ? '/chat/' + nextToken + buildQuery() : '/chat' + buildQuery();
            }

            function fetchJson(url, options) {
                return fetch(url, options).then(function (response) {
                    if (!response.ok) {
                        throw new Error('Request failed');
                    }

                    return response.json();
                });
            }

            function syncSessionContext() {
                if (!token || !packageId) {
                    return Promise.resolve();
                }

                return fetchJson('/chat/' + token + '/context', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': CSRF,
                        'X-Requested-With': 'XMLHttpRequest',
                    },
                    body: JSON.stringify({
                        vendor_id: vendorId || null,
                        package_id: packageId || null,
                    }),
                }).catch(function () {
                    return null;
                });
            }

            function loadMessages(afterId) {
                if (!token) {
                    return Promise.resolve();
                }

                return fetchJson('/chat/' + token + '/messages?after=' + afterId, {
                    headers: { 'X-Requested-With': 'XMLHttpRequest' },
                }).then(function (data) {
                    if (afterId === 0) {
                        removeMessages();
                        lastId = 0;
                    }

                    (data.messages || []).forEach(function (message) {
                        appendMessage(message);
                    });

                    setClosed(data.status === 'closed');
                    updateEmptyState();
                    return data;
                });
            }

            function startPolling() {
                stopPolling();
                if (!token) {
                    return;
                }

                pollTimer = window.setInterval(function () {
                    loadMessages(lastId).catch(function () {
                        stopPolling();
                    });
                }, 3000);
            }

            function stopPolling() {
                if (pollTimer) {
                    window.clearInterval(pollTimer);
                }
                pollTimer = null;
            }

            function hideNameSheet() {
                nameSheet.classList.add('hidden');
                nameSheet.classList.remove('flex');
                nameError.classList.add('hidden');
            }

            function showNameSheet(action) {
                pendingAction = action;
                nameSheet.classList.remove('hidden');
                nameSheet.classList.add('flex');
                nameInput.value = guestName || '';
                window.setTimeout(function () {
                    nameInput.focus();
                }, 50);
            }

            function runPendingAction() {
                if (typeof pendingAction === 'function') {
                    var action = pendingAction;
                    pendingAction = null;
                    action();
                }
            }

            function ensureSession(callback) {
                if (token) {
                    callback();
                    return;
                }

                var continueStart = function () {
                    fetchJson('/chat/start', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': CSRF,
                            'X-Requested-With': 'XMLHttpRequest',
                        },
                        body: JSON.stringify({ guest_name: guestName, vendor_id: vendorId || null, package_id: packageId || null }),
                    })
                        .then(function (data) {
                            token = data.token;

                            try {
                                sessionStorage.setItem(tokenStorageKey, token);
                                sessionStorage.setItem(lastIdStorageKey, '0');
                                sessionStorage.setItem(guestNameStorageKey, guestName);
                                sessionStorage.setItem(packageStorageKey, String(packageId || ''));
                                sessionStorage.setItem(pkgDataStorageKey, currentPkgData ? JSON.stringify(currentPkgData) : '');
                            } catch (error) {}

                            history.replaceState({}, '', buildChatUrl(token));
                            return syncSessionContext().then(function () {
                                return loadMessages(0);
                            });
                        })
                        .then(function () {
                            startPolling();
                            scrollBottom();
                            callback();
                        })
                        .catch(function () {
                            syncSendState();
                        });
                };

                if (guestName) {
                    continueStart();
                    return;
                }

                showNameSheet(continueStart);
            }

            function sendMessage(presetMessage) {
                var message = typeof presetMessage === 'string' ? presetMessage.trim() : inputEl.value.trim();
                if (!message) {
                    return;
                }

                if (!isDifferentPackage) {
                    showContextTop();
                } else {
                    injectPackageDivider();
                }

                ensureSession(function () {
                    fetchJson('/chat/' + token + '/send', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': CSRF,
                            'X-Requested-With': 'XMLHttpRequest',
                        },
                        body: JSON.stringify({ message: message, package_id: packageId || null }),
                    })
                        .then(function (data) {
                            appendMessage({
                                id: data.id,
                                sender: 'guest',
                                message: message,
                                created_at: data.created_at,
                            });
                            inputEl.value = '';
                            syncSendState();
                            scrollBottom();
                        })
                        .catch(function () {
                            syncSendState();
                        });
                });
            }

            function restoreStoredSession() {
                function resolveContextAndDivider(isDiffPkg) {
                    if (isDiffPkg) {
                        var storedData = null;
                        try { storedData = JSON.parse(sessionStorage.getItem(pkgDataStorageKey) || ''); } catch (e) {}
                        updateContextTopWithStoredPackage(storedData);
                        showContextTop();
                        injectPackageDivider();
                    } else {
                        showContextTop();
                    }
                }

                if (token) {
                    var storedPkgId = null;
                    try { storedPkgId = sessionStorage.getItem(packageStorageKey); } catch (e) {}
                    isDifferentPackage = !!storedPkgId && !!packageId && String(packageId) !== storedPkgId;
                    history.replaceState({}, '', buildChatUrl(token));
                    syncSessionContext().then(function () {
                        return loadMessages(0);
                    }).then(function () {
                        resolveContextAndDivider(isDifferentPackage);
                        startPolling();
                        scrollBottom();
                    });
                    return;
                }

                var storedToken = null;
                var storedLastId = 0;
                var storedPkgId2 = null;

                try {
                    storedToken = sessionStorage.getItem(tokenStorageKey);
                    storedLastId = parseInt(sessionStorage.getItem(lastIdStorageKey) || '0', 10) || 0;
                    storedPkgId2 = sessionStorage.getItem(packageStorageKey);
                } catch (error) {}

                if (!storedToken) {
                    updateEmptyState();
                    return;
                }

                isDifferentPackage = !!storedPkgId2 && !!packageId && String(packageId) !== storedPkgId2;
                token = storedToken;
                lastId = storedLastId;

                history.replaceState({}, '', buildChatUrl(token));
                syncSessionContext()
                    .then(function () {
                        return loadMessages(0);
                    })
                    .then(function () {
                        resolveContextAndDivider(isDifferentPackage);
                        startPolling();
                        scrollBottom();
                    })
                    .catch(function () {
                        token = null;
                        lastId = 0;
                        try {
                            sessionStorage.removeItem(tokenStorageKey);
                            sessionStorage.removeItem(lastIdStorageKey);
                        } catch (error) {}
                        history.replaceState({}, '', buildChatUrl(null));
                        updateEmptyState();
                    });
            }

            if (inputEl) {
                inputEl.addEventListener('input', syncSendState);
                inputEl.addEventListener('keydown', function (event) {
                    if (event.key === 'Enter') {
                        event.preventDefault();
                        sendMessage();
                    }
                });
            }

            if (sendBtn) {
                sendBtn.addEventListener('click', function () {
                    sendMessage();
                });
            }

            quickReplyButtons.forEach(function (button) {
                button.addEventListener('click', function () {
                    sendMessage(button.getAttribute('data-quick-reply') || '');
                });
            });

            nameBackdrop.addEventListener('click', hideNameSheet);
            nameCancelBtn.addEventListener('click', hideNameSheet);
            nameSaveBtn.addEventListener('click', function () {
                var value = nameInput.value.trim();
                if (!value) {
                    nameError.classList.remove('hidden');
                    return;
                }

                guestName = value;
                try {
                    sessionStorage.setItem(guestNameStorageKey, guestName);
                } catch (error) {}
                hideNameSheet();
                runPendingAction();
            });
            nameInput.addEventListener('keydown', function (event) {
                if (event.key === 'Enter') {
                    event.preventDefault();
                    nameSaveBtn.click();
                }
            });

            // ── dynamic footer padding (prevent text overlap) ──────────────
            var mainEl = document.querySelector('.public-chat-main');
            var footerEl = document.querySelector('.public-chat-footer');
            function adjustMainPadding() {
                if (mainEl && footerEl) {
                    mainEl.style.paddingBottom = (footerEl.offsetHeight + 24) + 'px';
                }
            }
            adjustMainPadding();
            window.addEventListener('resize', adjustMainPadding);
            // ── promo banner fadeout ────────────────────────────────────
            var promoBanner = document.getElementById('chat-promo-banner');
            var promoBannerFaded = false;
            function fadePromoBanner() {
                if (!promoBanner || promoBannerFaded) { return; }
                promoBannerFaded = true;
                promoBanner.classList.add('chat-banner--fadeout');
                window.setTimeout(adjustMainPadding, 600);
            }

            // ── inject inline package divider into messages ────────────────────
            function injectPackageDivider() {
                if (dividerInjected || !messagesEl) { return; }
                dividerInjected = true;
                var tpl = document.getElementById('chat-pkg-divider-tpl');
                if (!tpl) { return; }
                var card = tpl.cloneNode(true);
                card.id = '';
                card.style.display = 'block';
                messagesEl.appendChild(card);
                updateEmptyState();
            }

            // ── update #chat-context-top package card with stored (first) package data ────
            function updateContextTopWithStoredPackage(pkgData) {
                if (!pkgData) { return; }
                var linkEl  = document.getElementById('chat-ctx-pkg-link');
                var imgEl   = document.getElementById('chat-ctx-pkg-img');
                var nameEl  = document.getElementById('chat-ctx-pkg-name');
                var priceEl = document.getElementById('chat-ctx-pkg-price');
                if (linkEl)  { linkEl.href = pkgData.url || '#'; }
                if (imgEl)   { imgEl.src = pkgData.image || ''; imgEl.alt = pkgData.name || ''; }
                if (nameEl)  { nameEl.textContent = pkgData.name || ''; }
                if (priceEl) {
                    if (pkgData.price) {
                        priceEl.textContent = pkgData.price;
                        priceEl.style.display = '';
                    } else {
                        priceEl.style.display = 'none';
                    }
                }
            }

            // ── show context block at top of chat + fade footer items ─────────
            var contextTopEl = document.getElementById('chat-context-top');
            var footerNoticeEl = document.getElementById('chat-footer-notice');
            var contextTopShown = false;
            function showContextTop() {
                if (contextTopShown) { return; }
                contextTopShown = true;
                // Reveal block at top of messages
                if (contextTopEl) {
                    contextTopEl.style.display = 'block';
                    window.requestAnimationFrame(function () {
                        contextTopEl.style.opacity = '1';
                    });
                }
                // Fade footer notice
                if (footerNoticeEl) {
                    footerNoticeEl.style.opacity = '0';
                    footerNoticeEl.style.maxHeight = '0';
                    window.setTimeout(function () { footerNoticeEl.style.display = 'none'; }, 560);
                }
                // Fade promo banner
                fadePromoBanner();
                // Fade footer product card
                if (productCard) {
                    productCard.style.transition = 'opacity 0.5s ease, max-height 0.5s ease, margin-top 0.5s ease, padding 0.5s ease';
                    productCard.style.opacity = '0';
                    productCard.style.maxHeight = '0';
                    productCard.style.overflow = 'hidden';
                    productCard.style.marginTop = '0';
                    productCard.style.paddingTop = '0';
                    productCard.style.paddingBottom = '0';
                    window.setTimeout(function () {
                        productCard.style.display = 'none';
                        adjustMainPadding();
                    }, 560);
                }
            }
            // ── product card dismiss ─────────────────────────────
            var productCard = document.getElementById('chat-product-card');
            var productDismiss = document.getElementById('chat-product-dismiss');
            if (productDismiss && productCard) {
                productDismiss.addEventListener('click', function () {
                    productCard.style.display = 'none';
                    window.setTimeout(adjustMainPadding, 50);
                });
            }

            updateEmptyState();
            syncSendState();
            restoreStoredSession();
        })();
    </script>
@endsection