@extends('layout.app')

@section('title', ($package->name ?? 'Detail Paket') . ' - Makna Wedding')

@section('body-class', 'bg-cream text-dark')

@section('extra-head')
<style>
    .prose { font-size: 12px; }
    .prose ul { list-style: disc; padding-left: 1.4rem; margin: 4px 0; }
    .prose ol { list-style: decimal; padding-left: 1.4rem; margin: 4px 0; }
    .prose li { margin: 2px 0; }
    .prose strong { font-weight: 700; }
    .prose em { font-style: italic; }
</style>
@endsection

@section('content')
    @include('layout.header')

    @php
        $price = (int) ($package->price ?? 0);
        $discount = (int) ($package->discount ?? 0);
        $final = max($price - $discount, 0);
        $wa = preg_replace('/[^0-9]/', '', (string) ($vendor->phone ?? ''));
        $waText = rawurlencode('Halo ' . $vendor->name . ', saya tertarik dengan paket "' . $package->name . '". Mohon info lengkap ya.');
        $waUrl = $wa ? "https://wa.me/{$wa}?text={$waText}" : null;

        // Resolve kategori dari array category_vendor_id
        $pkgCatIds = is_array($package->category_vendor_id) ? $package->category_vendor_id : [];
        $firstCat = !empty($pkgCatIds)
            ? \App\Models\CategoryVendor::find((int) $pkgCatIds[0])
            : null;
        $categorySlug = $firstCat?->slug ?? $vendor->category;
        $categoryName = $firstCat?->name ?? $vendor->category;
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
                    {{-- ── Header Card ───────────────────────────────────── --}}
                    <div class="bg-white rounded-2xl border border-gray-100 overflow-hidden">
                        {{-- Top accent bar --}}
                        <div class="h-1 w-full bg-accent"></div>
                        <div class="p-5">
                            <div class="flex items-start justify-between gap-4">
                                <div class="min-w-0 flex-1">
                                    {{-- Category badge --}}
                                    @if($categoryName)
                                        <span class="inline-block text-[10px] font-bold uppercase tracking-widest border border-accent/40 rounded-full px-3 py-0.5 mb-2 text-accent">
                                            {{ $categoryName }}
                                        </span>
                                    @endif
                                    <h1 class="text-lg sm:text-xl font-extrabold leading-tight text-dark">{{ $package->name }}</h1>
                                    {{-- Vendor row --}}
                                    <div class="flex items-center gap-2 mt-2">
                                        <svg class="w-3.5 h-3.5 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10l9-6 9 6v10a1 1 0 01-1 1h-5v-6H9v6H4a1 1 0 01-1-1V10z"/>
                                        </svg>
                                        <a href="{{ route('vendor.detail', $vendor->slug) }}" class="text-xs font-semibold hover:opacity-80 transition text-accent">{{ $vendor->name }}</a>
                                        @if($vendor->city)
                                            <span class="text-gray-300">·</span>
                                            <svg class="w-3.5 h-3.5 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            </svg>
                                            <span class="text-xs text-gray-500">{{ $vendor->city }}</span>
                                        @endif
                                    </div>
                                </div>
                                <a href="{{ route('store') }}" class="flex-shrink-0 text-xs font-bold px-3 py-2 rounded-xl border border-gray-200 bg-white hover:border-gray-300 transition text-dark">
                                    ← Kembali
                                </a>
                                @auth
                                    @php
                                        $authUser = auth()->user();
                                        $canEdit = $authUser && (
                                            $authUser->hasRole(['super_admin', 'admin']) ||
                                            (int) $vendor->owner_user_id === (int) $authUser->id
                                        );
                                    @endphp
                                    @if($canEdit)
                                        <a href="{{ route('vendor.packages.edit', ['vendor' => $vendor->slug, 'package' => $package->id]) }}"
                                           class="flex-shrink-0 inline-flex items-center gap-1.5 text-xs font-bold px-3 py-2 rounded-xl border border-accent/30 bg-accent/10 text-accent hover:bg-accent/20 transition">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                            </svg>
                                            Edit
                                        </a>
                                    @endif
                                @endauth
                            </div>

                            {{-- Tags / Badges --}}
                            <div class="mt-4 flex flex-wrap items-center gap-2">
                                @if($package->max_guests)
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[11px] font-bold bg-accent/10 text-accent border border-accent/20">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        </svg>
                                        {{ $package->max_guests }}
                                    </span>
                                @endif
                                @if($vendor->location)
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[11px] font-bold bg-gray-50 text-gray-600 border border-gray-100">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                        </svg>
                                        {{ $vendor->location }}
                                    </span>
                                @endif
                                @if($discount > 0)
                                    <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-[11px] font-extrabold bg-red-50 text-red-500 border border-red-100">
                                        🔥 Hemat IDR {{ number_format($discount, 0, ',', '.') }}
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- ── Gallery ─────────────────────────────────────── --}}
                    <div class="mt-5 bg-white rounded-2xl border border-gray-100 overflow-hidden">
                        <div class="flex items-center gap-3 px-5 py-3.5 border-b border-gray-100 bg-light-sage/20">
                            <div class="w-1 h-5 rounded-full bg-accent flex-shrink-0"></div>
                            <span class="text-xs font-bold uppercase tracking-widest text-gray-400">Galeri Foto</span>
                            @if(!$images->isEmpty())
                                <span class="ml-auto text-[11px] text-gray-400">{{ $images->count() }} foto</span>
                            @endif
                        </div>
                        <div class="p-5">
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
                                                class="group flex-none w-48 rounded-2xl overflow-hidden border border-gray-100 bg-gray-50 relative aspect-[4/5]">
                                            <img src="{{ $imgUrl }}" alt="Paket {{ $package->name }}" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
                                            <div class="absolute inset-0 bg-black/0 group-hover:bg-black/20 transition"></div>
                                            <div class="absolute bottom-2 right-2 px-2 py-1 rounded-full text-[10px] font-bold bg-white/90 border border-gray-200 opacity-0 group-hover:opacity-100 transition text-dark flex items-center gap-1">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v6m3-3H7"/></svg>
                                                Perbesar
                                            </div>
                                        </button>
                                    @endif
                                @endforeach

                                @if($images->isEmpty())
                                    <div class="flex-none w-48 rounded-2xl overflow-hidden border border-gray-100 bg-gray-50 flex items-center justify-center text-xs text-gray-400 aspect-[4/5]">
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
                        </div>{{-- /p-5 --}}
                    </div>

                    {{-- ── Items / Detail Paket ────────────────────────── --}}
                    <div class="mt-5 bg-white rounded-2xl border border-gray-100 overflow-hidden">
                        <div class="flex items-center gap-3 px-5 py-3.5 border-b border-gray-100 bg-light-sage/20">
                            <div class="w-1 h-5 rounded-full bg-accent flex-shrink-0"></div>
                            <span class="text-xs font-bold uppercase tracking-widest text-gray-400">Fasilitas</span>
                        </div>
                        <div class="p-5">
                        @if($package->item)
                            <div class="prose prose-sm max-w-none text-gray-700">
                                {!! $package->item !!}
                            </div>
                        @else
                            <p class="text-sm text-gray-400 italic">Detail paket belum tersedia.</p>
                        @endif
                        </div>
                    </div>

                    @if($package->type || $package->capacity || (is_array($package->facilities) && count($package->facilities)))
                        {{-- ── Venue Detail ─────────────────────────────── --}}
                        <div class="mt-5 bg-white rounded-2xl border border-gray-100 overflow-hidden">
                            <div class="flex items-center gap-3 px-5 py-3.5 border-b border-gray-100 bg-light-sage/20">
                                <div class="w-1 h-5 rounded-full bg-accent flex-shrink-0"></div>
                                <span class="text-xs font-bold uppercase tracking-widest text-gray-400">Informasi Venue</span>
                            </div>
                            <div class="p-5 grid grid-cols-2 sm:grid-cols-3 gap-3">
                                @if($package->type)
                                    <div class="flex items-center gap-3 p-4 bg-cream/60 rounded-xl border border-gray-100">
                                        <div class="flex-shrink-0 w-9 h-9 rounded-full bg-accent/10 flex items-center justify-center">
                                            <svg class="w-4 h-4 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                                        </div>
                                        <div class="min-w-0">
                                            <p class="text-[10px] uppercase tracking-widest text-gray-400">Tipe</p>
                                            <p class="text-sm font-bold text-dark truncate">{{ $package->type }}</p>
                                        </div>
                                    </div>
                                @endif
                                @if($package->capacity)
                                    <div class="flex items-center gap-3 p-4 bg-cream/60 rounded-xl border border-gray-100">
                                        <div class="flex-shrink-0 w-9 h-9 rounded-full bg-accent/10 flex items-center justify-center">
                                            <svg class="w-4 h-4 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                        </div>
                                        <div class="min-w-0">
                                            <p class="text-[10px] uppercase tracking-widest text-gray-400">Kapasitas</p>
                                            <p class="text-sm font-bold text-dark">{{ number_format((int) $package->capacity, 0, ',', '.') }} <span class="text-xs font-normal text-gray-500">orang</span></p>
                                        </div>
                                    </div>
                                @endif
                                @if(is_array($package->facilities) && count($package->facilities))
                                    <div class="col-span-2 sm:col-span-{{ ($package->type && $package->capacity) ? '1' : '3' }} p-4 bg-cream/60 rounded-xl border border-gray-100">
                                        <div class="flex items-center gap-2 mb-2.5">
                                            <div class="flex-shrink-0 w-9 h-9 rounded-full bg-accent/10 flex items-center justify-center">
                                                <svg class="w-4 h-4 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                            </div>
                                            <p class="text-[10px] uppercase tracking-widest text-gray-400">Fasilitas</p>
                                        </div>
                                        <div class="flex flex-wrap gap-1.5">
                                            @foreach($package->facilities as $fac)
                                                <span class="inline-flex items-center gap-1 text-[11px] px-2.5 py-1 bg-white border border-gray-100 rounded-full font-medium text-dark shadow-sm">
                                                    <span class="w-1 h-1 rounded-full bg-accent flex-shrink-0"></span>
                                                    {{ $fac }}
                                                </span>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif
                            </div>
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
                                        $opPrice = (int) ($op->price ?? 0);
                                        $opDiscount = (int) ($op->discount ?? 0);
                                        $opFinal = max($opPrice - $opDiscount, 0);
                                        $opCover = $vendor->cover_image_url ?: null;
                                        if (!$opCover && is_array($vendor->cover_image ?? null) && count($vendor->cover_image) > 0) {
                                            $opCover = $vendor->cover_image[0];
                                        }
                                        $opCover = $opCover ?: ('data:image/svg+xml;utf8,' . rawurlencode('<svg xmlns="http://www.w3.org/2000/svg" width="640" height="480" viewBox="0 0 640 480"><defs><linearGradient id="g" x1="0" x2="0" y1="0" y2="1"><stop offset="0" stop-color="#f3f4f6"/><stop offset="1" stop-color="#e5e7eb"/></linearGradient></defs><rect width="640" height="480" fill="url(#g)"/><text x="50%" y="50%" dominant-baseline="middle" text-anchor="middle" fill="#9ca3af" font-family="Arial, sans-serif" font-size="20">No Image</text></svg>'));
                                    @endphp
                                    <a href="{{ route('store.package.show', $op) }}"
                                       class="flex-none w-40 bg-white rounded-2xl border border-gray-100 overflow-hidden hover:border-gray-200 hover:shadow-sm transition">
                                        <div class="relative aspect-[4/5]">
                                            <img src="{{ $opCover }}" alt="{{ $op->name }}" class="w-full h-full object-cover">
                                            <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/10 to-transparent"></div>
                                            @if($opDiscount > 0)
                                                <span class="absolute top-3 left-3 text-[10px] font-bold px-2 py-1 rounded-full bg-white/90 border border-gray-200 text-dark">
                                                    Diskon
                                                </span>
                                            @endif
                                            <div class="absolute bottom-0 left-0 right-0 p-3">
                                                <p class="text-white text-xs font-bold leading-snug">{{ $op->name }}</p>
                                                <p class="text-white/80 text-[10px] mt-1">{{ $vendor->name }}</p>
                                                <p class="text-white/60 text-[10px] truncate">{{ $vendor->city }} · {{ $vendor->location }}</p>
                                            </div>
                                        </div>
                                        <div class="p-4">
                                            @if($opDiscount > 0)
                                                <p class="text-[11px] text-gray-400 line-through">IDR {{ number_format($opPrice, 0, ',', '.') }}</p>
                                                <p class="text-sm font-extrabold leading-tight text-accent">IDR {{ number_format($opFinal, 0, ',', '.') }}</p>
                                            @else
                                                <p class="text-sm font-extrabold leading-tight text-accent">IDR {{ number_format($opPrice, 0, ',', '.') }}</p>
                                            @endif
                                        </div>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>

                
                <div class="lg:col-span-4">
                    <div class="bg-white rounded-2xl border border-gray-100 overflow-hidden shadow-sm lg:sticky lg:top-24">

                        {{-- Accent top bar --}}
                        <div class="h-1 w-full bg-accent"></div>

                        {{-- Vendor Info --}}
                        <div class="px-5 pt-5 pb-4 border-b border-gray-100">
                            <div class="flex items-center justify-between gap-3">
                                <div class="min-w-0">
                                    <p class="text-[10px] uppercase tracking-widest text-gray-400 mb-0.5">Vendor</p>
                                    <p class="text-sm font-bold truncate text-dark">{{ $vendor->name }}</p>
                                    @if($vendor->city)
                                        <p class="text-xs text-gray-400 mt-0.5 flex items-center gap-1">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                            {{ $vendor->city }}
                                        </p>
                                    @endif
                                </div>
                                <a href="{{ route('vendor.detail', $vendor->slug) }}"
                                   class="flex-shrink-0 px-3 py-1.5 rounded-lg text-[11px] font-bold border border-gray-200 bg-white hover:border-gray-300 transition text-dark">
                                    Profil Vendor
                                </a>
                            </div>
                        </div>

                        {{-- Price --}}
                        <div class="px-5 pt-4 pb-4 border-b border-gray-100">
                            <p class="text-[10px] uppercase tracking-widest text-gray-400 mb-2">Harga Paket</p>
                            <div class="flex items-start justify-between gap-3">
                                <div>
                                    @if($discount > 0)
                                        <p id="store-price-original"
                                           data-unit="{{ $price }}"
                                           class="text-sm line-through text-gray-400">IDR {{ number_format($price, 0, ',', '.') }}</p>
                                        <p id="store-price-final"
                                           data-unit="{{ $final }}"
                                           class="text-2xl font-extrabold leading-tight text-accent">IDR {{ number_format($final, 0, ',', '.') }}</p>
                                    @else
                                        <p id="store-price-final"
                                           data-unit="{{ $price }}"
                                           class="text-2xl font-extrabold leading-tight text-accent">IDR {{ number_format($price, 0, ',', '.') }}</p>
                                    @endif
                                    <p class="mt-0.5 text-[11px] text-gray-400">/ paket</p>
                                </div>
                                @if($discount > 0)
                                    <span id="store-price-save"
                                          data-unit="{{ $discount }}"
                                          class="flex-shrink-0 text-[10px] font-extrabold px-2.5 py-1 rounded-full bg-red-500 text-white">
                                        Hemat IDR {{ number_format($discount, 0, ',', '.') }}
                                    </span>
                                @endif
                            </div>
                        </div>

                        {{-- DP & Capacity info --}}
                        @if((int) ($package->dp_paket ?? 0) > 0 || $package->max_guests)
                        <div class="px-5 py-3 border-b border-gray-100 flex flex-wrap gap-4">
                            @if((int) ($package->dp_paket ?? 0) > 0)
                                <div>
                                    <p class="text-[10px] uppercase tracking-widest text-gray-400">DP Paket</p>
                                    <p class="text-sm font-bold text-dark">IDR {{ number_format((int) $package->dp_paket, 0, ',', '.') }}</p>
                                </div>
                            @endif
                            @if($package->max_guests)
                                <div>
                                    <p class="text-[10px] uppercase tracking-widest text-gray-400">Kapasitas</p>
                                    <p class="text-sm font-bold text-dark">{{ $package->max_guests }}</p>
                                </div>
                            @endif
                        </div>
                        @endif

                        {{-- Quantity --}}
                        <div class="px-5 py-4 border-b border-gray-100">
                            <p class="text-[10px] uppercase tracking-widest text-gray-400 mb-2">Jumlah</p>
                            <div class="flex items-center gap-2">
                                <button type="button" data-store-qty="-1"
                                        class="w-10 h-10 rounded-xl border border-gray-200 bg-white hover:border-accent hover:text-accent transition text-dark text-lg font-bold flex items-center justify-center">−</button>
                                <input id="store-qty" type="text" value="1" inputmode="numeric" autocomplete="off"
                                       class="w-16 h-10 rounded-xl border border-gray-200 text-center text-sm font-bold focus:outline-none focus:border-accent transition text-dark">
                                <button type="button" data-store-qty="1"
                                        class="w-10 h-10 rounded-xl border border-gray-200 bg-white hover:border-accent hover:text-accent transition text-dark text-lg font-bold flex items-center justify-center">+</button>
                            </div>
                        </div>

                        {{-- CTA Buttons --}}
                        <div class="px-5 py-5 space-y-2.5">
                            <a id="store-booking-link"
                               data-base-href="{{ route('booking.package', $package) }}"
                               href="{{ route('booking.package', $package) }}"
                               class="flex items-center justify-center gap-2 w-full py-3 rounded-xl text-sm font-bold transition hover:opacity-90 bg-accent text-white shadow-sm">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                Booking Sekarang
                            </a>
                            @if($waUrl)
                                <a href="{{ $waUrl }}" target="_blank"
                                   class="flex items-center justify-center gap-2 w-full py-3 rounded-xl text-sm font-bold transition hover:opacity-90 btn-wa">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z"/>
                                        <path d="M12 0C5.373 0 0 5.373 0 12c0 2.127.558 4.126 1.535 5.857L.057 23.43l5.752-1.507A11.953 11.953 0 0012 24c6.627 0 12-5.373 12-12S18.627 0 12 0zm0 21.818a9.81 9.81 0 01-5.007-1.373l-.36-.214-3.715.974.99-3.618-.234-.372A9.818 9.818 0 012.182 12C2.182 6.57 6.57 2.182 12 2.182S21.818 6.57 21.818 12 17.43 21.818 12 21.818z"/>
                                    </svg>
                                    Chat via WhatsApp
                                </a>
                            @else
                                <button type="button" disabled
                                        class="flex items-center justify-center gap-2 w-full py-3 rounded-xl text-sm font-bold bg-gray-100 text-gray-400 cursor-not-allowed">
                                    Chat via WhatsApp
                                </button>
                            @endif
                            <p class="text-center text-[10px] text-gray-400">Chat untuk info lebih lanjut & kustomisasi paket.</p>
                        </div>
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
