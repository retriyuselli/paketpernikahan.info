@extends('layout.dashboard')

@section('title', 'Invoice Booking — Makna Wedding')
@section('page-title', 'Invoice Booking')

@section('content')
@php
    $invoiceNo = 'INV-' . str_pad((string) $booking->id, 6, '0', STR_PAD_LEFT);
    $issuedAt = $booking->updated_at ?: now();
    $vendor = $booking->vendor;
    $package = $booking->vendorPackage;
    $statusLabel = match($booking->status) {
        'done' => 'Done',
        'confirmed' => 'Confirmed',
        'cancelled' => 'Cancelled',
        'no_response' => 'No Response',
        'contacted' => 'Contacted',
        default => 'Pending',
    };
@endphp

<style>
    @media print {
        .no-print { display: none !important; }
        .print-area { box-shadow: none !important; border: none !important; }
        body { background: #ffffff !important; }
        body > .sticky { display: none !important; }
        aside { display: none !important; }
        footer { display: none !important; }
        main { padding: 0 !important; max-width: none !important; }
    }
</style>

<div class="no-print mb-6 flex items-center justify-between gap-3">
    <div>
        <h1 class="text-2xl font-bold mb-1" style="color: var(--dark-gray)">Invoice Booking</h1>
        <p class="text-sm text-gray-500">Simpan atau cetak sebagai bukti transaksi.</p>
    </div>
    <div class="flex items-center gap-2">
        <a href="{{ route('dashboard.booking') }}" class="text-xs font-bold px-4 py-2 rounded-lg bg-gray-50 hover:bg-gray-100 transition" style="color: var(--dark-gray)">
            Kembali
        </a>
        <button type="button" onclick="window.print()" class="text-xs font-bold px-4 py-2 rounded-lg transition hover:opacity-90" style="background-color: var(--dark-gray); color: var(--cream)">
            Cetak
        </button>
    </div>
</div>

<div class="print-area bg-white rounded-2xl border border-gray-100 overflow-hidden">
    <div class="p-6 border-b border-gray-100">
        <div class="flex items-start justify-between gap-6">
            <div class="min-w-0">
                <div class="text-xs uppercase tracking-widest text-gray-400 mb-1">Invoice</div>
                <div class="text-xl font-extrabold leading-tight" style="color: var(--dark-gray)">{{ $invoiceNo }}</div>
                <div class="text-xs text-gray-500 mt-2">
                    Tanggal invoice: <span class="font-semibold">{{ $issuedAt->format('d M Y, H:i') }}</span>
                </div>
                <div class="text-xs text-gray-500 mt-1">
                    Status booking: <span class="font-semibold">{{ $statusLabel }}</span>
                </div>
                <div class="text-xs text-gray-500 mt-1">
                    Status pembayaran: <span class="font-semibold">{{ $booking->payment_status }}</span>
                </div>
            </div>
            <div class="text-right">
                <div class="text-xs uppercase tracking-widest text-gray-400 mb-1">Vendor</div>
                <div class="text-sm font-bold" style="color: var(--dark-gray)">{{ $vendor?->name ?? '—' }}</div>
                <div class="text-xs text-gray-500 mt-1">{{ $vendor?->city }}{{ $vendor?->location ? ' · ' . $vendor->location : '' }}</div>
            </div>
        </div>
    </div>

    <div class="p-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
            <div class="rounded-2xl border border-gray-100 p-5">
                <div class="text-xs uppercase tracking-widest text-gray-400 mb-2">Customer</div>
                <div class="text-sm font-bold" style="color: var(--dark-gray)">{{ $user->name }}</div>
                <div class="text-xs text-gray-500 mt-1">{{ $user->email }}</div>
                <div class="text-xs text-gray-500 mt-1">WhatsApp: <span class="font-semibold">{{ $booking->phone }}</span></div>
            </div>
            <div class="rounded-2xl border border-gray-100 p-5">
                <div class="text-xs uppercase tracking-widest text-gray-400 mb-2">Event</div>
                <div class="text-sm font-bold" style="color: var(--dark-gray)">{{ $booking->event_date?->format('d M Y') }}</div>
                @if(filled($booking->notes))
                    <div class="text-xs text-gray-500 mt-2">
                        <span class="font-semibold text-gray-600">Catatan:</span> {{ $booking->notes }}
                    </div>
                @endif
            </div>
        </div>

        <div class="rounded-2xl border border-gray-100 overflow-hidden mb-6">
            <div class="px-5 py-3 bg-gray-50 text-xs uppercase tracking-widest text-gray-400">Rincian</div>
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="bg-white text-gray-500 text-xs uppercase tracking-wider border-b border-gray-100">
                        <tr>
                            <th class="text-left px-5 py-3 font-semibold">Item</th>
                            <th class="text-left px-5 py-3 font-semibold">Keterangan</th>
                            <th class="text-right px-5 py-3 font-semibold">Nominal</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <tr>
                            <td class="px-5 py-3">
                                <div class="text-xs font-bold" style="color: var(--dark-gray)">{{ $package?->name ?? 'Booking Vendor' }}</div>
                                <div class="text-[10px] text-gray-400 mt-1">{{ $vendor?->name }}</div>
                            </td>
                            <td class="px-5 py-3 text-xs text-gray-600">
                                @if($booking->agreed_total)
                                    Total disepakati
                                @else
                                    Harga mulai paket
                                @endif
                                @if($booking->dp_required_amount)
                                    · DP {{ number_format((int) $booking->dp_required_amount, 0, ',', '.') }}
                                @endif
                            </td>
                            <td class="px-5 py-3 text-right">
                                @php
                                    $amount = (int) ($booking->agreed_total ?: ($package->price_raw ?? 0));
                                @endphp
                                <div class="text-xs font-bold" style="color: var(--dark-gray)">{{ number_format($amount, 0, ',', '.') }}</div>
                            </td>
                        </tr>
                    </tbody>
                    <tfoot class="bg-white border-t border-gray-100">
                        <tr>
                            <td colspan="2" class="px-5 py-3 text-right text-xs font-semibold text-gray-500">Total</td>
                            <td class="px-5 py-3 text-right text-sm font-extrabold" style="color: var(--sage-green)">{{ number_format($amount, 0, ',', '.') }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>

        <div class="rounded-2xl border border-gray-100 overflow-hidden">
            <div class="px-5 py-3 bg-gray-50 text-xs uppercase tracking-widest text-gray-400">Riwayat Pembayaran</div>
            @if(($booking->payments ?? collect())->isEmpty())
                <div class="p-5 text-sm text-gray-500">Belum ada pembayaran.</div>
            @else
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead class="bg-white text-gray-500 text-xs uppercase tracking-wider border-b border-gray-100">
                            <tr>
                                <th class="text-left px-5 py-3 font-semibold">Tanggal</th>
                                <th class="text-left px-5 py-3 font-semibold">Tipe</th>
                                <th class="text-left px-5 py-3 font-semibold">Metode</th>
                                <th class="text-left px-5 py-3 font-semibold">Status</th>
                                <th class="text-right px-5 py-3 font-semibold">Nominal</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach($booking->payments as $p)
                                <tr>
                                    <td class="px-5 py-3 text-xs text-gray-600">{{ $p->paid_at?->format('d M Y') ?? ($p->created_at?->format('d M Y') ?? '—') }}</td>
                                    <td class="px-5 py-3 text-xs text-gray-600">{{ $p->type }}</td>
                                    <td class="px-5 py-3 text-xs text-gray-600">{{ $p->method }}</td>
                                    <td class="px-5 py-3 text-xs text-gray-600">{{ $p->status }}</td>
                                    <td class="px-5 py-3 text-right text-xs font-bold" style="color: var(--dark-gray)">{{ number_format((int) $p->amount, 0, ',', '.') }}</td>
                                </tr>
                                @if(filled($p->note))
                                    <tr class="bg-gray-50/40">
                                        <td colspan="5" class="px-5 pb-3 pt-2 text-xs text-gray-500">
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
@endsection
