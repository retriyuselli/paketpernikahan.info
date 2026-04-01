@extends('layout.dashboard')

@section('title', 'Pengajuan Vendor — Makna Wedding')
@section('page-title', 'Pengajuan Vendor')

@section('content')
<div class="mb-8">
    <div class="flex items-start justify-between gap-3">
        <div>
            <h1 class="text-2xl font-bold mb-2" style="color: var(--dark-gray)">Pengajuan Vendor</h1>
            <p class="text-sm text-gray-500">Kelola pendaftaran vendor (approve / reject).</p>
        </div>
        <div class="inline-flex rounded-xl overflow-hidden border border-gray-100 bg-white flex-shrink-0">
            <a href="{{ request()->fullUrlWithQuery(['status' => null]) }}"
               class="text-xs font-bold px-3 py-2 transition {{ ($status ?? null) ? 'bg-white text-gray-500' : 'bg-gray-50 text-gray-700' }}">
                Semua
            </a>
            <a href="{{ request()->fullUrlWithQuery(['status' => 'pending']) }}"
               class="text-xs font-bold px-3 py-2 transition border-l border-gray-100 {{ ($status ?? null) === 'pending' ? 'bg-gray-50 text-gray-700' : 'bg-white text-gray-500' }}">
                Pending
            </a>
            <a href="{{ request()->fullUrlWithQuery(['status' => 'approved']) }}"
               class="text-xs font-bold px-3 py-2 transition border-l border-gray-100 {{ ($status ?? null) === 'approved' ? 'bg-gray-50 text-gray-700' : 'bg-white text-gray-500' }}">
                Approved
            </a>
            <a href="{{ request()->fullUrlWithQuery(['status' => 'rejected']) }}"
               class="text-xs font-bold px-3 py-2 transition border-l border-gray-100 {{ ($status ?? null) === 'rejected' ? 'bg-gray-50 text-gray-700' : 'bg-white text-gray-500' }}">
                Rejected
            </a>
        </div>
    </div>
</div>

@if (session('vendor_app_success'))
    <div class="mb-4 text-xs font-semibold px-3 py-2 rounded-xl bg-green-50 text-green-700">
        {{ session('vendor_app_success') }}
    </div>
@endif

@if ($errors->vendor_app->any())
    <div class="mb-4 text-xs font-semibold px-3 py-2 rounded-xl bg-red-50 text-red-700">
        <ul class="list-disc pl-4 space-y-1">
            @foreach ($errors->vendor_app->all() as $err)
                <li>{{ $err }}</li>
            @endforeach
        </ul>
    </div>
@endif

@if($applications->isEmpty())
    <div class="bg-white rounded-2xl border border-gray-100 p-10 text-center flex flex-col items-center">
        <div class="w-16 h-16 rounded-full bg-gray-50 flex items-center justify-center mb-4">
            <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 1.343-3 3v2h6v-2c0-1.657-1.343-3-3-3z"/>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 11a7 7 0 0114 0v7a1 1 0 01-1 1H6a1 1 0 01-1-1v-7z"/>
            </svg>
        </div>
        <h3 class="text-base font-bold mb-1" style="color: var(--dark-gray)">Belum ada pengajuan</h3>
        <p class="text-sm text-gray-500 max-w-sm">Belum ada pendaftaran vendor.</p>
    </div>
