@extends('layout.dashboard')

@section('title', 'Dashboard — Makna Wedding')
@section('page-title', 'Dashboard')

@section('content')

    {{-- Welcome --}}
    <div class="mb-8 flex items-start justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-dark">
                Selamat datang, {{ explode(' ', $user->name)[0] }} 👋
            </h1>
            <p class="text-sm text-gray-400 mt-1">Kelola aktivitas dan ulasan pernikahanmu di sini.</p>
        </div>

        @if($user->hasRole('super_admin'))
        <button type="button"
                data-open-theme-modal
                class="flex-shrink-0 flex items-center gap-2 px-4 py-2 rounded-xl bg-accent text-white text-xs font-semibold shadow-sm hover:opacity-90 transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <circle cx="12" cy="12" r="3"/>
                <path stroke-linecap="round" stroke-linejoin="round" d="M19.4 15a1.65 1.65 0 00.33 1.82l.06.06a2 2 0 010 2.83 2 2 0 01-2.83 0l-.06-.06a1.65 1.65 0 00-1.82-.33 1.65 1.65 0 00-1 1.51V21a2 2 0 01-4 0v-.09A1.65 1.65 0 009 19.4a1.65 1.65 0 00-1.82.33l-.06.06a2 2 0 01-2.83-2.83l.06-.06A1.65 1.65 0 004.68 15a1.65 1.65 0 00-1.51-1H3a2 2 0 010-4h.09A1.65 1.65 0 004.6 9a1.65 1.65 0 00-.33-1.82l-.06-.06a2 2 0 012.83-2.83l.06.06A1.65 1.65 0 009 4.68a1.65 1.65 0 001-1.51V3a2 2 0 014 0v.09a1.65 1.65 0 001 1.51 1.65 1.65 0 001.82-.33l.06-.06a2 2 0 012.83 2.83l-.06.06A1.65 1.65 0 0019.4 9a1.65 1.65 0 001.51 1H21a2 2 0 010 4h-.09a1.65 1.65 0 00-1.51 1z"/>
            </svg>
            Ganti Tema
        </button>
        @endif
    </div>

    @php
        $isAdmin = $user->hasRole(['super_admin', 'admin']);
        $isVendor = $user->hasRole(['vendor']);
    @endphp

    {{-- Chat Saya shortcut --}}
    @if(!$isAdmin && !$isVendor)
    <a href="{{ route('dashboard.my-chats') }}"
       class="bg-white rounded-2xl border border-gray-100 p-5 flex items-center gap-4 hover:border-gray-200 hover:shadow-sm transition group mb-4">
        <div class="w-11 h-11 rounded-xl flex items-center justify-center flex-shrink-0 bg-blue-50">
            <svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
            </svg>
        </div>
        <div class="flex-1">
            <p class="text-sm font-bold text-dark">Chat Saya</p>
            <p class="text-xs text-gray-400">Temukan rekapan chat dengan vendor</p>
        </div>
        <svg class="w-4 h-4 text-gray-300 group-hover:text-gray-400 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
        </svg>
    </a>
    @endif

    {{-- Quick Links --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-8">
        <a href="{{ route('vendor') }}"
           class="bg-white rounded-2xl border border-gray-100 p-5 flex items-center gap-4 hover:border-gray-200 hover:shadow-sm transition group">
            <div class="w-11 h-11 rounded-xl flex items-center justify-center flex-shrink-0 bg-light-sage">
                <svg class="w-5 h-5 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
            </div>
            <div class="flex-1">
                <p class="text-sm font-bold text-dark">Jelajahi Vendor</p>
                <p class="text-xs text-gray-400">Temukan WO, venue &amp; fotografer</p>
            </div>
            <svg class="w-4 h-4 text-gray-300 group-hover:text-gray-400 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
        </a>

        <a href="{{ route('home') }}"
           class="bg-white rounded-2xl border border-gray-100 p-5 flex items-center gap-4 hover:border-gray-200 hover:shadow-sm transition group">
            <div class="w-11 h-11 rounded-xl flex items-center justify-center flex-shrink-0 bg-accent-pink">
                <svg class="w-5 h-5 text-accent-pink" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                </svg>
            </div>
            <div class="flex-1">
                <p class="text-sm font-bold text-dark">Beranda</p>
                <p class="text-xs text-gray-400">Kembali ke halaman utama</p>
            </div>
            <svg class="w-4 h-4 text-gray-300 group-hover:text-gray-400 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
        </a>
    </div>

    {{-- Stats --}}
    <div class="grid grid-cols-2 sm:grid-cols-3 gap-4 mb-8">
        @if($isAdmin)
            <div class="bg-white rounded-2xl border border-gray-100 p-5">
                <p class="text-[10px] uppercase tracking-widest text-gray-400 mb-1">Booking Aktif</p>
                <p class="text-3xl font-bold text-dark">{{ $bookingActiveCount ?? 0 }}</p>
                <p class="text-xs text-gray-400 mt-0.5">pending / contacted / confirmed</p>
            </div>
            <div class="bg-white rounded-2xl border border-gray-100 p-5">
                <p class="text-[10px] uppercase tracking-widest text-gray-400 mb-1">Booking Selesai</p>
                <p class="text-3xl font-bold text-dark">{{ $bookingDoneCount ?? 0 }}</p>
                <p class="text-xs text-gray-400 mt-0.5">status done</p>
            </div>
            <div class="bg-white rounded-2xl border border-gray-100 p-5">
                <p class="text-[10px] uppercase tracking-widest text-gray-400 mb-1">Pembayaran Masuk</p>
                <p class="text-3xl font-bold text-dark">{{ $paymentTotalCount ?? 0 }}</p>
                <p class="text-xs text-gray-400 mt-0.5">total data</p>
            </div>
            <div class="bg-white rounded-2xl border border-gray-100 p-5">
                <p class="text-[10px] uppercase tracking-widest text-gray-400 mb-1">Menunggu Verifikasi</p>
                <p class="text-3xl font-bold text-dark">{{ $paymentPendingCount ?? 0 }}</p>
                <p class="text-xs text-gray-400 mt-0.5">pending</p>
            </div>
            <div class="bg-white rounded-2xl border border-gray-100 p-5">
                <p class="text-[10px] uppercase tracking-widest text-gray-400 mb-1">Pembayaran Disetujui</p>
                <p class="text-3xl font-bold text-dark">{{ $paymentApprovedCount ?? 0 }}</p>
                <p class="text-xs text-gray-400 mt-0.5">approved</p>
            </div>
            <div class="bg-white rounded-2xl border border-gray-100 p-5 col-span-2 sm:col-span-1">
                <p class="text-[10px] uppercase tracking-widest text-gray-400 mb-1">Nominal Disetujui</p>
                <p class="text-lg font-bold leading-tight text-dark">{{ number_format((int) ($paymentApprovedSum ?? 0), 0, ',', '.') }}</p>
                <p class="text-xs text-gray-400 mt-0.5">
                    DP {{ number_format((int) ($paymentApprovedDpSum ?? 0), 0, ',', '.') }} ·
                    Lunas {{ number_format((int) ($paymentApprovedFinalSum ?? 0), 0, ',', '.') }} ·
                    Cicilan {{ number_format((int) ($paymentApprovedInstallmentSum ?? 0), 0, ',', '.') }}
                </p>
            </div>

            <div class="bg-white rounded-2xl border border-gray-100 p-5">
                <p class="text-[10px] uppercase tracking-widest text-gray-400 mb-1">Total Vendor</p>
                <p class="text-3xl font-bold text-dark">{{ $vendorTotalCount ?? 0 }}</p>
                <p class="text-xs text-gray-400 mt-0.5">semua data</p>
            </div>
            <div class="bg-white rounded-2xl border border-gray-100 p-5">
                <p class="text-[10px] uppercase tracking-widest text-gray-400 mb-1">Vendor Aktif</p>
                <p class="text-3xl font-bold text-dark">{{ $vendorActiveCount ?? 0 }}</p>
                <p class="text-xs text-gray-400 mt-0.5">is_active true</p>
            </div>
            <div class="bg-white rounded-2xl border border-gray-100 p-5">
                <p class="text-[10px] uppercase tracking-widest text-gray-400 mb-1">Vendor Nonaktif</p>
                <p class="text-3xl font-bold text-dark">{{ $vendorInactiveCount ?? 0 }}</p>
                <p class="text-xs text-gray-400 mt-0.5">is_active false</p>
            </div>
            <div class="bg-white rounded-2xl border border-gray-100 p-5">
                <p class="text-[10px] uppercase tracking-widest text-gray-400 mb-1">Pengajuan Vendor</p>
                <p class="text-3xl font-bold text-dark">{{ $menuVendorApplicationPendingCount ?? 0 }}</p>
                <p class="text-xs text-gray-400 mt-0.5">pending</p>
            </div>
            <div class="bg-white rounded-2xl border border-gray-100 p-5 col-span-2 sm:col-span-1">
                <p class="text-[10px] uppercase tracking-widest text-gray-400 mb-1">Perlu Ditinjau</p>
                <p class="text-lg font-bold leading-tight text-dark">{{ $vendorIncompleteCount ?? 0 }} vendor</p>
                <p class="text-xs text-gray-400 mt-0.5">{{ $reviewPendingCount ?? 0 }} ulasan pending</p>
            </div>
            <div class="bg-white rounded-2xl border border-gray-100 p-5 col-span-2 sm:col-span-1">
                <p class="text-[10px] uppercase tracking-widest text-gray-400 mb-1">Akun Sejak</p>
                <p class="text-lg font-bold leading-tight text-dark">{{ $user->created_at->translatedFormat('M Y') }}</p>
                <p class="text-xs mt-0.5 {{ $user->email_verified_at ? 'text-green-500' : 'text-amber-500' }}">
                    {{ $user->email_verified_at ? '✓ Terverifikasi' : '⚠ Belum verifikasi' }}
                </p>
            </div>
        @elseif($isVendor)
            <div class="bg-white rounded-2xl border border-gray-100 p-5">
                <p class="text-[10px] uppercase tracking-widest text-gray-400 mb-1">Vendor Saya</p>
                <p class="text-3xl font-bold text-dark">{{ $menuVendorCount ?? 0 }}</p>
                <p class="text-xs text-gray-400 mt-0.5">terhubung ke akun</p>
            </div>
            <div class="bg-white rounded-2xl border border-gray-100 p-5">
                <p class="text-[10px] uppercase tracking-widest text-gray-400 mb-1">Booking Aktif</p>
                <p class="text-3xl font-bold text-dark">{{ $bookingActiveCount ?? 0 }}</p>
                <p class="text-xs text-gray-400 mt-0.5">pending / contacted / confirmed</p>
            </div>
            <div class="bg-white rounded-2xl border border-gray-100 p-5">
                <p class="text-[10px] uppercase tracking-widest text-gray-400 mb-1">Booking Selesai</p>
                <p class="text-3xl font-bold text-dark">{{ $bookingDoneCount ?? 0 }}</p>
                <p class="text-xs text-gray-400 mt-0.5">status done</p>
            </div>
            <div class="bg-white rounded-2xl border border-gray-100 p-5">
                <p class="text-[10px] uppercase tracking-widest text-gray-400 mb-1">Pembayaran Masuk</p>
                <p class="text-3xl font-bold text-dark">{{ $paymentTotalCount ?? 0 }}</p>
                <p class="text-xs text-gray-400 mt-0.5">total data</p>
            </div>
            <div class="bg-white rounded-2xl border border-gray-100 p-5">
                <p class="text-[10px] uppercase tracking-widest text-gray-400 mb-1">Menunggu Verifikasi</p>
                <p class="text-3xl font-bold text-dark">{{ $paymentPendingCount ?? 0 }}</p>
                <p class="text-xs text-gray-400 mt-0.5">pending</p>
            </div>
            <div class="bg-white rounded-2xl border border-gray-100 p-5">
                <p class="text-[10px] uppercase tracking-widest text-gray-400 mb-1">Pembayaran Disetujui</p>
                <p class="text-3xl font-bold text-dark">{{ $paymentApprovedCount ?? 0 }}</p>
                <p class="text-xs text-gray-400 mt-0.5">approved</p>
            </div>
            <div class="bg-white rounded-2xl border border-gray-100 p-5 col-span-2 sm:col-span-1">
                <p class="text-[10px] uppercase tracking-widest text-gray-400 mb-1">Nominal Disetujui</p>
                <p class="text-lg font-bold leading-tight text-dark">{{ number_format((int) ($paymentApprovedSum ?? 0), 0, ',', '.') }}</p>
                <p class="text-xs text-gray-400 mt-0.5">
                    DP {{ number_format((int) ($paymentApprovedDpSum ?? 0), 0, ',', '.') }} ·
                    Lunas {{ number_format((int) ($paymentApprovedFinalSum ?? 0), 0, ',', '.') }} ·
                    Cicilan {{ number_format((int) ($paymentApprovedInstallmentSum ?? 0), 0, ',', '.') }}
                </p>
            </div>
            <div class="bg-white rounded-2xl border border-gray-100 p-5">
                <p class="text-[10px] uppercase tracking-widest text-gray-400 mb-1">Ulasan Dikirim</p>
                <p class="text-3xl font-bold text-dark">{{ $reviewCount }}</p>
                <p class="text-xs text-gray-400 mt-0.5">total ulasan</p>
            </div>
            <div class="bg-white rounded-2xl border border-gray-100 p-5 col-span-2 sm:col-span-1">
                <p class="text-[10px] uppercase tracking-widest text-gray-400 mb-1">Akun Sejak</p>
                <p class="text-lg font-bold leading-tight text-dark">{{ $user->created_at->translatedFormat('M Y') }}</p>
                <p class="text-xs mt-0.5 {{ $user->email_verified_at ? 'text-green-500' : 'text-amber-500' }}">
                    {{ $user->email_verified_at ? '✓ Terverifikasi' : '⚠ Belum verifikasi' }}
                </p>
            </div>
        @else
            <div class="bg-white rounded-2xl border border-gray-100 p-5">
                <p class="text-[10px] uppercase tracking-widest text-gray-400 mb-1">Booking Aktif</p>
                <p class="text-3xl font-bold text-dark">{{ $bookingActiveCount ?? 0 }}</p>
                <p class="text-xs text-gray-400 mt-0.5">pending / contacted / confirmed</p>
            </div>
            <div class="bg-white rounded-2xl border border-gray-100 p-5">
                <p class="text-[10px] uppercase tracking-widest text-gray-400 mb-1">Booking Selesai</p>
                <p class="text-3xl font-bold text-dark">{{ $bookingDoneCount ?? 0 }}</p>
                <p class="text-xs text-gray-400 mt-0.5">status done</p>
            </div>
            <div class="bg-white rounded-2xl border border-gray-100 p-5">
                <p class="text-[10px] uppercase tracking-widest text-gray-400 mb-1">Pembayaran Saya</p>
                <p class="text-3xl font-bold text-dark">{{ $paymentTotalCount ?? 0 }}</p>
                <p class="text-xs text-gray-400 mt-0.5">total data</p>
            </div>
            <div class="bg-white rounded-2xl border border-gray-100 p-5">
                <p class="text-[10px] uppercase tracking-widest text-gray-400 mb-1">Menunggu Verifikasi</p>
                <p class="text-3xl font-bold text-dark">{{ $paymentPendingCount ?? 0 }}</p>
                <p class="text-xs text-gray-400 mt-0.5">pending</p>
            </div>
            <div class="bg-white rounded-2xl border border-gray-100 p-5 col-span-2 sm:col-span-1">
                <p class="text-[10px] uppercase tracking-widest text-gray-400 mb-1">Nominal Disetujui</p>
                <p class="text-lg font-bold leading-tight text-dark">{{ number_format((int) ($paymentApprovedSum ?? 0), 0, ',', '.') }}</p>
                <p class="text-xs text-gray-400 mt-0.5">
                    DP {{ number_format((int) ($paymentApprovedDpSum ?? 0), 0, ',', '.') }} ·
                    Lunas {{ number_format((int) ($paymentApprovedFinalSum ?? 0), 0, ',', '.') }} ·
                    Cicilan {{ number_format((int) ($paymentApprovedInstallmentSum ?? 0), 0, ',', '.') }}
                </p>
            </div>
            <div class="bg-white rounded-2xl border border-gray-100 p-5">
                <p class="text-[10px] uppercase tracking-widest text-gray-400 mb-1">Favorit</p>
                <p class="text-3xl font-bold text-dark">{{ $favoriteCount ?? 0 }}</p>
                <p class="text-xs text-gray-400 mt-0.5">vendor disimpan</p>
            </div>
            <div class="bg-white rounded-2xl border border-gray-100 p-5">
                <p class="text-[10px] uppercase tracking-widest text-gray-400 mb-1">Ulasan Dikirim</p>
                <p class="text-3xl font-bold text-dark">{{ $reviewCount }}</p>
                <p class="text-xs text-gray-400 mt-0.5">total ulasan</p>
            </div>
            <div class="bg-white rounded-2xl border border-gray-100 p-5">
                <p class="text-[10px] uppercase tracking-widest text-gray-400 mb-1">Bergabung</p>
                <p class="text-3xl font-bold text-dark">{{ (int) $user->created_at->diffInDays(now()) }}</p>
                <p class="text-xs text-gray-400 mt-0.5">hari lalu</p>
            </div>
            <div class="bg-white rounded-2xl border border-gray-100 p-5 col-span-2 sm:col-span-1">
                <p class="text-[10px] uppercase tracking-widest text-gray-400 mb-1">Akun Sejak</p>
                <p class="text-lg font-bold leading-tight text-dark">{{ $user->created_at->translatedFormat('M Y') }}</p>
                <p class="text-xs mt-0.5 {{ $user->email_verified_at ? 'text-green-500' : 'text-amber-500' }}">
                    {{ $user->email_verified_at ? '✓ Terverifikasi' : '⚠ Belum verifikasi' }}
                </p>
            </div>
        @endif
    </div>

    @if($user->hasRole('super_admin'))
    {{-- ── Theme Picker Modal ───────────────────────────────── --}}
    @php
        $themes = \App\Http\Controllers\ThemeController::$themes;
        $activeThemeName = \App\Http\Controllers\ThemeController::active()['name'];

        $themeSwatches = [
            'gold-ivory'      => ['from' => '#C9A84C', 'to' => '#E8D5A3'],
            'sage-green'      => ['from' => '#9CAF88', 'to' => '#C8D5B9'],
            'dusty-rose'      => ['from' => '#C4846B', 'to' => '#D4A5A5'],
            'navy-gold'       => ['from' => '#1B3A6B', 'to' => '#4A7FAA'],
            'blush-burgundy'  => ['from' => '#7B2D42', 'to' => '#C8909A'],
        ];
    @endphp

    <div id="theme-modal" class="hidden fixed inset-0 z-[9999] flex items-center justify-center p-4 bg-black/40 backdrop-blur-sm">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-sm p-6">
            <div class="flex items-center justify-between mb-5">
                <h2 class="text-base font-bold text-dark">Ganti Tema Warna</h2>
                <button type="button" data-close-theme-modal class="p-1.5 rounded-lg hover:bg-gray-100 transition">
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <form method="POST" action="{{ route('dashboard.theme.update') }}">
                @csrf
                <div class="space-y-2 mb-5">
                    @foreach($themes as $key => $theme)
                    <label class="flex items-center gap-3 p-3 rounded-xl border-2 cursor-pointer transition
                        {{ $activeThemeName === $key ? 'border-accent bg-cream' : 'border-gray-100 hover:border-gray-200' }}">
                        <input type="radio" name="theme" value="{{ $key }}"
                               class="sr-only peer"
                               {{ $activeThemeName === $key ? 'checked' : '' }}>
                        <span class="w-8 h-8 rounded-full flex-shrink-0 shadow-sm border border-white/60"
                              style="background: linear-gradient(135deg, {{ $themeSwatches[$key]['from'] }}, {{ $themeSwatches[$key]['to'] }})"></span>
                        <span class="flex-1 text-sm font-medium text-dark">{{ $theme['label'] }}</span>
                        @if($activeThemeName === $key)
                        <span class="text-[10px] font-semibold text-accent uppercase tracking-wide">Aktif</span>
                        @endif
                    </label>
                    @endforeach
                </div>

                <button type="submit"
                        class="w-full py-2.5 rounded-xl bg-accent text-white text-sm font-semibold hover:opacity-90 transition">
                    Terapkan Tema
                </button>
            </form>
        </div>
    </div>

    <script>
        (function () {
            const openBtn  = document.querySelector('[data-open-theme-modal]');
            const closeBtn = document.querySelector('[data-close-theme-modal]');
            const modal    = document.getElementById('theme-modal');

            if (openBtn)  openBtn.addEventListener('click',  () => modal.classList.remove('hidden'));
            if (closeBtn) closeBtn.addEventListener('click', () => modal.classList.add('hidden'));
            modal.addEventListener('click', (e) => { if (e.target === modal) modal.classList.add('hidden'); });

            // Auto-highlight selected radio on click
            document.querySelectorAll('input[name="theme"]').forEach(radio => {
                radio.addEventListener('change', () => {
                    document.querySelectorAll('input[name="theme"]').forEach(r => {
                        r.closest('label').classList.remove('border-accent', 'bg-cream');
                        r.closest('label').classList.add('border-gray-100');
                    });
                    radio.closest('label').classList.add('border-accent', 'bg-cream');
                    radio.closest('label').classList.remove('border-gray-100');
                });
            });
        })();
    </script>
    @endif

    @if(session('theme_updated'))
    <div id="theme-toast"
         class="fixed bottom-6 right-6 z-[99999] flex items-center gap-3 bg-white border border-gray-100 shadow-lg rounded-2xl px-4 py-3 text-sm font-medium text-dark">
        <span class="w-2 h-2 rounded-full bg-accent flex-shrink-0"></span>
        {{ session('theme_updated') }}
    </div>
    <script>
        setTimeout(() => {
            const t = document.getElementById('theme-toast');
            if (t) t.style.transition = 'opacity .4s', t.style.opacity = 0, setTimeout(() => t.remove(), 400);
        }, 3500);
    </script>
    @endif

@endsection
