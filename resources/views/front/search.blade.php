@extends('layout.app')

@section('title', $q ? 'Hasil Pencarian "' . $q . '" - Makna Wedding' : 'Pencarian - Makna Wedding')

@section('body-class', 'bg-cream text-dark')

@section('content')
    @include('layout.header')

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

        {{-- Search Bar --}}
        <form method="GET" action="{{ route('search') }}" class="flex items-center gap-2 mb-8 max-w-xl">
            <div class="relative flex-1">
                <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                <input type="text" name="q" value="{{ $q }}"
                       placeholder="Cari vendor atau paket..."
                       autofocus
                       class="w-full border border-gray-200 rounded-xl pl-9 pr-4 py-2.5 text-sm focus:outline-none focus:border-accent transition bg-white">
            </div>
            <button type="submit" class="px-5 py-2.5 rounded-xl text-sm font-bold transition hover:opacity-90 bg-accent text-white">
                Cari
            </button>
        </form>

        @if ($q === '')
            {{-- No query yet --}}
            <div class="flex flex-col items-center justify-center py-20 text-center">
                <div class="w-16 h-16 rounded-full bg-white flex items-center justify-center mb-4 border border-gray-100">
                    <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>
                <p class="text-base font-semibold text-dark">Ketik kata kunci di atas</p>
                <p class="text-sm text-gray-400 mt-1">Temukan vendor dan paket pernikahan favoritmu</p>
            </div>
        @else
            {{-- Summary --}}
            <div class="mb-6 flex flex-wrap items-center gap-3">
                <p class="text-sm text-gray-600">
                    Hasil untuk <strong class="text-dark">"{{ $q }}"</strong>:
                    <span class="ml-1 text-accent font-semibold">{{ $vendors->count() }}</span> vendor,
                    <span class="text-accent font-semibold">{{ $packages->count() }}</span> paket
                </p>
                <a href="{{ route('search') }}" class="text-xs text-gray-400 hover:text-accent transition flex items-center gap-1">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                    Hapus filter
                </a>
            </div>

            {{-- ===== VENDOR RESULTS ===== --}}
            <section class="mb-12">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-base font-bold text-dark">Vendor</h2>
                    @if($vendors->count() > 0)
                        <a href="{{ route('vendor') }}?q={{ urlencode($q) }}" class="text-xs font-semibold text-accent hover:underline">
                            Lihat Semua Vendor →
                        </a>
                    @endif
                </div>

                @if($vendors->isEmpty())
                    <div class="bg-white rounded-2xl border border-gray-100 p-8 text-center">
                        <p class="text-sm text-gray-400">Tidak ada vendor yang cocok dengan "<strong>{{ $q }}</strong>"</p>
                    </div>
                @else
                    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-3">
                        @foreach ($vendors as $v)
                        @php
                            $pkg = $v->cheapestPackage;
                            $cover = $v->cover_image_url ?: (optional($v->galleries->first())->image_url ?? null);
                            $vData = [
                                'name'           => $v->name,
                                'city'           => $v->city,
                                'location'       => $v->location,
                                'rating'         => $v->rating,
                                'likes'          => $v->likes,
                                'comments_count' => $v->comments_count,
                                'cover'          => $cover,
                                'detail_url'     => route('vendor.detail', $v->slug),
                                'wa_url'         => 'https://wa.me/' . preg_replace('/[^0-9]/', '', $v->phone ?? ''),
                                'pkg_price'      => optional($pkg)->price,
                                'pkg_discount'   => optional($pkg)->discount ?? 0,
                                'pkg_name'       => optional($pkg)->name,
                                'price_start'    => is_numeric($v->price_start) ? 'Rp ' . number_format((int) $v->price_start, 0, ',', '.') : ($v->price_start ?: '—'),
                            ];
                        @endphp
                        <a href="{{ route('vendor.detail', $v->slug) }}"
                           class="group border border-gray-200 rounded-2xl p-2 hover:border-gray-300 transition bg-white block">
                            {{-- Photo --}}
                            <div class="relative rounded-xl overflow-hidden mb-2 aspect-[4/5]">
                                @if($cover)
                                    <img src="{{ $cover }}" alt="{{ $v->name }}" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
                                @else
                                    <div class="w-full h-full bg-gray-100 flex items-center justify-center">
                                        <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                    </div>
                                @endif
                                @if($v->city)
                                <div class="absolute bottom-2 left-0 right-0 flex justify-center z-10">
                                    <span class="flex items-center gap-1 text-[10px] font-semibold text-white px-2.5 py-0.5 rounded-full bg-black/40 backdrop-blur-[2px]">
                                        <svg class="w-2.5 h-2.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/></svg>
                                        {{ $v->city }}
                                    </span>
                                </div>
                                @endif
                            </div>

                            <p class="font-bold text-sm leading-snug group-hover:underline text-dark">{{ $v->name }}</p>

                            @if($pkg)
                            <div class="flex items-center gap-1.5 mt-1 mb-1">
                                <span class="text-[9px] text-gray-400">Mulai</span>
                                @if($pkg->discount > 0)
                                    <span class="text-[10px] line-through text-gray-400">Rp {{ number_format($pkg->price, 0, ',', '.') }}</span>
                                    <span class="text-[11px] font-bold text-dark">Rp {{ number_format($pkg->price - $pkg->discount, 0, ',', '.') }}</span>
                                @else
                                    <span class="text-[11px] font-semibold text-dark">Rp {{ number_format($pkg->price, 0, ',', '.') }}</span>
                                @endif
                            </div>
                            @elseif($v->price_start)
                            <div class="flex items-center gap-1.5 mt-1 mb-1">
                                <span class="text-[9px] text-gray-400">Mulai</span>
                                <span class="text-[11px] font-semibold text-dark">{{ is_numeric($v->price_start) ? 'Rp ' . number_format((int) $v->price_start, 0, ',', '.') : $v->price_start }}</span>
                            </div>
                            @endif

                            @php
                                $vComments = (int) ($v->comments_count ?? 0);
                                $vRating   = (float) ($v->rating ?? 0);
                                $vLikes    = (int) ($v->likes ?? 0);
                            @endphp
                            @if($vRating >= 1 || $vComments >= 1 || $vLikes >= 1)
                            <div class="flex items-center gap-2 text-[10px] text-gray-500 flex-wrap">
                                @if($vRating >= 1)
                                <span class="flex items-center gap-0.5 font-semibold text-yellow-500">
                                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                    {{ number_format($vRating, 1) }}
                                </span>
                                @endif
                                @if($vComments >= 1)
                                <span>{{ $vComments }} ulasan</span>
                                @endif
                            </div>
                            @endif
                        </a>
                        @endforeach
                    </div>
                @endif
            </section>

            {{-- ===== PACKAGE RESULTS ===== --}}
            <section>
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-base font-bold text-dark">Paket</h2>
                    @if($packages->count() > 0)
                        <a href="{{ route('store') }}?q={{ urlencode($q) }}" class="text-xs font-semibold text-accent hover:underline">
                            Lihat di Store →
                        </a>
                    @endif
                </div>

                @if($packages->isEmpty())
                    <div class="bg-white rounded-2xl border border-gray-100 p-8 text-center">
                        <p class="text-sm text-gray-400">Tidak ada paket yang cocok dengan "<strong>{{ $q }}</strong>"</p>
                    </div>
                @else
                    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-3">
                        @foreach ($packages as $pkg)
                        @php
                            $vendor = $pkg->vendor;
                            if (!$vendor) continue;
                            $price    = (int) ($pkg->price ?? 0);
                            $discount = (int) ($pkg->discount ?? 0);
                            $final    = max($price - $discount, 0);
                            $cover    = $pkg->image_url ?? null;
                            if (!$cover) {
                                $cover = $vendor->cover_image_url ?: null;
                                if (!$cover && is_array($vendor->cover_image ?? null) && count($vendor->cover_image) > 0) {
                                    $cover = $vendor->cover_image[0];
                                }
                            }
                        @endphp
                        <a href="{{ route('store.package.show', $pkg) }}"
                           class="group bg-white rounded-2xl border border-gray-100 overflow-hidden hover:border-gray-200 hover:shadow-sm transition block">
                            <div class="relative ar-4x3">
                                @if($cover)
                                    <img src="{{ $cover }}" alt="{{ $pkg->name }}" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
                                @else
                                    <div class="w-full h-full bg-gray-100 flex items-center justify-center">
                                        <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                    </div>
                                @endif
                                <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/10 to-transparent"></div>
                                @if($discount > 0)
                                <span class="absolute top-2 left-2 text-[10px] font-bold px-2 py-0.5 rounded-full bg-white/90 border border-gray-200 text-dark">
                                    Diskon
                                </span>
                                @endif
                                <div class="absolute bottom-0 left-0 right-0 p-3">
                                    <p class="text-white text-xs font-bold leading-snug">{{ $pkg->name }}</p>
                                    <p class="text-white/80 text-[10px] mt-0.5">{{ $vendor->name }}</p>
                                </div>
                            </div>
                            <div class="p-3">
                                <p class="text-[10px] text-gray-400 truncate mb-1">{{ $vendor->city }}{{ $vendor->location ? ' · ' . $vendor->location : '' }}</p>
                                @if($discount > 0)
                                    <p class="text-[11px] text-gray-400 line-through">Rp {{ number_format($price, 0, ',', '.') }}</p>
                                    <p class="text-sm font-extrabold leading-tight text-accent">Rp {{ number_format($final, 0, ',', '.') }}</p>
                                @else
                                    <p class="text-sm font-extrabold leading-tight text-accent">Rp {{ number_format($price, 0, ',', '.') }}</p>
                                @endif
                            </div>
                        </a>
                        @endforeach
                    </div>
                @endif
            </section>
        @endif
    </div>

    @include('layout.footer')
@endsection