@else
    <div class="bg-white rounded-2xl border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="bg-gray-50 text-gray-500 text-xs uppercase tracking-wider">
                    <tr>
                        <th class="text-left px-4 py-3 font-semibold">No</th>
                        <th class="text-left px-4 py-3 font-semibold">User</th>
                        <th class="text-left px-4 py-3 font-semibold">Logo</th>
                        <th class="text-left px-4 py-3 font-semibold">Usaha</th>
                        <th class="text-left px-4 py-3 font-semibold">Kategori</th>
                        <th class="text-left px-4 py-3 font-semibold">Kota</th>
                        <th class="text-left px-4 py-3 font-semibold">Status</th>
                        <th class="text-left px-4 py-3 font-semibold"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($applications as $i => $a)
                        @php
                            $statusClass = match($a->status) {
                                'approved' => 'bg-green-50 text-green-700',
                                'rejected' => 'bg-red-50 text-red-700',
                                default => 'bg-yellow-50 text-yellow-700',
                            };
                        @endphp
                        <tr class="hover:bg-gray-50/60 transition">
                            <td class="px-4 py-3 text-xs text-gray-500 align-top">{{ $i + 1 }}</td>
                            <td class="px-4 py-3 align-top">
                                <div class="font-bold text-xs" style="color: var(--dark-gray)">{{ $a->user?->name ?? '—' }}</div>
                                <div class="text-[10px] mt-1 text-gray-400">{{ $a->user?->email ?? '' }}</div>
                            </td>
                            <td class="px-4 py-3 align-top">
                                @if($a->logo_vendor)
                                    <a href="{{ asset('storage/' . ltrim($a->logo_vendor, '/')) }}" target="_blank" class="block w-10 h-10 rounded-xl overflow-hidden border border-gray-100 bg-gray-50">
                                        <img src="{{ asset('storage/' . ltrim($a->logo_vendor, '/')) }}" alt="Logo" class="w-full h-full object-cover">
                                    </a>
                                @else
                                    <div class="w-10 h-10 rounded-xl overflow-hidden border border-gray-100 bg-gray-50 flex items-center justify-center">
                                        <span class="text-[10px] text-gray-400">—</span>
                                    </div>
                                @endif
                            </td>
                            <td class="px-4 py-3 align-top">
                                <div class="font-bold text-xs" style="color: var(--dark-gray)">{{ $a->business_name }}</div>
                                <div class="text-[10px] mt-1 text-gray-400">{{ $a->phone }}</div>
                            </td>
                            @php
                                $appCats = is_array($a->categories) && count($a->categories) ? $a->categories : ($a->category ? [$a->category] : []);
                                $appCatsLabel = collect($appCats)->filter()->join(', ');
                            @endphp
                            <td class="px-4 py-3 text-xs text-gray-600 align-top">{{ $appCatsLabel }}</td>
                            <td class="px-4 py-3 text-xs text-gray-600 align-top">{{ $a->city }}</td>
                            <td class="px-4 py-3 align-top">
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs {{ $statusClass }}">
                                    {{ $a->status }}
                                </span>
                            </td>
                            <td class="px-4 py-3 align-top">
                                <div class="inline-flex flex-col rounded-lg overflow-hidden border border-gray-100 bg-gray-50">
                                    @if($a->status !== 'approved')
                                        <form method="POST" action="{{ route('dashboard.vendor.applications.approve', $a) }}">
                                            @csrf
                                            <button type="submit" class="text-xs font-bold px-3 py-2 hover:bg-gray-100 transition text-left w-full" style="color: var(--dark-gray)">
                                                Approve
                                            </button>
                                        </form>
                                    @endif
                                    @if($a->status !== 'rejected')
                                        <form method="POST" action="{{ route('dashboard.vendor.applications.reject', $a) }}" data-reject-form class="{{ $a->status !== 'approved' ? 'border-t border-gray-100' : '' }}">
                                            @csrf
                                            <input type="hidden" name="admin_note" value="">
                                            <button type="submit" class="text-xs font-bold px-3 py-2 hover:bg-gray-100 transition text-left w-full" style="color: var(--dark-gray)">
                                                Reject
                                            </button>
                                        </form>
                                    @endif
                                    @if($a->vendor)
                                        <a href="{{ route('vendor.detail', $a->vendor->slug) }}" class="text-xs font-bold px-3 py-2 hover:bg-gray-100 transition border-t border-gray-100" style="color: var(--dark-gray)">
                                            Lihat Vendor
                                        </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @if(filled($a->note) || filled($a->admin_note))
                            <tr class="bg-gray-50/40">
                                <td colspan="8" class="px-4 pb-3 pt-2 text-xs text-gray-500">
                                    @if(filled($a->note))
                                        <div class="mb-1"><span class="font-semibold text-gray-600">Catatan user:</span> {{ $a->note }}</div>
                                    @endif
                                    @if(filled($a->admin_note))
                                        <div><span class="font-semibold text-gray-600">Catatan admin:</span> {{ $a->admin_note }}</div>
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
            var note = window.prompt('Alasan menolak pengajuan?');
            if (note === null) {
                e.preventDefault();
                return;
            }
            note = (note || '').trim();
            if (!note) {
                e.preventDefault();
                return;
            }
            var input = form.querySelector('input[name="admin_note"]');
            if (input) input.value = note;
        });
    });
});
</script>
@endsection
