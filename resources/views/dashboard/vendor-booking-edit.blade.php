@extends('layout.dashboard')

@section('title', 'Kelola Booking — Makna Wedding')
@section('page-title', 'Kelola Booking')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold mb-1" style="color: var(--dark-gray)">Kelola Booking</h1>
    <p class="text-sm text-gray-500">Atur status booking dan info pembayaran (DP & total).</p>
</div>

<div class="bg-white rounded-2xl border border-gray-100 overflow-hidden">
    <div class="p-6 border-b border-gray-100">
        <div class="text-xs uppercase tracking-widest text-gray-400 mb-1">Vendor</div>
        <div class="text-lg font-bold" style="color: var(--dark-gray)">{{ $booking->vendor?->name ?? '—' }}</div>
        <div class="text-xs text-gray-500 mt-1">User: <span class="font-semibold">{{ $booking->user?->name ?? '—' }}</span> <span class="text-gray-400">{{ $booking->user?->email ? '— ' . $booking->user->email : '' }}</span></div>
        <div class="text-xs text-gray-500 mt-1">Tanggal acara: <span class="font-semibold">{{ $booking->event_date?->format('d M Y') }}</span></div>
        <div class="text-xs text-gray-500 mt-1">Status pembayaran: <span class="font-semibold">{{ $booking->payment_status }}</span></div>
    </div>

    <div class="p-6">
        @if (session('vendor_booking_success'))
            <div class="mb-4 text-xs font-semibold px-3 py-2 rounded-xl bg-green-50 text-green-700">
                {{ session('vendor_booking_success') }}
            </div>
        @endif

        @if ($errors->vendor_booking->any())
            <div class="mb-4 text-xs font-semibold px-3 py-2 rounded-xl bg-red-50 text-red-700">
                <ul class="list-disc pl-4 space-y-1">
                    @foreach ($errors->vendor_booking->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('dashboard.vendor.bookings.update', $booking) }}" class="space-y-4">
            @csrf
            @method('PUT')

            @php
                $defaultAgreed = $booking->vendorPackage?->price_raw ?? 0;
                $agreedValue = old('agreed_total', $booking->agreed_total ?? $defaultAgreed);
                $dpValue = old('dp_required_amount', $booking->dp_required_amount ?? 0);
            @endphp

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-500 mb-1">Status Booking</label>
                    <select name="status" class="w-full h-11 border border-gray-200 rounded-xl px-3.5 py-2.5 text-sm focus:outline-none focus:border-gray-400 transition">
                        @foreach(['pending','contacted','confirmed','done','no_response','cancelled'] as $st)
                            <option value="{{ $st }}" {{ old('status', $booking->status) === $st ? 'selected' : '' }}>{{ $st }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-500 mb-1">Total Deal (angka)</label>
                    <input type="hidden" id="agreed_total" name="agreed_total" value="{{ (int) $agreedValue }}">
                    <input type="text" id="agreed_total_text" readonly
                           value="{{ number_format((int) $agreedValue, 0, ',', '.') }}"
                           class="w-full h-11 border border-gray-200 rounded-xl px-3.5 py-2.5 text-sm bg-gray-50 text-gray-700 cursor-not-allowed">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-500 mb-1">DP yang Dibutuhkan (angka)</label>
                    <input type="hidden" id="dp_required_amount" name="dp_required_amount" value="{{ (int) $dpValue }}">
                    <input type="text" id="dp_required_amount_text" inputmode="numeric" autocomplete="off"
                           value="{{ number_format((int) $dpValue, 0, ',', '.') }}"
                           class="w-full h-11 border border-gray-200 rounded-xl px-3.5 py-2.5 text-sm focus:outline-none focus:border-gray-400 transition">
                </div>
                <div class="flex items-end justify-end gap-2">
                    <a href="{{ route('dashboard.vendor.bookings') }}" class="text-xs font-bold px-4 py-2 rounded-lg bg-gray-50 hover:bg-gray-100 transition" style="color: var(--dark-gray)">
                        Kembali
                    </a>
                    @if($user->hasRole(['super_admin', 'admin']))
                        <button type="submit"
                                form="vendor-booking-delete-{{ $booking->id }}"
                                class="text-xs font-bold px-4 py-2 rounded-lg transition hover:opacity-90"
                                style="background-color: #ef4444; color: #fff">
                            Hapus
                        </button>
                    @endif
                    <button type="submit" class="text-xs font-bold px-4 py-2 rounded-lg transition hover:opacity-90" style="background-color: var(--sage-green); color: var(--cream)">
                        Simpan
                    </button>
                </div>
            </div>
        </form>

        @if($user->hasRole(['super_admin', 'admin']))
            <form id="vendor-booking-delete-{{ $booking->id }}"
                  method="POST"
                  action="{{ route('dashboard.vendor.bookings.destroy', $booking) }}"
                  onsubmit="return confirm('Hapus booking ini?');"
                  class="hidden">
                @csrf
                @method('DELETE')
            </form>
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
                                    <td class="px-4 py-3 text-xs">
                                        <div class="font-bold" style="color: var(--dark-gray)">{{ $booking->status }}</div>
                                        <div class="text-[10px] text-gray-400">pembayaran: {{ $booking->payment_status }} · verifikasi: {{ $p->status }}</div>
                                    </td>
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
        function bindMoney(textId, hiddenId) {
            var text = document.getElementById(textId);
            var hidden = document.getElementById(hiddenId);
            if (!text || !hidden) return;
            if (text.hasAttribute('readonly')) return;

            function toInt(v) {
                v = String(v || '').replace(/\D+/g, '');
                v = parseInt(v || '0', 10);
                if (!Number.isFinite(v) || v < 0) v = 0;
                return v;
            }

            function fmt(v) {
                return toInt(v).toLocaleString('id-ID');
            }

            function sync() {
                var n = toInt(text.value);
                hidden.value = String(n);
                text.value = fmt(n);
            }

            text.addEventListener('input', sync);
            text.addEventListener('blur', sync);
            sync();
        }

        bindMoney('agreed_total_text', 'agreed_total');
        bindMoney('dp_required_amount_text', 'dp_required_amount');
    })();
</script>
@endsection
