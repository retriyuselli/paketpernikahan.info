@extends('layout.dashboard')

@section('title', 'Chat Masuk — ' . $vendor->name)
@section('page-title', 'Chat Masuk')

@section('content')
<div class="mb-6">
    <h1 class="text-xl font-bold text-dark">Chat Masuk</h1>
    <p class="text-sm text-gray-400 mt-1">Percakapan customer yang masuk ke <span class="font-medium text-dark">{{ $vendor->name }}</span>.</p>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    @if($sessions->isEmpty())
        <div class="py-16 text-center text-gray-400">
            <svg class="w-12 h-12 mx-auto mb-3 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
            </svg>
            <p class="text-sm font-medium text-gray-500">Belum ada percakapan</p>
            <p class="text-xs text-gray-400 mt-1">Chat dari customer akan muncul di sini.</p>
        </div>
    @else
        <div class="divide-y divide-gray-100">
            @foreach($sessions as $s)
            @php
                $lastMsg     = $s->latestMessage;
                $isOpen      = $s->status === 'open';
                $updatedAt   = $s->updated_at->timezone('Asia/Jakarta');
                $timeLabel   = $updatedAt->isToday()     ? $updatedAt->format('H:i')
                             : ($updatedAt->isYesterday() ? 'Kemarin' : $updatedAt->format('d M'));
            @endphp
            <a href="{{ route('chat.vendor.detail', $s->session_token) }}"
               class="flex items-center gap-3 px-4 py-4 hover:bg-gray-50 transition no-underline">

                {{-- Avatar inisial --}}
                <div class="shrink-0 w-11 h-11 rounded-full bg-accent flex items-center justify-center text-white font-bold text-sm">
                    {{ strtoupper(substr($s->guest_name ?? 'G', 0, 1)) }}
                </div>

                {{-- Info --}}
                <div class="min-w-0 flex-1">
                    <div class="flex items-center justify-between gap-2">
                        <p class="text-sm font-semibold text-dark truncate">{{ $s->guest_name ?? 'Tamu' }}</p>
                        <span class="shrink-0 text-[11px] text-gray-400">{{ $timeLabel }}</span>
                    </div>
                    @if($s->vendorPackage)
                        <p class="text-[11px] text-gray-400 truncate">{{ $s->vendorPackage->name }}</p>
                    @endif
                    <p class="text-xs text-gray-500 truncate mt-0.5">
                        {{ $lastMsg ? $lastMsg->message : 'Belum ada pesan' }}
                    </p>
                </div>

                {{-- Status --}}
                <div class="shrink-0">
                    @if($isOpen)
                        <span class="inline-flex items-center gap-1 text-[10px] font-semibold px-2 py-0.5 rounded-full bg-green-50 text-green-700">
                            <span class="w-1.5 h-1.5 rounded-full bg-green-500 animate-pulse"></span>
                            Aktif
                        </span>
                    @else
                        <span class="inline-flex text-[10px] font-semibold px-2 py-0.5 rounded-full bg-gray-100 text-gray-400">
                            Selesai
                        </span>
                    @endif
                </div>
            </a>
            @endforeach
        </div>
    @endif
</div>
@endsection
