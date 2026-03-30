@extends('layout.dashboard')

@section('title', 'Vendor Saya — Makna Wedding')
@section('page-title', 'Vendor Saya')

@section('content')
<div class="mb-8">
    <h1 class="text-2xl font-bold mb-2" style="color: var(--dark-gray)">Vendor Saya</h1>
    <p class="text-sm text-gray-500">Lengkapi profil vendor terlebih dahulu sebelum tampil di halaman vendor.</p>
</div>

@if($vendors->isEmpty())
    <div class="bg-white rounded-2xl border border-gray-100 p-10 text-center flex flex-col items-center">
        <div class="w-16 h-16 rounded-full bg-gray-50 flex items-center justify-center mb-4">
            <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6l4 2"/>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>
        <h3 class="text-base font-bold mb-1" style="color: var(--dark-gray)">Belum ada vendor</h3>
        <p class="text-sm text-gray-500 max-w-sm">Silakan minta admin menghubungkan akun Anda ke vendor (field Pemilik Vendor).</p>
    </div>
@else
    <div class="bg-white rounded-2xl border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="bg-gray-50 text-gray-500 text-xs uppercase tracking-wider">
                    <tr>
                        <th class="text-left px-4 py-3 font-semibold">No</th>
                        <th class="text-left px-4 py-3 font-semibold">Vendor</th>
                        <th class="text-left px-4 py-3 font-semibold">Kategori</th>
                        <th class="text-left px-4 py-3 font-semibold">Kota</th>
                        <th class="text-left px-4 py-3 font-semibold"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($vendors as $i => $v)
                        <tr class="hover:bg-gray-50/60 transition">
                            <td class="px-4 py-3 text-xs text-gray-500 align-top">{{ $i + 1 }}</td>
                            <td class="px-4 py-3 align-top">
                                <div class="font-bold text-xs" style="color: var(--dark-gray)">{{ $v->name }}</div>
                                <div class="text-[10px] mt-1 text-gray-400">{{ $v->location }}</div>
                                @if(!$v->is_profile_complete)
                                    <div class="mt-2">
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-[10px] font-bold bg-yellow-50 text-yellow-700">
                                            Lengkapi profil dulu
                                        </span>
                                    </div>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-xs text-gray-600 align-top">{{ $v->category }}</td>
                            <td class="px-4 py-3 text-xs text-gray-600 align-top">{{ $v->city }}</td>
                            <td class="px-4 py-3 align-top">
                                <div class="inline-flex flex-col rounded-lg overflow-hidden border border-gray-100 bg-gray-50">
                                    <a href="{{ route('vendor.edit', $v) }}" class="text-xs font-bold px-3 py-2 hover:bg-gray-100 transition" style="color: var(--dark-gray)">
                                        {{ $v->is_profile_complete ? 'Kelola' : 'Lengkapi' }}
                                    </a>
                                    @if($v->is_profile_complete)
                                        <a href="{{ route('vendor.detail', $v->slug) }}" class="text-xs font-bold px-3 py-2 hover:bg-gray-100 transition border-t border-gray-100" style="color: var(--dark-gray)">
                                            Lihat
                                        </a>
                                    @endif
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
