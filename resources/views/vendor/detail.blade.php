@extends('layout.app')

@php
    $vendorCategoryLabel = $vendor->categoryVendor?->name ?? ucfirst((string) $vendor->category);
    $vendorPageTitle = $vendor->name
        . ($vendorCategoryLabel ? ' - ' . $vendorCategoryLabel : '')
        . ($vendor->city ? ' di ' . $vendor->city : '')
        . ' | Makna Wedding';

    $vendorInstagramUrl = blank($vendor->instagram)
        ? null
        : (str_starts_with($vendor->instagram, 'http')
            ? $vendor->instagram
            : 'https://www.instagram.com/' . ltrim($vendor->instagram, '@/'));
    $vendorLogoUrl = blank($vendor->logo_vendor)
        ? null
        : (str_starts_with($vendor->logo_vendor, 'http')
            ? $vendor->logo_vendor
                : \Illuminate\Support\Facades\Storage::url($vendor->logo_vendor));
    $vendorMetaImage = $vendor->cover_image_url ?: $vendorLogoUrl ?: url(config('app.logo_url'));
            $vendorMetaDescription = \Illuminate\Support\Str::limit(
        collect([
            $vendor->name,
            $vendor->categoryVendor?->name ?? $vendor->category,
            $vendor->city ? 'di ' . $vendor->city : null,
            filled($vendor->description) ? strip_tags((string) $vendor->description) : null,
        ])->filter()->implode(' - '),
        160,
        ''
    );
    $vendorReviewCount = $vendor->approvedReviews->count();
    $vendorRatingValue = $vendorReviewCount > 0 ? round((float) $vendor->approvedReviews->avg('rating'), 1) : null;
    $vendorPriceStart = $vendor->cheapestPackage?->final_price ?? $vendor->computePriceStartFromPackages();

    $vendorBusinessSchema = [
        '@type' => 'LocalBusiness',
        '@id' => route('vendor.detail', $vendor) . '#business',
        'name' => $vendor->name,
        'url' => route('vendor.detail', $vendor),
        'description' => \Illuminate\Support\Str::limit(strip_tags((string) $vendor->description), 200, ''),
        'image' => array_values(array_filter([$vendorMetaImage, $vendorLogoUrl])),
    ];

    if (filled($vendor->phone)) {
        $vendorBusinessSchema['telephone'] = $vendor->phone;
    }

    if (filled($vendor->email)) {
        $vendorBusinessSchema['email'] = $vendor->email;
    }

    if ($vendorInstagramUrl) {
        $vendorBusinessSchema['sameAs'] = [$vendorInstagramUrl];
    }

    if ($vendorPriceStart) {
        $vendorBusinessSchema['priceRange'] = 'Rp' . number_format((int) $vendorPriceStart, 0, ',', '.');
    }

    $vendorAddress = ['@type' => 'PostalAddress'];
    if (filled($vendor->location)) {
        $vendorAddress['streetAddress'] = $vendor->location;
    }
    if (filled($vendor->city)) {
        $vendorAddress['addressLocality'] = $vendor->city;
    }
    if (filled($vendor->province)) {
        $vendorAddress['addressRegion'] = $vendor->province;
    }
    $vendorAddress['addressCountry'] = 'ID';
    if (count($vendorAddress) > 2) {
        $vendorBusinessSchema['address'] = $vendorAddress;
    }

    if ($vendorReviewCount > 0 && $vendorRatingValue) {
        $vendorBusinessSchema['aggregateRating'] = [
            '@type' => 'AggregateRating',
            'ratingValue' => $vendorRatingValue,
            'reviewCount' => $vendorReviewCount,
            'bestRating' => 5,
            'worstRating' => 1,
        ];
        $vendorBusinessSchema['review'] = $vendor->approvedReviews->take(5)->map(function ($rev) {
            return [
                '@type' => 'Review',
                'author' => ['@type' => 'Person', 'name' => $rev->reviewer_name ?: 'Pengguna'],
                'reviewRating' => ['@type' => 'Rating', 'ratingValue' => $rev->rating, 'bestRating' => 5, 'worstRating' => 1],
                'reviewBody' => \Illuminate\Support\Str::limit(strip_tags((string) $rev->body), 300, ''),
                'datePublished' => optional($rev->reviewed_at ?? $rev->created_at)->format('Y-m-d'),
            ];
        })->values()->toArray();
    }

    // FAQ Schema
    $vendorFaqItems = [];
    if ($vendorPriceStart) {
        $vendorFaqItems[] = [
            '@type' => 'Question',
            'name' => 'Berapa harga paket pernikahan di ' . $vendor->name . '?',
            'acceptedAnswer' => ['@type' => 'Answer', 'text' => 'Harga paket di ' . $vendor->name . ' mulai dari Rp' . number_format((int) $vendorPriceStart, 0, ',', '.') . '. Tersedia berbagai pilihan paket sesuai kebutuhan dan anggaran Anda.'],
        ];
    }
    if (filled($vendor->city)) {
        $vendorFaqItems[] = [
            '@type' => 'Question',
            'name' => 'Dimana lokasi ' . $vendor->name . '?',
            'acceptedAnswer' => ['@type' => 'Answer', 'text' => $vendor->name . ' berlokasi di ' . (filled($vendor->location) ? $vendor->location . ', ' : '') . $vendor->city . (filled($vendor->province) ? ', ' . $vendor->province : '') . '.'],
        ];
    }
    $vendorFaqItems[] = [
        '@type' => 'Question',
        'name' => 'Bagaimana cara memesan paket di ' . $vendor->name . '?',
        'acceptedAnswer' => ['@type' => 'Answer', 'text' => 'Klik tombol Chat atau WhatsApp yang tersedia di halaman ini untuk menghubungi ' . $vendor->name . ' secara langsung. Anda juga bisa memilih paket terlebih dahulu lalu klik tombol booking.'],
    ];
    $vendorFaqItems[] = [
        '@type' => 'Question',
        'name' => 'Apa layanan yang ditawarkan ' . $vendor->name . '?',
        'acceptedAnswer' => ['@type' => 'Answer', 'text' => $vendor->name . ' adalah vendor ' . $vendorCategoryLabel . ' yang menyediakan layanan pernikahan profesional' . (filled($vendor->city) ? ' di ' . $vendor->city : '') . '. Lihat daftar paket lengkap di halaman ini.'],
    ];
    $vendorFaqItems[] = [
        '@type' => 'Question',
        'name' => 'Apakah bisa konsultasi sebelum memesan?',
        'acceptedAnswer' => ['@type' => 'Answer', 'text' => 'Ya, Anda dapat berkonsultasi dulu dengan ' . $vendor->name . ' melalui fitur chat yang tersedia. Diskusikan kebutuhan, anggaran, dan tanggal pernikahan Anda sebelum memilih paket.'],
    ];
    $vendorFaqSchema = [
        '@context' => 'https://schema.org',
        '@type' => 'FAQPage',
        'mainEntity' => $vendorFaqItems,
    ];

    $vendorSchema = [
        '@context' => 'https://schema.org',
        '@graph' => [
            $vendorBusinessSchema,
            [
                '@type' => 'BreadcrumbList',
                'itemListElement' => [
                    [
                        '@type' => 'ListItem',
                        'position' => 1,
                        'name' => 'Home',
                        'item' => route('home'),
                    ],
                    [
                        '@type' => 'ListItem',
                        'position' => 2,
                        'name' => 'Vendor',
                        'item' => route('vendor'),
                    ],
                    [
                        '@type' => 'ListItem',
                        'position' => 3,
                        'name' => $vendor->name,
                        'item' => route('vendor.detail', $vendor),
                    ],
                ],
            ],
        ],
    ];
@endphp
@section('title', $vendorPageTitle)
@section('meta-description', $vendorMetaDescription)
@section('meta-image', $vendorMetaImage)
@section('meta-type', 'business.business')

@section('body-class', 'bg-cream text-dark')

@section('extra-head')
<script type="application/ld+json">@json($vendorSchema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)</script>
<script type="application/ld+json">@json($vendorFaqSchema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)</script>
<style>
    .prose ul { list-style: disc; padding-left: 1.4rem; margin: 4px 0; }
    .prose ol { list-style: decimal; padding-left: 1.4rem; margin: 4px 0; }
    .prose li { margin: 2px 0; }
    .prose strong { font-weight: 700; }
    .prose em { font-style: italic; }
    #modal-items ul, #modal-items ol { padding-left: 1.4rem; margin: 4px 0; }
    #modal-items ul { list-style: disc; }
    #modal-items ol { list-style: decimal; }
    #modal-items li { margin: 2px 0; }
    #modal-items strong { font-weight: 700; }
</style>
@endsection

