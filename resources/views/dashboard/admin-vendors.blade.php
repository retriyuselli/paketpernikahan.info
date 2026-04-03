@extends('layout.dashboard')

@section('title', 'All Vendor — Makna Wedding')
@section('page-title', 'All Vendor')

@section('content')
<div class="mb-8">
    <h1 class="text-2xl font-bold mb-2 text-dark">All Vendor</h1>
    <p class="text-sm text-gray-500">Daftar semua vendor yang terdaftar di sistem.</p>
</div>

<div class="bg-white rounded-2xl border border-gray-100 overflow-hidden mb-6">
    <form method="GET" action="{{ route('dashboard.admin.vendors') }}" class="p-4 flex flex-col sm:flex-row gap-3 sm:items-center">
        <input type="text" name="q" value="{{ $q ?? '' }}" placeholder="Cari vendor / slug / kategori / kota..."
               class="flex-1 rounded-xl border border-gray-200 px-3 py-2 text-sm focus:outline-none focus:border-accent focus:ring-accent">
        <div class="flex items-center gap-2">
            <button type="submit"
                    class="px-4 py-2 rounded-xl text-sm font-bold bg-accent text-cream transition hover:opacity-90">
                Cari
            </button>
            <a href="{{ route('dashboard.admin.vendors') }}"
               class="px-4 py-2 rounded-xl text-sm font-bold border border-gray-200 bg-white text-dark hover:border-gray-300 transition">
                Reset
            </a>
        </div>
    </form>
</div>

@if($vendors->isEmpty())
    <div class="bg-white rounded-2xl border border-gray-100 p-10 text-center flex flex-col items-center">
        <div class="w-16 h-16 rounded-full bg-gray-50 flex items-center justify-center mb-4">
            <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6l4 2"/>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>
        <h3 class="text-base font-bold mb-1 text-dark">Tidak ada vendor</h3>
        <p class="text-sm text-gray-500 max-w-sm">Coba ubah kata kunci pencarian.</p>
    </div>
@else
    <div class="bg-white rounded-2xl border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="bg-gray-50 text-gray-500 text-xs uppercase tracking-wider">
                    <tr>
                        <th class="text-left px-4 py-3 font-semibold">No</th>
                        <th class="text-left px-4 py-3 font-semibold">Vendor</th>
                        <th class="text-left px-4 py-3 font-semibold">Pemilik</th>
                        <th class="text-left px-4 py-3 font-semibold">Kategori</th>
                        <th class="text-left px-4 py-3 font-semibold">Kota</th>
                        <th class="text-left px-4 py-3 font-semibold">Status</th>
                        <th class="text-left px-4 py-3 font-semibold"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($vendors as $i => $v)
                        <tr class="hover:bg-gray-50/60 transition">
                            <td class="px-4 py-3 text-xs text-gray-500 align-top">{{ $i + 1 }}</td>
                            <td class="px-4 py-3 align-top">
                                <div class="font-bold text-xs text-dark">{{ $v->name }}</div>
                                <div class="text-[10px] mt-1 text-gray-400">{{ $v->location }}</div>
                                <div class="text-[10px] mt-1 text-gray-400">{{ $v->slug }}</div>
                            </td>
                            <td class="px-4 py-3 text-xs text-gray-600 align-top">
                                @if($v->owner)
                                    <div class="font-semibold text-dark">{{ $v->owner->name }}</div>
                                    <div class="text-[10px] text-gray-400 mt-1">{{ $v->owner->email }}</div>
                                @else
                                    <span class="text-[10px] text-gray-400">Belum ada pemilik</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-xs text-gray-600 align-top">{{ $v->category }}</td>
                            <td class="px-4 py-3 text-xs text-gray-600 align-top">{{ $v->city }}</td>
                            <td class="px-4 py-3 align-top">
                                <div class="flex flex-col gap-1">
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-[10px] font-bold {{ $v->is_active ? 'bg-green-50 text-green-700' : 'bg-gray-100 text-gray-600' }}">
                                        {{ $v->is_active ? 'Aktif' : 'Nonaktif' }}
                                    </span>
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-[10px] font-bold {{ $v->is_profile_complete ? 'bg-sky-50 text-sky-700' : 'bg-yellow-50 text-yellow-700' }}">
                                        {{ $v->is_profile_complete ? 'Profil lengkap' : 'Profil belum lengkap' }}
                                    </span>
                                </div>
                            </td>
                            <td class="px-4 py-3 align-top">
                                <div class="inline-flex flex-col rounded-lg overflow-hidden border border-gray-100 bg-gray-50">
                                    <a href="{{ route('vendor.edit', $v) }}" class="text-xs font-bold px-3 py-2 text-dark hover:bg-gray-100 transition">
                                        Kelola
                                    </a>
                                    <a href="{{ url('/admin/vendors/' . $v->slug . '/edit') }}" class="text-xs font-bold px-3 py-2 text-dark hover:bg-gray-100 transition border-t border-gray-100">
                                        Panel Admin
                                    </a>
                                    <a href="{{ route('vendor.detail', $v->slug) }}" class="text-xs font-bold px-3 py-2 text-dark hover:bg-gray-100 transition border-t border-gray-100">
                                        Lihat
                                    </a>
                                    <form method="POST" action="{{ route('dashboard.admin.vendors.toggle', $v) }}" class="border-t border-gray-100">
                                        @csrf
                                        <button type="submit" class="w-full text-left text-xs font-bold px-3 py-2 text-dark hover:bg-gray-100 transition">
                                            {{ $v->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endif
@endsection
