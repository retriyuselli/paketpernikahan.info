@extends('layout.app')

@section('title', $realWedding->couple_names . ' - Real Wedding - Makna Wedding')

@section('body-class', 'bg-cream text-dark')

@section('content')
    @include('layout.header')

    @php
        $breadcrumbItems = [
            ['label' => 'Home', 'url' => route('home')],
            ['label' => 'Real Wedding', 'url' => route('real-wedding.index')],
            ['label' => $realWedding->couple_names, 'url' => null],
        ];
        $coverImage = $realWedding->cover_image_url ?: 'https://picsum.photos/seed/rw-' . $realWedding->id . '/1200/800';
    @endphp

    <section class="py-8 bg-cream">
        <x-ui.container>
            <div class="pt-4 pb-4">
                @include('layout.breadcrumb', ['items' => $breadcrumbItems])
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

                <!-- Main Content -->
                <div class="lg:col-span-8">

                    <!-- Header Card -->
                    <div class="bg-white rounded-2xl border border-gray-100 p-6">
                        <div class="flex items-start justify-between gap-4 mb-4">
                            <div>
                                @if($realWedding->badge)
                                    <span class="inline-block text-[10px] font-bold uppercase tracking-widest border border-accent/40 rounded-full px-3 py-0.5 mb-3 text-accent">
                                        {{ $realWedding->badge }}
                                    </span>
                                @endif
                                <h1 class="text-xl sm:text-2xl font-extrabold leading-tight text-dark">{{ $realWedding->title }}</h1>
                                <p class="text-sm font-bold text-accent mt-1 uppercase tracking-wide">{{ $realWedding->couple_names }}</p>
                                <div class="flex flex-wrap gap-3 mt-2 text-xs text-gray-500">
                                    @if($realWedding->wedding_date)
                                        <span>{{ $realWedding->wedding_date->translatedFormat('d F Y') }}</span>
                                    @endif
                                    @if($realWedding->venue_name)
                                        <span>· {{ $realWedding->venue_name }}</span>
                                    @endif
                                    <span>· {{ $realWedding->views_count }} views</span>
                                </div>
                            </div>
                            <a href="{{ route('real-wedding.index') }}"
                               class="flex-shrink-0 text-xs font-bold px-3 py-2 rounded-xl border border-gray-200 bg-white hover:border-gray-300 transition text-dark">
                                Kembali
                            </a>
                        </div>

                        <!-- Cover Image -->
                        <div class="rounded-xl overflow-hidden aspect-video">
                            <img src="{{ $coverImage }}" alt="{{ $realWedding->couple_names }}" class="w-full h-full object-cover">
                        </div>
                    </div>

                    <!-- Content -->
                    @if($realWedding->content)
                        @php
                            $wordCount = str_word_count(strip_tags($realWedding->content));
                            $readMinutes = max(1, (int) ceil($wordCount / 200));
                        @endphp
                        <div class="mt-5 bg-white rounded-2xl border border-gray-100 overflow-hidden">

                            {{-- Content Header Bar --}}
                            <div class="flex items-center gap-3 px-6 py-4 border-b border-gray-100 bg-light-sage/20">
                                <div class="w-1 h-5 rounded-full bg-accent flex-shrink-0"></div>
                                <span class="text-xs font-bold uppercase tracking-widest text-gray-400">Cerita Pernikahan</span>
                                <span class="ml-auto text-[11px] text-gray-400 flex items-center gap-1">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    {{ $readMinutes }} menit baca
                                </span>
                            </div>

                            {{-- Highlight Strip (tanggal & venue) --}}
                            @if($realWedding->wedding_date || $realWedding->venue_name)
                            <div class="flex flex-wrap gap-4 px-6 py-4 border-b border-dashed border-gray-100 bg-cream/60">
                                @if($realWedding->wedding_date)
                                <div class="flex items-center gap-2 text-xs text-gray-500">
                                    <svg class="w-4 h-4 text-accent flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                    <span class="font-semibold text-dark">{{ $realWedding->wedding_date->translatedFormat('d F Y') }}</span>
                                </div>
                                @endif
                                @if($realWedding->venue_name)
                                <div class="flex items-center gap-2 text-xs text-gray-500">
                                    <svg class="w-4 h-4 text-accent flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                    <span class="font-semibold text-dark">{{ $realWedding->venue_name }}</span>
                                </div>
                                @endif
                            </div>
                            @endif

                            {{-- Article Body --}}
                            <div class="px-6 py-6">
                                <div class="blog-content">
                                    {!! $realWedding->content !!}
                                </div>
                            </div>

                            {{-- Footer strip --}}
                            <div class="px-6 py-4 border-t border-gray-100 bg-cream/40 flex items-center justify-between gap-4">
                                <p class="text-xs text-gray-400 italic">"{{ $realWedding->couple_names }}"</p>
                                <span class="text-[11px] text-gray-400">{{ $realWedding->views_count }} views</span>
                            </div>
                        </div>
                    @endif

                </div>

                <!-- Sidebar -->
                <div class="lg:col-span-4 space-y-5">

                    <!-- Vendors Involved -->
                    @if($realWedding->vendors->isNotEmpty() || $realWedding->vendorPackages->isNotEmpty())
                        <div class="bg-white rounded-2xl border border-gray-100 p-5">
                            <h3 class="text-xs font-extrabold uppercase tracking-widest text-center text-gray-400 border-b border-gray-100 pb-3 mb-4">
                                Vendors Terkait
                            </h3>
                            <div class="space-y-4">
                                @foreach($realWedding->vendors as $vendor)
                                    <a href="{{ route('vendor.detail', $vendor->slug) }}"
                                       class="flex items-center gap-3 hover:opacity-80 transition">
                                        @php
                                            $vLogo = $vendor->cover_image_url ?? null;
                                        @endphp
                                        @if($vLogo)
                                            <img src="{{ $vLogo }}" alt="{{ $vendor->name }}"
                                                 class="w-16 h-16 object-cover rounded-xl flex-shrink-0">
                                        @else
                                            <div class="w-16 h-16 rounded-xl bg-gray-100 flex items-center justify-center flex-shrink-0">
                                                <svg class="w-6 h-6 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                </svg>
                                            </div>
                                        @endif
                                        <div>
                                            <p class="text-[10px] font-extrabold uppercase tracking-widest text-accent">
                                                {{ $vendor->categoryVendor?->name ?? $vendor->category }}
                                            </p>
                                            <p class="text-sm font-bold text-dark">{{ $vendor->name }}</p>
                                        </div>
                                    </a>
                                @endforeach

                                @foreach($realWedding->vendorPackages as $pkg)
                                    <a href="{{ route('store.package.show', $pkg) }}"
                                       class="flex items-center gap-3 hover:opacity-80 transition">
                                        @php
                                            $pkgImg = $pkg->image_url ?? ($pkg->vendor->cover_image_url ?? null);
                                        @endphp
                                        @if($pkgImg)
                                            <img src="{{ $pkgImg }}" alt="{{ $pkg->name }}"
                                                 class="w-16 h-16 object-cover rounded-xl flex-shrink-0">
                                        @else
                                            <div class="w-16 h-16 rounded-xl bg-gray-100 flex items-center justify-center flex-shrink-0">
                                                <svg class="w-6 h-6 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10"/>
                                                </svg>
                                            </div>
                                        @endif
                                        <div>
                                            <p class="text-[10px] font-extrabold uppercase tracking-widest text-accent">
                                                {{ $pkg->vendor?->categoryVendor?->name ?? 'Paket' }}
                                            </p>
                                            <p class="text-sm font-bold text-dark">{{ $pkg->name }}</p>
                                            @if($pkg->vendor)
                                                <p class="text-[11px] text-gray-400">{{ $pkg->vendor->name }}</p>
                                            @endif
                                        </div>
                                    </a>
                                @endforeach
                            </div>

                            @if($realWedding->venue_name)
                                <p class="text-[11px] text-gray-400 mt-4 pt-3 border-t border-gray-100">
                                    Venue: {{ $realWedding->venue_name }}
                                </p>
                            @endif
                        </div>
                    @endif

                    <!-- Related -->
                    @if($related->isNotEmpty())
                        <div class="bg-white rounded-2xl border border-gray-100 p-5">
                            <h3 class="text-xs font-extrabold uppercase tracking-widest text-gray-400 border-b border-gray-100 pb-3 mb-4">
                                Real Wedding Lainnya
                            </h3>
                            <div class="space-y-3">
                                @foreach($related as $rw)
                                    @php
                                        $rwThumb = $rw->cover_image_url ?: 'https://picsum.photos/seed/rw-' . $rw->id . '/200/200';
                                    @endphp
                                    <a href="{{ route('real-wedding.show', $rw->slug) }}"
                                       class="flex items-center gap-3 hover:opacity-80 transition">
                                        <img src="{{ $rwThumb }}" alt="{{ $rw->couple_names }}"
                                             class="w-14 h-14 object-cover rounded-xl flex-shrink-0">
                                        <div>
                                            <p class="text-xs font-bold text-dark uppercase">{{ $rw->couple_names }}</p>
                                            @if($rw->venue_name)
                                                <p class="text-[11px] text-gray-400">{{ $rw->venue_name }}</p>
                                            @endif
                                            @if($rw->wedding_date)
                                                <p class="text-[10px] text-gray-300">{{ $rw->wedding_date->translatedFormat('d M Y') }}</p>
                                            @endif
                                        </div>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @endif

                </div>
            </div>
        </x-ui.container>
    </section>

    @include('layout.footer')
@endsection
