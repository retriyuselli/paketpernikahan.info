@extends('layout.dashboard')

@section('title', 'Edit Booking — Makna Wedding')
@section('page-title', 'Edit Booking')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold mb-1" style="color: var(--dark-gray)">Edit Booking</h1>
    <p class="text-sm text-gray-500">Ubah detail booking selama status masih pending.</p>
</div>

@if ($booking->status !== 'pending')
    <div class="bg-white rounded-2xl border border-gray-100 p-6">
        <div class="text-sm font-semibold text-gray-700 mb-1">Booking tidak bisa diedit</div>
        <div class="text-sm text-gray-500 mb-4">Status booking saat ini: <span class="font-bold">{{ $booking->status }}</span></div>
        <a href="{{ route('dashboard.booking') }}" class="text-xs font-bold px-4 py-2 rounded-lg bg-gray-50 hover:bg-gray-100 transition" style="color: var(--dark-gray)">
            Kembali
        </a>
    </div>
@else
    <div class="bg-white rounded-2xl border border-gray-100 overflow-hidden">
        <div class="p-6 border-b border-gray-100">
            <div class="text-xs uppercase tracking-widest text-gray-400 mb-1">Vendor</div>
            <div class="text-lg font-bold" style="color: var(--dark-gray)">{{ $booking->vendor?->name ?? '—' }}</div>
            <div class="text-xs text-gray-500 mt-1">Dibuat: {{ $booking->created_at?->format('d M Y, H:i') }}</div>
        </div>

        <div class="p-6">
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

            @if ($errors->booking->any())
                <div class="mb-4 text-xs font-semibold px-3 py-2 rounded-xl bg-red-50 text-red-700">
                    <ul class="list-disc pl-4 space-y-1">
                        @foreach ($errors->booking->all() as $err)
                            <li>{{ $err }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('dashboard.booking.update', $booking) }}" class="space-y-4">
                @csrf
                @method('PUT')

                <div>
                    <label class="block text-xs font-semibold text-gray-500 mb-1">Paket (opsional)</label>
                    <select name="vendor_package_id" class="w-full h-11 border border-gray-200 rounded-xl px-3.5 py-2.5 text-sm focus:outline-none focus:border-gray-400 transition">
                        <option value="">Tanpa paket</option>
                        @foreach (($booking->vendor?->packages ?? collect()) as $pkg)
                            <option value="{{ $pkg->id }}" {{ (string) old('vendor_package_id', $booking->vendor_package_id) === (string) $pkg->id ? 'selected' : '' }}>
                                {{ $pkg->name }} — {{ $pkg->price }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-500 mb-1">Tanggal acara</label>
                        <input type="date" name="event_date" value="{{ old('event_date', optional($booking->event_date)->format('Y-m-d')) }}"
                               class="w-full h-11 border border-gray-200 rounded-xl px-3.5 py-2.5 text-sm focus:outline-none focus:border-gray-400 transition">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-500 mb-1">Nomor WhatsApp</label>
                        <input type="text" name="phone" value="{{ old('phone', $booking->phone) }}"
                               class="w-full h-11 border border-gray-200 rounded-xl px-3.5 py-2.5 text-sm focus:outline-none focus:border-gray-400 transition">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-500 mb-1">Catatan (opsional)</label>
                    <textarea name="notes" rows="3" maxlength="2000"
                              class="w-full border border-gray-200 rounded-xl px-3.5 py-2.5 text-sm focus:outline-none focus:border-gray-400 transition resize-none"
                              placeholder="Mis: jam acara, estimasi tamu, request konsep...">{{ old('notes', $booking->notes) }}</textarea>
                </div>

                <div class="flex items-center justify-end gap-2 pt-2">
                    <a href="{{ route('dashboard.booking') }}" class="text-xs font-bold px-4 py-2 rounded-lg bg-gray-50 hover:bg-gray-100 transition" style="color: var(--dark-gray)">
                        Batal
                    </a>
                    <button type="submit" class="text-xs font-bold px-4 py-2 rounded-lg transition hover:opacity-90" style="background-color: var(--sage-green); color: var(--cream)">
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
@endif
@endsection

