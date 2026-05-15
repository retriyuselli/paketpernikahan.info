@extends('layout.dashboard')

@section('title', 'Chat — Makna Wedding')
@section('page-title', 'Live Chat')

@section('content')
<div class="mb-6 flex items-center justify-between">
    <div>
        <h1 class="text-2xl font-bold text-dark">Live Chat</h1>
        <p class="text-sm text-gray-500 mt-1">Balas pesan dari pengunjung website.</p>
    </div>
</div>

<form method="GET" class="mb-4 flex flex-col gap-3 rounded-2xl border border-gray-100 bg-white p-4 shadow-sm sm:flex-row sm:items-end sm:justify-between">
    <div class="w-full max-w-sm">
        <label for="chat-vendor-filter" class="mb-1 block text-xs font-semibold uppercase tracking-wide text-gray-500">Filter Vendor</label>
        <select id="chat-vendor-filter" name="vendor_id" class="w-full rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-sm text-dark outline-none transition focus:border-accent">
            <option value="">Semua vendor</option>
            @foreach($vendors as $vendorOption)
                <option value="{{ $vendorOption->id }}" @selected($selectedVendorId === (int) $vendorOption->id)>{{ $vendorOption->name }} ({{ $vendorOption->chat_sessions_count }})</option>
            @endforeach
        </select>
    </div>
    <div class="flex items-center gap-2">
        <button type="submit" class="inline-flex items-center justify-center rounded-xl bg-accent px-4 py-2.5 text-sm font-semibold text-white transition hover:opacity-90">Terapkan</button>
        @if($selectedVendorId > 0)
            <a href="{{ route('chat.admin') }}" class="inline-flex items-center justify-center rounded-xl border border-gray-200 px-4 py-2.5 text-sm font-semibold text-gray-600 transition hover:bg-gray-50">Reset</a>
        @endif
    </div>
</form>

@if(session('success'))
    <div class="mb-4 px-4 py-3 bg-green-50 border border-green-200 text-green-700 rounded-xl text-sm">
        {{ session('success') }}
    </div>
@endif

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    @if($sessions->count() === 0)
        <div class="py-16 text-center text-gray-400">
            <svg class="w-12 h-12 mx-auto mb-3 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
            </svg>
            <p class="text-sm">Belum ada sesi chat.</p>
        </div>
    @else
        <table class="min-w-full text-sm">
            <thead class="bg-gray-50 text-gray-500 text-xs uppercase tracking-wider">
                <tr>
                    <th class="text-left px-4 py-3 font-semibold">Nama</th>
                    <th class="text-left px-4 py-3 font-semibold">Vendor</th>
                    <th class="text-left px-4 py-3 font-semibold">Status</th>
                    <th class="text-left px-4 py-3 font-semibold">Dibalas oleh</th>
                    <th class="text-left px-4 py-3 font-semibold">Terakhir aktif</th>
                    <th class="text-left px-4 py-3 font-semibold"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @foreach($sessions as $s)
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-4 py-3 font-medium text-dark">
                        {{ $s->guest_name }}
                    </td>
                    <td class="px-4 py-3 text-gray-500 text-xs">
                        <p>{{ $s->vendor?->name ?? 'Belum terdeteksi' }}</p>
                        <p class="mt-0.5 text-[11px] text-gray-400">Paket pertama: {{ $s->vendorPackage?->name ?? 'Belum terdeteksi' }}</p>
                    </td>
                    <td class="px-4 py-3">
                        @if($s->status === 'open')
                            <span class="inline-flex items-center gap-1.5 text-xs font-semibold px-2.5 py-1 rounded-full bg-green-50 text-green-700">
                                <span class="w-1.5 h-1.5 rounded-full bg-green-500 animate-pulse"></span>
                                Aktif
                            </span>
                        @else
                            <span class="inline-flex text-xs font-semibold px-2.5 py-1 rounded-full bg-gray-100 text-gray-500">
                                Ditutup
                            </span>
                        @endif
                    </td>
                    <td class="px-4 py-3 text-gray-400 text-xs">
                        @php($adminName = $adminUsers[$s->last_admin_user_id]->name ?? null)
                        {{ $adminName ?: '—' }}
                    </td>
                    <td class="px-4 py-3 text-gray-400 text-xs">
                        {{ $s->updated_at->diffForHumans() }}
                    </td>
                    <td class="px-4 py-3 text-right">
                        <div class="inline-flex items-center gap-2">
                            <a href="{{ route('chat.admin.detail', $s->session_token) }}"
                               class="inline-flex items-center gap-1.5 text-xs font-semibold px-3 py-1.5 rounded-lg bg-accent/10 text-accent hover:bg-accent hover:text-white transition">
                                Buka
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                            </a>
                            <form method="POST" action="{{ route('chat.admin.delete', $s->session_token) }}">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        onclick="return confirm('Hapus sesi chat ini beserta semua pesannya?')"
                                        class="inline-flex items-center text-xs font-semibold px-3 py-1.5 rounded-lg bg-red-50 text-red-600 hover:bg-red-100 transition">
                                    Hapus
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        @if($sessions->hasPages())
            <div class="px-4 py-3 border-t border-gray-100">
                {{ $sessions->links() }}
            </div>
        @endif
    @endif
</div>
@endsection
