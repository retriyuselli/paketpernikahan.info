@extends('layout.dashboard')

@section('title', 'Pembayaran Masuk — Makna Wedding')
@section('page-title', 'Pembayaran Masuk')

@section('content')
<div class="mb-8 flex items-start justify-between gap-3">
    <div>
        <h1 class="text-2xl font-bold mb-2 text-dark">Pembayaran Masuk</h1>
        <p class="text-sm text-gray-500">Daftar pembayaran terbaru (maks. 200).</p>
    </div>
    <div class="inline-flex rounded-xl overflow-hidden border border-gray-100 bg-white flex-shrink-0">
        <a href="{{ request()->fullUrlWithQuery(['payment_filter' => null]) }}"
           class="text-xs font-bold px-3 py-2 transition {{ ($paymentFilter ?? null) === 'pending' ? 'bg-white text-gray-500' : 'bg-gray-50 text-gray-700' }}">
            Semua
        </a>
        <a href="{{ request()->fullUrlWithQuery(['payment_filter' => 'pending']) }}"
           class="text-xs font-bold px-3 py-2 transition border-l border-gray-100 {{ ($paymentFilter ?? null) === 'pending' ? 'bg-gray-50 text-gray-700' : 'bg-white text-gray-500' }}">
            Pending
            @if(($pendingPaymentCount ?? 0) > 0)
                <span class="ml-1 inline-flex items-center px-1.5 py-0.5 rounded-full text-[10px] font-bold bg-light-sage text-dark">{{ $pendingPaymentCount }}</span>
            @endif
        </a>
    </div>
</div>

@if (session('payment_success'))
    <div class="mb-4 text-xs font-semibold px-3 py-2 rounded-xl bg-green-50 text-green-700">
        {{ session('payment_success') }}
    </div>
@endif

@if($payments->isEmpty())
    <div class="bg-white rounded-2xl border border-gray-100 p-10 text-center flex flex-col items-center">
        <div class="w-16 h-16 rounded-full bg-gray-50 flex items-center justify-center mb-4">
            <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 1.343-3 3v2h6v-2c0-1.657-1.343-3-3-3z"/>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 11a7 7 0 0114 0v7a1 1 0 01-1 1H6a1 1 0 01-1-1v-7z"/>
            </svg>
        </div>
        <h3 class="text-base font-bold mb-1 text-dark">Belum ada pembayaran</h3>
        <p class="text-sm text-gray-500 max-w-sm">Belum ada bukti pembayaran yang masuk.</p>
    </div>
@else
    <div class="bg-white rounded-2xl border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="bg-gray-50 text-gray-500 text-xs uppercase tracking-wider">
                    <tr>
                        <th class="text-left px-4 py-3 font-semibold">No</th>
                        <th class="text-left px-4 py-3 font-semibold">Vendor</th>
                        <th class="text-left px-4 py-3 font-semibold">User</th>
                        <th class="text-left px-4 py-3 font-semibold">Tipe</th>
                        <th class="text-left px-4 py-3 font-semibold">Nominal</th>
                        <th class="text-left px-4 py-3 font-semibold">Metode</th>
                        <th class="text-left px-4 py-3 font-semibold">Status</th>
                        <th class="text-left px-4 py-3 font-semibold">Bukti</th>
                        <th class="text-left px-4 py-3 font-semibold"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($payments as $i => $p)
                        @php
                            $statusClass = match($p->status) {
                                'approved' => 'bg-green-50 text-green-700',
                                'rejected' => 'bg-red-50 text-red-700',
                                default => 'bg-yellow-50 text-yellow-700',
                            };
                        @endphp
                        <tr class="hover:bg-gray-50/60 transition">
                            <td class="px-4 py-3 text-xs text-gray-500 align-top">{{ $i + 1 }}</td>
                            <td class="px-4 py-3 align-top">
                                <div class="font-bold text-xs text-dark">{{ $p->booking?->vendor?->name ?? '—' }}</div>
                                <div class="text-[10px] mt-1 text-gray-400">{{ $p->created_at?->format('d M Y, H:i') }}</div>
                            </td>
                            <td class="px-4 py-3 align-top">
                                <div class="font-bold text-xs text-dark">{{ $p->booking?->user?->name ?? '—' }}</div>
                                <div class="text-[10px] mt-1 text-gray-400">{{ $p->booking?->user?->email ?? '' }}</div>
                            </td>
                            <td class="px-4 py-3 text-xs text-gray-600 align-top">{{ $p->type }}</td>
                            <td class="px-4 py-3 text-xs text-gray-600 align-top">{{ number_format($p->amount) }}</td>
                            <td class="px-4 py-3 text-xs text-gray-600 align-top">{{ $p->method }}</td>
                            <td class="px-4 py-3 align-top">
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs {{ $statusClass }}">
                                    {{ $p->status }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-xs align-top">
                                @if($p->proof_url)
                                    <button type="button"
                                            data-proof-open
                                            data-proof-url="{{ $p->proof_url }}"
                                            class="font-bold text-dark hover:underline">
                                        Lihat
                                    </button>
                                @else
                                    <span class="text-gray-400">—</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 align-top">
                                @if($p->status === 'pending_verification')
                                    <div class="flex gap-2">
                                        <form method="POST" action="{{ route('dashboard.vendor.payments.verify', $p) }}">
                                            @csrf
                                            <input type="hidden" name="action" value="approve">
                                            <button type="submit" class="text-xs font-bold px-3 py-2 rounded-lg bg-accent text-white transition hover:opacity-90">
                                                Approve
                                            </button>
                                        </form>
                                        <form method="POST" action="{{ route('dashboard.vendor.payments.verify', $p) }}">
                                            @csrf
                                            <input type="hidden" name="action" value="reject">
                                            <input type="hidden" name="note" value="">
                                            <button type="submit" class="text-xs font-bold px-3 py-2 rounded-lg bg-gray-50 text-dark hover:bg-gray-100 transition">
                                                Reject
                                            </button>
                                        </form>
                                    </div>
                                @endif
                            </td>
                        </tr>
                        @if(filled($p->note) || filled($p->sender_name) || filled($p->sender_bank))
                            <tr class="bg-gray-50/40">
                                <td colspan="9" class="px-4 pb-3 pt-2 text-xs text-gray-500">
                                    @if(filled($p->sender_name) || filled($p->sender_bank))
                                        <div class="mb-1">
                                            <span class="font-semibold text-gray-600">Pengirim:</span>
                                            {{ trim(($p->sender_name ?? '') . ' ' . ($p->sender_bank ? '— ' . $p->sender_bank : '')) }}
                                        </div>
                                    @endif
                                    @if(filled($p->note))
                                        <div>
                                            <span class="font-semibold text-gray-600">Catatan:</span> {{ $p->note }}
                                        </div>
                                    @endif
                                </td>
                            </tr>
                        @endif
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endif

<script>
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('form[action*="/dashboard/vendor/payments/"][action$="/verify"]').forEach(function (form) {
        var action = form.querySelector('input[name="action"]')?.value;
        if (action !== 'reject') return;
        form.addEventListener('submit', function (e) {
            var note = window.prompt('Alasan penolakan pembayaran?');
            if (note === null) {
                e.preventDefault();
                return;
            }
            note = (note || '').trim();
            if (!note) {
                e.preventDefault();
                return;
            }
            var input = form.querySelector('input[name="note"]');
            if (input) input.value = note;
        });
    });
});
</script>

