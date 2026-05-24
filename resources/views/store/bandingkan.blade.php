@extends('layout.app')

@section('title', 'Bandingkan Paket Pernikahan - Makna Wedding')
@section('meta-description', 'Bandingkan paket pernikahan secara berdampingan — harga, fasilitas, vendor, dan detail lengkap untuk membantu kamu memilih yang terbaik.')
@section('canonical-url', route('store.bandingkan'))

@section('body-class', 'bg-cream text-dark')

@section('content')
    @include('layout.header')

    @php
        $breadcrumbItems = [
            ['label' => 'Home', 'url' => route('home')],
            ['label' => 'Store', 'url' => route('store')],
            ['label' => 'Bandingkan Paket', 'url' => null],
        ];
    @endphp

    <section class="pt-3 lg:pt-3 lg:pb-8 bg-cream">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="pt-1 pb-4 lg:pt-1">
                <x-breadcrumb :items="$breadcrumbItems" />
            </div>

            {{-- Header --}}
            <div class="mb-5">
                <h1 class="text-xl font-bold text-dark">Bandingkan Paket</h1>
                <p class="text-[13px] text-gray-500 mt-0.5">
                    {{ count($packages) > 0 ? 'Perbandingan ' . count($packages) . ' paket yang kamu pilih' : 'Pilih 2–3 paket dari halaman Store untuk dibandingkan' }}
                </p>
            </div>

            @if(count($packages) < 2)
                {{-- Empty state --}}
                <div class="bg-white rounded-2xl border border-gray-100 p-10 text-center">
                    <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                    </div>
                    <p class="text-dark font-semibold mb-1">Pilih minimal 2 paket untuk dibandingkan</p>
                    <p class="text-[13px] text-gray-400 mb-5">Klik tombol <strong>"Bandingkan"</strong> pada kartu paket, lalu klik <strong>"Bandingkan (2)"</strong> di bar bawah</p>
                    <a href="{{ route('store') }}" class="inline-flex items-center gap-2 bg-accent text-white text-[13px] font-semibold px-5 py-2.5 rounded-xl hover:bg-accent/90 transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 10h16M4 14h16M4 18h16"/>
                        </svg>
                        Lihat Semua Paket
                    </a>
                </div>

            @else
                {{-- Tabel Perbandingan --}}
                <div class="bg-white rounded-2xl border border-gray-100 overflow-hidden mb-5">

                    {{-- Header: foto + nama --}}
                    <div class="grid border-b border-gray-100" style="grid-template-columns: 140px repeat({{ count($packages) }}, 1fr)">
                        <div class="p-4 bg-gray-50/70 flex items-end">
                            <span class="text-[11px] font-semibold text-gray-400 uppercase tracking-wide">Paket</span>
                        </div>
                        @foreach($packages as $pkg)
                        @php
                            $basePrice = (int) ($pkg->price ?? 0);
                            $disc      = (int) ($pkg->discount ?? 0);
                            $finalPrice = max($basePrice - $disc, 0);
                            $discPct   = $basePrice > 0 && $disc > 0 ? (int) round(($disc / $basePrice) * 100) : 0;
                            $rawImg    = is_array($pkg->image_path) ? ($pkg->image_path[0] ?? null) : $pkg->image_path;
                            $imgUrl    = $rawImg
                                ? (\Illuminate\Support\Str::startsWith($rawImg, 'http') ? $rawImg : asset('storage/' . $rawImg))
                                : 'https://placehold.co/400x225/f3f4f6/9ca3af?text=No+Photo';
                        @endphp
                        <div class="p-4 border-l border-gray-100 text-center">
                            <div class="relative rounded-xl overflow-hidden aspect-video mb-3">
                                <img src="{{ $imgUrl }}" alt="{{ $pkg->name }}" loading="lazy" class="w-full h-full object-cover">
                                @if($discPct > 0)
                                    <span class="absolute top-1.5 right-1.5 bg-accent text-white text-[10px] font-bold px-1.5 py-0.5 rounded-lg">-{{ $discPct }}%</span>
                                @endif
                            </div>
                            <p class="font-bold text-[13px] text-dark leading-tight mb-0.5">{{ Str::title($pkg->name) }}</p>
                            <p class="text-[11px] text-gray-400 mb-3">{{ $pkg->vendor->name ?? '-' }}</p>
                            <a href="{{ route('store.package.show', $pkg) }}"
                               class="inline-block w-full bg-accent text-white text-[12px] font-semibold py-2 rounded-xl hover:bg-accent/90 transition">
                                Lihat Detail
                            </a>
                        </div>
                        @endforeach
                    </div>

                    @php
                    $rows = [
                        ['label' => 'Harga',          'type' => 'price'],
                        ['label' => 'Diskon',          'type' => 'discount'],
                        ['label' => 'DP / Uang Muka',  'type' => 'dp'],
                        ['label' => 'Kapasitas Tamu',  'type' => 'guests'],
                        ['label' => 'Vendor',          'type' => 'vendor'],
                        ['label' => 'Kota',            'type' => 'city'],
                        ['label' => 'Kategori',        'type' => 'category'],
                        ['label' => 'Yang Termasuk',   'type' => 'item'],
                    ];
                    @endphp

                    @foreach($rows as $rowIdx => $row)
                    <div class="grid border-b border-gray-100 last:border-b-0 {{ $rowIdx % 2 === 0 ? 'bg-white' : 'bg-gray-50/40' }}"
                         style="grid-template-columns: 140px repeat({{ count($packages) }}, 1fr)">

                        {{-- Label --}}
                        <div class="p-4 flex items-start bg-gray-50/70 border-r border-gray-100">
                            <span class="text-[12px] font-semibold text-gray-500">{{ $row['label'] }}</span>
                        </div>

                        {{-- Nilai per paket --}}
                        @foreach($packages as $pkg)
                        @php
                            $basePrice  = (int) ($pkg->price ?? 0);
                            $disc       = (int) ($pkg->discount ?? 0);
                            $finalPrice = max($basePrice - $disc, 0);
                            $discPct    = $basePrice > 0 && $disc > 0 ? (int) round(($disc / $basePrice) * 100) : 0;
                            $dp         = (int) ($pkg->dp_paket ?? 0);
                            $categoryLabel = match($pkg->vendor->category ?? '') {
                                'wo', 'wedding-organizer' => 'Wedding Organizer',
                                'fotografer'  => 'Fotografer',
                                'katering'    => 'Katering',
                                'dekorasi'    => 'Dekorasi',
                                'hotel'       => 'Hotel & Venue',
                                'undangan'    => 'Undangan',
                                'mua'         => 'MUA & Rias',
                                default       => Str::title(str_replace('-', ' ', $pkg->vendor->category ?? '-')),
                            };
                        @endphp
                        <div class="p-4 border-l border-gray-100">
                            @if($row['type'] === 'price')
                                @if($disc > 0)
                                    <p class="text-[11px] text-gray-400 line-through leading-tight">Rp{{ number_format($basePrice, 0, ',', '.') }}</p>
                                    <p class="text-[15px] font-extrabold text-accent leading-tight">Rp{{ number_format($finalPrice, 0, ',', '.') }}</p>
                                @else
                                    <p class="text-[15px] font-extrabold text-accent leading-tight">Rp{{ number_format($basePrice, 0, ',', '.') }}</p>
                                @endif

                            @elseif($row['type'] === 'discount')
                                @if($disc > 0)
                                    <span class="inline-flex items-center gap-1 bg-red-50 text-red-500 text-[11px] font-semibold px-2 py-0.5 rounded-lg">
                                        -{{ $discPct }}% &nbsp;(Rp{{ number_format($disc, 0, ',', '.') }})
                                    </span>
                                @else
                                    <span class="text-[12px] text-gray-300">—</span>
                                @endif

                            @elseif($row['type'] === 'dp')
                                @if($dp > 0)
                                    <p class="text-[13px] font-semibold text-dark">Rp{{ number_format($dp, 0, ',', '.') }}</p>
                                @else
                                    <span class="text-[12px] text-gray-300">—</span>
                                @endif

                            @elseif($row['type'] === 'guests')
                                <p class="text-[13px] text-dark">{{ filled($pkg->max_guests) ? $pkg->max_guests : '—' }}</p>

                            @elseif($row['type'] === 'vendor')
                                @if($pkg->vendor)
                                    <a href="{{ route('vendor.detail', $pkg->vendor) }}" class="text-[12px] font-medium text-accent hover:underline">
                                        {{ $pkg->vendor->name }}
                                    </a>
                                @else
                                    <span class="text-[12px] text-gray-300">—</span>
                                @endif

                            @elseif($row['type'] === 'city')
                                <p class="text-[12px] text-dark">{{ $pkg->vendor->city ?? '—' }}</p>

                            @elseif($row['type'] === 'category')
                                <p class="text-[12px] text-dark">{{ $categoryLabel }}</p>

                            @elseif($row['type'] === 'item')
                                @if(filled($pkg->item))
                                    <div class="text-[12px] text-dark leading-relaxed compare-item-list">
                                        {!! $pkg->item !!}
                                    </div>
                                @else
                                    <span class="text-[12px] text-gray-300">—</span>
                                @endif
                            @endif
                        </div>
                        @endforeach
                    </div>
                    @endforeach
                </div>

                {{-- Tombol bawah --}}
                <div class="flex items-center justify-between gap-4 mb-8">
                    <a href="{{ route('store') }}" class="inline-flex items-center gap-2 text-[13px] text-gray-500 hover:text-dark transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                        Kembali ke Paket
                    </a>
                    <button
                        x-data
                        @click="$store.compare.clear(); window.location.href='{{ route('store') }}';"
                        class="text-[12px] text-gray-400 hover:text-red-500 transition"
                    >
                        Hapus Perbandingan
                    </button>
                </div>
            @endif
        </div>
    </section>

    @include('layout.footer')
@endsection

@section('extra-scripts')
<style>
.compare-item-list ul, .compare-item-list ol { list-style: disc; padding-left: 1.25rem; }
.compare-item-list li { font-size: 12px; color: #374151; line-height: 1.7; }
.compare-item-list p { margin-bottom: 0.25rem; font-size: 12px; }
.compare-item-list strong { font-weight: 600; }
</style>
@endsection
