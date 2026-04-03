@extends('layout.dashboard')

@section('title', 'Booking User — Makna Wedding')
@section('page-title', 'Booking User')

@section('content')
<div class="mb-8">
    <h1 class="text-2xl font-bold mb-2 text-dark">Booking User</h1>
    <p class="text-sm text-gray-500">Daftar booking terbaru dari semua user.</p>
</div>

@if($bookings->isEmpty())
    <div class="bg-white rounded-2xl border border-gray-100 p-10 text-center flex flex-col items-center">
        <div class="w-16 h-16 rounded-full bg-gray-50 flex items-center justify-center mb-4">
            <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
        </div>
        <h3 class="text-base font-bold mb-1 text-dark">Belum ada booking</h3>
        <p class="text-sm text-gray-500 max-w-sm">Belum ada booking yang masuk.</p>
    </div>
@else
    <div class="bg-white rounded-2xl border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="bg-gray-50 text-gray-500 text-xs uppercase tracking-wider">
                    <tr>
                        <th class="text-left px-4 py-3 font-semibold">No</th>
                        <th class="text-left px-4 py-3 font-semibold">User</th>
                        <th class="text-left px-4 py-3 font-semibold">Vendor</th>
                        <th class="text-left px-4 py-3 font-semibold">Paket</th>
                        <th class="text-left px-4 py-3 font-semibold">Tanggal</th>
                        <th class="text-left px-4 py-3 font-semibold">WhatsApp</th>
                        <th class="text-left px-4 py-3 font-semibold">Status</th>
                        <th class="text-left px-4 py-3 font-semibold"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($bookings as $i => $b)
                        @php
                            $badgeClass = match($b->status) {
                                'done' => 'bg-green-50 text-green-700',
                                'confirmed' => 'bg-green-50 text-green-700',
                                'cancelled' => 'bg-red-50 text-red-700',
                                'no_response' => 'bg-gray-100 text-gray-700',
                                'contacted' => 'bg-blue-50 text-blue-700',
                                default => 'bg-yellow-50 text-yellow-700',
                            };
                            $statusLabel = match($b->status) {
                                'done' => 'Done',
                                'confirmed' => 'Confirmed',
                                'cancelled' => 'Cancelled',
                                'no_response' => 'No Response',
                                'contacted' => 'Contacted',
                                default => 'Pending',
                            };
                        @endphp
                        <tr class="hover:bg-gray-50/60 transition {{ $b->status === 'pending' ? 'bg-amber-50/20' : '' }}">
                            <td class="px-4 py-3 text-xs text-gray-500 align-top">{{ $i + 1 }}</td>
                            <td class="px-4 py-3 align-top">
                                <div class="font-bold text-xs text-dark">{{ $b->user?->name ?? '—' }}</div>
                                <div class="text-[10px] mt-1 text-gray-400">{{ $b->user?->email ?? '' }}</div>
                            </td>
                            <td class="px-4 py-3 align-top">
                                <div class="font-bold text-xs text-dark">{{ $b->vendor?->name ?? '—' }}</div>
                                <div class="text-[10px] mt-1 text-gray-400">{{ $b->created_at?->format('d M Y, H:i') }}</div>
                            </td>
                            <td class="px-4 py-3 align-top">
                                <div class="font-bold text-xs text-dark">{{ $b->vendorPackage?->name ?? 'Tanpa paket' }}</div>
                                @if($b->vendorPackage?->price)
                                    <div class="text-[10px] mt-1 text-gray-400">{{ $b->vendorPackage->price }}</div>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-gray-600 text-xs align-top">{{ $b->event_date?->format('d M Y') }}</td>
                            <td class="px-4 py-3 text-gray-600 text-xs align-top">
                                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $b->phone) }}" target="_blank" class="hover:underline">
                                    {{ $b->phone }}
                                </a>
                            </td>
                            <td class="px-4 py-3 align-top">
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs {{ $badgeClass }}">
                                    {{ $statusLabel }}
                                </span>
                            </td>
                            <td class="px-4 py-3 align-top">
                                @if($b->vendor)
                                    <a href="{{ route('vendor.detail', $b->vendor->slug) }}" class="text-xs font-bold px-3 py-2 rounded-lg bg-gray-50 text-dark hover:bg-gray-100 transition">
                                        Lihat
                                    </a>
                                @endif
                            </td>
                        </tr>
                        @if(filled($b->notes))
                            <tr class="bg-gray-50/40">
                                <td colspan="8" class="px-4 pb-3 pt-2 text-xs text-gray-500">
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
