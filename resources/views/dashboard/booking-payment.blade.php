@extends('layout.dashboard')

@section('title', 'Pembayaran Booking — Makna Wedding')
@section('page-title', 'Pembayaran Booking')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold mb-1 text-dark">{{ in_array($booking->payment_status, ['dp_paid', 'paid'], true) ? 'Invoice Booking' : 'Pembayaran Booking' }}</h1>
    <p class="text-sm text-gray-500">
        {{ in_array($booking->payment_status, ['dp_paid', 'paid'], true) ? 'Rincian pembayaran yang sudah diverifikasi.' : 'Unggah bukti pembayaran untuk diverifikasi vendor.' }}
    </p>
</div>

<div class="bg-white rounded-2xl border border-gray-100 overflow-hidden">
    <div class="p-6 border-b border-gray-100">
        <div class="text-xs uppercase tracking-widest text-gray-400 mb-1">Vendor</div>
        <div class="text-lg font-bold text-dark">{{ $booking->vendor?->name ?? '—' }}</div>
        <div class="text-xs text-gray-500 mt-1">
            Tanggal acara: <span class="font-semibold">{{ $booking->event_date?->format('d M Y') }}</span>
        </div>
        @php
            $unitRawForQty = (int) ($booking->vendorPackage?->price ?? 0);
            $unitDiscountForQty = (int) ($booking->vendorPackage?->discount ?? 0);
            $unitFinalForQty = max($unitRawForQty - $unitDiscountForQty, 0);
            $agreedTotalForQty = (int) ($booking->agreed_total ?? 0);
            $qty = 1;
            if ($unitFinalForQty > 0 && $agreedTotalForQty > 0) {
                $guess = intdiv($agreedTotalForQty, $unitFinalForQty);
                if ($guess >= 1 && $guess <= 99 && ($guess * $unitFinalForQty) === $agreedTotalForQty) {
                    $qty = $guess;
                }
            }
        @endphp
        <div class="text-xs text-gray-500 mt-1">
            Jumlah: <span class="font-semibold">{{ $qty }}</span>
        </div>
        <div class="text-xs text-gray-500 mt-1">
            Status pembayaran: <span class="font-semibold">{{ $booking->payment_status }}</span>
        </div>
    </div>

    <div class="p-6">
        @if (session('payment_success'))
            <div class="mb-4 text-xs font-semibold px-3 py-2 rounded-xl bg-green-50 text-green-700">
                {{ session('payment_success') }}
            </div>
        @endif

        @if ($errors->payment->any())
            <div class="mb-4 text-xs font-semibold px-3 py-2 rounded-xl bg-red-50 text-red-700">
                <ul class="list-disc pl-4 space-y-1">
                    @foreach ($errors->payment->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if(!in_array($booking->payment_status, ['dp_paid', 'paid'], true))
            <form method="POST" action="{{ route('dashboard.booking.payment.store', $booking) }}" enctype="multipart/form-data" class="space-y-4">
                @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-500 mb-1">Tipe</label>
                    @php
                        $dpPaketAmount = (int) ($booking->dp_required_amount ?? ($booking->vendorPackage?->dp_paket ?? 0));
                        $dpPaketLabel = $dpPaketAmount > 0 ? ('Rp ' . number_format($dpPaketAmount, 0, ',', '.')) : '—';
                        $pkgFinal = (int) ($booking->agreed_total ?? 0);
                        $pkgTotalLabel = $pkgFinal > 0
                            ? ('Rp ' . number_format($pkgFinal, 0, ',', '.'))
                            : (($booking->vendorPackage?->price ?? null) ?: '—');
                    @endphp
                    <select name="type" class="w-full h-11 border border-gray-200 rounded-xl px-3.5 py-2.5 text-sm focus:outline-none focus:border-gray-400 transition">
                        <option value="dp" {{ old('type') === 'dp' ? 'selected' : '' }}>DP</option>
                        <option value="final" {{ old('type') === 'final' ? 'selected' : '' }}>Pelunasan</option>
                        {{-- <option value="installment" {{ old('type') === 'installment' ? 'selected' : '' }}>Cicilan</option> --}}
                    </select>
                    <p id="dp-helper" data-dp="{{ $dpPaketAmount }}" class="mt-1.5 text-[11px] text-gray-400 hidden">
                        DP paket (x {{ $qty }}): <span class="font-semibold text-gray-600">{{ $dpPaketLabel }}</span>
                    </p>
                    <p id="final-helper" data-total="{{ $pkgFinal }}" class="mt-1.5 text-[11px] text-gray-400 hidden">
                        Total paket (x {{ $qty }}): <span class="font-semibold text-gray-600">{{ $pkgTotalLabel }}</span>
                    </p>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-500 mb-1">Metode</label>
                    <select name="method" class="w-full h-11 border border-gray-200 rounded-xl px-3.5 py-2.5 text-sm focus:outline-none focus:border-gray-400 transition">
                        <option value="transfer" {{ old('method') === 'transfer' ? 'selected' : '' }}>Transfer</option>
                        <option value="qris" disabled {{ old('method') === 'qris' ? 'selected' : '' }}>QRIS</option>
                        <option value="cash" disabled {{ old('method') === 'cash' ? 'selected' : '' }}>Cash</option>
                        <option value="other" disabled {{ old('method') === 'other' ? 'selected' : '' }}>Lainnya</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-500 mb-1">Nominal (angka)</label>
                    <input type="hidden" name="amount" id="amount_raw" value="{{ old('amount') }}">
                    <input type="text" id="amount_display" inputmode="numeric" autocomplete="off"
                           value="{{ old('amount') !== null && old('amount') !== '' ? number_format((int) old('amount'), 0, ',', '.') : '' }}"
                           class="w-full h-11 border border-gray-200 rounded-xl px-3.5 py-2.5 text-sm focus:outline-none focus:border-gray-400 transition">
                    <p id="amount-dp-note" class="mt-1.5 text-[11px] text-amber-700 hidden"></p>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-500 mb-1">Tanggal bayar (opsional)</label>
                    <input type="date" name="paid_at" value="{{ old('paid_at') }}"
                           class="w-full h-11 border border-gray-200 rounded-xl px-3.5 py-2.5 text-sm focus:outline-none focus:border-gray-400 transition">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-500 mb-1">Nama pengirim</label>
                    <input type="text" name="sender_name" maxlength="120" value="{{ old('sender_name') }}"
                           required
                           class="w-full h-11 border border-gray-200 rounded-xl px-3.5 py-2.5 text-sm focus:outline-none focus:border-gray-400 transition">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-500 mb-1">Bank pengirim</label>
                    <input type="text" name="sender_bank" maxlength="80" value="{{ old('sender_bank') }}"
                           required
                           class="w-full h-11 border border-gray-200 rounded-xl px-3.5 py-2.5 text-sm focus:outline-none focus:border-gray-400 transition">
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-500 mb-1">Bukti pembayaran</label>
                <input type="file" name="proof" accept=".jpg,.jpeg,.png,.webp,.pdf"
                       required
                       class="w-full border border-gray-200 rounded-xl px-3.5 py-2.5 text-sm focus:outline-none focus:border-gray-400 transition bg-white">
                <p class="text-[10px] text-gray-400 mt-1.5">Format: JPG/PNG/WEBP/PDF, maks 5MB.</p>
            </div>

                <div class="flex items-center justify-end gap-2 pt-2">
                    <x-ui.button href="{{ route('dashboard.booking') }}" variant="ghost" size="compact">
                        Kembali
                    </x-ui.button>
                    <x-ui.button type="submit" variant="primary" size="compact">
                        Kirim Bukti
                    </x-ui.button>
                </div>
            </form>
        @else
            <div class="mb-6 text-xs font-semibold px-3 py-2 rounded-xl bg-green-50 text-green-700">
                Pembayaran sudah diverifikasi. Silakan simpan invoice ini sebagai bukti.
            </div>
            <div class="flex items-center justify-end gap-2 mb-2">
                <x-ui.button href="{{ route('dashboard.booking.invoice', $booking) }}" variant="dark" size="compact">
                    Lihat Invoice
                </x-ui.button>
            </div>
        @endif

        <div class="mt-8">
            <div class="text-xs uppercase tracking-widest text-gray-400 mb-2">Riwayat Pembayaran</div>
            @if(($booking->payments ?? collect())->isEmpty())
                <div class="text-sm text-gray-500 bg-gray-50 border border-gray-100 rounded-xl p-4">
                    Belum ada pembayaran.
                </div>
            @else
                <div class="overflow-x-auto border border-gray-100 rounded-xl">
                    <table class="min-w-full text-sm">
                        <thead class="bg-gray-50 text-gray-500 text-xs uppercase tracking-wider">
                            <tr>
                                <th class="text-left px-4 py-3 font-semibold">Tipe</th>
                                <th class="text-left px-4 py-3 font-semibold">Nominal</th>
                                <th class="text-left px-4 py-3 font-semibold">Metode</th>
                                <th class="text-left px-4 py-3 font-semibold">Status</th>
                                <th class="text-left px-4 py-3 font-semibold">Bukti</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach($booking->payments as $p)
                                <tr class="hover:bg-gray-50/60 transition">
                                    <td class="px-4 py-3 text-xs text-gray-600">{{ $p->type }}</td>
                                    <td class="px-4 py-3 text-xs text-gray-600">{{ number_format($p->amount) }}</td>
                                    <td class="px-4 py-3 text-xs text-gray-600">{{ $p->method }}</td>
                                    <td class="px-4 py-3 text-xs text-gray-600">{{ $p->status }}</td>
                                    <td class="px-4 py-3 text-xs">
                                        @if($p->proof_url)
                                            <a href="{{ $p->proof_url }}" target="_blank" class="font-bold text-dark hover:underline">Lihat</a>
                                        @else
                                            <span class="text-gray-400">—</span>
                                        @endif
                                    </td>
                                </tr>
                                @if(filled($p->note))
                                    <tr class="bg-gray-50/40">
                                        <td colspan="5" class="px-4 pb-3 pt-2 text-xs text-gray-500">
                                            <span class="font-semibold text-gray-600">Catatan:</span> {{ $p->note }}
                                        </td>
                                    </tr>
                                @endif
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</div>

<script>
    (function () {
        var typeSelect = document.querySelector('select[name="type"]');
        var dpHelper = document.getElementById('dp-helper');
        var finalHelper = document.getElementById('final-helper');
        var amountNote = document.getElementById('amount-dp-note');
        var submitBtn = document.querySelector('form[action*="booking"][action*="payment"] button[type="submit"]');

        function getDpValue() {
            if (!dpHelper) return 0;
            var n = parseInt(dpHelper.getAttribute('data-dp') || '0', 10);
            return Number.isFinite(n) ? n : 0;
        }
        function formatId(n) {
            if (!n) return '';
            return new Intl.NumberFormat('id-ID').format(n);
        }
        function syncAmountNote() {
            if (!typeSelect || !amountNote) return;
            if (typeSelect.value !== 'dp') {
                amountNote.classList.add('hidden');
                amountNote.textContent = '';
                if (submitBtn) {
                    submitBtn.disabled = false;
                    submitBtn.classList.remove('opacity-50', 'cursor-not-allowed', 'pointer-events-none');
                }
                return;
            }
            var dpVal = getDpValue();
            if (!dpVal) {
                amountNote.classList.add('hidden');
                amountNote.textContent = '';
                if (submitBtn) {
                    submitBtn.disabled = false;
                    submitBtn.classList.remove('opacity-50', 'cursor-not-allowed', 'pointer-events-none');
                }
                return;
            }
            var rawEl = document.getElementById('amount_raw');
            var amountVal = rawEl ? parseInt(rawEl.value || '0', 10) : 0;
            if (amountVal !== dpVal) {
                amountNote.textContent = 'Nominal tidak sama dengan DP paket (' + 'Rp ' + formatId(dpVal) + ').';
                amountNote.classList.remove('hidden');
                if (submitBtn) {
                    submitBtn.disabled = true;
                    submitBtn.classList.add('opacity-50', 'cursor-not-allowed', 'pointer-events-none');
                }
            } else {
                amountNote.classList.add('hidden');
                amountNote.textContent = '';
                if (submitBtn) {
                    submitBtn.disabled = false;
                    submitBtn.classList.remove('opacity-50', 'cursor-not-allowed', 'pointer-events-none');
                }
            }
        }

        function syncTypeHelpers() {
            if (!typeSelect) return;
            if (dpHelper) {
                if (typeSelect.value === 'dp') dpHelper.classList.remove('hidden');
                else dpHelper.classList.add('hidden');
            }
            if (finalHelper) {
                if (typeSelect.value === 'final') finalHelper.classList.remove('hidden');
                else finalHelper.classList.add('hidden');
            }
            syncAmountNote();
        }
        if (typeSelect) {
            typeSelect.addEventListener('change', syncTypeHelpers);
            syncTypeHelpers();
        }

        var display = document.getElementById('amount_display');
        var raw = document.getElementById('amount_raw');
        if (!display || !raw) return;

        function digitsOnly(value) {
            return String(value || '').replace(/\D+/g, '');
        }

        function syncFromDisplay() {
            var digits = digitsOnly(display.value);
            raw.value = digits;
            display.value = digits ? formatId(digits) : '';
            syncAmountNote();
        }

        function syncFromRaw() {
            var digits = digitsOnly(raw.value);
            raw.value = digits;
            display.value = digits ? formatId(digits) : '';
            syncAmountNote();
        }

        display.addEventListener('input', syncFromDisplay);
        display.addEventListener('blur', syncFromDisplay);
        syncFromRaw();
    })();
</script>
@endsection
