@extends('layout.app')

@section('title', $q ? 'Hasil Pencarian "' . $q . '" - Makna Wedding' : 'Pencarian - Makna Wedding')

@section('body-class', 'bg-cream text-dark')

@section('content')
    @include('layout.header')

    @php
        $defaultSearchTab = $packages->count() > 0 || $vendors->isEmpty() ? 'packages' : 'vendors';
    @endphp

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

        {{-- Search Bar --}}
        <form method="GET" action="{{ route('search') }}" class="hidden sm:flex items-center gap-2 mb-8 max-w-xl">
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

            <div class="mb-5 flex justify-center">
                <div class="inline-flex w-full max-w-xs overflow-hidden rounded-full border border-gray-200 bg-white p-1">
                    <button type="button"
                            class="search-tab-button flex-1 rounded-full px-4 py-2 text-sm font-semibold transition"
                            data-search-tab="packages"
                            aria-controls="search-tab-packages"
                            aria-selected="{{ $defaultSearchTab === 'packages' ? 'true' : 'false' }}">
                        Paket
                    </button>
                    <button type="button"
                            class="search-tab-button flex-1 rounded-full px-4 py-2 text-sm font-semibold transition"
                            data-search-tab="vendors"
                            aria-controls="search-tab-vendors"
                            aria-selected="{{ $defaultSearchTab === 'vendors' ? 'true' : 'false' }}">
                        Vendor
                    </button>
                </div>
            </div>

            {{-- ===== PACKAGE RESULTS ===== --}}
            <section id="search-tab-packages" class="search-tab-panel {{ $defaultSearchTab !== 'packages' ? 'hidden' : '' }}">
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
                            $price = (int) ($pkg->price ?? 0);
                            $discount = (int) ($pkg->discount ?? 0);
                            $cover = $pkg->image_url ?? null;
                            if (!$cover) {
                                $cover = $vendor->cover_image_url ?: null;
                                if (!$cover && is_array($vendor->cover_image ?? null) && count($vendor->cover_image) > 0) {
                                    $cover = $vendor->cover_image[0];
                                }
                            }
                            $items = $pkg->items;
                            $primaryBenefit = $discount > 0 ? 'Harga Diskon' : 'Paket Pilihan';
                            $secondaryBenefit = !empty($items[0]) ? \Illuminate\Support\Str::limit($items[0], 16) : 'Gratis Konsultasi';
                        @endphp
                        <x-package-card
                            :href="route('store.package.show', $pkg)"
                            :name="$pkg->name"
                            :image="$cover"
                            :price="$price"
                            :discount="$discount"
                            :vendor-name="$vendor->name"
                            :location="$vendor->city ?? 'Indonesia'"
                            :rating="$vendor?->rating"
                            :benefit-primary="$primaryBenefit"
                            :benefit-secondary="$secondaryBenefit"
                            width-class="w-full"
                        />
                        @endforeach
                    </div>
                @endif
            </section>

            {{-- ===== VENDOR RESULTS ===== --}}
            <section id="search-tab-vendors" class="search-tab-panel {{ $defaultSearchTab !== 'vendors' ? 'hidden' : '' }}">
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
                            $vData = [
                                'name' => $v->name,
                                'city' => $v->city,
                                'location' => $v->location,
                                'rating' => $v->rating,
                                'likes' => $v->likes,
                                'comments_count' => $v->comments_count,
                                'description' => $v->description,
                                'cover' => $v->cover_image_url ?: (optional($v->galleries->first())->image_url ?? 'https://picsum.photos/seed/' . $v->id . '/800/600'),
                                'detail_url' => route('vendor.detail', $v->slug),
                                'wa_url' => 'https://wa.me/' . preg_replace('/[^0-9]/', '', $v->phone ?? ''),
                                'pkg_price' => optional($v->cheapestPackage)->price,
                                'pkg_discount' => optional($v->cheapestPackage)->discount ?? 0,
                                'pkg_name' => optional($v->cheapestPackage)->name,
                                'price_start' => is_numeric($v->price_start) ? 'Rp ' . number_format((int) $v->price_start, 0, ',', '.') : ($v->price_start ?: '—'),
                            ];
                        @endphp
                        <a href="{{ route('vendor.detail', $v->slug) }}" class="block">
                            <x-vendor-card
                                :vendor="$v"
                                :vendor-data="$vData"
                                width-class="w-full"
                            />
                        </a>
                        @endforeach
                    </div>
                @endif
            </section>
        @endif
    </div>

    @include('layout.footer')

    <script>
    document.addEventListener('DOMContentLoaded', function () {
        const tabButtons = Array.from(document.querySelectorAll('.search-tab-button'));
        const tabPanels = Array.from(document.querySelectorAll('.search-tab-panel'));

        if (!tabButtons.length || !tabPanels.length) {
            return;
        }

        function setActiveTab(target) {
            tabButtons.forEach(function (button) {
                const isActive = button.getAttribute('data-search-tab') === target;
                button.classList.toggle('bg-accent', isActive);
                button.classList.toggle('text-white', isActive);
                button.classList.toggle('text-gray-500', !isActive);
                button.setAttribute('aria-selected', isActive ? 'true' : 'false');
            });

            tabPanels.forEach(function (panel) {
                panel.classList.toggle('hidden', panel.id !== 'search-tab-' + target);
            });
        }

        tabButtons.forEach(function (button) {
            button.addEventListener('click', function () {
                setActiveTab(button.getAttribute('data-search-tab'));
            });
        });

        setActiveTab(@json($defaultSearchTab));
    });
    </script>
@endsection
