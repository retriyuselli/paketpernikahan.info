@extends('layout.dashboard')

@section('title', 'Paket — Dashboard')
@section('page-title', 'Paket')

@section('content')
<div class="mb-6 flex items-start justify-between gap-4">
    <div>
        <h1 class="text-2xl font-bold mb-1 text-dark">Paket</h1>
        <p class="text-sm text-gray-500">
            {{ $isAdmin ? 'Semua paket dari seluruh vendor.' : 'Paket dari vendor Anda.' }}
        </p>
    </div>
    <div class="flex items-center gap-2">
        @if($isAdmin)
            <a href="/admin/vendor-packages/create"
               class="inline-flex items-center gap-1.5 px-4 py-2 text-sm font-semibold rounded-xl bg-accent text-white hover:opacity-90 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Tambah Paket
            </a>
        @elseif($vendors->count() === 1)
            <a href="{{ route('vendor.edit', $vendors->first()->slug) }}#packages"
               class="inline-flex items-center gap-1.5 px-4 py-2 text-sm font-semibold rounded-xl bg-accent text-white hover:opacity-90 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Tambah Paket
            </a>
        @elseif($vendors->count() > 1)
            <div class="relative" x-data="{ open: false }">
                <button @click="open = !open"
                        class="inline-flex items-center gap-1.5 px-4 py-2 text-sm font-semibold rounded-xl bg-accent text-white hover:opacity-90 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Tambah Paket
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>
                <div x-show="open" @click.outside="open = false"
                     class="absolute right-0 mt-1 w-48 bg-white rounded-xl shadow-lg border border-gray-100 py-1 z-10">
                    @foreach($vendors as $vendor)
                        <a href="{{ route('vendor.edit', $vendor->slug) }}#packages"
                           class="block px-4 py-2 text-sm text-dark hover:bg-gray-50">
                            {{ $vendor->name }}
                        </a>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</div>

{{-- Filter --}}
<form method="GET" action="{{ route('dashboard.paket') }}" class="mb-5 flex flex-wrap gap-2">
    <input type="text" name="q" value="{{ $q }}"
           placeholder="Cari nama paket atau vendor…"
           class="flex-1 min-w-[200px] px-3.5 py-2 text-sm rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-light-sage text-dark bg-white">

    <select name="status"
            class="px-3 py-2 text-sm rounded-xl border border-gray-200 focus:outline-none text-dark bg-white">
        <option value="">Semua Status</option>
        <option value="aktif" @selected($statusFilter === 'aktif')>Aktif</option>
        <option value="nonaktif" @selected($statusFilter === 'nonaktif')>Non-aktif</option>
    </select>

    <button type="submit"
            class="px-4 py-2 text-sm font-semibold rounded-xl bg-accent text-white hover:opacity-90 transition">
        Cari
    </button>

    @if($q || $statusFilter)
    <a href="{{ route('dashboard.paket') }}"
       class="px-4 py-2 text-sm font-semibold rounded-xl border border-gray-200 text-gray-500 hover:bg-gray-50 transition">
        Reset
    </a>
    @endif
</form>

@if($packages->isEmpty())
    <div class="bg-white rounded-2xl border border-gray-100 p-10 text-center flex flex-col items-center">
        <div class="w-16 h-16 rounded-full bg-gray-50 flex items-center justify-center mb-4">
            <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10"/>
            </svg>
        </div>
        <h3 class="text-base font-bold mb-1 text-dark">Belum ada paket</h3>
        <p class="text-sm text-gray-500 max-w-sm">
            @if($q || $statusFilter)
                Tidak ada paket yang cocok dengan filter.
            @else
                Tambahkan paket melalui halaman kelola vendor.
            @endif
        </p>
    </div>
