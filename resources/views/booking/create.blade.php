@extends('layout.app')

@section('title', 'Booking - ' . ($vendor->name ?? 'Vendor') . ' - Makna Wedding')

@section('body-class', 'bg-cream text-dark')

@section('content')
    @include('layout.header')

    @php
        $breadcrumbItems = [
            ['label' => 'Home', 'url' => route('home')],
            ['label' => 'Vendor', 'url' => route('vendor')],
            ['label' => $vendor->name, 'url' => route('vendor.detail', $vendor)],
            ['label' => 'Booking', 'url' => null],
        ];
        $prefillWa = auth()->check() && auth()->user()->whatsapp
            ? preg_replace('/^62/', '0', auth()->user()->whatsapp)
            : '';
        $qty = max(1, min(99, (int) (old('qty', $qty ?? request()->query('qty', 1)))));
        $selectedId = old('vendor_package_id', $selectedPackage?->id);
        $hasPackages = isset($packages) && $packages->count() > 0;
        $bookingId = session('booking_id');
        $bookingPaymentUrl = $bookingId ? route('dashboard.booking.payment', $bookingId) : null;
    @endphp

    <section class="pt-3 lg:py-8 bg-cream">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="pt-1 pb-4 lg:pt-4">
                @include('layout.breadcrumb', ['items' => $breadcrumbItems])
            </div>

            @if(($bookingOwnerBlocked ?? false))
                <div class="bg-white rounded-2xl border border-gray-100 overflow-hidden">
                    <div class="p-6 pb-5 bg-cream">
                        <p class="text-xs font-bold uppercase tracking-widest text-gray-400 mb-1">Pemberitahuan</p>
                        <p class="text-xl font-bold text-dark">Booking Dinonaktifkan</p>
                        <p class="text-xs text-gray-500 mt-1">Anda adalah pemilik vendor ini, sehingga booking tidak dapat dilakukan.</p>
                    </div>
                    <div class="p-6">
                        <a href="{{ $vendorBookingBackUrl ?? route('vendor.detail', $vendor) }}"
                           class="flex items-center justify-center w-full py-3 rounded-xl text-sm font-bold bg-dark text-cream transition hover:opacity-90">
                            Kembali
                        </a>
                    </div>
                </div>
            @elseif(($vendorBookingDisabled ?? false))
                <div class="bg-white rounded-2xl border border-gray-100 overflow-hidden">
                    <div class="p-6 pb-5 bg-cream">
                        <p class="text-xs font-bold uppercase tracking-widest text-gray-400 mb-1">Pemberitahuan</p>
                        <p class="text-xl font-bold text-dark">Vendor Belum Lengkap</p>
                        <p class="text-xs text-gray-500 mt-1">Profil vendor ini belum lengkap 100% sehingga booking belum dapat dilakukan.</p>
                    </div>
                    <div class="p-6">
                        <a href="{{ $vendorBookingBackUrl ?? route('vendor.detail', $vendor) }}"
                           class="flex items-center justify-center w-full py-3 rounded-xl text-sm font-bold bg-dark text-cream transition hover:opacity-90">
                            Kembali
                        </a>
                    </div>
                </div>
            @else
                <div class="bg-white rounded-3xl border border-gray-100 overflow-hidden shadow-sm">
                    <div class="p-6 pb-5 bg-cream">
                        <p class="text-xs font-bold uppercase tracking-widest text-gray-400 mb-1">Booking</p>
                        <p class="text-xl font-bold text-dark">{{ $vendor->name }}</p>
                        <p class="text-xs text-gray-500 mt-1">Isi detail singkat, lalu kami hubungi via WhatsApp/telepon.</p>
                    </div>

                    <div class="p-6 space-y-4">
                        @if ($errors->booking->any())
                            <div class="text-xs font-semibold px-3 py-2 rounded-xl bg-red-50 text-red-700">
                                <ul class="list-disc pl-4 space-y-1">
                                    @foreach ($errors->booking->all() as $err)
                                        <li>{{ $err }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        @guest
                            <a href="{{ route('login') }}?redirect={{ urlencode(url()->current() . '?' . http_build_query(request()->query())) }}"
                               class="flex items-center justify-center gap-2 w-full py-3 rounded-xl text-sm font-bold bg-accent text-cream transition hover:opacity-90">
                                Login untuk Booking
                            </a>
                        @endguest

                        @auth
                            <form method="POST" action="{{ route('vendor.booking.store', $vendor) }}" class="space-y-3">
                                @csrf
                                <input type="hidden" name="qty" value="{{ $qty }}">

                                <div>
                                    <label class="block text-xs font-semibold text-gray-500 mb-1">Paket {{ $hasPackages ? '' : '(opsional)' }}</label>
                                    <select id="booking-vendor-package-select" name="vendor_package_id"
                                            class="w-full h-11 border border-gray-200 rounded-xl px-3.5 text-sm focus:outline-none focus:border-gray-400 transition bg-white"
                                            {{ $hasPackages ? 'required' : '' }}>
                                        @if(!$hasPackages)
                                            <option value="">Tanpa paket</option>
                                        @else
                                            <option value="" {{ $selectedId ? '' : 'selected' }}>Pilih paket</option>
                                            @foreach($packages as $pkg)
                                                <option value="{{ $pkg->id }}"
                                                        data-name="{{ $pkg->name }}"
                                                        data-price="{{ $pkg->price }}"
                                                        data-guests="{{ $pkg->max_guests }}"
                                                        data-price-raw="{{ (int) ($pkg->price ?? 0) }}"
                                                        data-discount="{{ (int) ($pkg->discount ?? 0) }}"
                                                        data-dp="{{ (int) ($pkg->dp_paket ?? 0) }}"
                                                        {{ (int) $selectedId === (int) $pkg->id ? 'selected' : '' }}>
                                                    {{ $pkg->name }} — {{ $pkg->price }}
                                                </option>
                                            @endforeach
                                        @endif
                                    </select>
                                    <div id="booking-package-summary" class="{{ $selectedId ? '' : 'hidden' }} mt-3 rounded-2xl p-4 border border-gray-100 bg-cream">
                                        <div class="flex items-center justify-between mb-1">
                                            <span class="text-xs text-gray-500">Paket dipilih</span>
                                            <span id="booking-summary-name" class="text-xs font-bold text-dark">{{ $selectedPackage?->name }}</span>
                                        </div>
                                        <div class="flex items-center justify-between">
                                            <span class="text-xs text-gray-500">Total (x <span id="booking-summary-qty">{{ $qty }}</span>)</span>
                                            <span id="booking-summary-price" class="text-base font-bold text-accent">{{ $selectedPackage?->price }}</span>
                                        </div>
                                        <div id="booking-summary-dp-wrap" class="flex items-center justify-between mt-1 {{ ($selectedPackage?->dp_paket ?? 0) > 0 ? '' : 'hidden' }}">
                                            <span class="text-[11px] text-gray-500">DP Paket</span>
                                            <span id="booking-summary-dp" class="text-[11px] font-bold text-dark"></span>
                                        </div>
                                        <div class="mt-1 text-[10px] text-gray-500">
                                            <span id="booking-summary-guests">{{ $selectedPackage?->max_guests }}</span> Pax
                                        </div>
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-xs font-semibold text-gray-500 mb-1">Jumlah</label>
                                    <div class="flex items-center gap-2">
                                        <button type="button" data-booking-qty="-1" class="w-9 h-11 rounded-xl border border-gray-200 bg-white hover:border-gray-300 transition text-dark">-</button>
                                        <input id="booking-qty" type="text" value="{{ $qty }}" inputmode="numeric" autocomplete="off"
                                               class="w-16 h-11 rounded-xl border border-gray-200 text-center text-sm font-bold focus:outline-none focus:border-gray-400 transition text-dark">
                                        <button type="button" data-booking-qty="1" class="w-9 h-11 rounded-xl border border-gray-200 bg-white hover:border-gray-300 transition text-dark">+</button>
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-xs font-semibold text-gray-500 mb-1">Tanggal acara</label>
                                    <input type="date" name="event_date" value="{{ old('event_date') }}"
                                           class="w-full h-11 border border-gray-200 rounded-xl px-3.5 py-2.5 text-sm focus:outline-none focus:border-gray-400 transition">
                                </div>

                                <div>
                                    <label class="block text-xs font-semibold text-gray-500 mb-1">Nomor WhatsApp</label>
                                    <input type="tel" inputmode="numeric" autocomplete="tel" name="phone" value="{{ old('phone', $prefillWa) }}"
                                           placeholder="Contoh: 08xxxxxxxxxx"
                                           class="w-full h-11 border border-gray-200 rounded-xl px-3.5 py-2.5 text-sm focus:outline-none focus:border-gray-400 transition">
                                </div>

                                <div>
                                    <label class="block text-xs font-semibold text-gray-500 mb-1">Catatan (opsional)</label>
                                    <textarea name="notes" rows="3" maxlength="2000"
                                              class="w-full border border-gray-200 rounded-xl px-3.5 py-2.5 text-sm focus:outline-none focus:border-gray-400 transition resize-none"
                                              placeholder="Mis: jam acara, estimasi tamu, request konsep...">{{ old('notes') }}</textarea>
                                </div>

                                <button type="submit"
                                        class="flex items-center justify-center gap-2 w-full py-3 rounded-xl text-sm font-bold bg-accent text-cream transition hover:opacity-90">
                                    Kirim Booking
                                </button>
                            </form>
                        @endauth

                        <div class="pt-1">
                            <a href="{{ $vendorBookingBackUrl ?? route('vendor.detail', $vendor) }}"
                               class="w-full inline-flex items-center justify-center py-2.5 rounded-xl text-sm font-semibold border border-gray-200 text-dark hover:bg-gray-50 transition">
                                Kembali
                            </a>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </section>

    @if(session('booking_success'))
        <x-ui.modal id="booking-success-modal" backdrop-class="bg-black/50 backdrop-blur-sm" class="place-items-center">
                <div class="p-6 pb-5 bg-cream">
                    <button type="button" data-booking-success-close
                            class="absolute top-4 right-4 w-8 h-8 rounded-full bg-white/60 hover:bg-white/80 flex items-center justify-center transition">
                        <svg class="w-4 h-4 text-dark" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                    <p class="text-xs font-bold uppercase tracking-widest text-gray-400 mb-1">Booking</p>
                    <p class="text-xl font-bold text-dark">Booking Berhasil</p>
                    <p class="text-xs text-gray-500 mt-1">{{ session('booking_success') }}</p>
                </div>
                <div class="p-6 space-y-3">
                    @if($bookingPaymentUrl)
                        <a href="{{ $bookingPaymentUrl }}"
                           class="flex items-center justify-center gap-2 w-full py-3 rounded-xl text-sm font-bold bg-accent text-cream transition hover:opacity-90">
                            Lanjut Pembayaran
                        </a>
                    @endif
                    <a href="{{ route('vendor.detail', $vendor) }}"
                       class="w-full flex items-center justify-center py-3 rounded-xl text-sm font-bold border border-gray-200 text-dark hover:bg-gray-50 transition">
                        Kembali Ke Vendor
                    </a>
                </div>
        </x-ui.modal>
    @endif

    <script>
        (function () {
            var sel = document.getElementById('booking-vendor-package-select');
            var box = document.getElementById('booking-package-summary');
            var nameEl = document.getElementById('booking-summary-name');
            var priceEl = document.getElementById('booking-summary-price');
            var guestsEl = document.getElementById('booking-summary-guests');
            var qtyEl = document.getElementById('booking-qty');
            var qtyLabelEl = document.getElementById('booking-summary-qty');
            var qtyInputHidden = document.querySelector('input[name="qty"]');
            var dpWrap = document.getElementById('booking-summary-dp-wrap');
            var dpEl = document.getElementById('booking-summary-dp');
            if (!sel || !box || !nameEl || !priceEl || !guestsEl) return;

            function clamp(v) {
                v = parseInt(String(v || '').replace(/\\D+/g, ''), 10);
                if (!Number.isFinite(v) || v < 1) v = 1;
                if (v > 99) v = 99;
                return v;
            }
            function money(n) {
                n = parseInt(String(n || '0').replace(/\\D+/g, ''), 10);
                if (!Number.isFinite(n)) n = 0;
                return 'Rp ' + n.toLocaleString('id-ID');
            }

            function update() {
                var opt = sel.options[sel.selectedIndex];
                var id = sel.value;
                if (!id) {
                    box.classList.add('hidden');
                    return;
                }
                nameEl.textContent = opt.getAttribute('data-name') || opt.textContent;
                var qty = qtyEl ? clamp(qtyEl.value) : 1;
                if (qtyEl) qtyEl.value = String(qty);
                if (qtyLabelEl) qtyLabelEl.textContent = String(qty);
                if (qtyInputHidden) qtyInputHidden.value = String(qty);

                var priceRaw = parseInt(opt.getAttribute('data-price-raw') || '0', 10) || 0;
                var discount = parseInt(opt.getAttribute('data-discount') || '0', 10) || 0;
                var unitFinal = Math.max(priceRaw - discount, 0);
                priceEl.textContent = money(unitFinal * qty);
                guestsEl.textContent = opt.getAttribute('data-guests') || '';
                if (dpWrap && dpEl) {
                    var dp = parseInt(opt.getAttribute('data-dp') || '0', 10) || 0;
                    if (dp > 0) {
                        dpWrap.classList.remove('hidden');
                        dpEl.textContent = money(dp * qty);
                    } else {
                        dpWrap.classList.add('hidden');
                        dpEl.textContent = '';
                    }
                }
                box.classList.remove('hidden');
            }

            sel.addEventListener('change', update);
            document.addEventListener('click', function (e) {
                var btn = e.target.closest('[data-booking-qty]');
                if (!btn || !qtyEl) return;
                var delta = parseInt(btn.getAttribute('data-booking-qty') || '0', 10);
                if (!Number.isFinite(delta) || delta === 0) return;
                qtyEl.value = String(clamp(clamp(qtyEl.value) + delta));
                update();
            });
            if (qtyEl) {
                qtyEl.addEventListener('input', function () {
                    qtyEl.value = String(clamp(qtyEl.value));
                    update();
                });
            }
            update();
        })();
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const modal = document.getElementById('booking-success-modal');
            if (!modal) return;

            const closeBtn = modal.querySelector('[data-booking-success-close]');

            function open() {
                modal.classList.remove('hidden');
                modal.classList.add('grid');
                document.body.classList.add('overflow-hidden');
            }

            function close() {
                modal.classList.add('hidden');
                modal.classList.remove('grid');
                document.body.classList.remove('overflow-hidden');
            }

            modal.addEventListener('click', (e) => {
                if (e.target === modal) close();
            });

            if (closeBtn) closeBtn.addEventListener('click', close);

            document.addEventListener('keydown', (e) => {
                if (e.key !== 'Escape') return;
                if (!modal.classList.contains('hidden')) close();
            });

            const hasBookingSuccess = {{ session()->has('booking_success') ? 'true' : 'false' }};
            if (hasBookingSuccess) open();
        });
    </script>

    @include('layout.footer')
@endsection
