@extends('layout.app')

@section('title', ($package->name ?? 'Detail Paket') . ' oleh ' . ($vendor->name ?? '') . ($vendor->city ? ' di ' . $vendor->city : '') . ' | Makna Wedding')
@php
    $packageMetaImage = $package->image_url ?: $vendor->cover_image_url ?: url(config('app.logo_url'));
    $packageMetaDescription = \Illuminate\Support\Str::limit(
        collect([
            $package->name,
            'oleh ' . $vendor->name,
            $vendor->city ? 'di ' . $vendor->city : null,
            $package->price ? 'mulai Rp' . number_format((int) max(((int) $package->price) - ((int) ($package->discount ?? 0)), 0), 0, ',', '.') : null,
        ])->filter()->implode(' - '),
        160,
        ''
    );

    $packageServiceSchema = [
        '@type' => 'Service',
        '@id' => url()->current() . '#service',
        'name' => $package->name,
        'description' => \Illuminate\Support\Str::limit(strip_tags((string) ($package->item ?? $vendor->description ?? $package->name)), 200, ''),
        'url' => url()->current(),
        'image' => [$packageMetaImage],
        'provider' => [
            '@type' => 'LocalBusiness',
            'name' => $vendor->name,
            'url' => route('vendor.detail', $vendor),
        ],
        'offers' => [
            '@type' => 'Offer',
            'priceCurrency' => 'IDR',
            'price' => max(((int) $package->price) - ((int) ($package->discount ?? 0)), 0),
            'url' => url()->current(),
            'availability' => 'https://schema.org/InStock',
        ],
    ];

    if (filled($vendor->city) || filled($vendor->province)) {
        $packageServiceSchema['areaServed'] = array_values(array_filter([$vendor->city, $vendor->province]));
    }

    $packageFinalPrice = max(((int) ($package->price ?? 0)) - ((int) ($package->discount ?? 0)), 0);

    // FAQ Schema
    $packageFaqItems = [];
    if ($package->price) {
        $packageFaqItems[] = [
            '@type' => 'Question',
            'name' => 'Berapa harga paket ' . $package->name . '?',
            'acceptedAnswer' => ['@type' => 'Answer', 'text' => 'Harga paket ' . $package->name . ' adalah Rp' . number_format($packageFinalPrice, 0, ',', '.') . ($package->discount ? ' (sudah termasuk diskon Rp' . number_format((int) $package->discount, 0, ',', '.') . ')' : '') . '.'],
        ];
    }
    if ($package->max_guests) {
        $packageFaqItems[] = [
            '@type' => 'Question',
            'name' => 'Berapa kapasitas tamu untuk paket ' . $package->name . '?',
            'acceptedAnswer' => ['@type' => 'Answer', 'text' => 'Paket ' . $package->name . ' dapat menampung hingga ' . $package->max_guests . ' tamu undangan.'],
        ];
    }
    if ($package->dp_paket) {
        $packageFaqItems[] = [
            '@type' => 'Question',
            'name' => 'Berapa uang muka (DP) untuk paket ini?',
            'acceptedAnswer' => ['@type' => 'Answer', 'text' => 'Down Payment (DP) untuk paket ' . $package->name . ' adalah Rp' . number_format((int) $package->dp_paket, 0, ',', '.') . '.'],
        ];
    }
    $packageFaqItems[] = [
        '@type' => 'Question',
        'name' => 'Apa saja yang termasuk dalam paket ' . $package->name . '?',
        'acceptedAnswer' => ['@type' => 'Answer', 'text' => filled($package->item) ? \Illuminate\Support\Str::limit(strip_tags((string) $package->item), 300, '') : 'Silakan lihat detail fasilitas di halaman ini atau hubungi vendor untuk informasi lengkap.'],
    ];
    $packageFaqItems[] = [
        '@type' => 'Question',
        'name' => 'Bagaimana cara memesan paket ' . $package->name . '?',
        'acceptedAnswer' => ['@type' => 'Answer', 'text' => 'Klik tombol "Pesan Sekarang" atau hubungi ' . $vendor->name . ' melalui WhatsApp/chat yang tersedia di halaman ini. Anda juga bisa konsultasi terlebih dahulu sebelum melakukan pemesanan.'],
    ];
    $packageFaqSchema = [
        '@context' => 'https://schema.org',
        '@type' => 'FAQPage',
        'mainEntity' => $packageFaqItems,
    ];

    $packageImages = array_values(array_filter([
        $package->image_url ?? null,
        $packageMetaImage,
    ]));

    $packageProductSchema = [
        '@type'       => 'Product',
        'name'        => $package->name,
        'description' => \Illuminate\Support\Str::limit(strip_tags((string) ($package->item ?? $vendor->description ?? $package->name)), 200, ''),
        'image'       => $packageImages,
        'brand'       => [
            '@type' => 'Brand',
            'name'  => $vendor->name,
        ],
        'offers' => [
            '@type'           => 'Offer',
            'price'           => $packageFinalPrice,
            'priceCurrency'   => 'IDR',
            'availability'    => 'https://schema.org/InStock',
            'url'             => url()->current(),
            'priceValidUntil' => now()->addYear()->toDateString(),
            'seller'          => [
                '@type' => 'Organization',
                'name'  => $vendor->name,
            ],
        ],
    ];

    if ($vendor->rating && $vendor->rating > 0 && $vendor->approvedReviews->count() > 0) {
        $packageProductSchema['aggregateRating'] = [
            '@type'       => 'AggregateRating',
            'ratingValue' => round((float) $vendor->rating, 1),
            'reviewCount' => $vendor->approvedReviews->count(),
            'bestRating'  => 5,
            'worstRating' => 1,
        ];
    }

    $packageSchema = [
        '@context' => 'https://schema.org',
        '@graph'   => [
            $packageServiceSchema,
            $packageProductSchema,
        ],
    ];
@endphp
@section('meta-description', $packageMetaDescription)
@section('meta-image', $packageMetaImage)
@section('meta-type', 'product')

