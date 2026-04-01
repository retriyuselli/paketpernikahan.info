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
        $selectedId = old('vendor_package_id', $selectedPackage?->id);
        $hasPackages = isset($packages) && $packages->count() > 0;
        $bookingId = session('booking_id');
        $bookingPaymentUrl = $bookingId ? route('dashboard.booking.payment', $bookingId) : null;
    @endphp

    <section class="py-8" style="background-color: var(--cream)">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="pt-4 pb-4">
                @include('layout.breadcrumb', ['items' => $breadcrumbItems])
            </div>

            @if(($vendorBookingDisabled ?? false))
                <div class="bg-white rounded-2xl border border-gray-100 overflow-hidden">
                    <div class="p-6 pb-5" style="background: var(--cream)">
                        <p class="text-xs font-bold uppercase tracking-widest text-gray-400 mb-1">Pemberitahuan</p>
                        <p class="text-xl font-bold" style="color: var(--dark-gray)">Vendor Belum Lengkap</p>
                        <p class="text-xs text-gray-500 mt-1">Profil vendor ini belum lengkap 100% sehingga booking belum dapat dilakukan.</p>
                    </div>
                    <div class="p-6">
                        <a href="{{ $vendorBookingBackUrl ?? route('vendor.detail', $vendor) }}"
                           class="flex items-center justify-center w-full py-3 rounded-xl text-sm font-bold transition hover:opacity-90"
                           style="background-color: var(--dark-gray); color: var(--cream)">
                            Kembali
                        </a>
                    </div>
                </div>
            @else
                <div class="bg-white rounded-3xl border border-gray-100 overflow-hidden shadow-sm">
                    <div class="p-6 pb-5" style="background: var(--cream)">
                        <p class="text-xs font-bold uppercase tracking-widest text-gray-400 mb-1">Booking</p>
                        <p class="text-xl font-bold" style="color: var(--dark-gray)">{{ $vendor->name }}</p>
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
                               class="flex items-center justify-center gap-2 w-full py-3 rounded-xl text-sm font-bold transition hover:opacity-90"
                               style="background-color: var(--sage-green); color: var(--cream)">
                                Login untuk Booking
                            </a>
                        @endguest

                        @auth
                            <form method="POST" action="{{ route('vendor.booking.store', $vendor) }}" class="space-y-3">
                                @csrf

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
                                                        {{ (int) $selectedId === (int) $pkg->id ? 'selected' : '' }}>
                                                    {{ $pkg->name }} — {{ $pkg->price }}
                                                </option>
                                            @endforeach
                                        @endif
                                    </select>
                                    <div id="booking-package-summary" class="{{ $selectedId ? '' : 'hidden' }} mt-3 rounded-2xl p-4 border border-gray-100" style="background-color: var(--cream)">
                                        <div class="flex items-center justify-between mb-1">
                                            <span class="text-xs text-gray-500">Paket dipilih</span>
                                            <span id="booking-summary-name" class="text-xs font-bold" style="color: var(--dark-gray)">{{ $selectedPackage?->name }}</span>
                                        </div>
                                        <div class="flex items-center justify-between">
                                            <span class="text-xs text-gray-500">Nominal</span>
                                            <span id="booking-summary-price" class="text-base font-bold" style="color: var(--sage-green)">{{ $selectedPackage?->price }}</span>
                                        </div>
                                        <div class="mt-1 text-[10px] text-gray-500">
                                            <span id="booking-summary-guests">{{ $selectedPackage?->max_guests }}</span> Pax
                                        </div>
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
                                        class="flex items-center justify-center gap-2 w-full py-3 rounded-xl text-sm font-bold transition hover:opacity-90"
                                        style="background-color: var(--sage-green); color: var(--cream)">
                                    Kirim Booking
                                </button>
                            </form>
                        @endauth

                        <div class="pt-1">
                            <a href="{{ $vendorBookingBackUrl ?? route('vendor.detail', $vendor) }}"
                               class="w-full inline-flex items-center justify-center py-2.5 rounded-xl text-sm font-semibold border border-gray-200 hover:bg-gray-50 transition"
                               style="color: var(--dark-gray)">
                                Kembali
                            </a>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </section>

    @if(session('booking_success'))
        <div id="booking-success-modal"
             class="fixed inset-0 z-50 flex items-center justify-center p-4 hidden"
             onclick="if(event.target===this) closeBookingSuccessModal()">
            <div class="absolute inset-0 bg-black/50 backdrop-blur-sm"></div>
            <div class="relative bg-white rounded-3xl shadow-2xl w-full max-w-md overflow-hidden">
                <div class="p-6 pb-5" style="background: var(--cream)">
                    <button type="button" onclick="closeBookingSuccessModal()"
                            class="absolute top-4 right-4 w-8 h-8 rounded-full bg-white/60 hover:bg-white/80 flex items-center justify-center transition">
                        <svg class="w-4 h-4" style="color: var(--dark-gray)" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                    <p class="text-xs font-bold uppercase tracking-widest text-gray-400 mb-1">Booking</p>
                    <p class="text-xl font-bold" style="color: var(--dark-gray)">Booking Berhasil</p>
                    <p class="text-xs text-gray-500 mt-1">{{ session('booking_success') }}</p>
                </div>
                <div class="p-6 space-y-3">
                    @if($bookingPaymentUrl)
                        <a href="{{ $bookingPaymentUrl }}"
                           class="flex items-center justify-center gap-2 w-full py-3 rounded-xl text-sm font-bold transition hover:opacity-90"
                           style="background-color: var(--sage-green); color: var(--cream)">
                            Lanjut Pembayaran
                        </a>
                    @endif
                    <a href="{{ route('vendor.detail', $vendor) }}"
                       class="w-full flex items-center justify-center py-3 rounded-xl text-sm font-bold border border-gray-200 hover:bg-gray-50 transition"
                       style="color: var(--dark-gray)">
                        Kembali Ke Vendor
                    </a>
                </div>
            </div>
        </div>
    @endif

    <script>
        (function () {
            var sel = document.getElementById('booking-vendor-package-select');
            var box = document.getElementById('booking-package-summary');
            var nameEl = document.getElementById('booking-summary-name');
            var priceEl = document.getElementById('booking-summary-price');
            var guestsEl = document.getElementById('booking-summary-guests');
            if (!sel || !box || !nameEl || !priceEl || !guestsEl) return;

            function update() {
                var opt = sel.options[sel.selectedIndex];
                var id = sel.value;
                if (!id) {
                    box.classList.add('hidden');
                    return;
                }
                nameEl.textContent = opt.getAttribute('data-name') || opt.textContent;
                priceEl.textContent = opt.getAttribute('data-price') || '';
                guestsEl.textContent = opt.getAttribute('data-guests') || '';
                box.classList.remove('hidden');
            }

            sel.addEventListener('change', update);
            update();
        })();
    </script>

    <script>
        function closeBookingSuccessModal() {
            const modal = document.getElementById('booking-success-modal');
            if (!modal) return;
            modal.classList.add('hidden');
            document.body.style.overflow = '';
        }

        document.addEventListener('DOMContentLoaded', () => {
            const hasBookingSuccess = {{ session()->has('booking_success') ? 'true' : 'false' }};
            if (!hasBookingSuccess) return;
            const modal = document.getElementById('booking-success-modal');
            if (!modal) return;
            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        });
    </script>

    @include('layout.footer')
@endsection
