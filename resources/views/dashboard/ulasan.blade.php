@extends('layout.dashboard')

@section('title', 'Ulasan Saya — Makna Wedding')
@section('page-title', 'Ulasan Saya')

@section('content')

    <div class="mb-6">
        <h1 class="text-xl font-bold" style="color: var(--dark-gray)">Ulasan Saya</h1>
        <p class="text-sm text-gray-400 mt-1">Semua ulasan yang pernah kamu tulis.</p>
    </div>

    @if($myReviews->isEmpty())
    <div class="bg-white rounded-2xl border border-gray-100 p-10 flex flex-col items-center text-center gap-3">
        <div class="w-12 h-12 rounded-full flex items-center justify-center" style="background: var(--light-sage)">
            <svg class="w-6 h-6" style="color: var(--sage-green)" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
            </svg>
        </div>
        <p class="text-sm text-gray-500">Kamu belum menulis ulasan apa pun.</p>
        <a href="{{ route('vendor') }}"
           class="text-xs font-semibold px-4 py-2 rounded-xl transition hover:opacity-90"
           style="background: var(--sage-green); color: #fff">
            Temukan Vendor
        </a>
    </div>
    @else
    <div class="space-y-3">
        @foreach($myReviews as $rev)
        <div class="bg-white rounded-2xl border border-gray-100 p-4 flex items-start gap-4">
            <div class="flex-1 min-w-0">
                <div class="flex items-center justify-between gap-2 mb-1.5">
                    <p class="text-sm font-semibold truncate" style="color: var(--dark-gray)">
                        {{ $rev->vendor?->name ?? '(Vendor dihapus)' }}
                    </p>
                    <span class="text-[10px] flex-shrink-0 px-1.5 py-0.5 rounded-full font-semibold
                        {{ $rev->is_approved ? 'bg-green-50 text-green-600' : 'bg-amber-50 text-amber-600' }}">
                        {{ $rev->is_approved ? 'Disetujui' : 'Menunggu' }}
                    </span>
                </div>
                <div class="flex items-center gap-0.5 mb-1.5">
                    @for($s = 1; $s <= 5; $s++)
                    <svg class="w-3 h-3 {{ $s <= $rev->rating ? '' : 'opacity-20' }}"
                         style="color: #f59e0b" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                    </svg>
                    @endfor
                    <span class="text-[10px] text-gray-400 ml-1">{{ $rev->reviewed_at->translatedFormat('d M Y') }}</span>
                </div>
                <p class="text-xs text-gray-500">{{ $rev->body }}</p>
                @if(filled($rev->admin_reply))
                    <div class="mt-3 p-3 rounded-xl bg-gray-50 border border-gray-100">
                        <div class="flex items-center justify-between gap-2 mb-1">
                            <p class="text-[10px] uppercase tracking-widest text-gray-400 font-bold">Balasan Admin</p>
                            @if($rev->admin_replied_at)
                                <p class="text-[10px] text-gray-400">{{ $rev->admin_replied_at->translatedFormat('d M Y') }}</p>
                            @endif
                        </div>
                        <p class="text-xs text-gray-600">{{ $rev->admin_reply }}</p>
                    </div>
                @endif
            </div>
            @if($rev->vendor)
            <a href="{{ route('vendor.detail', $rev->vendor->slug) }}"
               class="flex-shrink-0 self-center p-2 rounded-xl hover:bg-gray-50 transition text-gray-300 hover:text-gray-400">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </a>
            @endif
        </div>
        @endforeach
    </div>
    @endif

    @if($user->hasRole(['super_admin', 'admin']))
        <div class="mt-10">
            <div class="mb-4 flex items-start justify-between gap-3">
                <div>
                <h2 class="text-lg font-bold" style="color: var(--dark-gray)">Ulasan User</h2>
                <p class="text-sm text-gray-500">Ulasan terbaru dari semua user (maks. 200).</p>
                </div>
                <div class="inline-flex rounded-xl overflow-hidden border border-gray-100 bg-white flex-shrink-0">
                    <a href="{{ request()->fullUrlWithQuery(['review_filter' => null]) }}"
                       class="text-xs font-bold px-3 py-2 transition {{ ($reviewFilter ?? null) === 'pending' ? 'bg-white text-gray-500' : 'bg-gray-50 text-gray-700' }}">
                        Semua
                    </a>
                    <a href="{{ request()->fullUrlWithQuery(['review_filter' => 'pending']) }}"
                       class="text-xs font-bold px-3 py-2 transition border-l border-gray-100 {{ ($reviewFilter ?? null) === 'pending' ? 'bg-gray-50 text-gray-700' : 'bg-white text-gray-500' }}">
                        Menunggu
                        @if(($pendingReviewCount ?? 0) > 0)
                            <span class="ml-1 inline-flex items-center px-1.5 py-0.5 rounded-full text-[10px] font-bold"
                                  style="background: var(--light-sage); color: var(--dark-gray)">{{ $pendingReviewCount }}</span>
                        @endif
                    </a>
                </div>
            </div>

            @if(($allReviews ?? collect())->isEmpty())
                <div class="bg-white rounded-2xl border border-gray-100 p-8 text-sm text-gray-500">
                    Belum ada ulasan.
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
                                    <th class="text-left px-4 py-3 font-semibold">Rating</th>
                                    <th class="text-left px-4 py-3 font-semibold">Status</th>
                                    <th class="text-left px-4 py-3 font-semibold">Dibalas</th>
                                    <th class="text-left px-4 py-3 font-semibold">Tanggal</th>
                                    <th class="text-left px-4 py-3 font-semibold"></th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @foreach($allReviews as $i => $rev)
                                    @php
                                        $approveClass = $rev->is_approved ? 'bg-green-50 text-green-700' : 'bg-amber-50 text-amber-700';
                                        $approveLabel = $rev->is_approved ? 'Disetujui' : 'Menunggu';
                                        $replyClass = filled($rev->admin_reply) ? 'bg-blue-50 text-blue-700' : 'bg-gray-100 text-gray-700';
                                        $replyLabel = filled($rev->admin_reply) ? 'Ya' : 'Belum';
                                    @endphp
                                    <tr class="hover:bg-gray-50/60 transition">
                                        <td class="px-4 py-3 text-xs text-gray-500 align-top">{{ $i + 1 }}</td>
                                        <td class="px-4 py-3 align-top">
                                            <div class="font-bold text-xs" style="color: var(--dark-gray)">{{ $rev->user?->name ?? $rev->reviewer_name }}</div>
                                            <div class="text-[10px] mt-1 text-gray-400">{{ $rev->user?->email ?? '' }}</div>
                                        </td>
                                        <td class="px-4 py-3 align-top">
                                            <div class="font-bold text-xs" style="color: var(--dark-gray)">{{ $rev->vendor?->name ?? '—' }}</div>
                                        </td>
                                        <td class="px-4 py-3 align-top">
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs bg-yellow-50 text-yellow-700">
                                                {{ $rev->rating }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-3 align-top">
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs {{ $approveClass }}">
                                                {{ $approveLabel }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-3 align-top">
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs {{ $replyClass }}">
                                                {{ $replyLabel }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-3 align-top text-xs text-gray-600">
                                            {{ $rev->reviewed_at?->translatedFormat('d M Y') }}
                                        </td>
                                        <td class="px-4 py-3 align-top">
                                            @if($rev->vendor)
                                                <a href="{{ route('vendor.detail', $rev->vendor->slug) }}" class="text-xs font-bold px-3 py-2 rounded-lg bg-gray-50 hover:bg-gray-100 transition" style="color: var(--dark-gray)">
                                                    Lihat
                                                </a>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr class="bg-gray-50/40">
                                        <td colspan="8" class="px-4 pb-3 pt-2 text-xs text-gray-500">
                                            <span class="font-semibold text-gray-600">Ulasan:</span> {{ $rev->body }}
                                        </td>
                                    </tr>
                                    @if(filled($rev->admin_reply))
                                        <tr class="bg-gray-50/40">
                                            <td colspan="8" class="px-4 pb-3 pt-0 text-xs text-gray-500">
                                                <span class="font-semibold text-gray-600">Balasan Admin:</span> {{ $rev->admin_reply }}
                                            </td>
                                        </tr>
                                    @endif
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif
        </div>
    @endif

@endsection
