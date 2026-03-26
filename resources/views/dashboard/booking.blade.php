@extends('layout.dashboard')

@section('title', 'Booking Saya — Makna Wedding')
@section('page-title', 'Booking Saya')

@section('content')
<div class="mb-8">
    <h1 class="text-2xl font-bold mb-2" style="color: var(--dark-gray)">Booking Saya</h1>
    <p class="text-sm text-gray-500">Daftar booking yang pernah Anda kirim ke vendor.</p>
</div>

@if (session('booking_success'))
    <div class="mb-4 text-xs font-semibold px-3 py-2 rounded-xl bg-green-50 text-green-700">
        {{ session('booking_success') }}
    </div>
@endif
@if (session('booking_error'))
    <div class="mb-4 text-xs font-semibold px-3 py-2 rounded-xl bg-red-50 text-red-700">
        {{ session('booking_error') }}
    </div>
@endif

@if($bookings->isEmpty())
    <div class="bg-white rounded-2xl border border-gray-100 p-10 text-center flex flex-col items-center">
        <div class="w-16 h-16 rounded-full bg-gray-50 flex items-center justify-center mb-4">
            <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
        </div>
        <h3 class="text-base font-bold mb-1" style="color: var(--dark-gray)">Belum ada booking</h3>
        <p class="text-sm text-gray-500 max-w-sm mb-6">Anda belum mengirim booking apa pun. Jelajahi vendor dan gunakan tombol Booking Sekarang untuk mengirim permintaan.</p>
        <a href="{{ route('vendor') }}" class="inline-flex items-center justify-center px-6 py-2.5 rounded-xl text-sm font-bold text-white transition hover:opacity-90" style="background-color: var(--sage-green)">
            Jelajahi Vendor
        </a>
    </div>
@else
    <div class="bg-white rounded-2xl border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="bg-gray-50 text-gray-500 text-xs uppercase tracking-wider">
                    <tr>
                        <th class="text-left px-4 py-3 font-semibold">No</th>
                        <th class="text-left px-4 py-3 font-semibold">Vendor</th>
                        <th class="text-left px-4 py-3 font-semibold">Paket</th>
                        <th class="text-left px-4 py-3 font-semibold">Tanggal</th>
                        <th class="text-left px-4 py-3 font-semibold">WhatsApp</th>
                        <th class="text-left px-4 py-3 font-semibold">Status</th>
                        <th class="text-right px-4 py-3 font-semibold"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($bookings as $i => $b)
                        <tr class="hover:bg-gray-50/60 transition">
                            <td class="px-4 py-3 text-xs text-gray-500">
                                {{ $i + 1 }}
                            </td>
                            <td class="px-4 py-3">
                                <div class="font-bold text-xs" style="color: var(--dark-gray)">{{ $b->vendor?->name ?? '—' }}</div>
                                <div class="text-[10px] mt-1 text-gray-400">{{ $b->created_at?->format('d M Y, H:i') }}</div>
                            </td>
                            <td class="px-4 py-3">
                                <div class="font-bold text-xs" style="color: var(--dark-gray)">{{ $b->vendorPackage?->name ?? 'Tanpa paket' }}</div>
                            </td>
                            <td class="px-4 py-3 text-gray-600 text-xs">
                                {{ $b->event_date?->format('d M Y') }}
                            </td>
                            <td class="px-4 py-3 text-gray-600 text-xs">
                                {{ $b->phone }}
                            </td>
                            <td class="px-4 py-3">
                                @php
                                    $badgeClass = match($b->status) {
                                        'confirmed' => 'bg-green-50 text-green-700',
                                        'cancelled' => 'bg-red-50 text-red-700',
                                        default => 'bg-yellow-50 text-yellow-700',
                                    };
                                    $statusLabel = match($b->status) {
                                        'confirmed' => 'Confirmed',
                                        'cancelled' => 'Cancelled',
                                        default => 'Pending',
                                    };
                                @endphp
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs {{ $badgeClass }}">
                                    {{ $statusLabel }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-right">
                                <div class="flex items-center justify-end gap-2">
                                @if($b->status === 'pending')
                                    <a href="{{ route('dashboard.booking.edit', $b) }}" class="text-xs font-bold px-3 py-2 rounded-lg bg-gray-50 hover:bg-gray-100 transition" style="color: var(--dark-gray)">
                                        Edit
                                    </a>
                                @endif
                                @if($b->vendor)
                                    <a href="{{ route('vendor.detail', $b->vendor->slug) }}" class="text-xs font-bold px-3 py-2 rounded-lg bg-gray-50 hover:bg-gray-100 transition" style="color: var(--dark-gray)">
                                        Lihat
                                    </a>
                                @endif
                                </div>
                            </td>
                        </tr>
                        @if(filled($b->notes))
                            <tr class="bg-gray-50/40">
                                <td colspan="7" class="px-4 pb-3 pt-2 text-xs text-gray-500">
                                    <span class="font-semibold text-gray-600">Catatan:</span> {{ $b->notes }}
                                </td>
                            </tr>
                        @endif
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endif
@endsection
