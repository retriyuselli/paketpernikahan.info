@extends('layout.app')

@php
    $blogCoverImage  = $blog->cover_image_url ?: 'https://picsum.photos/seed/blog-' . $blog->id . '/1200/630';
    $blogMetaDesc    = \Illuminate\Support\Str::limit(strip_tags((string) ($blog->excerpt ?? $blog->content ?? '')), 155, '');

    $articleSchema = [
        '@context'         => 'https://schema.org',
        '@type'            => 'BlogPosting',
        'headline'         => $blog->title,
        'description'      => $blogMetaDesc,
        'image'            => [$blogCoverImage],
        'url'              => route('blog.show', $blog->slug),
        'datePublished'    => optional($blog->published_at)->toIso8601String(),
        'dateModified'     => optional($blog->updated_at)->toIso8601String(),
        'inLanguage'       => 'id-ID',
        'mainEntityOfPage' => [
            '@type' => 'WebPage',
            '@id'   => route('blog.show', $blog->slug),
        ],
        'publisher' => [
            '@type' => 'Organization',
            'name'  => 'Makna Wedding & Event Planner',
            'url'   => url('/'),
            'logo'  => [
                '@type' => 'ImageObject',
                'url'   => url(config('app.logo_url')),
            ],
        ],
        'author' => $blog->author
            ? ['@type' => 'Person',       'name' => $blog->author->name]
            : ['@type' => 'Organization', 'name' => 'Makna Wedding & Event Planner'],
    ];

    if ($blog->category) {
        $articleSchema['articleSection'] = $blog->category;
    }
    if (!empty($blog->tags)) {
        $articleSchema['keywords'] = implode(', ', (array) $blog->tags);
    }
@endphp

@section('title', $blog->title . ' | Makna Wedding')
@section('meta-description', $blogMetaDesc)
@section('meta-image', $blogCoverImage)
@section('meta-type', 'article')
@section('canonical-url', route('blog.show', $blog->slug))

@section('extra-head')
<script type="application/ld+json">{!! json_encode($articleSchema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) !!}</script>
@endsection

@section('body-class', 'bg-cream text-dark')

