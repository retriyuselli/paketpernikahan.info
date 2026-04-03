@extends('layout.app')

@section('title', ($package->name ?? 'Detail Paket') . ' - Makna Wedding')

@section('body-class', 'bg-cream text-dark')

@section('content')
    @include('layout.header')

    @php
        $price = (int) ($package->price_raw ?? 0);
        $discount = (int) ($package->discount ?? 0);
        $final = max($price - $discount, 0);
        $wa = preg_replace('/[^0-9]/', '', (string) ($vendor->phone ?? ''));
        $waText = rawurlencode('Halo ' . $vendor->name . ', saya tertarik dengan paket "' . $package->name . '". Mohon info lengkap ya.');
        $waUrl = $wa ? "https://wa.me/{$wa}?text={$waText}" : null;
        $categorySlug = $package->categoryVendor?->slug ?? $vendor->category;
        $categoryName = $package->categoryVendor?->name ?? ($vendor->categoryVendor?->name ?? $vendor->category);
        $breadcrumbItems = [
            ['label' => 'Home', 'url' => route('home')],
            ['label' => 'Store', 'url' => route('store')],
            ['label' => $categoryName, 'url' => route('vendor') . '?category=' . $categorySlug],
            ['label' => $package->name, 'url' => null],
        ];
    @endphp

    <section class="py-8 bg-cream">
        <x-ui.container>
            <div class="pt-4 pb-4">
                @include('layout.breadcrumb', ['items' => $breadcrumbItems])
            </div>
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
                <div class="lg:col-span-8">
                    <div class="bg-white rounded-2xl border border-gray-100 p-5">
                        <div class="flex items-start justify-between gap-4">
                            <div class="min-w-0">
                                <h1 class="text-lg sm:text-xl font-extrabold leading-tight text-dark">{{ $package->name }}</h1>
                                <p class="text-xs text-gray-400 mt-1">
                                    <a href="{{ route('vendor.detail', $vendor->slug) }}" class="font-semibold hover:opacity-80 transition text-accent">{{ $vendor->name }}</a>
                                    <span class="text-gray-300">—</span>
                                    <span>{{ $categoryName }}</span>
                                </p>
                            </div>
                            <a href="{{ route('store') }}" class="text-xs font-bold px-3 py-2 rounded-xl border border-gray-200 bg-white hover:border-gray-300 transition text-dark">
                                Kembali
                            </a>
                        </div>

                        <div class="mt-4 flex flex-wrap items-center gap-2">
                            @if($package->max_guests)
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-bold bg-light-sage text-dark">
                                    {{ $package->max_guests }}
                                </span>
                            @endif
                            @if($vendor->city)
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-bold bg-gray-50 text-gray-700 border border-gray-100">
                                    {{ $vendor->city }}
                                </span>
                            @endif
                            @if($vendor->location)
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-bold bg-gray-50 text-gray-700 border border-gray-100">
                                    {{ $vendor->location }}
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="mt-5 bg-white rounded-2xl border border-gray-100 p-5">
                        <div class="relative">
                            <button type="button"
                                    data-scroll-target="store-detail-images-scroll" data-scroll-by="-500"
                                    class="hidden lg:flex absolute left-0 top-1/2 -translate-y-1/2 -translate-x-4 w-10 h-10 bg-white rounded-full shadow-md items-center justify-center hover:shadow-lg transition z-10">
                                <svg class="w-5 h-5 text-dark" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                                </svg>
                            </button>

                            <div id="store-detail-images-scroll" class="flex gap-3 overflow-x-auto scroll-smooth pb-2 scrollbar-hide">
                                @foreach($images as $img)
                                    @php
                                        $imgUrl = is_array($img) ? ($img[0] ?? null) : $img;
                                        if (is_string($imgUrl) && $imgUrl !== '' && !str_starts_with($imgUrl, 'http') && !str_starts_with($imgUrl, '/storage')) {
                                            $imgUrl = \Illuminate\Support\Facades\Storage::url($imgUrl);
                                        }
                                    @endphp
                                    @if($imgUrl)
                                        <button type="button"
                                                data-store-image-open data-store-image-src="{{ $imgUrl }}"
                                                class="group flex-none w-72 rounded-2xl overflow-hidden border border-gray-100 bg-gray-50 relative ar-16x10">
                                            <img src="{{ $imgUrl }}" alt="Paket {{ $package->name }}" class="w-full h-full object-cover">
                                            <div class="absolute inset-0 bg-black/0 group-hover:bg-black/10 transition"></div>
                                            <div class="absolute bottom-2 right-2 px-2 py-1 rounded-full text-[10px] font-bold bg-white/90 border border-gray-200 opacity-0 group-hover:opacity-100 transition text-dark">
                                                Perbesar
                                            </div>
                                        </button>
                                    @endif
                                @endforeach

                                @if($images->isEmpty())
                                    <div class="flex-none w-72 rounded-2xl overflow-hidden border border-gray-100 bg-gray-50 flex items-center justify-center text-xs text-gray-400 ar-16x10">
                                        Tidak ada foto
                                    </div>
                                @endif
                            </div>

                            <button type="button"
                                    data-scroll-target="store-detail-images-scroll" data-scroll-by="500"
                                    class="hidden lg:flex absolute right-0 top-1/2 -translate-y-1/2 translate-x-4 w-10 h-10 bg-white rounded-full shadow-md items-center justify-center hover:shadow-lg transition z-10">
                                <svg class="w-5 h-5 text-dark" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <div class="mt-5 bg-white rounded-2xl border border-gray-100 p-5">
                        <p class="text-sm font-bold mb-3 text-dark">Detail</p>
                        @if(is_array($package->items) && count($package->items))
                            @php $itemsCount = count($package->items); @endphp
                            <div class="relative">
                                <div id="store-detail-items" class="{{ $itemsCount > 10 ? 'max-h-64 overflow-hidden' : '' }} transition-[max-height]">
                                    <ul class="space-y-1.5 text-sm text-gray-700">
                                        @foreach($package->items as $it)
                                            <li class="flex items-start gap-2">
                                                <span class="mt-1 w-1.5 h-1.5 rounded-full flex-shrink-0 bg-accent"></span>
                                                <span>{{ $it }}</span>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>

                                @if($itemsCount > 10)
                                    <div id="store-detail-items-fade" class="pointer-events-none absolute left-0 right-0 bottom-0 h-16 bg-gradient-to-t from-white to-transparent"></div>
                                    <div class="pt-3 flex justify-center">
                                        <button type="button"
                                                id="store-detail-items-toggle"
                                                class="text-xs font-bold px-3 py-2 rounded-xl border border-gray-200 bg-white hover:border-gray-300 transition text-dark">
                                            Lihat semua ({{ $itemsCount }})
                                        </button>
                                    </div>
                                @endif
                            </div>
                        @else
                            <p class="text-sm text-gray-500">Detail paket belum tersedia.</p>
                        @endif
                    </div>

                    @if($package->type || $package->capacity || (is_array($package->facilities) && count($package->facilities)))
                        <div class="mt-5 bg-white rounded-2xl border border-gray-100 p-5">
                            <p class="text-sm font-bold mb-3 text-dark">Detail Venue</p>
                            <ul class="space-y-2 text-sm text-gray-700">
                                @if($package->type)
                                    <li class="flex items-start gap-2">
                                        <svg class="w-4 h-4 flex-shrink-0 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                                        <span><strong>Tipe:</strong> {{ $package->type }}</span>
                                    </li>
                                @endif
                                @if($package->capacity)
                                    <li class="flex items-start gap-2">
                                        <svg class="w-4 h-4 flex-shrink-0 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                                        <span><strong>Kapasitas:</strong> {{ number_format((int) $package->capacity, 0, ',', '.') }} Orang</span>
                                    </li>
                                @endif
                                @if(is_array($package->facilities) && count($package->facilities))
                                    <li class="flex items-start gap-2">
                                        <svg class="w-4 h-4 flex-shrink-0 text-accent mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                        <span><strong>Fasilitas:</strong> {{ implode(', ', $package->facilities) }}</span>
                                    </li>
                                @endif
                            </ul>
                        </div>
                    @endif

                    @if(isset($videos) && $videos->isNotEmpty())
                        <div class="mt-5">
                            <div class="flex items-center justify-between mb-4">
                                <h2 class="text-base font-bold text-dark">Videos Paket</h2>
                            </div>
                            <div class="flex gap-3 overflow-x-auto pb-2 snap-x snap-mandatory scrollbar-hide">
                                @foreach ($videos as $idx => $v)
                                    @php
                                        $hasVideo = !empty($v->video_url);
                                        $cover = null;
                                        if (is_string($v->image_video) && $v->image_video !== '') {
                                            $cover = str_starts_with($v->image_video, 'http')
                                                ? $v->image_video
                                                : \Illuminate\Support\Facades\Storage::url($v->image_video);
                                        }
                                        if (!$cover) {
                                            $cover = $vendor->cover_image_url ?: null;
                                        }
                                        if (!$cover && is_array($vendor->cover_image ?? null) && count($vendor->cover_image) > 0) {
                                            $cover = $vendor->cover_image[0];
                                            if ($cover && !str_starts_with($cover, 'http')) {
                                                $cover = \Illuminate\Support\Facades\Storage::url($cover);
                                            }
                                        }
                                        $cover = $cover ?: ('data:image/svg+xml;utf8,' . rawurlencode('<svg xmlns="http://www.w3.org/2000/svg" width="640" height="480" viewBox="0 0 640 480"><defs><linearGradient id="g" x1="0" x2="0" y1="0" y2="1"><stop offset="0" stop-color="#111827"/><stop offset="1" stop-color="#1f2937"/></linearGradient></defs><rect width="640" height="480" fill="url(#g)"/><text x="50%" y="50%" dominant-baseline="middle" text-anchor="middle" fill="#9ca3af" font-family=\"Arial, sans-serif\" font-size=\"20\">No Video Cover</text></svg>'));
                                    @endphp
                                    <div class="relative flex-shrink-0 rounded-2xl overflow-hidden cursor-pointer group snap-start w-[180px] h-[280px]"
                                         @if($hasVideo) data-action="open-video" data-video-url="{{ $v->video_url }}" @endif>
                                        <img src="{{ $cover }}"
                                             alt="Review Video {{ $idx + 1 }}"
                                             class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
                                        <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent"></div>
                                        @if($hasVideo)
                                            <div class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 group-focus-within:opacity-100 transition">
                                                <div class="w-12 h-12 rounded-full bg-white/90 group-hover:bg-white flex items-center justify-center shadow-lg transition">
                                                    <svg class="w-5 h-5 ml-0.5 text-dark" fill="currentColor" viewBox="0 0 24 24">
                                                        <path d="M8 5v14l11-7z"/>
                                                    </svg>
                                                </div>
                                            </div>
                                        @endif
                                        <div class="absolute bottom-0 left-0 right-0 p-3">
                                            <p class="text-[9px] uppercase tracking-widest text-white/70 mb-0.5">Introducing</p>
                                            <p class="text-xs font-bold uppercase leading-tight text-white">{{ $v->caption }}</p>
                                            <p class="text-[10px] text-white/60 mt-0.5">{{ $vendor->name }}</p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    @if($otherPackages->isNotEmpty())
                        <div class="mt-5">
                            <div class="flex items-end justify-between gap-4 mb-3">
                                <div>
                                    <p class="text-sm font-bold text-dark">Produk Lainnya oleh Vendor Ini</p>
                                    <p class="text-xs text-gray-400">{{ $otherPackages->count() }} paket</p>
                                </div>
                            </div>

                            <div class="flex gap-3 overflow-x-auto scroll-smooth pb-2 scrollbar-hide">
                                @foreach($otherPackages->take(12) as $op)
                                    @php
                                        $opPrice = (int) ($op->price_raw ?? 0);
                                        $opDiscount = (int) ($op->discount ?? 0);
                                        $opFinal = max($opPrice - $opDiscount, 0);
                                        $opCover = $vendor->cover_image_url ?: null;
                                        if (!$opCover && is_array($vendor->cover_image ?? null) && count($vendor->cover_image) > 0) {
                                            $opCover = $vendor->cover_image[0];
                                        }
                                        $opCover = $opCover ?: ('data:image/svg+xml;utf8,' . rawurlencode('<svg xmlns="http://www.w3.org/2000/svg" width="640" height="480" viewBox="0 0 640 480"><defs><linearGradient id="g" x1="0" x2="0" y1="0" y2="1"><stop offset="0" stop-color="#f3f4f6"/><stop offset="1" stop-color="#e5e7eb"/></linearGradient></defs><rect width="640" height="480" fill="url(#g)"/><text x="50%" y="50%" dominant-baseline="middle" text-anchor="middle" fill="#9ca3af" font-family="Arial, sans-serif" font-size="20">No Image</text></svg>'));
                                    @endphp
                                    <a href="{{ route('store.package.show', $op) }}"
                                       class="flex-none w-64 bg-white rounded-2xl border border-gray-100 overflow-hidden hover:border-gray-200 hover:shadow-sm transition">
                                        <div class="relative ar-4x3">
                                            <img src="{{ $opCover }}" alt="{{ $op->name }}" class="w-full h-full object-cover">
                                            <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/10 to-transparent"></div>
                                            <div class="absolute bottom-0 left-0 right-0 p-3">
                                                <p class="text-white text-xs font-bold leading-snug">{{ $op->name }}</p>
                                            </div>
                                        </div>
                                        <div class="p-4">
                                            @if($opDiscount > 0)
                                                <p class="text-[11px] text-gray-400 line-through">{{ number_format($opPrice, 0, ',', '.') }}</p>
                                                <p class="text-sm font-extrabold leading-tight text-accent">{{ number_format($opFinal, 0, ',', '.') }}</p>
                                            @else
                                                <p class="text-sm font-extrabold leading-tight text-accent">{{ number_format($opPrice, 0, ',', '.') }}</p>
                                            @endif
                                            <p class="text-[10px] text-gray-400 truncate">{{ $vendor->name }}</p>
                                        </div>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>

                
                <div class="lg:col-span-4">
                    <div class="bg-white rounded-2xl border border-gray-100 p-5 shadow-sm lg:sticky lg:top-24">
                        <div class="pb-4 mb-4 border-b border-gray-100">
                            <div class="flex items-center justify-between gap-4">
                                <div class="min-w-0">
                                    <p class="text-sm font-bold truncate text-dark">{{ $vendor->name }}</p>
                                </div>
                                <a href="{{ route('vendor.detail', $vendor->slug) }}"
                                   class="px-4 py-2 rounded-xl text-xs font-bold border border-gray-200 bg-white hover:border-gray-300 transition flex-shrink-0 text-dark">
                                    Profil Vendor
                                </a>
                            </div>
                            @if($vendor->city)
                                <p class="mt-3 text-xs text-gray-500">Lokasi: {{ $vendor->city }}</p>
                            @endif
                        </div>

                        <div class="flex items-start justify-between gap-3">
                            <div>
                                <p class="text-[10px] uppercase tracking-widest text-gray-400 mb-1">Total</p>
                                @if($discount > 0)
                                    <p id="store-price-original"
                                       data-unit="{{ $price }}"
                                       class="text-sm line-through text-gray-400 mb-0">Rp {{ number_format($price, 0, ',', '.') }}</p>
                                    <p id="store-price-final"
                                       data-unit="{{ $final }}"
                                       class="text-2xl font-extrabold leading-tight text-accent">{{ number_format($final, 0, ',', '.') }}</p>
                                @else
                                    <p id="store-price-final"
                                       data-unit="{{ $price }}"
                                       class="text-2xl font-extrabold leading-tight text-accent">{{ number_format($price, 0, ',', '.') }}</p>
                                @endif
                                <p class="mt-1 text-[11px] text-gray-500">
                                    Harga satuan:
                                    @if($discount > 0)
                                        <span class="line-through">Rp {{ number_format($price, 0, ',', '.') }}</span>
                                        <span class="ml-1 font-semibold">→ Rp {{ number_format($final, 0, ',', '.') }}</span>
                                    @else
                                        <span class="font-semibold">Rp {{ number_format($price, 0, ',', '.') }}</span>
                                    @endif
                                    <span class="opacity-70">/ paket</span>
                                </p>
                            </div>
                            @if($discount > 0)
                                <span id="store-price-save"
                                      data-unit="{{ $discount }}"
                                      class="text-[10px] font-extrabold px-2 py-1 rounded-full bg-red-500 text-white">
                                    Hemat {{ number_format($discount, 0, ',', '.') }}
                                </span>
                            @endif
                        </div>

                        @if((int) ($package->dp_paket ?? 0) > 0)
                            <div class="mt-4 border-t border-gray-100 pt-4">
                                <p class="text-[10px] uppercase tracking-widest text-gray-400 mb-1">DP Paket</p>
                                <p class="text-sm font-bold text-dark">Rp {{ number_format((int) $package->dp_paket, 0, ',', '.') }}</p>
                            </div>
                        @endif

                        @if($package->max_guests)
                            <div class="mt-4 border-t border-gray-100 pt-4">
                                <p class="text-[10px] uppercase tracking-widest text-gray-400 mb-1">Kapasitas</p>
                                <p class="text-sm font-bold text-dark">{{ $package->max_guests }}</p>
                            </div>
                        @endif

                        <div class="mt-4 border-t border-gray-100 pt-4">
                            <p class="text-[10px] uppercase tracking-widest text-gray-400 mb-2">Jumlah</p>
                            <div class="flex items-center gap-2">
                                <button type="button" data-store-qty="-1" class="w-9 h-9 rounded-xl border border-gray-200 bg-white hover:border-gray-300 transition text-dark">-</button>
                                <input id="store-qty" type="text" value="1" inputmode="numeric" autocomplete="off"
                                       class="w-14 h-9 rounded-xl border border-gray-200 text-center text-sm font-bold focus:outline-none focus:border-gray-400 transition text-dark">
                                <button type="button" data-store-qty="1" class="w-9 h-9 rounded-xl border border-gray-200 bg-white hover:border-gray-300 transition text-dark">+</button>
                            </div>
                        </div>

                        <div class="mt-5 grid grid-cols-2 gap-2">
                            @if($waUrl)
                                <a href="{{ $waUrl }}" target="_blank"
                                   class="flex items-center justify-center gap-2 py-3 rounded-xl text-sm font-bold transition hover:opacity-90 btn-wa">
                                    Chat
                                </a>
                            @else
                                <button type="button" disabled
                                        class="flex items-center justify-center gap-2 py-3 rounded-xl text-sm font-bold bg-gray-100 text-gray-400 cursor-not-allowed">
                                    Chat
                                </button>
                            @endif
                            <a id="store-booking-link"
                               data-base-href="{{ route('booking.package', $package) }}"
                               href="{{ route('booking.package', $package) }}"
                               class="flex items-center justify-center gap-2 py-3 rounded-xl text-sm font-bold transition hover:opacity-90 bg-dark text-cream">
                                Booking Sekarang
                            </a>
                        </div>

                        <p class="text-[10px] text-gray-400 mt-3">Chat untuk info lebih lanjut & kustomisasi paket.</p>
                    </div>
                </div>
            </div>
        </x-ui.container>
    </section>

    <div id="store-image-modal" class="fixed inset-0 z-50 hidden items-center justify-center p-4">
        <button type="button" id="store-image-modal-backdrop" class="absolute inset-0 bg-black/60"></button>
        <div class="relative w-full max-w-5xl">
            <button type="button"
                    id="store-image-modal-close"
                    class="absolute -top-3 -right-3 w-10 h-10 rounded-full bg-white border border-gray-200 flex items-center justify-center text-sm font-bold hover:bg-gray-50 transition text-dark">
                ×
            </button>
            <div class="bg-white rounded-2xl overflow-hidden border border-gray-100 shadow-2xl">
                <img id="store-image-modal-img" src="" alt="Preview" class="w-full h-auto max-h-[85vh] object-contain bg-black">
            </div>
        </div>
    </div>

    <div id="video-modal" class="fixed inset-0 z-50 hidden items-center justify-center p-4">
        <div data-action="close-video-modal" class="absolute inset-0 bg-black/80 backdrop-blur-sm"></div>
        <div class="relative w-full max-w-3xl">
            <button type="button" data-action="close-video-modal"
                    class="absolute -top-10 right-0 w-9 h-9 rounded-full bg-white/20 hover:bg-white/30 flex items-center justify-center transition">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
            <div class="relative rounded-2xl overflow-hidden bg-black ar-16x9">
                <iframe id="video-iframe"
                        src=""
                        class="w-full h-full"
                        frameborder="0"
                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                        allowfullscreen></iframe>
            </div>
        </div>
    </div>

    <script>
        (function () {
            var el = document.getElementById('store-qty');
            if (!el) return;
            var priceOriginalEl = document.getElementById('store-price-original');
            var priceFinalEl = document.getElementById('store-price-final');
            var priceSaveEl = document.getElementById('store-price-save');

            function clamp(v) {
                v = parseInt(String(v || '').replace(/\\D+/g, ''), 10);
                if (!Number.isFinite(v) || v < 1) v = 1;
                if (v > 99) v = 99;
                return v;
            }
            function money(n) {
                n = parseInt(String(n || '0').replace(/\D+/g, ''), 10);
                if (!Number.isFinite(n)) n = 0;
                return n.toLocaleString('id-ID');
            }
            function updatePrice() {
                var qty = clamp(el.value);
                if (priceFinalEl) {
                    var unitFinal = parseInt(priceFinalEl.getAttribute('data-unit') || '0', 10) || 0;
                    priceFinalEl.textContent = money(unitFinal * qty);
                }
                if (priceOriginalEl) {
                    var unitOriginal = parseInt(priceOriginalEl.getAttribute('data-unit') || '0', 10) || 0;
                    priceOriginalEl.textContent = money(unitOriginal * qty);
                }
                if (priceSaveEl) {
                    var unitSave = parseInt(priceSaveEl.getAttribute('data-unit') || '0', 10) || 0;
                    priceSaveEl.textContent = 'Hemat ' + money(unitSave * qty);
                }
                var bookingLink = document.getElementById('store-booking-link');
                if (bookingLink) {
                    var base = bookingLink.getAttribute('data-base-href') || bookingLink.getAttribute('href') || '';
                    if (base) bookingLink.setAttribute('href', base + '?qty=' + qty);
                }
            }

            window.storeQty = function (delta) {
                el.value = String(clamp(clamp(el.value) + delta));
                updatePrice();
            };
            document.addEventListener('click', function (e) {
                var btn = e.target.closest('[data-store-qty]');
                if (!btn) return;
                var delta = parseInt(btn.getAttribute('data-store-qty') || '0', 10);
                if (!Number.isFinite(delta) || delta === 0) return;
                window.storeQty(delta);
            });
            el.addEventListener('input', function () {
                el.value = String(clamp(el.value));
                updatePrice();
            });
            updatePrice();
        })();
    </script>

    <script>
        (function () {
            // YouTube Video Modal (match vendor/detail script)
            function openVideoModal(url) {
                const match = url.match(/(?:youtu\.be\/|youtube\.com\/(?:watch\?v=|embed\/|shorts\/))([A-Za-z0-9_-]{11})/);
                if (!match) return;
                const videoId = match[1];
                document.getElementById('video-iframe').src =
                    'https://www.youtube.com/embed/' + videoId + '?autoplay=1&rel=0';
                const modal = document.getElementById('video-modal');
                if (modal) {
                    modal.classList.remove('hidden');
                    modal.classList.add('flex');
                }
                document.body.style.overflow = 'hidden';
            }

            function closeVideoModal() {
                const modal = document.getElementById('video-modal');
                if (modal) {
                    modal.classList.add('hidden');
                    modal.classList.remove('flex');
                }
                document.getElementById('video-iframe').src = '';
                document.body.style.overflow = '';
            }
            document.addEventListener('click', function (e) {
                const el = e.target.closest('[data-action]');
                if (!el) return;
                const action = el.getAttribute('data-action');
                if (action === 'open-video') {
                    const url = el.getAttribute('data-video-url') || '';
                    if (!url) return;
                    openVideoModal(url);
                }
                if (action === 'close-video-modal') {
                    closeVideoModal();
                }
            });
            document.addEventListener('keydown', function (e) {
                if (e.key === 'Escape') closeVideoModal();
            });
        })();
    </script>

    <script>
        (function () {
            var modal = document.getElementById('store-image-modal');
            var img = document.getElementById('store-image-modal-img');
            var closeBtn = document.getElementById('store-image-modal-close');
            var backdrop = document.getElementById('store-image-modal-backdrop');
            if (!modal || !img || !closeBtn || !backdrop) return;

            function open(src) {
                img.src = src || '';
                modal.classList.remove('hidden');
                modal.classList.add('flex');
                document.body.style.overflow = 'hidden';
            }

            function close() {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
                img.src = '';
                document.body.style.overflow = '';
            }

            window.openStoreImageModal = open;

            document.addEventListener('click', function (e) {
                var scrollBtn = e.target.closest('[data-scroll-target][data-scroll-by]');
                if (scrollBtn) {
                    var targetId = scrollBtn.getAttribute('data-scroll-target');
                    var by = parseInt(scrollBtn.getAttribute('data-scroll-by') || '0', 10);
                    if (targetId && Number.isFinite(by) && by !== 0) {
                        var track = document.getElementById(targetId);
                        if (track && typeof track.scrollBy === 'function') {
                            track.scrollBy({ left: by, behavior: 'smooth' });
                        }
                    }
                }

                var imgBtn = e.target.closest('[data-store-image-open][data-store-image-src]');
                if (imgBtn) {
                    open(imgBtn.getAttribute('data-store-image-src') || '');
                }
            });

            closeBtn.addEventListener('click', close);
            backdrop.addEventListener('click', close);
            document.addEventListener('keydown', function (e) {
                if (e.key === 'Escape' && !modal.classList.contains('hidden')) close();
            });
        })();
    </script>

    <script>
        (function () {
            var wrap = document.getElementById('store-detail-items');
            var btn = document.getElementById('store-detail-items-toggle');
            var fade = document.getElementById('store-detail-items-fade');
            if (!wrap || !btn) return;

            var expanded = false;
            btn.addEventListener('click', function () {
                expanded = !expanded;
                if (expanded) {
                    wrap.classList.remove('max-h-64', 'overflow-hidden');
                    if (fade) fade.classList.add('hidden');
                    btn.textContent = 'Tutup';
                } else {
                    wrap.classList.add('max-h-64', 'overflow-hidden');
                    if (fade) fade.classList.remove('hidden');
                    btn.textContent = btn.getAttribute('data-label') || btn.textContent;
                }
            });

            btn.setAttribute('data-label', btn.textContent);
        })();
    </script>

    @include('layout.footer')
@endsection
