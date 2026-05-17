@extends('layout.dashboard')

@section('title', 'Chat Saya — Makna Wedding')
@section('page-title', 'Chat Saya')

@section('content')
<div class="mb-6">
    <h1 class="text-xl font-bold text-dark">Chat Saya</h1>
    <p class="text-sm text-gray-400 mt-1">Riwayat percakapan kamu dengan vendor.</p>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    @if($sessions->isEmpty())
        <div class="py-16 text-center text-gray-400">
            <svg class="w-12 h-12 mx-auto mb-3 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
            </svg>
            <p class="text-sm font-medium text-gray-500">Belum ada percakapan</p>
            <p class="text-xs text-gray-400 mt-1">Mulai chat dengan vendor favorit kamu.</p>
            <a href="{{ route('store') }}" class="mt-4 inline-flex items-center gap-1.5 text-xs font-semibold px-4 py-2 rounded-xl bg-accent/10 text-accent hover:bg-accent hover:text-white transition">
                Jelajahi Vendor
            </a>
        </div>
    @else
        <div class="divide-y divide-gray-100">
            @foreach($sessions as $s)
            @php
                $lastMsg      = $s->latestMessage;
                $vendorAvatar = $s->vendor?->cover_image_url;
                $vendorInitial = strtoupper(substr($s->vendor?->name ?? 'M', 0, 1));
                $updatedAt    = $s->updated_at->timezone('Asia/Jakarta');
                $isToday      = $updatedAt->isToday();
                $isYesterday  = $updatedAt->isYesterday();
                $timeLabel    = $isToday ? $updatedAt->format('H:i')
                              : ($isYesterday ? 'Kemarin' : $updatedAt->format('d M'));
            @endphp
            <a href="{{ route('chat.public.session', $s->session_token) }}"
               class="flex items-center gap-3 px-4 py-4 hover:bg-gray-50 transition no-underline">

                {{-- Avatar vendor --}}
                <div class="shrink-0">
                    @if($vendorAvatar)
                        <img src="{{ $vendorAvatar }}" alt="{{ $s->vendor?->name }}"
                             class="w-12 h-12 rounded-full object-cover border border-gray-100">
                    @else
                        <div class="w-12 h-12 rounded-full bg-accent flex items-center justify-center text-white font-bold text-sm">
                            {{ $vendorInitial }}
                        </div>
                    @endif
                </div>

                {{-- Info --}}
                <div class="min-w-0 flex-1">
                    <div class="flex items-center justify-between gap-2">
                        <p class="text-sm font-semibold text-dark truncate">
                            {{ $s->vendor?->name ?? 'Makna Wedding' }}
                        </p>
                        <span class="shrink-0 text-[11px] text-gray-400">{{ $timeLabel }}</span>
                    </div>
                    @if($s->vendorPackage)
                        <p class="text-[11px] text-gray-400 truncate">{{ $s->vendorPackage->name }}</p>
                    @endif
                    <p class="text-xs text-gray-500 truncate mt-0.5">
                        {{ $lastMsg ? $lastMsg->message : 'Belum ada pesan' }}
                    </p>
                </div>

                {{-- Status badge --}}
                <div class="shrink-0">
                    @if($s->status === 'open')
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
