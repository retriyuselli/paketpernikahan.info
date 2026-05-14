@extends('layout.app')

@section('title', $city . ' - Store - Makna Wedding')

@section('body-class', 'bg-cream text-dark')

@section('content')
    @include('layout.header')

    @php
        $breadcrumbItems = [
            ['label' => 'Home', 'url' => route('home')],
            ['label' => 'Store', 'url' => route('store')],
            ['label' => $city, 'url' => null],
        ];
    @endphp

    <section class="pt-3 lg:py-8 bg-cream">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="pt-1 pb-4 lg:pt-4">
                @include('layout.breadcrumb', ['items' => $breadcrumbItems])
            </div>

            <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4 mb-5">
                <div>
                    <h1 class="text-xl sm:text-2xl font-extrabold text-dark">Paket Pernikahan di {{ $city }}</h1>
                </div>
                <div class="flex items-center gap-2">
                    <form method="GET" action="{{ route('store.city', $city) }}" class="flex items-center gap-2">
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
                            <option value="rekomendasi" {{ ($sort ?? 'rekomendasi') === 'rekomendasi' ? 'selected' : '' }}>Rekomendasi</option>
                            <option value="termurah" {{ ($sort ?? 'rekomendasi') === 'termurah' ? 'selected' : '' }}>Termurah</option>
                            <option value="termahal" {{ ($sort ?? 'rekomendasi') === 'termahal' ? 'selected' : '' }}>Termahal</option>
                            <option value="terbaru" {{ ($sort ?? 'rekomendasi') === 'terbaru' ? 'selected' : '' }}>Terbaru</option>
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
                    <p class="text-sm text-gray-500">Belum ada paket untuk kota ini.</p>
                </div>
            @else
                <div class="grid grid-cols-2 lg:grid-cols-5 gap-2 lg:gap-3">
                    @foreach($packages as $pkg)
                        @php
                            $vendor = $pkg->vendor;
                            $price = (int) ($pkg->price ?? 0);
                            $discount = (int) ($pkg->discount ?? 0);
                            $cover = null;
                            if ($vendor && is_array($vendor->cover_image ?? null) && count($vendor->cover_image) > 0) {
                                $cover = $vendor->cover_image[0];
                            }
                            $cover = $cover ?: 'https://picsum.photos/seed/store-city-' . $pkg->id . '/800/600';
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
                            :vendor-name="$vendor?->name"
                            :location="$vendor?->city ?? $city"
                            :rating="$vendor?->rating"
                            :benefit-primary="$primaryBenefit"
                            :benefit-secondary="$secondaryBenefit"
                            width-class="w-full"
                        />
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