@section('content')
    @include('layout.header')

    @php
        $hasLiked = $hasLiked ?? false;
        $reviewList = $vendor->approvedReviews;
        $reviewTotal = $reviewList->count();
        $displayRating = $reviewTotal > 0 ? round($reviewList->avg('rating'), 1) : 0;
        $openBookingModal = ($errors->booking->any() ?? false);
        $categoryName = $vendor->categoryVendor?->name ?? $vendor->category;
        $breadcrumbItems = [
            ['label' => 'Home', 'url' => route('home')],
            ['label' => 'Vendor', 'url' => route('vendor')],
            ['label' => $categoryName, 'url' => route('vendor') . '?category=' . $vendor->category],
            ['label' => $vendor->name, 'url' => null],
        ];
        $bookingPaymentUrl = session('booking_id')
            ? route('dashboard.booking.payment', session('booking_id'))
            : route('dashboard.booking');
        $vendorChatUrl = $vendor->cheapestPackage
            ? route('chat.public', ['package' => $vendor->cheapestPackage->id])
            : route('chat.public');
        $vendorPackagesJson = $vendor->packages->map(fn($p) => [
            'id'         => $p->id,
            'slug'       => $p->slug,
            'name'       => $p->name,
            'price'      => (int) $p->price,
            'discount'   => (int) ($p->discount ?? 0),
            'max_guests' => $p->max_guests,
        ])->values();
    @endphp

    <!-- Breadcrumb -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-3 lg:pt-3">
        <div class="pt-1 pb-4 lg:pt-1">
            <x-breadcrumb :items="$breadcrumbItems" />

        </div>
        <x-banner-ad mt="0" mb="1rem" />
    </div>

    @if(($vendorDetailDisabled ?? false))
        <div id="vendor-disabled-modal" class="fixed inset-0 z-50 flex items-center justify-center p-4">
            <div class="absolute inset-0 bg-black/50 backdrop-blur-sm"></div>
            <div class="relative bg-white rounded-3xl shadow-2xl w-full max-w-md overflow-hidden">
                <div class="p-6 pb-5 bg-cream">
                    <p class="text-xs font-bold uppercase tracking-widest text-gray-400 mb-1">Pemberitahuan</p>
                    <p class="text-xl font-bold text-dark">Vendor Belum Lengkap</p>
                    <p class="text-xs text-gray-500 mt-1">Profil vendor ini belum lengkap 100% sehingga halaman belum dapat diakses.</p>
                </div>
                <div class="p-6">
                    <a href="{{ $vendorDetailBackUrl ?? route('vendor') }}"
                       class="flex items-center justify-center w-full py-3 rounded-xl text-sm font-bold transition hover:opacity-90 bg-dark text-cream">
                        Kembali
                    </a>
                </div>
            </div>
        </div>
        <script>
            document.body.style.overflow = 'hidden';
        </script>
    @endif

    <!-- Hero Section -->
    @php
        // Build a unified pool: cover_image[] first, then galleries, then picsum fallback
        $heroPool = [];
        foreach (array_values(array_filter((array)($vendor->cover_image ?? []))) as $ci) {
            $heroPool[] = str_starts_with($ci, 'http') ? $ci : \Illuminate\Support\Facades\Storage::url($ci);
        }
        foreach ($vendor->galleries as $g) {
            if (count($heroPool) >= 5) break;
            if ($g->image_url) $heroPool[] = $g->image_url;
        }
        for ($pi = count($heroPool); $pi < 5; $pi++) {
            $heroPool[] = 'https://picsum.photos/seed/' . $vendor->slug . '-' . $pi . '/800/600';
        }
    @endphp
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-4"
        x-data="{
            mainSrc: '{{ $heroPool[0] }}',
            sideSrcs: [
                '{{ $heroPool[1] }}',
                '{{ $heroPool[2] }}',
                '{{ $heroPool[3] }}',
                '{{ $heroPool[4] }}'
            ],
            swap(index) {
                let prev = this.mainSrc;
                this.mainSrc = this.sideSrcs[index];
                this.sideSrcs[index] = prev;
            }
        }">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 items-stretch">

            <!-- Main Photo -->
            <div class="lg:col-span-2 -mx-4 w-[calc(100%+2rem)] rounded-none lg:mx-0 lg:w-full lg:rounded-2xl overflow-hidden relative ar-16x9">
                <img :src="mainSrc" loading="lazy"
                     alt="{{ $vendor->name }} - {{ $vendorCategoryLabel }}{{ $vendor->city ? ' di ' . $vendor->city : '' }}"
                     class="w-full h-full object-cover transition-all duration-500">
                <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent pointer-events-none"></div>
                <!-- Stats overlay bottom -->
                <div class="absolute bottom-4 left-4 flex items-center gap-3">
                    <span class="flex items-center gap-1 bg-black/40 backdrop-blur-sm text-white text-xs px-2.5 py-1 rounded-full">
                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                        {{ $displayRating }}
                    </span>
                    <span class="text-white text-xs bg-black/40 backdrop-blur-sm px-2.5 py-1 rounded-full">{{ $vendor->approvedReviews->count() }} Ulasan</span>
                    <span class="text-white text-xs bg-black/40 backdrop-blur-sm px-2.5 py-1 rounded-full">{{ count(array_filter((array) ($vendor->cover_image ?? []))) }} Foto</span>
                    <span class="text-white text-xs bg-black/40 backdrop-blur-sm px-2.5 py-1 rounded-full">{{ $vendor->packages->count() }} Paket</span>
                    <span class="text-white text-xs bg-black/40 backdrop-blur-sm px-2.5 py-1 rounded-full">{{ number_format($vendor->likes) }} Suka</span>
                </div>
            </div>

            <!-- Side Photos Grid -->
            <div class="hidden lg:grid grid-cols-2 grid-rows-2 gap-2 self-stretch h-full min-h-0">
                <template x-for="(src, index) in sideSrcs" :key="index">
                    <div class="relative rounded-xl overflow-hidden min-h-0 h-full cursor-pointer bg-gray-100" @click="swap(index)">
                        <img :src="src"
                             :alt="'{{ $vendor->name }} - Foto Galeri ' + (index + 1)"
                             class="absolute inset-0 w-full h-full object-cover hover:scale-105 hover:brightness-90 transition-all duration-300">
                    </div>
                </template>
            </div>

        </div>
    </section>

    <!-- Content -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-28 lg:pb-16">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 lg:gap-8">

            <!-- Left: Main Content -->
            <div class="lg:col-span-2 space-y-4 lg:space-y-8">

                <!-- Vendor Name + Type -->
                <div class="flex items-start justify-between gap-4 pt-2">
                    <div class="flex items-start gap-3 min-w-0">
                        @if(!empty($vendor->logo_vendor))
                            @php
                                $logoUrl = str_starts_with($vendor->logo_vendor, 'http')
                                    ? $vendor->logo_vendor
                                    : asset('storage/' . ltrim($vendor->logo_vendor, '/'));
                            @endphp
                            <div class="w-12 h-12 rounded-xl overflow-hidden border border-gray-100 bg-gray-50 flex-shrink-0">
                                <img src="{{ $logoUrl }}" alt="Logo {{ $vendor->name }}" loading="lazy" class="w-full h-full object-cover">
                            </div>
                        @endif
                        <div class="min-w-0">
                            <h1 class="text-2xl font-bold leading-tight mb-1 text-dark">{{ $vendor->name }}</h1>
                            <div class="flex items-center gap-1.5 mt-2 text-xs text-gray-600">
                                <svg class="w-3.5 h-3.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                <span class="truncate">{{ $vendor->location }}</span>
                            </div>
                        </div>
                    </div>
                    <form action="{{ route('vendor.like', $vendor) }}" method="POST" class="m-0 p-0">
                        @csrf
                        <button type="submit" class="flex-shrink-0 w-10 h-10 rounded-full border border-gray-200 flex items-center justify-center transition group {{ $hasLiked ? 'bg-red-50 border-red-200' : 'hover:bg-red-50' }}">
                            <svg class="w-5 h-5 transition {{ $hasLiked ? 'text-red-500 fill-red-500' : 'text-gray-400 group-hover:text-red-400' }}" fill="{{ $hasLiked ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                            </svg>
                        </button>
                    </form>
                </div>

                <!-- Stats Bar -->
                <div class="hidden items-center gap-3 py-3 border-y border-gray-100 text-xs text-gray-600 whitespace-nowrap overflow-x-auto">
                    <div class="flex items-center gap-1.5">
                        <svg class="w-4 h-4 text-rating" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                        <span class="font-bold">{{ $displayRating }}</span>
                        <span class="text-gray-400">({{ $vendor->approvedReviews->count() }})</span>
                    </div>
                    <span class="text-gray-300">•</span>
                    <div class="flex items-center gap-1.5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        <span class="font-bold">{{ $vendor->galleries->count() }}</span>
                    </div>
                    <span class="text-gray-300">•</span>
                    <form action="{{ route('vendor.like', $vendor) }}" method="POST" class="inline m-0 p-0">
                        @csrf
                        <button type="submit" class="flex items-center gap-1.5 transition group cursor-pointer {{ $hasLiked ? 'text-red-500' : 'text-gray-600 hover:text-red-500' }}">
                            <svg class="w-4 h-4 transition {{ $hasLiked ? 'fill-red-500' : 'group-hover:fill-red-500' }}" fill="{{ $hasLiked ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                            </svg>
                            <span class="font-bold">{{ number_format($vendor->likes) }}</span>
                        </button>
                    </form>
                    <span class="text-gray-300">•</span>
                    <div class="flex items-center gap-1.5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                        <span class="font-bold">{{ $vendor->comments_count }}</span>
                    </div>
                </div>

                <!-- About -->
                @php $cheapPkg = $vendor->cheapestPackage; @endphp
                <div>
                    <h2 class="text-base font-bold mb-3 text-dark">Tentang Vendor</h2>
                    <p class="text-sm leading-relaxed text-gray-600">{{ $vendor->description }}</p>
                    <div class="grid grid-cols-2 sm:grid-cols-2 gap-3 mt-4">
                        <div class="rounded-xl p-3 border border-gray-100 bg-white">
                            <p class="text-[10px] uppercase tracking-widest text-gray-400 mb-0.5">Harga Mulai</p>
                            @if ($cheapPkg)
                            @if ($cheapPkg->discount > 0)
                            <p class="text-[10px] line-through text-gray-400 leading-none mb-0.5">Rp {{ number_format($cheapPkg->price, 0, ',', '.') }}</p>
                            <p class="text-sm font-semibold text-accent">Rp {{ number_format($cheapPkg->price - $cheapPkg->discount, 0, ',', '.') }}</p>
                            @else
                            <p class="text-sm font-semibold text-accent">Rp {{ number_format($cheapPkg->price, 0, ',', '.') }}</p>
                            @endif
                            @else
                            @php
                                $rawPriceStart = $vendor->price_start;
                                $formattedPriceStart = is_numeric($rawPriceStart)
                                    ? 'Rp ' . number_format((int) $rawPriceStart, 0, ',', '.')
                                    : ($rawPriceStart ?: '—');
                            @endphp
                            <p class="text-sm font-semibold text-accent">{{ $formattedPriceStart }}</p>
                            @endif
                        </div>
                        <div class="rounded-xl p-3 border border-gray-100 bg-white">
                            <p class="text-[10px] uppercase tracking-widest text-gray-400 mb-0.5">Event Selesai</p>
                            <p class="text-sm font-semibold text-dark">{{ $vendor->events_done }}+ Acara</p>
                        </div>
                    </div>
                </div>

                <!-- Packages -->
                <div id="packages">
                    <h2 class="text-base font-bold mb-4 text-dark">Paket & Harga</h2>
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                        @foreach ($vendor->packages as $pkg)
                        <x-package-card
                            :href="route('store.package.show', $pkg->slug)"
                            :package-id="$pkg->id"
                            :name="$pkg->name"
                            :image="$pkg->image_url ?: asset('images/placeholder.jpg')"
                            :price="$pkg->price"
                            :discount="$pkg->discount ?: 0"
                            :location="$vendor->city ?: 'Indonesia'"
                            :benefitPrimary="($pkg->max_guests ? $pkg->max_guests . ' Pax' : 'Paket Pilihan')"
                            :benefitSecondary="$pkg->type ?: 'Gratis Konsultasi'"
                            widthClass="w-full"
                        />
                        @endforeach
                    </div>
                </div>

                <!-- Review Videos -->
                <div>
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-base font-bold text-dark">Videos Vendor</h2>
                    </div>
                    <!-- Horizontal scroll strip -->
                    <div class="flex gap-3 overflow-x-auto pb-2 snap-x snap-mandatory scrollbar-hide">
                        @foreach ($vendor->galleries as $idx => $g)
                        @php $hasVideo = !empty($g->video_url); @endphp
                        <div class="relative flex-shrink-0 rounded-2xl overflow-hidden cursor-pointer group snap-start w-[180px] h-[280px]"
                             @if($hasVideo) data-action="open-video" data-video-url="{{ $g->video_url }}" @endif>
                            <!-- Background image -->
                            <img src="{{ $g->image_url }}"
                                 alt="{{ $g->caption ?: $vendor->name . ' - Galeri Foto ' . ($idx + 1) }}"
                                 loading="lazy"
                                 class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
                            <!-- Dark gradient overlay -->
                            <div class="absolute inset-0 bg-linear-to-t from-black/80 via-black/20 to-transparent"></div>
                            <!-- Play button -->
                            @if($hasVideo)
                            <div class="absolute inset-0 flex items-center justify-center">
                                <div class="w-12 h-12 rounded-full bg-white/90 group-hover:bg-white flex items-center justify-center shadow-lg transition">
                                    <svg class="w-5 h-5 ml-0.5 text-dark" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M8 5v14l11-7z"/>
                                    </svg>
                                </div>
                            </div>
                            @endif
                            <!-- Label bottom -->
                            <div class="absolute bottom-0 left-0 right-0 p-3">
                                <p class="text-[9px] uppercase tracking-widest text-white/70 mb-0.5">Introducing</p>
                                <p class="text-xs font-bold uppercase leading-tight text-white">{{ $g->caption }}</p>
                                <p class="text-[10px] text-white/60 mt-0.5">{{ $vendor->name }}</p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                {{-- <x-paket-section :packages="$packages" :more-url="route('store')" /> --}}

                <!-- Reviews -->
                @php
                    $ratingCounts = [5 => 0, 4 => 0, 3 => 0, 2 => 0, 1 => 0];
                    foreach ($reviewList as $_r) {
                        $ratingCounts[(int) $_r->rating] = ($ratingCounts[(int) $_r->rating] ?? 0) + 1;
                    }
                    $ratingLabel = match(true) {
                        $displayRating >= 4.8 => 'Luar Biasa',
                        $displayRating >= 4.5 => 'Sangat Bagus',
                        $displayRating >= 4.0 => 'Bagus',
                        $displayRating >= 3.0 => 'Cukup Baik',
                        default                => 'Perlu Ditingkatkan',
                    };
                @endphp
                <div>
                    <h2 class="text-base font-bold mb-4 text-dark">Ulasan</h2>

                    {{-- Rating Summary Panel --}}
                    <div class="rounded-2xl border border-gray-100 bg-white p-5 mb-5 flex flex-col sm:flex-row items-center gap-6">
                        {{-- Big Score --}}
                        <div class="flex flex-col items-center justify-center flex-shrink-0 sm:border-r border-gray-100 sm:pr-6">
                            <p class="text-5xl font-bold leading-none mb-1 text-dark">{{ number_format($displayRating, 1) }}</p>
                            <div class="flex items-center gap-0.5 my-1.5">
                                @for ($s = 1; $s <= 5; $s++)
                                <svg class="w-4 h-4 {{ $s <= round($displayRating) ? '' : 'opacity-20' }} text-rating" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                </svg>
                                @endfor
                            </div>
                            <p class="text-xs font-semibold text-accent">{{ $ratingLabel }}</p>
                            <p class="text-[11px] text-gray-400 mt-0.5">{{ $reviewTotal }} ulasan</p>
                        </div>

                        {{-- Star Bars --}}
                        <div class="flex-1 w-full space-y-2">
                            @foreach ([5, 4, 3, 2, 1] as $star)
                            @php $pct = $reviewTotal > 0 ? round($ratingCounts[$star] / $reviewTotal * 100) : 0; @endphp
                            <div class="flex items-center gap-2">
                                <span class="text-xs font-semibold w-3 flex-shrink-0 text-dark">{{ $star }}</span>
                                <svg class="w-3.5 h-3.5 flex-shrink-0 text-rating" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                </svg>
                                <div class="flex-1 h-2 rounded-full bg-gray-100 overflow-hidden">
                                    <div class="h-full rounded-full transition-all duration-700 bg-accent" style="width: {{ $pct }}%"></div>
                                </div>
                                <span class="text-[11px] text-gray-400 w-6 text-right flex-shrink-0">{{ $ratingCounts[$star] }}</span>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- Review Cards --}}
                    <div class="space-y-4">
                        @if (session('reply_success'))
                            <div class="bg-green-50 text-green-700 border border-green-100 rounded-2xl p-3 text-xs font-semibold">
                                {{ session('reply_success') }}
                            </div>
                        @endif
                        @if (session('block_success'))
                            <div class="bg-orange-50 text-orange-700 border border-orange-100 rounded-2xl p-3 text-xs font-semibold">
                                {{ session('block_success') }}
                            </div>
                        @endif
                        @foreach ($reviewList as $revIdx => $rev)
                        <div class="bg-white rounded-2xl p-4 border border-gray-100 review-card {{ $revIdx >= 3 ? 'hidden' : '' }}">
                            <div class="flex items-center gap-3 mb-2">
                                <img src="{{ $rev->reviewer_avatar ?? 'https://picsum.photos/seed/rv-'.$rev->id.'/80/80' }}"
                                     alt="{{ $rev->reviewer_name }}"
                                     loading="lazy"
                                     class="w-9 h-9 rounded-full object-cover shrink-0">
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-bold leading-tight text-dark">{{ $rev->reviewer_name }}</p>
                                    <p class="text-[10px] text-gray-400">{{ $rev->reviewed_at->translatedFormat('d M Y') }}</p>
                                </div>
                                <div class="flex items-center gap-0.5">
                                    @for ($s = 1; $s <= 5; $s++)
                                    <svg class="w-3 h-3 {{ $s <= $rev->rating ? '' : 'opacity-20' }} text-rating" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                    @endfor
                                </div>
                            </div>
                            <p class="text-sm text-gray-600 leading-relaxed">{{ $rev->body }}</p>
                            @if(filled($rev->admin_reply))
                                <div class="mt-3 p-3 rounded-xl bg-gray-50 border border-gray-100">
                                    <div class="flex items-center justify-between gap-2 mb-1">
                                        <p class="text-[10px] uppercase tracking-widest text-gray-400 font-bold">Balasan Admin</p>
                                        @if($rev->admin_replied_at)
                                            <p class="text-[10px] text-gray-400">{{ $rev->admin_replied_at->translatedFormat('d M Y') }}</p>
                                        @endif
                                    </div>
                                    <p class="text-xs text-gray-600 leading-relaxed">{{ $rev->admin_reply }}</p>
                                </div>
                            @endif

                            @auth
                                @if(auth()->user()->hasRole(['super_admin', 'admin']))
                                    <div class="mt-3 p-3 rounded-xl border border-gray-100 bg-white">
                                        <p class="text-[10px] uppercase tracking-widest text-gray-400 font-bold mb-2">Reply Admin</p>
                                        <form method="POST" action="{{ route('vendor.review.reply', $rev) }}" class="space-y-2">
                                            @csrf
                                            <input type="hidden" name="review_id" value="{{ $rev->id }}">
                                            <textarea name="admin_reply" rows="3" maxlength="2000"
                                                      class="w-full border border-gray-200 rounded-xl px-3 py-2 text-xs focus:outline-none focus:border-gray-400 transition resize-none"
                                                      placeholder="Tulis balasan admin...">{{ (string) old('review_id') === (string) $rev->id ? old('admin_reply') : '' }}</textarea>
                                            <div class="flex items-center justify-end gap-2">
                                                <button type="submit" class="text-xs font-bold px-3 py-2 rounded-lg transition hover:opacity-90 bg-accent text-white">
                                                    Simpan
                                                </button>
                                            </div>
                                        </form>
                                        @if(filled($rev->admin_reply))
                                            <form method="POST" action="{{ route('vendor.review.reply', $rev) }}" class="mt-2 flex justify-end">
                                                @csrf
                                                <input type="hidden" name="admin_reply" value="">
                                                <button type="submit" class="text-xs font-bold px-3 py-2 rounded-lg bg-gray-50 hover:bg-gray-100 transition text-dark">
                                                    Hapus
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                @endif
                            @endauth

                            @auth
                                @if(!auth()->user()->hasRole(['super_admin', 'admin']) && $rev->user_id !== auth()->id())
                                <div class="mt-2 flex items-center justify-end gap-3" x-data="{ open: false }">
                                    {{-- Tombol Blokir --}}
                                    @if($rev->user_id)
                                        @php $isBlocked = ($blockedUserIds ?? collect())->contains($rev->user_id); @endphp
                                        @if($isBlocked)
                                            <form method="POST" action="{{ route('user.unblock', $rev->user) }}">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-[10px] text-blue-400 hover:text-blue-600 transition flex items-center gap-1">
                                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/></svg>
                                                    Batal Blokir
                                                </button>
                                            </form>
                                        @else
                                            <form method="POST" action="{{ route('user.block', $rev->user) }}">
                                                @csrf
                                                <button type="submit" class="text-[10px] text-gray-400 hover:text-orange-400 transition flex items-center gap-1"
                                                    onclick="return confirm('Blokir pengguna ini? Ulasan mereka tidak akan muncul untukmu.')">
                                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/></svg>
                                                    Blokir
                                                </button>
                                            </form>
                                        @endif
                                    @endif

                                    {{-- Tombol Laporkan --}}
                                    <button type="button" @click="open = !open"
                                        class="text-[10px] text-gray-400 hover:text-red-400 transition flex items-center gap-1">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 21v-4m0 0V5a2 2 0 012-2h6.5l1 1H21l-3 6 3 6h-8.5l-1-1H5a2 2 0 00-2 2zm9-13.5V9"/></svg>
                                        Laporkan
                                    </button>
                                    <div x-show="open" x-cloak class="absolute mt-5 mr-4 right-0 z-10 bg-white border border-gray-200 rounded-xl shadow-lg p-3 w-52">
                                        <p class="text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-2">Alasan laporan</p>
                                        <form method="POST" action="{{ route('report.review', $rev) }}">
                                            @csrf
                                            <select name="reason" class="w-full border border-gray-200 rounded-lg px-2 py-1.5 text-xs mb-2 focus:outline-none">
                                                <option value="spam">Spam</option>
                                                <option value="offensive">Konten tidak pantas</option>
                                                <option value="fake">Ulasan palsu</option>
                                                <option value="other">Lainnya</option>
                                            </select>
                                            <button type="submit" class="w-full py-1.5 rounded-lg text-xs font-bold bg-red-50 text-red-500 hover:bg-red-100 transition">
                                                Kirim Laporan
                                            </button>
                                        </form>
                                    </div>
                                </div>
                                @endif
                            @endauth
                        </div>
                        @endforeach
                    </div>

                    @if($reviewTotal > 3)
                    <button type="button" id="review-toggle-btn"
                            data-action="toggle-reviews"
                            class="mt-4 w-full py-2.5 rounded-xl text-sm font-semibold border border-gray-200 bg-white hover:border-gray-300 transition flex items-center justify-center gap-2 text-dark">
                        <span id="review-toggle-text">Lihat Semua Ulasan ({{ $reviewTotal }})</span>
                        <svg id="review-toggle-icon" class="w-4 h-4 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    @endif

                    {{-- Write Review Form --}}
                    <div class="mt-6 bg-white rounded-2xl border border-gray-100 p-5">
                        <h3 class="text-sm font-bold mb-4 text-dark">Tulis Ulasan</h3>

                        @guest
                        {{-- Guest: prompt to login --}}
                        <div class="flex flex-col items-center justify-center py-4 text-center gap-3">
                            <div class="w-12 h-12 rounded-full flex items-center justify-center mb-1 bg-light-sage">
                                <svg class="w-6 h-6 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                            </div>
                            <p class="text-sm text-gray-500">Kamu harus <strong class="font-semibold text-dark">masuk</strong> terlebih dahulu untuk memberikan ulasan.</p>
                            <a href="{{ route('login') }}?redirect={{ urlencode(request()->fullUrl()) }}"
                               class="inline-block px-6 py-2.5 rounded-xl text-sm font-bold transition hover:opacity-90 bg-accent text-white">
                                Masuk / Daftar
                            </a>
                        </div>
                        @endguest

                        @auth
                        @if((int) $vendor->owner_user_id === (int) auth()->id())
                            <div class="text-xs font-semibold px-3 py-2 rounded-lg bg-yellow-50 text-yellow-700">
                                Anda tidak dapat memberi ulasan pada vendor milik sendiri.
                            </div>
                        @else
                        {{-- Authenticated: show form --}}
                        <div class="flex items-center gap-2 mb-4 p-2.5 rounded-xl bg-cream">
                            <div class="w-7 h-7 rounded-full flex items-center justify-center text-[11px] font-bold flex-shrink-0 text-white bg-accent">
                                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                            </div>
                            <p class="text-xs font-semibold text-dark">{{ auth()->user()->name }}</p>
                        </div>

                        {{-- Star Rating Picker --}}
                        <div class="flex items-center gap-1 mb-4" id="star-picker">
                            @for ($si = 1; $si <= 5; $si++)
                            <button type="button"
                                    data-action="set-review-rating"
                                    data-val="{{ $si }}"
                                    class="star-btn w-8 h-8 transition-transform hover:scale-110"
                                    aria-label="{{ $si }} bintang">
                                <svg class="w-full h-full text-gray-300" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                </svg>
                            </button>
                            @endfor
                            <span id="star-label" class="text-xs text-gray-400 ml-1">Pilih rating</span>
                        </div>
                        <input type="hidden" id="review-rating" value="0">

                        <div class="mb-4">
                            <textarea id="review-body" rows="3" maxlength="1000"
                                      placeholder="Ceritakan pengalaman Anda dengan vendor ini (min. 10 karakter)..."
                                      class="w-full border border-gray-200 rounded-xl px-3.5 py-2.5 text-sm focus:outline-none focus:border-gray-400 transition resize-none"></textarea>
                            <p class="text-[10px] text-gray-300 text-right mt-0.5"><span id="review-char">0</span>/1000</p>
                        </div>

                        <div id="review-feedback" class="hidden mb-3 text-xs font-semibold px-3 py-2 rounded-lg"></div>

                        <button type="button" data-action="submit-review"
                                id="review-submit-btn"
                                class="w-full py-2.5 rounded-xl text-sm font-bold transition hover:opacity-90 bg-accent text-white">
                            Kirim Ulasan
                        </button>
                        @endif
                        @endauth
                    </div>
                </div>

            </div>

            <!-- Right: Sticky Sidebar -->
            <div class="lg:col-span-1">
                <div class="sticky top-24 space-y-4">

                    <!-- CTA Card -->
                    <div id="contact-cta" class="bg-white rounded-2xl border border-gray-100 p-5 shadow-sm">
                        <p class="text-[10px] uppercase tracking-widest text-gray-400 mb-1" id="sidebar-pkg-label">Harga Mulai</p>
                        @if ($cheapPkg)
                        @if ($cheapPkg->discount > 0)
                        <p id="sidebar-price-original"
                           class="text-sm line-through text-gray-400 mb-0">Rp {{ number_format($cheapPkg->price, 0, ',', '.') }}</p>
                        <p class="text-2xl font-bold mb-0.5 text-accent"
                           id="sidebar-price"
                           >Rp {{ number_format($cheapPkg->price - $cheapPkg->discount, 0, ',', '.') }}</p>
                        @else
                        <p class="text-2xl font-bold mb-0.5 text-accent"
                           id="sidebar-price"
                           >Rp {{ number_format($cheapPkg->price, 0, ',', '.') }}</p>
                        @endif
                        <div id="sidebar-dp-wrap" class="mt-1">
                            <p class="text-xs text-gray-400 mb-0">DP: <span id="sidebar-dp" class="font-semibold text-gray-600">{{ ($cheapPkg->dp_paket ?? 0) > 0 ? ('Rp ' . number_format((int) $cheapPkg->dp_paket, 0, ',', '.')) : '—' }}</span></p>
                        </div>
                        @else
                        @php
                            $rawPriceStart = $vendor->price_start;
                            $formattedPriceStart = is_numeric($rawPriceStart)
                                ? 'Rp ' . number_format((int) $rawPriceStart, 0, ',', '.')
                                : ($rawPriceStart ?: '—');
                        @endphp
                        <p class="text-2xl font-bold mb-0.5 text-accent"
                           id="sidebar-price"
                           >{{ $formattedPriceStart }}</p>
                        <div id="sidebar-dp-wrap" class="mt-1">
                            <p class="text-xs text-gray-400 mb-0">DP: <span id="sidebar-dp" class="font-semibold text-gray-600">—</span></p>
                        </div>
                        @endif
                        <p class="text-xs text-gray-400 mb-4" id="sidebar-pkg-sub"></p>

                        <a href="{{ $vendorChatUrl }}"
                           class="flex items-center justify-center gap-2 w-full py-3 rounded-xl text-sm font-bold transition hover:opacity-90 mb-3 border border-gray-200 bg-white text-dark">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                            </svg>
                            Chat
                        </a>

                        @if (session('booking_success'))
                            <div class="mb-3 text-xs font-semibold px-3 py-2 rounded-xl bg-green-50 text-green-700">
                                {{ session('booking_success') }}
                            </div>
                        @endif

                        @auth
                            @if(isset($myBooking) && $myBooking)
                                @php
                                    $myBookingBadge = match($myBooking->status) {
                                        'confirmed', 'done' => 'bg-green-50 text-green-700',
                                        'cancelled' => 'bg-red-50 text-red-700',
                                        'no_response' => 'bg-gray-100 text-gray-700',
                                        'contacted' => 'bg-blue-50 text-blue-700',
                                        default => 'bg-yellow-50 text-yellow-700',
                                    };
                                    $myBookingLabel = match($myBooking->status) {
                                        'confirmed' => 'Confirmed',
                                        'done' => 'Done',
                                        'cancelled' => 'Cancelled',
                                        'no_response' => 'No Response',
                                        'contacted' => 'Contacted',
                                        default => 'Pending',
                                    };
                                @endphp
                                <div class="mb-3 px-3 py-2 rounded-xl border border-gray-100 bg-white">
                                    <div class="flex items-center justify-between gap-2">
                                        <div class="text-xs font-semibold text-gray-700">Status Booking Anda</div>
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-bold {{ $myBookingBadge }}">
                                            {{ $myBookingLabel }}
                                        </span>
                                    </div>
                                    <div class="text-[10px] text-gray-400 mt-1">
                                        {{ $myBooking->created_at?->format('d M Y, H:i') }}
                                    </div>
                                    @if($myBooking->status === 'pending')
                                        <div class="text-[10px] text-gray-500 mt-1">
                                            Booking sudah terkirim, menunggu diproses admin.
                                        </div>
                                    @endif
                                </div>
                            @endif
                        @endauth

                        <div id="booking-warning" class="hidden mb-3 text-xs font-semibold px-3 py-2 rounded-xl bg-yellow-50 text-yellow-700"></div>

                        @guest
                            <button type="button" data-action="open-booking-page"
                                    class="flex items-center justify-center gap-2 w-full py-3 rounded-xl text-sm font-bold transition hover:opacity-90 mb-4 bg-accent text-cream">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                Booking Sekarang
                            </button>
                        @endguest

                        @auth
                            @php
                                $isOwnerBooking = (int) auth()->id() === (int) $vendor->owner_user_id;
                            @endphp

                            @if($isOwnerBooking)
                                <div class="mb-4 rounded-xl border border-gray-100 bg-gray-50 px-3 py-2 text-xs text-gray-600">
                                    Anda adalah pemilik vendor ini. Booking dinonaktifkan.
                                </div>
                                <a href="{{ route('vendor.edit', $vendor) }}"
                                   class="flex items-center justify-center gap-2 w-full py-3 rounded-xl text-sm font-bold transition hover:opacity-90 mb-4 bg-accent text-cream">
                                    Kelola Vendor
                                </a>
                            @elseif(isset($myBooking) && $myBooking && $myBooking->status === 'pending')
                                <div class="flex gap-2 mb-4">
                                    <a href="{{ route('dashboard.booking.edit', $myBooking) }}"
                                       class="flex-1 flex items-center justify-center gap-2 py-3 rounded-xl text-sm font-bold transition hover:opacity-90 bg-accent text-cream">
                                        Edit Booking
                                    </a>
                                    <a href="{{ route('dashboard.booking') }}"
                                       class="flex items-center justify-center px-4 py-3 rounded-xl text-sm font-bold border border-gray-200 hover:border-gray-300 transition text-dark">
                                        Riwayat
                                    </a>
                                </div>
                            @elseif(isset($myBooking) && $myBooking)
                                <div class="flex gap-2 mb-4">
                                    <a href="{{ route('dashboard.booking.payment', $myBooking) }}"
                                       class="flex-1 flex items-center justify-center gap-2 py-3 rounded-xl text-sm font-bold transition hover:opacity-90 bg-accent text-cream">
                                        Lihat Booking
                                    </a>
                                    @if(in_array($myBooking->status, ['done', 'cancelled'], true))
                                        <button type="button" data-action="open-booking-page"
                                                class="flex items-center justify-center px-4 py-3 rounded-xl text-sm font-bold border border-gray-200 hover:border-gray-300 transition text-dark">
                                            Booking Lagi
                                        </button>
                                    @else
                                        <a href="{{ route('dashboard.booking') }}"
                                           class="flex items-center justify-center px-4 py-3 rounded-xl text-sm font-bold border border-gray-200 hover:border-gray-300 transition text-dark">
                                            Riwayat
                                        </a>
                                    @endif
                                </div>
                            @else
                                <button type="button" data-action="open-booking-page"
                                        class="flex items-center justify-center gap-2 w-full py-3 rounded-xl text-sm font-bold transition hover:opacity-90 mb-4 bg-accent text-cream">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                    Booking Sekarang
                                </button>
                                <p class="text-[10px] text-gray-400 -mt-2 mb-4">Booking membutuhkan pemilihan paket.</p>
                            @endif
                        @endauth

                        <div class="flex gap-2">
                            <button type="button" data-action="share-vendor" class="flex-1 flex items-center justify-center gap-1.5 py-2 rounded-xl text-xs font-semibold border border-gray-200 hover:border-gray-300 transition text-dark">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"/></svg>
                                Bagikan
                            </button>
                            @auth
                                @if(auth()->user()->hasRole(['super_admin', 'admin']) || (int) $vendor->owner_user_id === (int) auth()->id())
                                <a href="{{ route('vendor.edit', $vendor) }}" class="flex-1 flex items-center justify-center gap-1.5 py-2 rounded-xl text-xs font-semibold border border-gray-200 hover:border-gray-300 transition text-dark">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    Edit
                                </a>
                                @endif
                                <form method="POST" action="{{ route('vendor.like', $vendor) }}" class="flex-1">
                                    @csrf
                                    <button type="submit" class="w-full flex items-center justify-center gap-1.5 py-2 rounded-xl text-xs font-semibold border border-gray-200 hover:border-gray-300 transition text-dark">
                                        <svg class="w-3.5 h-3.5" fill="{{ ($hasLiked ?? false) ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"/></svg>
                                        {{ ($hasLiked ?? false) ? 'Tersimpan' : 'Simpan' }}
                                    </button>
                                </form>
                            @else
                                <a href="{{ route('login') }}?redirect={{ urlencode(request()->fullUrl()) }}" class="flex-1 flex items-center justify-center gap-1.5 py-2 rounded-xl text-xs font-semibold border border-gray-200 hover:border-gray-300 transition text-dark">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"/></svg>
                                    Simpan
                                </a>
                            @endauth
                        </div>
                    </div>

                    <!-- Contact Info -->
                    <div class="bg-white rounded-2xl border border-gray-100 p-5">
                        <h3 class="text-xs sm:text-sm font-bold mb-3 text-dark">Informasi Kontak</h3>
                        <div class="space-y-3">
                            <div class="flex items-center gap-3 text-xs sm:text-sm text-gray-600">
                                <div class="w-7 h-7 sm:w-8 sm:h-8 rounded-full flex items-center justify-center flex-shrink-0 bg-light-sage">
                                    <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4 text-dark" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                                </div>
                                {{ $vendor->phone }}
                            </div>
                            <div class="flex items-center gap-3 text-xs sm:text-sm text-gray-600">
                                <div class="w-7 h-7 sm:w-8 sm:h-8 rounded-full flex items-center justify-center flex-shrink-0 bg-light-sage">
                                    <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4 text-dark" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                </div>
                                <span class="break-all">{{ $vendor->email }}</span>
                            </div>
                            <div class="flex items-center gap-3 text-xs sm:text-sm text-gray-600">
                                <div class="w-7 h-7 sm:w-8 sm:h-8 rounded-full flex items-center justify-center flex-shrink-0 bg-light-sage">
                                    <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4 text-dark" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
                                </div>
                                <span class="break-all">{{ $vendor->instagram }}</span>
                            </div>
                            <div class="flex items-start gap-3 text-xs sm:text-sm text-gray-600">
                                <div class="w-7 h-7 sm:w-8 sm:h-8 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5 bg-light-sage">
                                    <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4 text-dark" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                </div>
                                {{ $vendor->location }}
                            </div>
                        </div>
                    </div>

                    <!-- Map Placeholder -->
                    <div class="bg-white rounded-2xl border border-gray-100 overflow-hidden">
                        <div class="relative h-36 bg-gray-100 flex items-center justify-center">
                            <img src="https://picsum.photos/seed/map-{{ $vendor->slug }}/600/200"
                                 alt="Lokasi" loading="lazy" class="w-full h-full object-cover opacity-70">
                            <div class="absolute inset-0 flex items-center justify-center">
                                @php
                                    $mapsQuery = trim(implode(' ', array_filter([$vendor->location, $vendor->city, $vendor->province])));
                                    $mapsUrl = 'https://www.google.com/maps/search/?api=1&query=' . urlencode($mapsQuery ?: $vendor->name);
                                @endphp
                                <a href="{{ $mapsUrl }}" target="_blank" rel="noopener noreferrer"
                                   class="bg-white/90 rounded-xl px-3 py-2 flex items-center gap-2 shadow-sm transition hover:bg-white">
                                    <svg class="w-4 h-4 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                    <span class="text-xs font-semibold text-dark">Lihat di Maps</span>
                                </a>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>

    <!-- YouTube Video Modal -->
    <div id="video-modal"
         class="fixed inset-0 z-50 hidden items-center justify-center p-4">
        <div data-action="close-video-modal" class="absolute inset-0 bg-black/80 backdrop-blur-sm"></div>
        <div class="relative w-full max-w-3xl">
            <!-- Close button -->
            <button type="button" data-action="close-video-modal"
                    class="absolute -top-10 right-0 w-9 h-9 rounded-full bg-white/20 hover:bg-white/30 flex items-center justify-center transition">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
            <!-- YouTube iframe container (16:9) -->
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

    <!-- Package Picker Modal -->
    <div id="pkg-picker-modal"
         class="fixed inset-0 z-50 hidden items-center justify-center p-4">
        <div data-action="close-pkg-picker" class="absolute inset-0 bg-black/50 backdrop-blur-sm"></div>
        <div class="relative bg-white rounded-3xl shadow-2xl w-full max-w-sm overflow-hidden max-h-[85vh] flex flex-col">
            <div class="p-5 pb-4 bg-cream flex items-center justify-between">
                <div>
                    <p class="text-xs font-bold uppercase tracking-widest text-gray-400 mb-0.5">Langkah 1</p>
                    <p class="text-base font-bold text-dark">Pilih Paket</p>
                </div>
                <button type="button" data-action="close-pkg-picker"
                        class="w-8 h-8 rounded-full bg-white/60 hover:bg-white/80 flex items-center justify-center transition">
                    <svg class="w-4 h-4 text-dark" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            <div id="pkg-picker-list" class="overflow-y-auto flex-1 p-4 space-y-2"></div>
            <div class="p-4 pt-3 border-t border-gray-100">
                <button type="button" id="pkg-picker-confirm" data-action="confirm-pkg-pick"
                        disabled
                        class="w-full py-3 rounded-xl text-sm font-bold transition bg-accent text-cream opacity-40 cursor-not-allowed">
                    Lanjut Booking
                </button>
            </div>
        </div>
    </div>

    <div id="booking-modal"
         class="fixed inset-0 z-50 hidden items-center justify-center p-4">
        <div data-action="close-booking-modal" class="absolute inset-0 bg-black/50 backdrop-blur-sm"></div>
        <div class="relative bg-white rounded-3xl shadow-2xl w-full max-w-md overflow-hidden max-h-[90vh] overflow-y-auto">
            <div class="p-6 pb-5 bg-cream">
                <button type="button" data-action="close-booking-modal"
                        class="absolute top-4 right-4 w-8 h-8 rounded-full bg-white/60 hover:bg-white/80 flex items-center justify-center transition">
                    <svg class="w-4 h-4 text-dark" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
                <p class="text-xs font-bold uppercase tracking-widest text-gray-400 mb-1">Booking</p>
                <p class="text-xl font-bold text-dark">{{ $vendor->name }}</p>
                <p class="text-xs text-gray-500 mt-1">Isi detail singkat, lalu kami hubungi via WhatsApp/telepon.</p>
            </div>
            <div class="p-6 space-y-4">
                @if (session('booking_success'))
                    <div class="text-xs font-semibold px-3 py-2 rounded-xl bg-green-50 text-green-700">
                        {{ session('booking_success') }}
                    </div>
                @endif

                @if ($errors->booking->any())
                    <div class="text-xs font-semibold px-3 py-2 rounded-xl bg-red-50 text-red-700">
                        <ul class="list-disc pl-4 space-y-1">
                            @foreach ($errors->booking->all() as $err)
                                <li>{{ $err }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('vendor.booking.store', $vendor) }}" class="space-y-3">
                    @csrf

                    <div>
                        <label class="block text-xs font-semibold text-gray-500 mb-1">Paket (opsional)</label>
                        @php
                            $oldBookingPkgId = old('vendor_package_id');
                            $oldBookingPkg = $oldBookingPkgId ? $vendor->packages->firstWhere('id', (int) $oldBookingPkgId) : null;
                        @endphp
                        <input type="hidden" name="vendor_package_id" id="booking-vendor-package-id" value="{{ $oldBookingPkg?->id }}">
                        <input type="text" id="booking-vendor-package-label"
                               value="{{ $oldBookingPkg ? ($oldBookingPkg->name . ' — Rp ' . number_format($oldBookingPkg->price, 0, ',', '.')) : 'Tanpa paket' }}"
                               readonly
                               class="w-full h-11 border border-gray-200 rounded-xl px-3.5 py-2.5 text-sm bg-gray-50 text-gray-700">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-500 mb-1">Tanggal acara</label>
                        <input type="date" name="event_date" value="{{ old('event_date') }}"
                               class="w-full h-11 border border-gray-200 rounded-xl px-3.5 py-2.5 text-sm focus:outline-none focus:border-gray-400 transition">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-500 mb-1">Nomor WhatsApp</label>
                        @php
                            $prefillWa = auth()->check() && auth()->user()->whatsapp
                                ? preg_replace('/^62/', '0', auth()->user()->whatsapp)
                                : '';
                        @endphp
                        <input type="tel" inputmode="numeric" autocomplete="tel" name="phone" value="{{ old('phone', $prefillWa) }}"
                               placeholder="Contoh: 08xxxxxxxxxx"
                               class="w-full h-11 border border-gray-200 rounded-xl px-3.5 py-2.5 text-sm focus:outline-none focus:border-gray-400 transition">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-500 mb-1">Catatan (opsional)</label>
                        <textarea name="notes" rows="3" maxlength="2000"
                                  class="w-full border border-gray-200 rounded-xl px-3.5 py-2.5 text-sm focus:outline-none focus:border-gray-400 transition resize-none"
                                  placeholder="Mis: jam acara, estimasi tamu, request konsep...">{{ old('notes') }}</textarea>
                    </div>

                    <button type="submit"
                            class="flex items-center justify-center gap-2 w-full py-3 rounded-xl text-sm font-bold transition hover:opacity-90 bg-accent text-cream">
                        Kirim Booking
                    </button>
                </form>
            </div>
        </div>
    </div>

    @if(session('booking_success'))
        <div id="booking-success-modal"
             class="fixed inset-0 z-50 hidden items-center justify-center p-4">
            <div data-action="close-booking-success-modal" class="absolute inset-0 bg-black/50 backdrop-blur-sm"></div>
            <div class="relative bg-white rounded-3xl shadow-2xl w-full max-w-md overflow-hidden">
                <div class="p-6 pb-5 bg-cream">
                    <button type="button" data-action="close-booking-success-modal"
                            class="absolute top-4 right-4 w-8 h-8 rounded-full bg-white/60 hover:bg-white/80 flex items-center justify-center transition">
                        <svg class="w-4 h-4 text-dark" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                    <p class="text-xs font-bold uppercase tracking-widest text-gray-400 mb-1">Booking</p>
                    <p class="text-xl font-bold text-dark">Booking Berhasil</p>
                    <p class="text-xs text-gray-500 mt-1">{{ session('booking_success') }}</p>
                </div>
                <div class="p-6 space-y-3">
                    <a href="{{ $bookingPaymentUrl }}"
                       class="flex items-center justify-center gap-2 w-full py-3 rounded-xl text-sm font-bold transition hover:opacity-90 bg-accent text-cream">
                        Lanjut Pembayaran
                    </a>
                    <button type="button"
                            data-action="close-booking-success-modal"
                            class="w-full py-3 rounded-xl text-sm font-bold border border-gray-200 hover:bg-gray-50 transition text-dark">
                        Kembali Ke Vendor
                    </button>
                </div>
            </div>
        </div>
    @endif

    <script>
        function openBookingModal() {
            const modal = document.getElementById('booking-modal');
            if (!modal) return;
            if (typeof syncBookingPackage === 'function') {
                syncBookingPackage();
            }
            const pkgId = document.getElementById('booking-vendor-package-id')?.value ?? '';
            if (!pkgId) {
                openPkgPickerModal();
                return;
            }
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            document.body.style.overflow = 'hidden';
        }

        function closeBookingModal() {
            const modal = document.getElementById('booking-modal');
            if (!modal) return;
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            document.body.style.overflow = '';
        }

        function openBookingSuccessModal() {
            const modal = document.getElementById('booking-success-modal');
            if (!modal) return;
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            document.body.style.overflow = 'hidden';
        }

        function closeBookingSuccessModal() {
            const modal = document.getElementById('booking-success-modal');
            if (!modal) return;
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            document.body.style.overflow = '';
        }

        function showBookingWarning(message) {
            const el = document.getElementById('booking-warning');
            if (!el) return;
            el.textContent = message;
            el.classList.remove('hidden');
            window.clearTimeout(window.__bookingWarningTimer);
            window.__bookingWarningTimer = window.setTimeout(() => {
                el.classList.add('hidden');
            }, 4500);
        }

        function openBookingPage() {
            if (typeof isVendorOwnerBooking !== 'undefined' && isVendorOwnerBooking) {
                showBookingWarning('Anda tidak dapat melakukan booking pada vendor milik sendiri.');
                return;
            }
            let pkgId = selectedPackage?.id ?? '';
            if (!pkgId) {
                try {
                    const raw = localStorage.getItem(`bookingPkg:${vendorSlug}`);
                    if (raw) {
                        const parsed = JSON.parse(raw);
                        pkgId = parsed?.id ?? '';
                    }
                } catch {}
            }
            if (!pkgId) {
                openPkgPickerModal();
                return;
            }
            window.location.href = bookingVendorUrl + '?vendor_package_id=' + encodeURIComponent(String(pkgId));
        }

        document.addEventListener('keydown', e => {
            if (e.key === 'Escape') {
                closeBookingModal();
                closeBookingSuccessModal();
            }
        });

        document.addEventListener('DOMContentLoaded', () => {
            const isAuth = {{ auth()->check() ? 'true' : 'false' }};
            const shouldOpen = {{ $openBookingModal ? 'true' : 'false' }};
            const hashOpen = window.location.hash === '#booking';
            const hasBookingSuccess = {{ session()->has('booking_success') ? 'true' : 'false' }};
            if (isAuth && hasBookingSuccess) {
                openBookingSuccessModal();
                return;
            }
            if (isAuth && (shouldOpen || hashOpen)) {
                openBookingModal();
            }
        });
    </script>

    <script>
    // ── YouTube Video Modal ───────────────────────────────────
    function openVideoModal(url) {
        // Ekstrak video ID dari berbagai format URL YouTube
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
        document.getElementById('video-iframe').src = ''; // stop video
        document.body.style.overflow = '';
    }

    let selectedPackage = null;
    let pickerChosenPkg = null;
    const vendorSlug = '{{ $vendor->slug }}';
    const vendorPackages = @json($vendorPackagesJson);
    const waNumber = '{{ preg_replace('/[^0-9]/', '', $vendor->phone) }}';
    const vendorName = '{{ addslashes($vendor->name) }}';
    const bookingVendorUrl = '{{ route('booking.vendor', $vendor) }}';
    const isVendorOwnerBooking = {{ auth()->check() && (int) auth()->id() === (int) $vendor->owner_user_id ? 'true' : 'false' }};
    const bookingSuccess = {{ session()->has('booking_success') ? 'true' : 'false' }};
    if (bookingSuccess) {
        try {
            localStorage.removeItem(`bookingPkg:${vendorSlug}`);
        } catch {}
    }

    function syncBookingPackage() {
        const idEl = document.getElementById('booking-vendor-package-id');
        const labelEl = document.getElementById('booking-vendor-package-label');
        if (!idEl || !labelEl) return;

        if (!selectedPackage) {
            try {
                const raw = localStorage.getItem(`bookingPkg:${vendorSlug}`);
                if (raw) selectedPackage = JSON.parse(raw);
            } catch {}
        }

        if (selectedPackage?.id) {
            idEl.value = selectedPackage.id;
            const fmtPkgPrice = (pkg) => pkg.price > 0 ? ('Rp ' + parseInt(pkg.price).toLocaleString('id-ID')) : (pkg.price || '—');
            labelEl.value = `${selectedPackage.name} — ${fmtPkgPrice(selectedPackage)}`;
            return;
        }

        if (!idEl.value) {
            labelEl.value = 'Tanpa paket';
        }
    }

    function openPkgPickerModal() {
        const modal = document.getElementById('pkg-picker-modal');
        const list  = document.getElementById('pkg-picker-list');
        const btn   = document.getElementById('pkg-picker-confirm');
        if (!modal || !list) return;
        pickerChosenPkg = null;
        btn.disabled = true;
        btn.classList.add('opacity-40', 'cursor-not-allowed');
        list.innerHTML = vendorPackages.map(p => {
            const final = p.discount > 0 ? p.price - p.discount : p.price;
            const priceStr = final > 0 ? 'Rp ' + final.toLocaleString('id-ID') : '—';
            const origStr  = p.discount > 0 ? 'Rp ' + p.price.toLocaleString('id-ID') : '';
            return `<label class="pkg-picker-item flex items-center gap-3 p-3 rounded-xl border border-gray-100 cursor-pointer hover:border-accent/40 hover:bg-cream transition has-[:checked]:border-accent has-[:checked]:bg-cream">
                <input type="radio" name="pkg-pick" value="${p.id}" class="sr-only" data-pkg='${JSON.stringify(p)}'>
                <span class="w-4 h-4 rounded-full border-2 border-gray-300 flex-shrink-0 flex items-center justify-center pkg-radio-dot">
                    <span class="w-2 h-2 rounded-full bg-accent hidden"></span>
                </span>
                <span class="flex-1 min-w-0">
                    <span class="block text-sm font-semibold text-dark leading-tight">${p.name}</span>
                    <span class="block text-xs text-gray-400">${p.max_guests ? p.max_guests + ' Pax' : ''}</span>
                </span>
                <span class="text-right flex-shrink-0">
                    ${origStr ? `<span class="block text-[10px] text-gray-400 line-through">${origStr}</span>` : ''}
                    <span class="block text-sm font-bold text-accent">${priceStr}</span>
                </span>
            </label>`;
        }).join('');
        list.querySelectorAll('input[type=radio]').forEach(radio => {
            radio.addEventListener('change', () => {
                pickerChosenPkg = JSON.parse(radio.dataset.pkg);
                list.querySelectorAll('.pkg-radio-dot span').forEach(dot => dot.classList.add('hidden'));
                radio.closest('label').querySelector('.pkg-radio-dot span').classList.remove('hidden');
                btn.disabled = false;
                btn.classList.remove('opacity-40', 'cursor-not-allowed');
            });
        });
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        document.body.style.overflow = 'hidden';
    }

    function closePkgPickerModal() {
        const modal = document.getElementById('pkg-picker-modal');
        if (!modal) return;
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        document.body.style.overflow = '';
    }

    function confirmPkgPick() {
        if (!pickerChosenPkg) return;
        selectedPackage = { id: pickerChosenPkg.id, name: pickerChosenPkg.name, price: pickerChosenPkg.price };
        try {
            localStorage.setItem(`bookingPkg:${vendorSlug}`, JSON.stringify(selectedPackage));
        } catch {}
        closePkgPickerModal();
        openBookingPage();
    }

    // Close on Escape key
    document.addEventListener('keydown', e => {
        if (e.key === 'Escape') {
            closePkgPickerModal();
            closeVideoModal();
        }
    });

    document.addEventListener('click', function (e) {
        const el = e.target.closest('[data-action]');
        if (!el) return;
        const action = el.getAttribute('data-action');
        if (!action) return;

        if (action === 'open-video') {
            const url = el.getAttribute('data-video-url') || '';
            if (!url) return;
            openVideoModal(url);
            return;
        }

        if (action === 'close-video-modal') {
            closeVideoModal();
            return;
        }

        if (action === 'toggle-reviews' && typeof toggleReviews === 'function') {
            toggleReviews();
            return;
        }

        if (action === 'set-review-rating') {
            const val = parseInt(el.getAttribute('data-val') || '0', 10);
            if (Number.isFinite(val) && val >= 1 && val <= 5) setReviewRating(val);
            return;
        }

        if (action === 'submit-review') {
            submitReview();
            return;
        }

        if (action === 'open-booking-page') {
            openBookingPage();
            return;
        }

        if (action === 'share-vendor') {
            shareVendor();
            return;
        }

        if (action === 'close-booking-modal') {
            closeBookingModal();
            return;
        }

        if (action === 'close-booking-success-modal') {
            closeBookingSuccessModal();
            return;
        }

        if (action === 'close-pkg-picker') {
            closePkgPickerModal();
            return;
        }

        if (action === 'confirm-pkg-pick') {
            confirmPkgPick();
            return;
        }
    });
    </script>

    <script>
    // ── Review Submit ────────────────────────────────────────────────
    const REVIEW_STORE_URL = '{{ route('vendor.review.store', $vendor->slug) }}';
    const STAR_LABELS = ['', 'Sangat Buruk', 'Buruk', 'Cukup', 'Bagus', 'Luar Biasa'];

    function setReviewRating(val) {
        document.getElementById('review-rating').value = val;
        document.getElementById('star-label').textContent = STAR_LABELS[val];
        document.querySelectorAll('.star-btn svg').forEach((svg, i) => {
            svg.style.color = i < val ? '#f59e0b' : '#d1d5db';
        });
    }

    // Char counter
    const reviewBody = document.getElementById('review-body');
    if (reviewBody) {
        reviewBody.addEventListener('input', function () {
            document.getElementById('review-char').textContent = this.value.length;
        });
    }

    function submitReview() {
        const rating  = parseInt(document.getElementById('review-rating').value, 10);
        const body    = document.getElementById('review-body')?.value?.trim() ?? '';
        const fb      = document.getElementById('review-feedback');
        const btn     = document.getElementById('review-submit-btn');
        if (!fb || !btn) return;

        const showFeedback = (msg, isError) => {
            fb.textContent = msg;
            fb.className   = `mb-3 text-xs font-semibold px-3 py-2 rounded-lg ${isError ? 'bg-red-50 text-red-600' : 'bg-green-50 text-green-700'}`;
            fb.classList.remove('hidden');
        };

        if (rating < 1)          { showFeedback('Pilih rating bintang terlebih dahulu.', true); return; }
        if (body.length < 10)    { showFeedback('Ulasan minimal 10 karakter.', true); return; }

        btn.disabled    = true;
        btn.textContent = 'Mengirim...';

        fetch(REVIEW_STORE_URL, {
            method : 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content ?? '',
                'Accept'      : 'application/json',
            },
            body: JSON.stringify({ rating, body }),
        })
        .then(async r => {
            const contentType = r.headers.get('content-type') ?? '';
            if (contentType.includes('application/json')) {
                const data = await r.json();
                return { status: r.status, data, redirected: r.redirected, url: r.url };
            }
            const text = await r.text();
            return { status: r.status, data: null, text, redirected: r.redirected, url: r.url };
        })
        .then(({ status, data, redirected, url }) => {
            if (status === 201 && data?.message) {
                showFeedback(data.message, false);
                document.getElementById('review-body').value = '';
                document.getElementById('review-char').textContent = '0';
                document.getElementById('review-rating').value = '0';
                document.getElementById('star-label').textContent = 'Pilih rating';
                document.querySelectorAll('.star-btn svg').forEach(s => s.style.color = '#d1d5db');
                btn.textContent = 'Terkirim ✓';
                return;
            }

            if (status === 401 || redirected || (url && url.includes('/login'))) {
                showFeedback('Sesi login sudah berakhir. Silakan masuk lagi, lalu coba ulang.', true);
            } else if (status === 419) {
                showFeedback('Sesi halaman sudah kedaluwarsa. Silakan refresh halaman lalu coba lagi.', true);
            } else if (data?.message) {
                showFeedback(data.message, true);
            } else {
                showFeedback('Terjadi kesalahan. Coba lagi.', true);
            }

            btn.disabled    = false;
            btn.textContent = 'Kirim Ulasan';
        })
        .catch(() => {
            showFeedback('Gagal terhubung. Coba lagi.', true);
            btn.disabled    = false;
            btn.textContent = 'Kirim Ulasan';
        });
    }

    // ── Review Toggle ────────────────────────────────────────────────
    function toggleReviews() {
        const cards   = document.querySelectorAll('.review-card');
        const btn     = document.getElementById('review-toggle-btn');
        const text    = document.getElementById('review-toggle-text');
        const icon    = document.getElementById('review-toggle-icon');
        const isOpen  = btn.dataset.open === '1';

        cards.forEach((c, i) => {
            if (i >= 3) c.classList.toggle('hidden', isOpen);
        });

        if (isOpen) {
            text.textContent = 'Lihat Semua Ulasan ({{ $reviewTotal ?? $vendor->approvedReviews->count() }})';
            icon.style.transform = '';
            btn.dataset.open = '0';
        } else {
            text.textContent = 'Sembunyikan Ulasan';
            icon.style.transform = 'rotate(180deg)';
            btn.dataset.open = '1';
        }
    }
    </script>

    <script>
    const SHARE_TITLE = @json($vendor->name);
    const SHARE_URL = @json(request()->fullUrl());

    function showShareToast(message) {
        const existing = document.getElementById('share-toast');
        if (existing) existing.remove();

        const el = document.createElement('div');
        el.id = 'share-toast';
        el.textContent = message;
        el.style.position = 'fixed';
        el.style.left = '50%';
        el.style.bottom = '24px';
        el.style.transform = 'translateX(-50%)';
        el.style.background = 'rgba(17, 24, 39, 0.92)';
        el.style.color = '#fff';
        el.style.padding = '10px 14px';
        el.style.borderRadius = '12px';
        el.style.fontSize = '12px';
        el.style.fontWeight = '600';
        el.style.zIndex = '9999';
        el.style.maxWidth = '90vw';
        el.style.boxShadow = '0 10px 30px rgba(0,0,0,0.15)';
        document.body.appendChild(el);
        setTimeout(() => el.remove(), 2200);
    }

    async function shareVendor() {
        try {
            if (window.Capacitor?.isNativePlatform?.()) {
                const { Share } = await import('@capacitor/share');
                await Share.share({ title: SHARE_TITLE, url: SHARE_URL, dialogTitle: 'Bagikan vendor ini' });
                return;
            }
        } catch (_) {}

        try {
            if (navigator.share) {
                await navigator.share({ title: SHARE_TITLE, url: SHARE_URL });
                return;
            }
        } catch (_) {}

        try {
            if (navigator.clipboard && navigator.clipboard.writeText) {
                await navigator.clipboard.writeText(SHARE_URL);
                showShareToast('Link berhasil disalin');
                return;
            }
        } catch (_) {}

        const ta = document.createElement('textarea');
        ta.value = SHARE_URL;
        ta.setAttribute('readonly', '');
        ta.style.position = 'fixed';
        ta.style.left = '-9999px';
        document.body.appendChild(ta);
        ta.select();
        try { document.execCommand('copy'); } catch (_) {}
        ta.remove();
        showShareToast('Link berhasil disalin');
    }
    </script>

    <!-- Mobile Sticky Bar -->
    <div class="lg:hidden fixed bottom-0 left-0 right-0 z-40 bg-white border-t border-gray-100 px-4 py-3">
        <div class="flex items-center gap-2.5">
            <a href="{{ $vendorChatUrl }}"
               class="inline-flex h-11 w-11 shrink-0 items-center justify-center rounded-xl border border-gray-200 bg-white text-gray-600 hover:bg-gray-50 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                </svg>
            </a>
            <button type="button" data-action="open-booking-page"
                    class="inline-flex h-11 min-w-0 flex-1 items-center justify-center rounded-2xl bg-accent px-3.5 text-sm font-bold text-white shadow-sm whitespace-nowrap transition hover:opacity-90">
                Booking Sekarang
            </button>
        </div>
    </div>

    @include('layout.footer')

@endsection