@else
    <div class="bg-white rounded-2xl border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="bg-gray-50 text-gray-500 text-xs uppercase tracking-wider">
                    <tr>
                        <th class="text-left px-4 py-3 font-semibold">No</th>
                        <th class="text-left px-4 py-3 font-semibold">Nama Paket</th>
                        @if($isAdmin)
                        <th class="text-left px-4 py-3 font-semibold">Vendor</th>
                        @endif
                        <th class="text-left px-4 py-3 font-semibold">Kategori</th>
                        <th class="text-left px-4 py-3 font-semibold">Harga</th>
                        <th class="text-left px-4 py-3 font-semibold">Diskon</th>
                        <th class="text-left px-4 py-3 font-semibold">Status</th>
                        <th class="text-left px-4 py-3 font-semibold"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($packages as $i => $pkg)
                        @php
                            $finalPrice = max(0, $pkg->price - ($pkg->discount ?? 0));
                        @endphp
                        <tr class="hover:bg-gray-50/60 transition">
                            <td class="px-4 py-3 text-xs text-gray-400 align-top">
                                {{ $packages->firstItem() + $i }}
                            </td>
                            <td class="px-4 py-3 align-top">
                                <div class="font-semibold text-xs text-dark">{{ $pkg->name }}</div>
                                @if($pkg->type)
                                    <div class="text-[10px] mt-0.5 text-gray-400">{{ $pkg->type }}</div>
                                @endif
                                @if($pkg->max_guests)
                                    <div class="text-[10px] text-gray-400">{{ $pkg->max_guests }} tamu</div>
                                @endif
                            </td>
                            @if($isAdmin)
                            <td class="px-4 py-3 align-top">
                                @if($pkg->vendor)
                                    <a href="{{ route('vendor.edit', $pkg->vendor->slug) }}"
                                       class="text-xs font-medium text-accent hover:underline">
                                        {{ $pkg->vendor->name }}
                                    </a>
                                @else
                                    <span class="text-xs text-gray-400">—</span>
                                @endif
                            </td>
                            @endif
                            <td class="px-4 py-3 text-xs text-gray-600 align-top">
                                {{ $pkg->categoryVendor?->name ?? '—' }}
                            </td>
                            <td class="px-4 py-3 align-top">
                                <div class="text-xs font-semibold text-dark">
                                    Rp {{ number_format($pkg->price, 0, ',', '.') }}
                                </div>
                                @if(($pkg->discount ?? 0) > 0)
                                    <div class="text-[10px] text-accent">
                                        Final: Rp {{ number_format($finalPrice, 0, ',', '.') }}
                                    </div>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-xs text-gray-600 align-top">
                                @if(($pkg->discount ?? 0) > 0)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-pink-50 text-pink-600">
                                        Rp {{ number_format($pkg->discount, 0, ',', '.') }}
                                    </span>
                                @else
                                    <span class="text-gray-300">—</span>
                                @endif
                            </td>       
                            <td class="px-4 py-3 align-top">
                                @if($pkg->is_active)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-green-50 text-green-700">
                                        Aktif
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-gray-100 text-gray-400">
                                        Non-aktif
                                    </span>
                                @endif
                            </td>
                            <td class="px-4 py-3 align-top">
                                <div class="flex items-center gap-2">
                                    @if($pkg->vendor)
                                        <a href="{{ route('vendor.edit', $pkg->vendor->slug) }}#paket"
                                           class="text-xs font-semibold px-3 py-1.5 rounded-lg border border-gray-200 text-dark hover:bg-gray-50 transition whitespace-nowrap">
                                            Kelola
                                        </a>
                                        <a href="{{ route('vendor.packages.edit', [$pkg->vendor->slug, $pkg->id]) }}"
                                           class="text-xs font-semibold px-3 py-1.5 rounded-lg border border-gray-200 text-dark hover:bg-gray-50 transition whitespace-nowrap">
                                            Edit
                                        </a>
                                    @endif
                                    @if($isAdmin)
                                        <a href="/admin/vendor-packages/{{ $pkg->id }}/edit"
                                           class="text-xs font-semibold px-3 py-1.5 rounded-lg border border-gray-200 text-dark hover:bg-gray-50 transition whitespace-nowrap">
                                            Admin
                                        </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        @if($packages->hasPages())
        <div class="px-4 py-4 border-t border-gray-100">
            {{ $packages->links() }}
        </div>
        @endif
    </div>

    <p class="mt-3 text-xs text-gray-400 text-right">
        Total: {{ $packages->total() }} paket
    </p>
@endif
@endsection
