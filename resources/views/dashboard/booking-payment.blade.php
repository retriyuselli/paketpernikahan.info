@extends('layout.dashboard')

@section('title', 'Pembayaran Booking — Makna Wedding')
@section('page-title', 'Pembayaran Booking')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold mb-1" style="color: var(--dark-gray)">{{ in_array($booking->payment_status, ['dp_paid', 'paid'], true) ? 'Invoice Booking' : 'Pembayaran Booking' }}</h1>
    <p class="text-sm text-gray-500">
        {{ in_array($booking->payment_status, ['dp_paid', 'paid'], true) ? 'Rincian pembayaran yang sudah diverifikasi.' : 'Unggah bukti pembayaran untuk diverifikasi vendor.' }}
    </p>
</div>

<div class="bg-white rounded-2xl border border-gray-100 overflow-hidden">
    <div class="p-6 border-b border-gray-100">
        <div class="text-xs uppercase tracking-widest text-gray-400 mb-1">Vendor</div>
        <div class="text-lg font-bold" style="color: var(--dark-gray)">{{ $booking->vendor?->name ?? '—' }}</div>
        <div class="text-xs text-gray-500 mt-1">
            Tanggal acara: <span class="font-semibold">{{ $booking->event_date?->format('d M Y') }}</span>
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
                    <select name="type" class="w-full h-11 border border-gray-200 rounded-xl px-3.5 py-2.5 text-sm focus:outline-none focus:border-gray-400 transition">
                        <option value="dp" {{ old('type') === 'dp' ? 'selected' : '' }}>DP</option>
                        <option value="final" {{ old('type') === 'final' ? 'selected' : '' }}>Pelunasan</option>
                        <option value="installment" {{ old('type') === 'installment' ? 'selected' : '' }}>Cicilan</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-500 mb-1">Metode</label>
                    <select name="method" class="w-full h-11 border border-gray-200 rounded-xl px-3.5 py-2.5 text-sm focus:outline-none focus:border-gray-400 transition">
                        <option value="transfer" {{ old('method') === 'transfer' ? 'selected' : '' }}>Transfer</option>
                        <option value="qris" {{ old('method') === 'qris' ? 'selected' : '' }}>QRIS</option>
                        <option value="cash" {{ old('method') === 'cash' ? 'selected' : '' }}>Cash</option>
                        <option value="other" {{ old('method') === 'other' ? 'selected' : '' }}>Lainnya</option>
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
                    <a href="{{ route('dashboard.booking') }}" class="text-xs font-bold px-4 py-2 rounded-lg bg-gray-50 hover:bg-gray-100 transition" style="color: var(--dark-gray)">
                        Kembali
                    </a>
                    <button type="submit" class="text-xs font-bold px-4 py-2 rounded-lg transition hover:opacity-90" style="background-color: var(--sage-green); color: var(--cream)">
                        Kirim Bukti
                    </button>
                </div>
            </form>
        @else
            <div class="mb-6 text-xs font-semibold px-3 py-2 rounded-xl bg-green-50 text-green-700">
                Pembayaran sudah diverifikasi. Silakan simpan invoice ini sebagai bukti.
            </div>
            <div class="flex items-center justify-end gap-2 mb-2">
                <a href="{{ route('dashboard.booking.invoice', $booking) }}" class="text-xs font-bold px-4 py-2 rounded-lg transition hover:opacity-90" style="background-color: var(--dark-gray); color: var(--cream)">
                    Lihat Invoice
                </a>
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
                                            <a href="{{ $p->proof_url }}" target="_blank" class="font-bold hover:underline" style="color: var(--dark-gray)">Lihat</a>
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
        var display = document.getElementById('amount_display');
        var raw = document.getElementById('amount_raw');
        if (!display || !raw) return;

        function digitsOnly(value) {
            return String(value || '').replace(/\D+/g, '');
        }

        function formatId(value) {
            if (!value) return '';
            var n = Number(value);
            if (!Number.isFinite(n)) return '';
            return new Intl.NumberFormat('id-ID').format(n);
        }

        function syncFromDisplay() {
            var digits = digitsOnly(display.value);
            raw.value = digits;
            display.value = digits ? formatId(digits) : '';
        }

        function syncFromRaw() {
            var digits = digitsOnly(raw.value);
            raw.value = digits;
            display.value = digits ? formatId(digits) : '';
        }

        display.addEventListener('input', syncFromDisplay);
        display.addEventListener('blur', syncFromDisplay);
        syncFromRaw();
    })();
</script>
@endsection
