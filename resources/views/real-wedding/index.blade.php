@extends('layout.app')

@section('title', 'Real Wedding - Makna Wedding')

@section('body-class', 'bg-cream text-dark')

@section('content')
    @include('layout.header')

    @php
        $breadcrumbItems = [
            ['label' => 'Home', 'url' => route('home')],
            ['label' => 'Real Wedding', 'url' => null],
        ];
    @endphp

    <section class="pt-3 lg:py-8 bg-cream">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="pt-1 pb-4 lg:pt-4">
                <x-breadcrumb :items="$breadcrumbItems" />
            </div>
            <x-banner-ad mt="0" mb="1rem" />

            <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4 mb-5">
                <div>
                    <h1 class="text-xl sm:text-2xl font-extrabold text-dark">Real Wedding</h1>
                    <p class="text-sm text-gray-500 mt-1">Inspirasi pernikahan nyata dari pasangan-pasangan yang telah merayakan momen spesial mereka.</p>
                </div>
                <div class="flex items-center gap-2">
                    <form method="GET" action="{{ route('real-wedding.index') }}" class="flex items-center gap-2">
                        <div class="relative">
                            <input name="q"
                                   value="{{ $q ?? '' }}"
                                   placeholder="Cari nama pasangan / venue..."
                                   class="h-10 w-48 sm:w-60 rounded-xl border border-gray-200 pl-10 pr-3 text-sm bg-white text-dark hover:border-gray-300 transition focus:outline-none">
                            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35m1.35-5.15a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </div>
                        <select name="sort"
                                data-auto-submit
                                class="h-10 rounded-xl border border-gray-200 px-3 text-xs font-bold bg-white text-dark hover:border-gray-300 transition focus:outline-none">
                            <option value="terbaru" {{ ($sort ?? 'terbaru') === 'terbaru' ? 'selected' : '' }}>Terbaru</option>
                            <option value="terlama" {{ ($sort ?? 'terbaru') === 'terlama' ? 'selected' : '' }}>Terlama</option>
                            <option value="az"      {{ ($sort ?? 'terbaru') === 'az'      ? 'selected' : '' }}>A–Z</option>
                        </select>
                        <button type="submit"
                                class="h-10 px-4 rounded-xl text-xs font-bold bg-accent text-cream transition hover:opacity-90">
                            Cari
                        </button>
                    </form>
                </div>
            </div>

            @if($realWeddings->isEmpty())
                <div class="bg-white rounded-2xl border border-gray-100 p-10 text-center">
                    <p class="text-sm text-gray-500">Belum ada Real Wedding yang tersedia.</p>
                </div>
            @else
                <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-4">
                    @foreach($realWeddings as $rw)
                        @php
                            $rwImage = $rw->cover_image_url ?: 'https://picsum.photos/seed/rw-' . $rw->id . '/400/533';
                        @endphp
                        <a href="{{ route('real-wedding.show', $rw->slug) }}"
                           class="relative rounded-2xl overflow-hidden cursor-pointer group aspect-[3/4] block">
                            <img src="{{ $rwImage }}"
                                 alt="{{ $rw->couple_names }}"
                                 class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
                            <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/10 to-transparent"></div>
                            <div class="absolute bottom-4 left-4 right-4 text-white">
                                @if($rw->badge)
                                    <span class="inline-block text-[9px] border border-white/70 rounded-full px-2 py-0.5 mb-2 bg-black/20 backdrop-blur-sm">
                                        {{ $rw->badge }}
                                    </span>
                                @endif
                                <p class="font-bold text-sm leading-tight tracking-wide uppercase">{{ $rw->couple_names }}</p>
                                @if($rw->venue_name)
                                    <p class="text-[11px] opacity-70 mt-1">{{ $rw->venue_name }}</p>
                                @endif
                                @if($rw->wedding_date)
                                    <p class="text-[10px] opacity-60 mt-0.5">{{ $rw->wedding_date->translatedFormat('d F Y') }}</p>
                                @endif
                            </div>
                        </a>
                    @endforeach
                </div>

                <div class="mt-8">
                    {{ $realWeddings->links() }}
                </div>
            @endif
        </div>
    </section>

    <script>
        document.addEventListener('change', function (e) {
            var el = e.target.closest('[data-auto-submit]');
            if (!el) return;
            if (el.form) el.form.submit();
        });
    </script>

    <x-paket-section :packages="$otherPackages" title="Paket Pernikahan Lainnya" :more-url="route('store')" />

    @include('layout.footer')
@endsection
