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
        'Hai, paket ini masih ready?',
        'Bisa kirim detail paket ini?',
        'Bisa dikirim hari ini?',
        'Terima kasih',
    ];
@endphp

@section('title', 'Chat dengan ' . $chatVendorName . ' - Makna Wedding')
@section('meta-description', 'Mulai chat langsung dengan ' . $chatVendorName . ' untuk tanya detail paket dan ketersediaan.')
@section('body-class', 'bg-white text-dark chat-public-page')
@section('hide-live-chat-widget', '1')

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
        padding-bottom: 24rem;
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
        color: rgba(148, 163, 184, 0.13);
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

    @media (min-width: 768px) {
        .public-chat-main {
            padding-bottom: 21rem;
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

        <main class="public-chat-main">
            <div class="public-chat-wallpaper" aria-hidden="true">
                {{-- Ghost 1: bird with wings, top-left --}}
                <div class="public-chat-wallpaper-ghost" style="width: 8rem; height: 9rem; top: 3rem; left: 0.5rem;">
                    <svg viewBox="0 0 100 110" fill="currentColor" xmlns="http://www.w3.org/2000/svg" class="w-full h-full">
                        <ellipse cx="50" cy="74" rx="30" ry="33"/>
                        <circle cx="50" cy="36" r="28"/>
                        <ellipse cx="16" cy="72" rx="13" ry="22" transform="rotate(-18 16 72)"/>
                        <ellipse cx="84" cy="72" rx="13" ry="22" transform="rotate(18 84 72)"/>
                        <path d="M35 34 L41 39 L35 44" fill="none" stroke="white" stroke-opacity="0.5" stroke-width="3.5" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M65 34 L59 39 L65 44" fill="none" stroke="white" stroke-opacity="0.5" stroke-width="3.5" stroke-linecap="round" stroke-linejoin="round"/>
                        <ellipse cx="50" cy="50" rx="7" ry="4" fill="white" opacity="0.3"/>
                    </svg>
                </div>
                {{-- Ghost 2: tiny round bird, top-right --}}
                <div class="public-chat-wallpaper-ghost" style="width: 4.5rem; height: 4.5rem; top: 7rem; right: 1.5rem;">
                    <svg viewBox="0 0 80 80" fill="currentColor" xmlns="http://www.w3.org/2000/svg" class="w-full h-full">
                        <circle cx="40" cy="40" r="36"/>
                        <path d="M25 38 L31 43 L25 48" fill="none" stroke="white" stroke-opacity="0.5" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M55 38 L49 43 L55 48" fill="none" stroke="white" stroke-opacity="0.5" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
                        <ellipse cx="40" cy="53" rx="5" ry="3" fill="white" opacity="0.3"/>
                    </svg>
                </div>
                {{-- Ghost 3: bear/panda, center --}}
                <div class="public-chat-wallpaper-ghost" style="width: 9rem; height: 8.5rem; top: 17rem; left: 50%; transform: translateX(-50%);">
                    <svg viewBox="0 0 110 100" fill="currentColor" xmlns="http://www.w3.org/2000/svg" class="w-full h-full">
                        <circle cx="24" cy="22" r="16"/>
                        <circle cx="86" cy="22" r="16"/>
                        <circle cx="55" cy="56" r="40"/>
                        <path d="M38 52 L45 57 L38 62" fill="none" stroke="white" stroke-opacity="0.5" stroke-width="3.5" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M72 52 L65 57 L72 62" fill="none" stroke="white" stroke-opacity="0.5" stroke-width="3.5" stroke-linecap="round" stroke-linejoin="round"/>
                        <ellipse cx="55" cy="68" rx="8" ry="5" fill="white" opacity="0.3"/>
                    </svg>
                </div>
                {{-- Ghost 4: classic ghost, bottom-left --}}
                <div class="public-chat-wallpaper-ghost" style="width: 5.5rem; height: 7rem; bottom: 14rem; left: 0.75rem;">
                    <svg viewBox="0 0 80 100" fill="currentColor" xmlns="http://www.w3.org/2000/svg" class="w-full h-full">
                        <path d="M40 5 C20 5 5 20 5 40 L5 92 L15 82 L25 92 L35 82 L45 92 L55 82 L65 92 L75 82 L75 40 C75 20 60 5 40 5Z"/>
                        <path d="M27 41 L33 46 L27 51" fill="none" stroke="white" stroke-opacity="0.5" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M53 41 L47 46 L53 51" fill="none" stroke="white" stroke-opacity="0.5" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </div>
                {{-- Ghost 5: cat/fox, bottom-right --}}
                <div class="public-chat-wallpaper-ghost" style="width: 8rem; height: 8rem; right: 1rem; bottom: 18rem;">
                    <svg viewBox="0 0 100 90" fill="currentColor" xmlns="http://www.w3.org/2000/svg" class="w-full h-full">
                        <path d="M20 36 L14 7 L38 28Z"/>
                        <path d="M80 36 L86 7 L62 28Z"/>
                        <circle cx="50" cy="54" r="35"/>
                        <path d="M33 51 L40 57 L33 63" fill="none" stroke="white" stroke-opacity="0.5" stroke-width="3.5" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M67 51 L60 57 L67 63" fill="none" stroke="white" stroke-opacity="0.5" stroke-width="3.5" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M45 66 L50 72 L55 66Z" fill="white" opacity="0.3"/>
                    </svg>
                </div>
                {{-- Ghost 6: extra small floating bird, mid-right --}}
                <div class="public-chat-wallpaper-ghost" style="width: 3.5rem; height: 3.5rem; top: 28rem; right: 0.5rem;">
                    <svg viewBox="0 0 60 70" fill="currentColor" xmlns="http://www.w3.org/2000/svg" class="w-full h-full">
                        <ellipse cx="30" cy="44" rx="20" ry="24"/>
                        <circle cx="30" cy="23" r="18"/>
                        <path d="M20 22 L25 26 L20 30" fill="none" stroke="white" stroke-opacity="0.5" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M40 22 L35 26 L40 30" fill="none" stroke="white" stroke-opacity="0.5" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M25 31 l5 5 5-5z" fill="white" opacity="0.3"/>
                    </svg>
                </div>
            </div>

            <div id="public-chat-messages" class="public-chat-scroll">
                <div id="public-chat-empty" class="public-chat-empty">
                    <div class="max-w-sm">
                        <p class="text-sm font-medium text-slate-400">Percakapan akan muncul di sini. Kirim pesan pertama untuk mulai tanya detail paket, jadwal, atau konsultasi.</p>
                    </div>
                </div>
            </div>
        </main>

        <div class="public-chat-footer">
            <div class="mx-auto w-full max-w-3xl px-4">
                <p class="mx-auto max-w-2xl text-center text-[12px] leading-6 text-slate-400 sm:text-[13px]">
                    Hati-hati penipuan! Mohon tidak bertransaksi di luar platform resmi dan tidak memberikan data pribadi kepada penjual, seperti nomor HP dan alamat. Tetap berinteraksi melalui Makna Wedding, ya.
                    <a href="{{ route('home') }}" class="font-bold text-emerald-600">Baca Panduan Keamanan.</a>
                </p>

                <div class="mt-3 rounded-2xl border border-cyan-300/70 bg-cyan-50/80 px-4 py-3 shadow-sm backdrop-blur-sm">
                    <div class="flex items-start gap-3">
                        <div class="mt-0.5 flex h-7 w-7 items-center justify-center rounded-full border border-cyan-300 text-cyan-600">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M12 8v.01"/>
                            </svg>
                        </div>
                        <div class="min-w-0 flex-1">
                            <p class="text-[13px] leading-5 text-slate-700">Ada konsultasi gratis untuk 1 sesi percakapan di vendor ini.</p>
                            <p class="text-sm font-bold text-emerald-600">Cek info terbaru</p>
                        </div>
                    </div>
                </div>

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

                <div class="public-chat-scrollbar mt-4 flex gap-2 overflow-x-auto px-1 pb-1">
                    @foreach($chatQuickReplies as $quickReply)
                        <button type="button" data-quick-reply="{{ $quickReply }}" class="shrink-0 rounded-full bg-white px-4 py-3 text-sm font-medium text-slate-700 shadow-sm ring-1 ring-slate-200 transition hover:bg-slate-50">
                            {{ $quickReply }}
                        </button>
                    @endforeach
                </div>

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
            </div>
        </div>
    </div>

    <div id="public-chat-name-sheet" class="public-chat-name-sheet fixed inset-0 z-[60] hidden items-end justify-center p-4 sm:items-center">
        <div id="public-chat-name-backdrop" class="absolute inset-0"></div>
        <div class="relative z-10 w-full max-w-md rounded-[2rem] bg-white p-6 shadow-2xl">
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
            var token = @json($session?->session_token);
            var guestName = @json($guestName);
            var pollTimer = null;
            var lastId = 0;
            var pendingAction = null;
            var tokenStorageKey = 'lc_token';
            var lastIdStorageKey = 'lc_last_id';
            var guestNameStorageKey = 'lc_guest_name';
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
                        body: JSON.stringify({ guest_name: guestName }),
                    })
                        .then(function (data) {
                            token = data.token;

                            try {
                                sessionStorage.setItem(tokenStorageKey, token);
                                sessionStorage.setItem(lastIdStorageKey, '0');
                                sessionStorage.setItem(guestNameStorageKey, guestName);
                            } catch (error) {}

                            history.replaceState({}, '', buildChatUrl(token));
                            return loadMessages(0);
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

                ensureSession(function () {
                    fetchJson('/chat/' + token + '/send', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': CSRF,
                            'X-Requested-With': 'XMLHttpRequest',
                        },
                        body: JSON.stringify({ message: message }),
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
                if (token) {
                    history.replaceState({}, '', buildChatUrl(token));
                    loadMessages(0).then(function () {
                        startPolling();
                        scrollBottom();
                    });
                    return;
                }

                var storedToken = null;
                var storedLastId = 0;

                try {
                    storedToken = sessionStorage.getItem(tokenStorageKey);
                    storedLastId = parseInt(sessionStorage.getItem(lastIdStorageKey) || '0', 10) || 0;
                } catch (error) {}

                if (!storedToken) {
                    updateEmptyState();
                    return;
                }

                token = storedToken;
                lastId = storedLastId;

                history.replaceState({}, '', buildChatUrl(token));
                loadMessages(0)
                    .then(function () {
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

            // ── product card dismiss ─────────────────────────────
            var productCard = document.getElementById('chat-product-card');
            var productDismiss = document.getElementById('chat-product-dismiss');
            if (productDismiss && productCard) {
                productDismiss.addEventListener('click', function () {
                    productCard.style.display = 'none';
                });
            }

            updateEmptyState();
            syncSendState();
            restoreStoredSession();
        })();
    </script>
@endsection