@section('body-class', 'bg-cream text-dark store-detail-page')

@section('extra-head')
<script type="application/ld+json">@json($packageSchema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)</script>
<script type="application/ld+json">@json($packageFaqSchema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)</script>
<style>
    .prose { font-size: 12px; }
    .prose ul { list-style: disc; padding-left: 1.4rem; margin: 4px 0; }
    .prose ol { list-style: decimal; padding-left: 1.4rem; margin: 4px 0; }
    .prose li { margin: 2px 0; }
    .prose strong { font-weight: 700; }
    .prose em { font-style: italic; }

    .store-detail-page {
        --store-mobile-bar-offset: calc(env(safe-area-inset-bottom, 0px) + 5.5rem);
    }

    @media (max-width: 1023px) {
        .store-detail-page #lc-widget {
            right: 0.75rem;
            bottom: var(--store-mobile-bar-offset);
        }

        .store-detail-page #lc-panel {
            max-height: min(70vh, calc(100vh - var(--store-mobile-bar-offset) - 1rem));
        }

        .store-detail-page #lc-welcome {
            max-width: min(14rem, calc(100vw - 1.5rem));
            margin-bottom: 0;
        }

        .store-detail-page #store-image-modal {
            padding: 0;
            background: rgba(0, 0, 0, 0.96);
        }

        .store-detail-page #store-image-modal-shell {
            width: 100%;
            height: 100dvh;
            max-height: none;
            border-radius: 0;
            background: #000;
        }

        .store-detail-page #store-image-modal-img {
            width: 100%;
            max-height: calc(100dvh - 18.5rem);
            padding-left: 1rem;
            padding-right: 1rem;
        }

        .store-detail-page .store-image-mobile-meta {
            padding-bottom: calc(env(safe-area-inset-bottom, 0px) + 1rem);
        }
    }

    @media (min-width: 1024px) {
        .store-detail-page #lc-widget {
            right: max(1rem, calc((100vw - 1280px) / 2 + 1rem));
        }

        .store-detail-page #lc-welcome {
            display: none !important;
        }
    }
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
        $chatUrl = route('chat.public', ['package' => $package->id]);
        $shareUrl = url()->current();
        $shareTitle = $package->name . ' - ' . $vendor->name;
        $shareText = 'Lihat paket "' . $package->name . '" oleh ' . $vendor->name . ($vendor->city ? ' di ' . $vendor->city : '') . '.';

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

    <section class="pt-3 pb-44 lg:pt-3 lg:pb-8 bg-cream">
        <x-ui.container>
            <div class="pt-1 pb-4 lg:pt-1">
                <x-breadcrumb :items="$breadcrumbItems" />
            </div>
            <x-banner-ad mt="0" mb="1rem" />

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

                {{-- ── Left: Gallery Column ─────────────────────────────── --}}
                <div class="lg:col-span-4 lg:self-start">
                    @php
                        $galleryImages = [];
                        foreach ($images as $img) {
                            $imgUrl = is_array($img) ? ($img[0] ?? null) : $img;
                            if (is_string($imgUrl) && $imgUrl !== '' && !str_starts_with($imgUrl, 'http') && !str_starts_with($imgUrl, '/storage')) {
                                $imgUrl = \Illuminate\Support\Facades\Storage::url($imgUrl);
                            }
                            if ($imgUrl) $galleryImages[] = $imgUrl;
                        }
                    @endphp
                    @if(!empty($galleryImages))
                        <div class="relative -mx-4 w-[calc(100%+2rem)] lg:mx-0 lg:w-full rounded-none lg:rounded-2xl overflow-hidden bg-white lg:border lg:border-gray-100 aspect-square cursor-zoom-in" id="gallery-main-wrap">
                            <img id="gallery-main-img"
                                 src="{{ $galleryImages[0] }}"
                                 alt="Paket {{ $package->name }}"
                                 class="w-full h-full object-cover transition-opacity duration-300"
                                 data-store-image-open
                                 data-store-image-src="{{ $galleryImages[0] }}">
                            <div class="absolute inset-0 pointer-events-none bg-linear-to-t from-black/10 to-transparent"></div>
                            <span class="absolute bottom-3 right-3 px-2.5 py-1 rounded-full text-[10px] font-bold bg-white/90 border border-gray-200 text-dark flex items-center gap-1 pointer-events-none">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v6m3-3H7"/></svg>
                                Perbesar
                            </span>
                        </div>
                        <div class="mt-2 relative">
                            <div id="gallery-thumbs-scroll" class="flex gap-2 overflow-x-auto scroll-smooth scrollbar-hide pb-1">
                                @foreach($galleryImages as $ti => $tUrl)
                                    <button type="button"
                                            data-gallery-thumb="{{ $tUrl }}"
                                            data-gallery-index="{{ $ti }}"
                                            class="gallery-thumb shrink-0 w-17.5 h-17.5 rounded-xl overflow-hidden border-2 transition-all duration-200 bg-gray-50 {{ $ti === 0 ? 'border-accent' : 'border-transparent' }}">
                                        <img src="{{ $tUrl }}" alt="Paket {{ $package->name }} - Foto {{ $ti + 1 }}" loading="lazy" class="w-full h-full object-cover">
                                    </button>
                                @endforeach
                            </div>
                            @if(count($galleryImages) > 5)
                                <button type="button"
                                        data-scroll-target="gallery-thumbs-scroll" data-scroll-by="200"
                                        class="absolute right-0 top-1/2 -translate-y-1/2 w-8 h-8 rounded-full bg-white border border-gray-200 shadow-md flex items-center justify-center hover:shadow-lg transition z-10">
                                    <svg class="w-4 h-4 text-dark" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                    </svg>
                                </button>
                            @endif
                        </div>
                    @else
                        <div class="w-full rounded-2xl overflow-hidden border border-gray-100 bg-gray-50 flex items-center justify-center text-xs text-gray-400 aspect-square">
                            Tidak ada foto
                        </div>
                    @endif

                </div>

                {{-- ── Middle: Detail Column ─────────────────────────────── --}}
                <div class="lg:col-span-5">
                    {{-- ── Header Card ───────────────────────────────────── --}}
                    <div class="bg-white rounded-2xl border border-gray-100 overflow-hidden">
                        {{-- Top accent bar --}}
                        <div class="h-1 w-full"></div>
                        <div class="p-5">
                            <div class="flex items-start justify-between gap-4">
                                <div class="min-w-0 flex-1">
                                    {{-- Category badge --}}
                                    {{-- @if($categoryName)
                                        <span class="inline-block text-[10px] font-bold uppercase tracking-widest border border-accent/40 rounded-full px-3 py-0.5 mb-2 text-accent">
                                            {{ $categoryName }}
                                        </span>
                                    @endif --}}
                                    <h1 class="text-lg sm:text-xl font-extrabold leading-tight text-dark">{{ $package->name }}</h1>
                                    {{-- Vendor row --}}
                                    <div class="flex items-center gap-2 mt-2">
                                        <svg class="w-3.5 h-3.5 text-gray-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10l9-6 9 6v10a1 1 0 01-1 1h-5v-6H9v6H4a1 1 0 01-1-1V10z"/>
                                        </svg>
                                        <a href="{{ route('vendor.detail', $vendor->slug) }}" class="text-xs font-semibold hover:opacity-80 transition text-accent">{{ $vendor->name }}</a>
                                        @if($vendor->city)
                                            <span class="text-gray-300">·</span>
                                            {{-- <svg class="w-3.5 h-3.5 text-gray-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            </svg>
                                            <span class="text-xs text-gray-500">{{ $vendor->city }}</span> --}}
                                        @endif
                                    </div>
                                </div>
                                <div class="flex flex-wrap items-center justify-end gap-2">
                                    <a href="{{ route('store') }}" class="hidden lg:inline-flex shrink-0 text-xs font-bold px-3 py-2 rounded-xl border border-gray-200 bg-white hover:border-gray-300 transition text-dark">
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
                                               class="shrink-0 inline-flex items-center gap-1.5 text-xs font-bold px-3 py-2 rounded-xl border border-gray-200 bg-white hover:border-gray-300 transition text-dark">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                                </svg>
                                                Edit
                                            </a>
                                        @endif
                                    @endauth
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- ── Fasilitas & Venue Tab ────────────────────── --}}
                    @php
                        $hasVenue = $package->type || $package->capacity || (is_array($package->facilities) && count($package->facilities));
                    @endphp
                    <div class="mt-5 bg-white rounded-2xl border border-gray-100 overflow-hidden">
                        {{-- Tab Headers --}}
                        <div class="flex border-b border-gray-100" id="pkg-tabs">
                            <button type="button"
                                    data-tab="fasilitas"
                                    class="pkg-tab-btn flex items-center gap-2 px-5 py-3.5 text-xs font-bold uppercase tracking-widest border-b-2 border-accent text-accent transition-all"
                                    aria-selected="true">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                                Fasilitas
                            </button>
                            @if($hasVenue)
                                <button type="button"
                                        data-tab="venue"
                                        class="pkg-tab-btn flex items-center gap-2 px-5 py-3.5 text-xs font-bold uppercase tracking-widest border-b-2 border-transparent text-gray-400 hover:text-dark transition-all"
                                        aria-selected="false">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                                    Informasi Venue
                                </button>
                            @endif
                        </div>

                        {{-- Tab: Fasilitas --}}
                        <div id="pkg-tab-fasilitas" class="pkg-tab-panel p-5">
                            @if($package->item)
                                <div class="relative">
                                    <div data-facilities-content class="prose prose-sm max-w-none max-h-64 overflow-hidden text-gray-700 transition-all duration-300">
                                        {!! $package->item !!}
                                    </div>
                                    <div data-facilities-fade class="pointer-events-none absolute inset-x-0 bottom-0 h-16 bg-linear-to-t from-white via-white/95 to-transparent"></div>
                                </div>
                                <button type="button"
                                        data-facilities-toggle
                                        data-label-more="Lihat Selengkapnya..."
                                        data-label-less="Tampilkan Ringkas..."
                                    class="-mt-1 relative z-10 hidden w-full justify-center text-xs font-semibold text-accent transition hover:text-accent/80 hover:underline">
                                    <span>Lihat Selengkapnya...</span>
                                </button>
                            @else
                                <p class="text-sm text-gray-400 italic">Detail paket belum tersedia.</p>
                            @endif
                        </div>

                        {{-- Tab: Informasi Venue --}}
                        @if($hasVenue)
                            <div id="pkg-tab-venue" class="pkg-tab-panel p-5 hidden">
                                <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                                    @if($package->type)
                                        <div class="flex items-center gap-3 p-4 bg-cream/60 rounded-xl border border-gray-100">
                                            <div class="shrink-0 w-9 h-9 rounded-full bg-accent/10 flex items-center justify-center">
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
                                            <div class="shrink-0 w-9 h-9 rounded-full bg-accent/10 flex items-center justify-center">
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
                                                <div class="shrink-0 w-9 h-9 rounded-full bg-accent/10 flex items-center justify-center">
                                                    <svg class="w-4 h-4 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                                </div>
                                                <p class="text-[10px] uppercase tracking-widest text-gray-400">Fasilitas</p>
                                            </div>
                                            <div class="flex flex-wrap gap-1.5">
                                                @foreach($package->facilities as $fac)
                                                    <span class="inline-flex items-center gap-1 text-[11px] px-2.5 py-1 bg-white border border-gray-100 rounded-full font-medium text-dark shadow-sm">
                                                        <span class="w-1 h-1 rounded-full bg-accent shrink-0"></span>
                                                        {{ $fac }}
                                                    </span>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endif
                    </div>

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
                                    <div class="relative shrink-0 rounded-2xl overflow-hidden cursor-pointer group snap-start w-45 h-70"
                                         @if($hasVideo) data-action="open-video" data-video-url="{{ $v->video_url }}" @endif>
                                        <img src="{{ $cover }}"
                                             alt="{{ $package->name }} - Video Review {{ $idx + 1 }}"
                                             loading="lazy"
                                             class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
                                        <div class="absolute inset-0 bg-linear-to-t from-black/80 via-black/20 to-transparent"></div>
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

                </div>

                <div class="lg:col-span-3">
                    <div class="bg-white rounded-2xl border border-gray-100 overflow-hidden shadow-sm lg:sticky lg:top-24">

                        {{-- ── Spesial Diskon Banner ───────────────────── --}}
                        @if($discount > 0)
                        <div class="relative overflow-hidden bg-accent px-5 py-3.5">
                            <div class="flex items-center justify-between gap-3">
                                <div>
                                    <p class="text-white font-extrabold text-sm leading-tight">Spesial Diskon</p>
                                    <div class="mt-1.5 h-1 w-24 bg-white/30 rounded-full overflow-hidden">
                                        <div class="h-full w-3/5 bg-white rounded-full"></div>
                                    </div>
                                </div>
                                <div class="text-right shrink-0">
                                    @if($package->discount_expires_at && $package->discount_expires_at->isFuture())
                                    <p class="text-white/80 text-[10px] font-semibold mb-1">Berakhir dalam</p>
                                    <div class="bg-white rounded-full px-3 py-1">
                                        <span id="sidebar-countdown"
                                              data-expires="{{ $package->discount_expires_at->timestamp * 1000 }}"
                                              class="text-accent font-extrabold text-sm tracking-wide">-- Hari</span>
                                    </div>
                                    @else
                                    <p class="text-white/80 text-[10px] font-semibold">Diskon Aktif</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @endif

                        {{-- ── Vendor info mini ────────────────────────── --}}
                        <div class="px-5 pt-4 pb-3 border-b border-gray-100 flex items-center justify-between gap-3">
                            <div class="min-w-0">
                                <p class="text-xs font-bold truncate text-dark">{{ $vendor->name }}</p>
                                @if($vendor->city)
                                    <p class="text-[11px] text-gray-400 flex items-center gap-1 mt-0.5">
                                        <svg class="w-3 h-3 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                        {{ $vendor->city }}
                                    </p>
                                @endif
                            </div>
                            <a href="{{ route('vendor.detail', $vendor->slug) }}"
                               class="shrink-0 px-3 py-1.5 rounded-lg text-[11px] font-bold border border-gray-200 bg-white hover:border-gray-300 transition text-dark">
                                Profil Vendor
                            </a>
                        </div>

                        {{-- ── Atur Jumlah & Catatan ───────────────────── --}}
                        <div class="px-5 pt-4 pb-3">
                            <p class="text-sm font-bold text-dark mb-3">Atur jumlah dan catatan</p>

                            {{-- Product thumb + name --}}
                            @php
                                $sideThumb = null;
                                if (!empty($galleryImages[0])) {
                                    $sideThumb = $galleryImages[0];
                                } elseif ($vendor->cover_image_url) {
                                    $sideThumb = $vendor->cover_image_url;
                                }
                            @endphp
                            <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-xl border border-gray-100 mb-4">
                                @if($sideThumb)
                                    <img src="{{ $sideThumb }}" alt="{{ $package->name }}"
                                         class="w-14 h-14 rounded-lg object-cover shrink-0 border border-gray-200">
                                @else
                                    <div class="w-14 h-14 rounded-lg bg-gray-200 shrink-0 flex items-center justify-center">
                                        <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                    </div>
                                @endif
                                <div class="min-w-0">
                                    <p class="text-xs font-semibold text-dark truncate">{{ $package->name }}</p>
                                    @if($categoryName)
                                        <p class="text-[11px] text-gray-400 mt-0.5">{{ $categoryName }}</p>
                                    @endif
                                    @if($package->max_guests)
                                        <p class="text-[11px] text-gray-400">Maks. {{ $package->max_guests }}</p>
                                    @endif
                                </div>
                            </div>

                            {{-- Quantity + stock --}}
                            <div class="flex items-center gap-3 mb-4">
                                <div class="flex items-center rounded-xl border border-gray-200 overflow-hidden">
                                    <button type="button" data-store-qty="-1"
                                            class="w-10 h-10 flex items-center justify-center text-gray-500 hover:text-accent hover:bg-gray-50 transition text-sm">−</button>
                                    <input id="store-qty" type="text" value="1" inputmode="numeric" autocomplete="off"
                                           class="w-12 h-10 text-center text-sm font-bold border-x border-gray-200 focus:outline-none focus:border-accent transition text-dark bg-white">
                                    <button type="button" data-store-qty="1"
                                            class="w-10 h-10 flex items-center justify-center text-gray-500 hover:text-accent hover:bg-gray-50 transition text-sm">+</button>
                                </div>
                                @if((int) ($package->dp_paket ?? 0) > 0 || $package->max_guests)
                                    <p class="text-xs text-gray-500">
                                        @if($package->max_guests)
                                            Maks. <span class="font-bold text-dark">{{ $package->max_guests }}</span>
                                        @endif
                                        @if((int) ($package->dp_paket ?? 0) > 0)
                                            · DP <span class="font-bold text-dark">IDR {{ number_format((int) $package->dp_paket, 0, ',', '.') }}</span>
                                        @endif
                                    </p>
                                @endif
                            </div>

                            {{-- Subtotal --}}
                            <div class="flex items-end justify-between border-t border-gray-100 pt-3 mb-4">
                                <p class="text-sm text-gray-500">Subtotal</p>
                                <div class="text-right">
                                    @if($discount > 0)
                                        <p id="store-price-original"
                                           data-store-price-original
                                           data-unit="{{ $price }}"
                                           class="text-xs line-through text-gray-400">IDR {{ number_format($price, 0, ',', '.') }}</p>
                                    @endif
                                    <p id="store-price-final"
                                       data-store-price-final
                                       data-unit="{{ $final }}"
                                       class="text-xl font-extrabold leading-tight text-dark">IDR {{ number_format($final, 0, ',', '.') }}</p>
                                </div>
                            </div>
                            @if($discount > 0)
                                <span id="store-price-save"
                                      data-store-price-save
                                      data-unit="{{ $discount }}"
                                      class="hidden">Hemat IDR {{ number_format($discount, 0, ',', '.') }}</span>
                            @endif
                        </div>

                        {{-- ── CTA Buttons ─────────────────────────────── --}}
                        <div class="hidden lg:block px-5 pb-4 space-y-2.5">
                            <a id="store-booking-link"
                               data-store-booking-link
                               data-base-href="{{ route('booking.package', $package) }}"
                               href="{{ route('booking.package', $package) }}"
                               class="flex items-center justify-center gap-2 w-full h-11 rounded-xl text-xs font-bold transition hover:opacity-90 bg-accent text-white shadow-sm">
                                + Booking Sekarang
                            </a>
                        </div>

                        {{-- ── Bottom actions: Chat | Wishlist | Share ─── --}}
                        <div class="border-t border-gray-100 flex divide-x divide-gray-100">
                            <a href="{{ $chatUrl }}"
                                   class="flex-1 flex items-center justify-center gap-1.5 py-3 text-xs font-semibold text-gray-500 hover:text-accent hover:bg-gray-50 transition">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                                    Chat
                                </a>
                            <button type="button"
                                    id="wishlist-btn"
                                    data-package-id="{{ $package->id }}"
                                    data-toggle-url="{{ route('wishlist.toggle', $package) }}"
                                    data-login-url="{{ route('login') }}"
                                    data-is-auth="{{ Auth::check() ? '1' : '0' }}"
                                    class="flex-1 flex items-center justify-center gap-1.5 py-3 text-xs font-semibold text-gray-500 hover:text-accent hover:bg-gray-50 transition">
                                <svg id="wishlist-icon" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                                <span id="wishlist-label">Wishlist</span>
                            </button>
                            <button type="button"
                                    id="store-share-button"
                                    data-share-url="{{ $shareUrl }}"
                                    data-share-title="{{ $shareTitle }}"
                                    data-share-text="{{ $shareText }}"
                                    class="flex-1 flex items-center justify-center gap-1.5 py-3 text-xs font-semibold text-gray-500 hover:text-accent hover:bg-gray-50 transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"/></svg>
                                Share
                            </button>
                        </div>
                    </div>
                </div>

                {{-- ── Produk Lainnya oleh Vendor Ini ──────────────── --}}
                @if($otherPackages->isNotEmpty())
                    <div class="lg:col-span-9 lg:mt-2">
                        <div class="flex items-end justify-between gap-4 mb-3">
                            <div>
                                <p class="text-sm font-bold text-dark">Produk Lainnya</p>
                                <p class="text-xs text-gray-400">oleh {{ $vendor->name }} · {{ $otherPackages->count() }} paket</p>
                            </div>
                        </div>
                        <div class="grid grid-cols-2 md:grid-cols-3 xl:grid-cols-4 gap-3 md:gap-4">
                            @foreach($otherPackages->take(8) as $op)
                                @php
                                    $opPrice    = (int) ($op->price ?? 0);
                                    $opDiscount = (int) ($op->discount ?? 0);
                                    $opCover    = $op->image_url ?: null;
                                    if (!$opCover && is_array($op->image_path ?? null) && count($op->image_path) > 0) {
                                        $opCover = $op->image_path[0];
                                        if ($opCover && !str_starts_with($opCover, 'http')) {
                                            $opCover = \Illuminate\Support\Facades\Storage::url($opCover);
                                        }
                                    }
                                    if (!$opCover) { $opCover = $vendor->cover_image_url ?: null; }
                                    if (!$opCover && is_array($vendor->cover_image ?? null)) { $opCover = $vendor->cover_image[0] ?? null; }
                                    $opPrimaryBenefit   = $opDiscount > 0 ? 'Harga Diskon' : 'Paket Pilihan';
                                    $opSecondaryBenefit = !empty($op->items[0]) ? \Illuminate\Support\Str::limit($op->items[0], 16) : 'Gratis Konsultasi';
                                @endphp
                                <x-package-card
                                    :href="route('store.package.show', $op)"
                                    :name="$op->name"
                                    :image="$opCover"
                                    :price="$opPrice"
                                    :discount="$opDiscount"
                                    :vendor-name="$vendor->name"
                                    :location="$vendor->city ?? 'Indonesia'"
                                    :rating="$vendor->rating ?? null"
                                    :benefit-primary="$opPrimaryBenefit"
                                    :benefit-secondary="$opSecondaryBenefit"
                                    width-class="w-full"
                                    :class="$loop->index >= 6 ? 'hidden lg:block' : ''"
                                />
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </x-ui.container>
    </section>

    <div class="lg:hidden fixed inset-x-0 bottom-0 z-40 border-t border-gray-200 bg-white shadow-2xl">
        <div class="px-4 py-3" style="padding-bottom: calc(env(safe-area-inset-bottom, 0px) + 0.75rem);">
            <div class="flex items-center gap-2.5">
                <a href="{{ $chatUrl }}"
                   class="inline-flex h-11 w-11 shrink-0 items-center justify-center rounded-xl border border-gray-200 bg-white text-gray-600 hover:bg-gray-50 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                    </svg>
                </a>
                <a data-store-booking-link
                   data-base-href="{{ route('booking.package', $package) }}"
                   href="{{ route('booking.package', $package) }}"
                   class="inline-flex h-11 min-w-0 flex-1 items-center justify-center rounded-2xl bg-accent px-3.5 text-sm font-bold text-white shadow-sm whitespace-nowrap">
                    Booking
                </a>
            </div>
        </div>
    </div>

    <div id="store-image-modal" class="fixed inset-0 z-50 hidden items-center justify-center p-4 bg-black/50">
        <button type="button" id="store-image-modal-backdrop" class="absolute inset-0" aria-label="Tutup"></button>
        <div id="store-image-modal-shell"
             class="relative z-1 flex h-full w-full max-w-4xl flex-col overflow-hidden bg-black lg:h-auto lg:max-h-[90vh] lg:rounded-2xl lg:bg-white lg:shadow-2xl">

            {{-- Full-width header: title + close --}}
            <div class="flex items-center justify-between gap-4 px-4 py-4 text-white shrink-0 lg:px-5 lg:py-3.5 lg:border-b lg:border-gray-100 lg:text-dark">
                <button type="button" id="store-image-modal-mobile-back"
                        class="inline-flex h-10 w-10 items-center justify-center rounded-full border border-white/10 bg-white/5 text-white transition hover:bg-white/10 lg:hidden"
                        aria-label="Kembali">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                </button>
                <p class="min-w-0 flex-1 text-center text-sm font-bold text-white truncate lg:text-left lg:text-dark">{{ $package->name }}</p>
                <button type="button" id="store-image-modal-close"
                        class="shrink-0 inline-flex h-10 w-10 items-center justify-center rounded-full border border-white/10 bg-white/5 text-white transition hover:bg-white/10 lg:h-9 lg:w-9 lg:border-gray-200 lg:bg-white lg:text-dark lg:hover:bg-gray-50">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            {{-- Content: image (left) + thumbnails (right) --}}
            <div class="flex flex-1 min-h-0 flex-col overflow-hidden lg:flex-row">

                {{-- Left: main image + arrows --}}
                <div class="relative flex min-h-0 flex-1 items-center justify-center bg-black py-4 lg:bg-gray-50 lg:py-6" style="min-height:300px;">
                    <button type="button" id="store-image-modal-prev"
                            class="absolute left-3 top-1/2 z-10 flex h-10 w-10 -translate-y-1/2 items-center justify-center rounded-full border border-white/10 bg-black/50 text-white transition hover:bg-black/70 lg:border-gray-200 lg:bg-white lg:text-dark lg:shadow-md lg:hover:shadow-lg">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                        </svg>
                    </button>
                    <img id="store-image-modal-img" src="" alt="Preview"
                         class="max-w-full object-contain px-4 lg:px-16" style="max-height:56vh;">
                    <button type="button" id="store-image-modal-next"
                            class="absolute right-3 top-1/2 z-10 flex h-10 w-10 -translate-y-1/2 items-center justify-center rounded-full border border-white/10 bg-black/50 text-white transition hover:bg-black/70 lg:border-gray-200 lg:bg-white lg:text-dark lg:shadow-md lg:hover:shadow-lg">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </button>
                    <div id="store-image-modal-counter"
                         class="absolute bottom-4 left-4 rounded-2xl bg-white/10 px-3 py-2 text-sm font-semibold text-white backdrop-blur-sm lg:hidden">
                        1/1
                    </div>
                </div>

                {{-- Right: thumbnail panel --}}
                <div class="store-image-mobile-meta w-full shrink-0 border-t border-white/10 bg-neutral-950/95 flex flex-col lg:w-64 lg:border-l lg:border-t-0 lg:border-gray-100 lg:bg-white">
                    <div class="hidden shrink-0 border-b border-gray-100 px-4 py-3 lg:block">
                        <p class="text-sm font-bold text-dark">Gambar Paket</p>
                    </div>
                    <div class="flex-1 overflow-x-auto overflow-y-hidden p-3 lg:overflow-y-auto lg:overflow-x-hidden">
                        <div id="store-image-modal-thumbs" class="flex gap-3 lg:grid lg:grid-cols-3 lg:gap-2"></div>
                    </div>
                    <div class="px-4 pb-4 lg:hidden">
                        <p class="line-clamp-2 text-sm font-semibold leading-snug text-white">{{ $package->name }}</p>
                        @if($discount > 0)
                            <div class="mt-2 flex items-end gap-2">
                                <p class="text-lg font-extrabold leading-none text-[#ff4d6d]">IDR {{ number_format($final, 0, ',', '.') }}</p>
                                <p class="text-sm text-white/35 line-through">IDR {{ number_format($price, 0, ',', '.') }}</p>
                            </div>
                        @else
                            <p class="mt-2 text-lg font-extrabold leading-none text-[#ff4d6d]">IDR {{ number_format($price, 0, ',', '.') }}</p>
                        @endif
                    </div>
                </div>
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
            var shareButton = document.getElementById('store-share-button');
            if (!shareButton) return;

            function copyText(text) {
                if (navigator.clipboard && typeof navigator.clipboard.writeText === 'function' && window.isSecureContext) {
                    return navigator.clipboard.writeText(text);
                }

                return new Promise(function (resolve, reject) {
                    var textarea = document.createElement('textarea');
                    textarea.value = text;
                    textarea.setAttribute('readonly', 'readonly');
                    textarea.className = 'fixed -left-[9999px] top-0 opacity-0';
                    document.body.appendChild(textarea);
                    textarea.select();
                    textarea.setSelectionRange(0, textarea.value.length);

                    try {
                        if (document.execCommand('copy')) {
                            resolve();
                        } else {
                            reject(new Error('Copy command failed.'));
                        }
                    } catch (error) {
                        reject(error);
                    } finally {
                        document.body.removeChild(textarea);
                    }
                });
            }

            shareButton.addEventListener('click', function () {
                var shareUrl = shareButton.getAttribute('data-share-url') || window.location.href;
                var shareTitle = shareButton.getAttribute('data-share-title') || document.title;
                var shareText = shareButton.getAttribute('data-share-text') || shareTitle;

                if (navigator.share) {
                    navigator.share({
                        title: shareTitle,
                        text: shareText,
                        url: shareUrl,
                    }).catch(function (error) {
                        if (error && error.name === 'AbortError') {
                            return;
                        }

                        copyText(shareUrl).catch(function () {
                            window.prompt('Salin link paket ini:', shareUrl);
                        });
                    });

                    return;
                }

                copyText(shareUrl).catch(function () {
                    window.prompt('Salin link paket ini:', shareUrl);
                });
            });
        })();
    </script>

    <script>
        (function () {
            var el = document.getElementById('store-qty');
            if (!el) return;
            var priceOriginalEls = Array.prototype.slice.call(document.querySelectorAll('[data-store-price-original]'));
            var priceFinalEls = Array.prototype.slice.call(document.querySelectorAll('[data-store-price-final]'));
            var priceSaveEls = Array.prototype.slice.call(document.querySelectorAll('[data-store-price-save]'));
            var bookingLinks = Array.prototype.slice.call(document.querySelectorAll('[data-store-booking-link]'));

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
                priceFinalEls.forEach(function (priceFinalEl) {
                    var unitFinal = parseInt(priceFinalEl.getAttribute('data-unit') || '0', 10) || 0;
                    priceFinalEl.textContent = 'IDR ' + money(unitFinal * qty);
                });
                priceOriginalEls.forEach(function (priceOriginalEl) {
                    var unitOriginal = parseInt(priceOriginalEl.getAttribute('data-unit') || '0', 10) || 0;
                    priceOriginalEl.textContent = 'IDR ' + money(unitOriginal * qty);
                });
                priceSaveEls.forEach(function (priceSaveEl) {
                    var unitSave = parseInt(priceSaveEl.getAttribute('data-unit') || '0', 10) || 0;
                    priceSaveEl.textContent = 'Hemat IDR ' + money(unitSave * qty);
                });
                bookingLinks.forEach(function (bookingLink) {
                    var base = bookingLink.getAttribute('data-base-href') || bookingLink.getAttribute('href') || '';
                    if (base) bookingLink.setAttribute('href', base + '?qty=' + qty);
                });
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
            var el = document.getElementById('sidebar-countdown');
            if (!el) return;
            var end = parseInt(el.dataset.expires, 10);
            if (!end || isNaN(end)) return;
            function tick() {
                var diff = Math.max(0, end - Date.now());
                var days = Math.ceil(diff / (1000 * 60 * 60 * 24));
                el.textContent = days + ' Hari';
            }
            tick();
        })();
    </script>

    <script>
        (function () {
            var facilitiesContent = document.querySelector('[data-facilities-content]');
            var facilitiesToggle = document.querySelector('[data-facilities-toggle]');
            var facilitiesFade = document.querySelector('[data-facilities-fade]');
            if (!facilitiesContent || !facilitiesToggle) return;

            var expanded = false;

            function syncFacilitiesState() {
                if (expanded) {
                    facilitiesContent.classList.remove('max-h-64', 'overflow-hidden');
                    if (facilitiesFade) facilitiesFade.classList.add('hidden');
                    facilitiesToggle.classList.remove('hidden');
                    facilitiesToggle.classList.add('flex');
                    facilitiesToggle.querySelector('span').textContent = facilitiesToggle.getAttribute('data-label-less') || 'Tampilkan Ringkas...';
                    return;
                }

                facilitiesContent.classList.add('max-h-64', 'overflow-hidden');
                var hasOverflow = facilitiesContent.scrollHeight > facilitiesContent.clientHeight + 8;
                facilitiesToggle.classList.toggle('hidden', !hasOverflow);
                facilitiesToggle.classList.toggle('flex', hasOverflow);
                if (facilitiesFade) {
                    facilitiesFade.classList.toggle('hidden', !hasOverflow);
                }
                facilitiesToggle.querySelector('span').textContent = facilitiesToggle.getAttribute('data-label-more') || 'Lihat Selengkapnya';
            }

            facilitiesToggle.addEventListener('click', function () {
                expanded = !expanded;
                syncFacilitiesState();
            });

            window.addEventListener('resize', syncFacilitiesState);
            syncFacilitiesState();
        })();
    </script>

    <script>
        (function () {
            var tabs = document.querySelectorAll('.pkg-tab-btn');
            if (!tabs.length) return;
            tabs.forEach(function (btn) {
                btn.addEventListener('click', function () {
                    var target = btn.getAttribute('data-tab');
                    tabs.forEach(function (b) {
                        var active = b.getAttribute('data-tab') === target;
                        b.setAttribute('aria-selected', active ? 'true' : 'false');
                        b.classList.toggle('border-accent', active);
                        b.classList.toggle('text-accent', active);
                        b.classList.toggle('border-transparent', !active);
                        b.classList.toggle('text-gray-400', !active);
                    });
                    document.querySelectorAll('.pkg-tab-panel').forEach(function (panel) {
                        panel.classList.toggle('hidden', panel.id !== 'pkg-tab-' + target);
                    });
                });
            });
        })();
    </script>

    <script>
        (function () {
            var mainImg = document.getElementById('gallery-main-img');
            if (!mainImg) return;
            document.addEventListener('click', function (e) {
                var thumb = e.target.closest('[data-gallery-thumb]');
                if (!thumb) return;
                var src = thumb.getAttribute('data-gallery-thumb');
                mainImg.src = src;
                mainImg.setAttribute('data-store-image-src', src);
                document.querySelectorAll('.gallery-thumb').forEach(function (t) {
                    t.classList.remove('border-accent');
                    t.classList.add('border-transparent');
                });
                thumb.classList.remove('border-transparent');
                thumb.classList.add('border-accent');
            });
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
            var modal       = document.getElementById('store-image-modal');
            var mainImg     = document.getElementById('store-image-modal-img');
            var closeBtn    = document.getElementById('store-image-modal-close');
            var mobileBack  = document.getElementById('store-image-modal-mobile-back');
            var backdrop    = document.getElementById('store-image-modal-backdrop');
            var prevBtn     = document.getElementById('store-image-modal-prev');
            var nextBtn     = document.getElementById('store-image-modal-next');
            var thumbsWrap  = document.getElementById('store-image-modal-thumbs');
            var counterEl   = document.getElementById('store-image-modal-counter');
            if (!modal || !mainImg || !closeBtn) return;

            var images = [];
            var currentIndex = 0;

            function buildImages() {
                var found = [];
                document.querySelectorAll('[data-gallery-thumb]').forEach(function (t) {
                    var s = t.getAttribute('data-gallery-thumb');
                    if (s && found.indexOf(s) < 0) found.push(s);
                });
                if (!found.length) {
                    document.querySelectorAll('[data-store-image-src]').forEach(function (b) {
                        var s = b.getAttribute('data-store-image-src');
                        if (s && found.indexOf(s) < 0) found.push(s);
                    });
                }
                images = found;
            }

            function renderThumbs() {
                if (!thumbsWrap) return;
                thumbsWrap.innerHTML = '';
                images.forEach(function (src, idx) {
                    var btn = document.createElement('button');
                    btn.type = 'button';
                    btn.setAttribute('data-modal-thumb-idx', String(idx));
                    btn.className = 'modal-thumb w-20 shrink-0 rounded-xl overflow-hidden border-2 transition-all duration-150 lg:w-full lg:rounded-lg ' + (idx === currentIndex ? 'border-accent' : 'border-transparent');
                    btn.style.aspectRatio = '1';
                    var img = document.createElement('img');
                    img.src = src;
                    img.alt = 'Foto ' + (idx + 1);
                    img.className = 'w-full h-full object-cover';
                    btn.appendChild(img);
                    thumbsWrap.appendChild(btn);
                });
            }

            function showAt(idx) {
                if (!images.length) return;
                currentIndex = ((idx % images.length) + images.length) % images.length;
                mainImg.src = images[currentIndex];
                if (counterEl) counterEl.textContent = (currentIndex + 1) + '/' + images.length;
                if (thumbsWrap) {
                    thumbsWrap.querySelectorAll('[data-modal-thumb-idx]').forEach(function (b) {
                        var i = parseInt(b.getAttribute('data-modal-thumb-idx'), 10);
                        b.classList.toggle('border-accent', i === currentIndex);
                        b.classList.toggle('border-transparent', i !== currentIndex);
                    });
                    var active = thumbsWrap.querySelector('[data-modal-thumb-idx="' + currentIndex + '"]');
                    if (active) active.scrollIntoView({ block: 'nearest', inline: 'center' });
                }
            }

            function open(src) {
                buildImages();
                var idx = images.indexOf(src);
                renderThumbs();
                showAt(idx < 0 ? 0 : idx);
                modal.classList.remove('hidden');
                modal.classList.add('flex');
                document.body.style.overflow = 'hidden';
            }

            function close() {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
                mainImg.src = '';
                document.body.style.overflow = '';
            }

            window.openStoreImageModal = open;

            if (prevBtn) prevBtn.addEventListener('click', function () { showAt(currentIndex - 1); });
            if (nextBtn) nextBtn.addEventListener('click', function () { showAt(currentIndex + 1); });

            document.addEventListener('click', function (e) {
                var scrollBtn = e.target.closest('[data-scroll-target][data-scroll-by]');
                if (scrollBtn) {
                    var targetId = scrollBtn.getAttribute('data-scroll-target');
                    var by = parseInt(scrollBtn.getAttribute('data-scroll-by') || '0', 10);
                    if (targetId && Number.isFinite(by) && by !== 0) {
                        var track = document.getElementById(targetId);
                        if (track) track.scrollBy({ left: by, behavior: 'smooth' });
                    }
                    return;
                }
                var imgBtn = e.target.closest('[data-store-image-open][data-store-image-src]');
                if (imgBtn) { open(imgBtn.getAttribute('data-store-image-src') || ''); return; }

                var thumbBtn = e.target.closest('[data-modal-thumb-idx]');
                if (thumbBtn && !modal.classList.contains('hidden')) {
                    showAt(parseInt(thumbBtn.getAttribute('data-modal-thumb-idx'), 10));
                    return;
                }
            });

            closeBtn.addEventListener('click', close);
            if (mobileBack) mobileBack.addEventListener('click', close);
            if (backdrop) backdrop.addEventListener('click', close);
            document.addEventListener('keydown', function (e) {
                if (modal.classList.contains('hidden')) return;
                if (e.key === 'Escape') close();
                if (e.key === 'ArrowLeft') showAt(currentIndex - 1);
                if (e.key === 'ArrowRight') showAt(currentIndex + 1);
            });
        })();
    </script>

    <script>
    (function () {
        var btn   = document.getElementById('wishlist-btn');
        var icon  = document.getElementById('wishlist-icon');
        var label = document.getElementById('wishlist-label');
        if (!btn) return;

        var csrfToken  = document.querySelector('meta[name="csrf-token"]')?.content ?? '';
        var toggleUrl  = btn.dataset.toggleUrl;
        var loginUrl   = btn.dataset.loginUrl;
        var isAuth     = btn.dataset.isAuth === '1';
        var wishlisted = false;

        function setWishlisted(val) {
            wishlisted = val;
            if (val) {
                icon.setAttribute('fill', 'currentColor');
                btn.classList.add('text-accent');
                btn.classList.remove('text-gray-500');
                label.textContent = 'Disimpan';
            } else {
                icon.setAttribute('fill', 'none');
                btn.classList.remove('text-accent');
                btn.classList.add('text-gray-500');
                label.textContent = 'Wishlist';
            }
        }

        @auth
        fetch('{{ route("wishlist.check", $package) }}', { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
            .then(r => r.json()).then(d => setWishlisted(d.wishlisted)).catch(() => {});
        @endauth

        btn.addEventListener('click', function () {
            if (!isAuth) { window.location.href = loginUrl; return; }
            fetch(toggleUrl, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                },
            }).then(r => r.json()).then(d => setWishlisted(d.wishlisted)).catch(() => {});
        });
    })();
    </script>

    @include('layout.footer')
@endsection
