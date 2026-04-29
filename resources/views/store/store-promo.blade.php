@extends('layout.app')

@section('title', 'Promo - Store - Makna Wedding')

@section('body-class', 'bg-cream text-dark')

@section('content')
    @include('layout.header')

    @php
        $breadcrumbItems = [
            ['label' => 'Home', 'url' => route('home')],
            ['label' => 'Store', 'url' => route('store')],
            ['label' => 'Promo', 'url' => null],
        ];
    @endphp

    <section class="py-8 bg-cream">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="pt-4 pb-4">
                @include('layout.breadcrumb', ['items' => $breadcrumbItems])
            </div>

            <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4 mb-5">
                <div>
                    <h1 class="text-xl sm:text-2xl font-extrabold text-dark">Promo</h1>
                    <p class="text-sm text-gray-500 mt-1">Paket pernikahan dengan harga spesial dan promo terbaik.</p>
                </div>
                <div class="flex items-center gap-2">
                    <form method="GET" action="{{ route('store.promo') }}" class="flex items-center gap-2">
                        <div class="relative">
                            <input name="q"
                                   value="{{ $q ?? '' }}"
                                   placeholder="Cari paket / vendor..."
                                   class="h-10 w-48 sm:w-60 rounded-xl border border-gray-200 pl-10 pr-3 text-sm bg-white text-dark hover:border-gray-300 transition focus:outline-none">
                            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35m1.35-5.15a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </div>
                        <select name="sort"
                                data-auto-submit
                                class="h-10 rounded-xl border border-gray-200 px-3 text-xs font-bold bg-white text-dark hover:border-gray-300 transition focus:outline-none">
                            <option value="diskon" {{ ($sort ?? 'diskon') === 'diskon' ? 'selected' : '' }}>Promo Terbesar</option>
                            <option value="termurah" {{ ($sort ?? 'diskon') === 'termurah' ? 'selected' : '' }}>Termurah</option>
                            <option value="termahal" {{ ($sort ?? 'diskon') === 'termahal' ? 'selected' : '' }}>Termahal</option>
                            <option value="terbaru" {{ ($sort ?? 'diskon') === 'terbaru' ? 'selected' : '' }}>Terbaru</option>
                        </select>
                        <button type="submit"
                                class="h-10 px-4 rounded-xl text-xs font-bold bg-accent text-cream transition hover:opacity-90">
                            Cari
                        </button>
                    </form>
                </div>
            </div>

            @if($packages->isEmpty())
                <div class="bg-white rounded-2xl border border-gray-100 p-10 text-center">
                    <p class="text-sm text-gray-500">Belum ada paket promo saat ini.</p>
                </div>
            @else
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
                    @foreach($packages as $pkg)
                        @php
                            $vendor = $pkg->vendor;
                            $price = (int) ($pkg->price ?? 0);
                            $discount = (int) ($pkg->discount ?? 0);
                            $final = max($price - $discount, 0);
                            $cover = $pkg->image_url;
                            if (!$cover && $vendor && is_array($vendor->cover_image ?? null) && count($vendor->cover_image) > 0) {
                                $cover = $vendor->cover_image[0];
                            }
                            $cover = $cover ?: 'https://picsum.photos/seed/store-promo-' . $pkg->id . '/800/600';
                        @endphp

                        <a href="{{ route('store.package.show', $pkg) }}"
                           class="bg-white rounded-2xl border border-gray-100 overflow-hidden hover:border-gray-200 hover:shadow-sm transition">
                            <div class="relative ar-4x5">
                                <img src="{{ $cover }}" alt="{{ $pkg->name }}" class="w-full h-full object-cover">
                                <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/30 to-transparent"></div>
                                <span class="absolute top-3 left-3 text-[10px] font-bold px-2 py-1 rounded-full bg-white/90 border border-gray-200 text-dark">
                                    Promo
                                </span>
                                <div class="absolute bottom-0 left-0 right-0 p-3">
                                    <p class="text-white text-xs font-bold leading-snug">{{ $pkg->name }}</p>
                                    <p class="text-white/80 text-[10px] mt-1">{{ $vendor?->name }}</p>
                                </div>
                            </div>
                            <div class="p-4">
                                <p class="text-[10px] text-gray-400 truncate">{{ $vendor?->city }} · {{ $vendor?->location }}</p>
                                <p class="text-[11px] text-gray-400 line-through mt-1">Rp {{ number_format($price, 0, ',', '.') }}</p>
                                <p class="text-sm font-extrabold leading-tight text-accent">Rp {{ number_format($final, 0, ',', '.') }}</p>
                                <p class="text-[10px] text-gray-400 truncate">{{ $vendor?->name }}</p>
                            </div>
                        </a>
                    @endforeach
                </div>

                <div class="mt-8">
                    {{ $packages->links() }}
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

    @include('layout.footer')
@endsection
