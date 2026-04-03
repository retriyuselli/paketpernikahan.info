@extends('layout.dashboard')

@section('title', 'Pembayaran User — Makna Wedding')
@section('page-title', 'Pembayaran User')

@section('content')
<div class="mb-8">
    <div class="flex items-start justify-between gap-3">
        <div>
            <h1 class="text-2xl font-bold mb-2 text-dark">Pembayaran User</h1>
            <p class="text-sm text-gray-500">Pantau pembayaran dari semua vendor (maks. 300).</p>
        </div>
        <div class="inline-flex rounded-xl overflow-hidden border border-gray-100 bg-white flex-shrink-0">
            <a href="{{ request()->fullUrlWithQuery(['status' => null]) }}"
               class="text-xs font-bold px-3 py-2 transition {{ ($status ?? null) ? 'bg-white text-gray-500' : 'bg-gray-50 text-gray-700' }}">
                Semua
            </a>
            <a href="{{ request()->fullUrlWithQuery(['status' => 'pending_verification']) }}"
               class="text-xs font-bold px-3 py-2 transition border-l border-gray-100 {{ ($status ?? null) === 'pending_verification' ? 'bg-gray-50 text-gray-700' : 'bg-white text-gray-500' }}">
                Pending
                @if(($pendingPaymentCount ?? 0) > 0)
                    <span class="ml-1 inline-flex items-center px-1.5 py-0.5 rounded-full text-[10px] font-bold bg-light-sage text-dark">{{ $pendingPaymentCount }}</span>
                @endif
            </a>
        </div>
    </div>

    <div class="mt-4 bg-white rounded-2xl border border-gray-100 p-4">
        <form method="GET" action="{{ route('dashboard.payment.user') }}" class="grid grid-cols-1 md:grid-cols-3 gap-3 items-end">
            <div>
                <label class="block text-xs font-semibold text-gray-500 mb-1">Vendor</label>
                <select name="vendor_id" class="w-full h-11 border border-gray-200 rounded-xl px-3.5 py-2.5 text-sm focus:outline-none focus:border-gray-400 transition">
                    <option value="">Semua vendor</option>
                    @foreach($vendors as $v)
                        <option value="{{ $v->id }}" {{ (string) $vendorId === (string) $v->id ? 'selected' : '' }}>{{ $v->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-500 mb-1">Status</label>
                <select name="status" class="w-full h-11 border border-gray-200 rounded-xl px-3.5 py-2.5 text-sm focus:outline-none focus:border-gray-400 transition">
                    <option value="">Semua</option>
                    <option value="pending_verification" {{ ($status ?? '') === 'pending_verification' ? 'selected' : '' }}>pending_verification</option>
                    <option value="approved" {{ ($status ?? '') === 'approved' ? 'selected' : '' }}>approved</option>
                    <option value="rejected" {{ ($status ?? '') === 'rejected' ? 'selected' : '' }}>rejected</option>
                </select>
            </div>
            <div class="flex gap-2">
                <x-ui.button type="submit" class="flex-1" variant="primary" size="md">
                    Terapkan
                </x-ui.button>
                <x-ui.button href="{{ route('dashboard.payment.user') }}" variant="ghost" size="md">
                    Reset
                </x-ui.button>
            </div>
        </form>
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
                                    <a href="{{ $p->proof_url }}" target="_blank" class="font-bold text-dark hover:underline">Lihat</a>
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
                                            <x-ui.button type="submit" variant="primary" size="compact">
                                                Approve
                                            </x-ui.button>
                                        </form>
                                        <form method="POST" action="{{ route('dashboard.vendor.payments.verify', $p) }}" data-reject-form>
                                            @csrf
                                            <input type="hidden" name="action" value="reject">
                                            <input type="hidden" name="note" value="">
                                            <x-ui.button type="submit" variant="ghost" size="compact">
                                                Reject
                                            </x-ui.button>
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
    document.querySelectorAll('form[data-reject-form]').forEach(function (form) {
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
@endsection