<x-ui.modal id="proof-modal" size="4xl" backdrop-class="bg-black/60" panel-class="bg-white rounded-2xl overflow-hidden border border-gray-100 shadow-2xl w-full" class="place-items-center">
    <button type="button"
            data-proof-close
            class="absolute top-3 right-3 w-9 h-9 rounded-full bg-white border border-gray-200 flex items-center justify-center text-sm font-bold text-dark hover:bg-gray-50 transition">
        ×
    </button>
    <div class="p-4 border-b border-gray-100">
        <p class="text-sm font-bold text-dark">Bukti Pembayaran</p>
    </div>
    <div class="p-4 bg-gray-50">
        <img id="proof-modal-img" src="" alt="Bukti pembayaran" class="hidden w-full h-auto max-h-[75vh] object-contain bg-black rounded-xl">
        <iframe id="proof-modal-frame" src="" class="hidden w-full h-[75vh] bg-white rounded-xl border border-gray-100"></iframe>
        <div id="proof-modal-fallback" class="hidden text-sm text-gray-500">
            <a id="proof-modal-link" href="#" target="_blank" class="font-bold text-dark hover:underline">Buka bukti di tab baru</a>
        </div>
    </div>
</x-ui.modal>

<script>
    function openProofModal(url) {
        var modal = document.getElementById('proof-modal');
        var img = document.getElementById('proof-modal-img');
        var frame = document.getElementById('proof-modal-frame');
        var fallback = document.getElementById('proof-modal-fallback');
        var link = document.getElementById('proof-modal-link');
        if (!modal || !img || !frame || !fallback || !link) return;

        img.classList.add('hidden');
        frame.classList.add('hidden');
        fallback.classList.add('hidden');
        img.src = '';
        frame.src = '';
        link.href = url || '#';

        var lower = String(url || '').toLowerCase();
        if (lower.endsWith('.pdf')) {
            frame.src = url;
            frame.classList.remove('hidden');
        } else if (lower.endsWith('.jpg') || lower.endsWith('.jpeg') || lower.endsWith('.png') || lower.endsWith('.webp') || lower.endsWith('.gif')) {
            img.src = url;
            img.classList.remove('hidden');
        } else {
            fallback.classList.remove('hidden');
        }

        modal.classList.remove('hidden');
        modal.classList.add('grid');
        document.body.classList.add('overflow-hidden');
    }

    function closeProofModal() {
        var modal = document.getElementById('proof-modal');
        var img = document.getElementById('proof-modal-img');
        var frame = document.getElementById('proof-modal-frame');
        if (!modal) return;
        modal.classList.add('hidden');
        modal.classList.remove('grid');
        if (img) img.src = '';
        if (frame) frame.src = '';
        document.body.classList.remove('overflow-hidden');
    }

    document.addEventListener('DOMContentLoaded', function () {
        var modal = document.getElementById('proof-modal');
        if (!modal) return;

        modal.addEventListener('click', function (e) {
            if (e.target === modal) closeProofModal();
        });

        var closeBtn = modal.querySelector('[data-proof-close]');
        if (closeBtn) closeBtn.addEventListener('click', closeProofModal);

        document.querySelectorAll('[data-proof-open][data-proof-url]').forEach(function (btn) {
            btn.addEventListener('click', function () {
                openProofModal(btn.getAttribute('data-proof-url'));
            });
        });
    });

    document.addEventListener('keydown', function (e) {
        if (e.key !== 'Escape') return;
        var modal = document.getElementById('proof-modal');
        if (modal && !modal.classList.contains('hidden')) closeProofModal();
    });
</script>
@endsection