@section('content')
    @include('layout.header')

    @php
        $breadcrumbItems = [
            ['label' => 'Home', 'url' => route('home')],
            ['label' => 'Blog', 'url' => route('blog.index')],
            ['label' => $blog->title, 'url' => null],
        ];
        $coverImage = $blogCoverImage;
    @endphp

    <section class="pt-3 lg:pt-3 lg:pb-8 bg-cream">
        <x-ui.container>
            <div class="pt-1 pb-4 lg:pt-1">
                <x-breadcrumb :items="$breadcrumbItems" />
            </div>
            <x-banner-ad mt="0" mb="1rem" />

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

                <!-- Main Content -->
                <div class="lg:col-span-8">

                    <!-- Header Card -->
                    <div class="bg-white rounded-2xl border border-gray-100 p-6">
                        <div class="flex items-start justify-between gap-4 mb-4">
                            <div class="flex-1">
                                @if($blog->category)
                                    <span class="inline-block text-[10px] font-bold uppercase tracking-widest border border-accent/40 rounded-full px-3 py-0.5 mb-3 text-accent">
                                        {{ $blog->category }}
                                    </span>
                                @endif
                                <h1 class="text-xl sm:text-2xl font-extrabold leading-tight text-dark">{{ $blog->title }}</h1>
                                <div class="flex flex-wrap gap-3 mt-2 text-xs text-gray-500">
                                    @if($blog->author)
                                        <span>Oleh <span class="font-semibold text-dark">{{ $blog->author->name }}</span></span>
                                    @endif
                                    @if($blog->published_at)
                                        <span>· {{ $blog->published_at->translatedFormat('d F Y') }}</span>
                                    @endif
                                    <span>· {{ number_format($blog->views_count) }} views</span>
                                </div>
                                @if($blog->excerpt)
                                    <p class="mt-3 text-sm text-gray-500 leading-relaxed">{{ $blog->excerpt }}</p>
                                @endif
                            </div>
                            <a href="{{ route('blog.index') }}"
                               class="shrink-0 text-xs font-bold px-3 py-2 rounded-xl border border-gray-200 bg-white hover:border-gray-300 transition text-dark">
                                Kembali
                            </a>
                        </div>

                        <!-- Cover Image -->
                        <div class="rounded-xl overflow-hidden">
                            <img src="{{ $coverImage }}" alt="{{ $blog->title }}" class="w-full object-cover max-h-[420px]">
                        </div>
                    </div>

                    <!-- Content -->
                    @if($blog->content)
                        <div class="mt-5 bg-white rounded-2xl border border-gray-100 overflow-hidden">

                            <!-- Content Header Bar -->
                            <div class="flex items-center gap-3 px-6 py-4 border-b border-gray-100 bg-light-sage/20">
                                <div class="w-1 h-5 rounded-full bg-accent shrink-0"></div>
                                <span class="text-xs font-bold uppercase tracking-widest text-gray-400">Isi Artikel</span>
                                @php
                                    $wordCount = str_word_count(strip_tags($blog->content));
                                    $readMinutes = max(1, (int) ceil($wordCount / 200));
                                @endphp
                                <span class="ml-auto text-[11px] text-gray-400 flex items-center gap-1">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    {{ $readMinutes }} menit baca
                                </span>
                            </div>

                            <div class="px-6 py-6">
                                <div class="blog-content">
                                    {!! $blog->content !!}
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Tags -->
                    @if(is_array($blog->tags) && count($blog->tags) > 0)
                        <div class="mt-5 bg-white rounded-2xl border border-gray-100 p-5">
                            <p class="text-xs font-bold uppercase tracking-widest text-gray-400 mb-3">Tags</p>
                            <div class="flex flex-wrap gap-2">
                                @foreach($blog->tags as $tag)
                                    <span class="text-xs px-3 py-1 rounded-full bg-light-sage/50 text-dark font-medium"># {{ $tag }}</span>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- Contextual CTA -->
                    @php
                        $tags = collect($blog->tags ?? []);
                        $ctaLinks = [];

                        $tagMap = [
                            'wedding organizer palembang' => ['label' => 'Cari Wedding Organizer Palembang', 'url' => route('store.category', 'wedding-organizer'), 'icon' => 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z'],
                            'wo palembang'                => ['label' => 'Cari Wedding Organizer Palembang', 'url' => route('store.category', 'wedding-organizer'), 'icon' => 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z'],
                            'fotografer pernikahan palembang' => ['label' => 'Lihat Fotografer di Palembang', 'url' => route('store.category', 'fotografer'), 'icon' => 'M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z M15 13a3 3 0 11-6 0 3 3 0 016 0z'],
                            'foto pernikahan palembang'   => ['label' => 'Lihat Fotografer di Palembang', 'url' => route('store.category', 'fotografer'), 'icon' => 'M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z M15 13a3 3 0 11-6 0 3 3 0 016 0z'],
                            'gedung pernikahan palembang' => ['label' => 'Lihat Paket Gedung di Palembang', 'url' => route('store.city', 'Palembang'), 'icon' => 'M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4'],
                            'harga paket pernikahan palembang' => ['label' => 'Bandingkan Paket Pernikahan', 'url' => route('store'), 'icon' => 'M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z'],
                            'katering'                    => ['label' => 'Lihat Paket Katering', 'url' => route('store.category', 'katering'), 'icon' => 'M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z'],
                            'dekorasi'                    => ['label' => 'Lihat Paket Dekorasi', 'url' => route('store.category', 'dekorasi'), 'icon' => 'M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z'],
                            'venue'                       => ['label' => 'Cari Venue Pernikahan', 'url' => route('store.city', 'Palembang'), 'icon' => 'M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z M15 11a3 3 0 11-6 0 3 3 0 016 0z'],
                        ];

                        foreach ($tagMap as $keyword => $link) {
                            if ($tags->contains(fn($t) => str_contains(strtolower($t), $keyword))) {
                                $ctaLinks[] = $link;
                            }
                        }

                        // Default CTA jika tidak ada tag yang cocok
                        if (empty($ctaLinks)) {
                            $ctaLinks[] = ['label' => 'Lihat Semua Paket Pernikahan', 'url' => route('store'), 'icon' => 'M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z'];
                        }

                        // Selalu tambahkan link promo
                        $ctaLinks[] = ['label' => 'Cek Promo Pernikahan', 'url' => route('store.promo'), 'icon' => 'M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z'];
                        $ctaLinks = array_slice($ctaLinks, 0, 3);
                    @endphp
                    <div class="mt-5 bg-accent/5 rounded-2xl border border-accent/20 p-5">
                        <p class="text-xs font-bold uppercase tracking-widest text-accent mb-3">Temukan di Makna Wedding</p>
                        <div class="flex flex-col gap-2">
                            @foreach($ctaLinks as $cta)
                            <a href="{{ $cta['url'] }}"
                               class="flex items-center gap-3 text-sm font-semibold text-dark hover:text-accent transition group">
                                <span class="w-8 h-8 rounded-xl bg-white border border-gray-100 flex items-center justify-center shrink-0 group-hover:border-accent/30 transition">
                                    <svg class="w-4 h-4 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $cta['icon'] }}"/>
                                    </svg>
                                </span>
                                {{ $cta['label'] }}
                                <svg class="w-3.5 h-3.5 ml-auto opacity-40 group-hover:opacity-100 group-hover:translate-x-0.5 transition-all" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                            </a>
                            @endforeach
                        </div>
                    </div>

                    <!-- Related Articles -->
                    @if($related->isNotEmpty())
                        <div class="mt-8">
                            <h2 class="text-base font-bold text-dark mb-4">Artikel Terkait</h2>
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                                @foreach($related as $rel)
                                    @php $relImage = $rel->cover_image_url ?: 'https://picsum.photos/seed/blog-' . $rel->id . '/400/250'; @endphp
                                    <a href="{{ route('blog.show', $rel->slug) }}"
                                       class="bg-white rounded-2xl overflow-hidden hover:shadow-md transition group block border border-gray-100">
                                        <div class="overflow-hidden">
                                            <img src="{{ $relImage }}" alt="{{ $rel->title }}" loading="lazy" class="w-full h-36 object-cover transition-transform duration-500 group-hover:scale-105">
                                        </div>
                                        <div class="p-3">
                                            @if($rel->category)
                                                <span class="text-[10px] font-bold text-accent">{{ $rel->category }}</span>
                                            @endif
                                            <p class="text-xs font-bold text-gray-900 leading-snug mt-1 group-hover:underline">{{ $rel->title }}</p>
                                        </div>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @endif

                </div>

                <!-- Sidebar -->
                <div class="lg:col-span-4 space-y-5">

                    <!-- Popular Articles -->
                    @if($popularBlogs->isNotEmpty())
                        <div class="bg-white rounded-2xl border border-gray-100 p-5">
                            <h3 class="text-xs font-extrabold uppercase tracking-widest text-gray-400 border-b border-gray-100 pb-3 mb-4">Artikel Terpopuler</h3>
                            <div class="flex flex-col gap-4">
                                @foreach($popularBlogs as $idx => $pop)
                                    @php $popImage = $pop->cover_image_url ?: 'https://picsum.photos/seed/popular-' . $pop->id . '/160/120'; @endphp
                                    @if($idx > 0)
                                        <div class="border-t border-gray-100"></div>
                                    @endif
                                    <a href="{{ route('blog.show', $pop->slug) }}" class="flex gap-3 group">
                                        <img src="{{ $popImage }}" alt="{{ $pop->title }}" loading="lazy" class="w-16 h-12 rounded-xl object-cover shrink-0">
                                        <div>
                                            @if($pop->category)
                                                <p class="text-[10px] font-bold text-accent">{{ $pop->category }} <span class="text-gray-400 font-normal">· {{ number_format($pop->views_count) }} views</span></p>
                                            @endif
                                            <p class="text-xs font-semibold text-gray-800 leading-snug group-hover:underline">{{ $pop->title }}</p>
                                        </div>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- Featured Packages -->
                    @if(!empty($featuredPackages) && $featuredPackages->isNotEmpty())
                        <div class="bg-white rounded-2xl border border-gray-100 p-5">
                            <h3 class="text-xs font-extrabold uppercase tracking-widest text-gray-400 border-b border-gray-100 pb-3 mb-4">Paket Unggulan</h3>
                            <div class="flex flex-col gap-4">
                                @foreach($featuredPackages as $idx => $pkg)
                                    @php
                                        $pkgPrice = (int) ($pkg->price ?? 0);
                                        $pkgDiscount = (int) ($pkg->discount ?? 0);
                                        $pkgFinal = max($pkgPrice - $pkgDiscount, 0);
                                        $pkgImg = $pkg->image_url ?? ($pkg->vendor->cover_image_url ?? null);
                                    @endphp
                                    @if($idx > 0)<div class="border-t border-gray-100"></div>@endif
                                    <a href="{{ route('store.package.show', $pkg) }}" class="flex gap-3 group">
                                        @if($pkgImg)
                                            <img src="{{ $pkgImg }}"
                                                 alt="{{ $pkg->name }} oleh {{ $pkg->vendor->name ?? '' }}"
                                                 class="w-16 h-14 rounded-xl object-cover shrink-0">
                                        @else
                                            <div class="w-16 h-14 rounded-xl bg-accent/10 flex items-center justify-center shrink-0">
                                                <svg class="w-5 h-5 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                                                </svg>
                                            </div>
                                        @endif
                                        <div class="min-w-0">
                                            <p class="text-xs font-bold text-dark leading-snug group-hover:underline truncate">{{ $pkg->name }}</p>
                                            <p class="text-[10px] text-gray-400 mt-0.5 truncate">{{ $pkg->vendor->name ?? '' }}{{ $pkg->vendor->city ? ' · ' . $pkg->vendor->city : '' }}</p>
                                            <p class="text-xs font-bold text-accent mt-1">Rp {{ number_format($pkgFinal ?: $pkgPrice, 0, ',', '.') }}</p>
                                        </div>
                                    </a>
                                @endforeach
                            </div>
                            <a href="{{ route('store') }}" class="mt-4 flex items-center justify-center gap-1 text-xs font-bold text-accent hover:underline">
                                Lihat Semua Paket
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                            </a>
                        </div>
                    @endif

                    <!-- Back to Blog -->
                    <a href="{{ route('blog.index') }}"
                       class="flex items-center justify-center gap-2 w-full py-3 rounded-2xl border border-gray-200 bg-white hover:border-accent hover:text-accent transition text-sm font-semibold text-dark">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                        </svg>
                        Lihat Semua Blog
                    </a>

                </div>

            </div>
        </x-ui.container>
    </section>

    @include('layout.footer')
@endsection
