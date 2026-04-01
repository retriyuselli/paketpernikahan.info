@extends('layout.app')

@section('title', ($category->name ?? 'Kategori') . ' - Store - Makna Wedding')

@section('body-class', 'bg-cream text-dark')

@section('content')
    @include('layout.header')

    @php
        $breadcrumbItems = [
            ['label' => 'Home', 'url' => route('home')],
            ['label' => 'Store', 'url' => route('store')],
            ['label' => $category->name, 'url' => null],
        ];
    @endphp

    <section class="py-8" style="background-color: var(--cream)">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="pt-4 pb-4">
                @include('layout.breadcrumb', ['items' => $breadcrumbItems])
            </div>

            <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4 mb-5">
                <div>
                    <h1 class="text-xl sm:text-2xl font-extrabold" style="color: var(--dark-gray)">{{ $category->name }}</h1>
                    @if($category->description)
                        <p class="text-sm text-gray-500 mt-1">{{ $category->description }}</p>
                    @endif
                </div>
                <div class="flex items-center gap-2">
                    <form method="GET" action="{{ route('store.category', $category) }}" class="flex items-center gap-2">
                        <div class="relative">
                            <input name="q"
                                   value="{{ $q ?? '' }}"
                                   placeholder="Cari paket / vendor..."
                                   class="h-10 w-48 sm:w-60 rounded-xl border border-gray-200 pl-10 pr-3 text-sm bg-white hover:border-gray-300 transition focus:outline-none"
                                   style="color: var(--dark-gray)">
                            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35m1.35-5.15a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </div>
                        <select name="sort"
                                onchange="this.form.submit()"
                                class="h-10 rounded-xl border border-gray-200 px-3 text-xs font-bold bg-white hover:border-gray-300 transition focus:outline-none"
                                style="color: var(--dark-gray)">
                            <option value="rekomendasi" {{ ($sort ?? 'rekomendasi') === 'rekomendasi' ? 'selected' : '' }}>Rekomendasi</option>
                            <option value="termurah" {{ ($sort ?? 'rekomendasi') === 'termurah' ? 'selected' : '' }}>Termurah</option>
                            <option value="termahal" {{ ($sort ?? 'rekomendasi') === 'termahal' ? 'selected' : '' }}>Termahal</option>
                            <option value="terbaru" {{ ($sort ?? 'rekomendasi') === 'terbaru' ? 'selected' : '' }}>Terbaru</option>
                        </select>
                        <button type="submit"
                                class="h-10 px-4 rounded-xl text-xs font-bold transition hover:opacity-90"
                                style="background-color: var(--sage-green); color: var(--cream)">
                            Cari
                        </button>
                    </form>
                    {{-- <a href="{{ route('store') }}" class="text-xs font-bold px-3 py-2 rounded-xl border border-gray-200 bg-white hover:border-gray-300 transition" style="color: var(--dark-gray)">
                        Kembali
                    </a> --}}
                </div>
            </div>

            @if($packages->isEmpty())
                <div class="bg-white rounded-2xl border border-gray-100 p-10 text-center">
                    <p class="text-sm text-gray-500">Belum ada paket untuk kategori ini.</p>
                </div>
            @else
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($packages as $pkg)
                        @php
                            $vendor = $pkg->vendor;
                            $price = (int) ($pkg->price_raw ?? 0);
                            $discount = (int) ($pkg->discount ?? 0);
                            $final = max($price - $discount, 0);
                            $cover = null;
                            if ($vendor && is_array($vendor->cover_image ?? null) && count($vendor->cover_image) > 0) {
                                $cover = $vendor->cover_image[0];
                            }
                            $cover = $cover ?: 'https://picsum.photos/seed/store-cat-' . $pkg->id . '/800/600';
                        @endphp

                        <a href="{{ route('store.package.show', $pkg) }}"
                           class="bg-white rounded-2xl border border-gray-100 overflow-hidden hover:border-gray-200 hover:shadow-sm transition">
                            <div class="relative" style="aspect-ratio: 4/3;">
                                <img src="{{ $cover }}" alt="{{ $pkg->name }}" class="w-full h-full object-cover">
                                <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/10 to-transparent"></div>
                                @if($discount > 0)
                                    <span class="absolute top-3 left-3 text-[10px] font-bold px-2 py-1 rounded-full bg-white/90 border border-gray-200"
                                          style="color: var(--dark-gray)">
                                        Diskon
                                    </span>
                                @endif
                                <div class="absolute bottom-0 left-0 right-0 p-3">
                                    <p class="text-white text-xs font-bold leading-snug">{{ $pkg->name }}</p>
                                    <p class="text-white/80 text-[10px] mt-1">{{ $vendor?->name }}</p>
                                </div>
                            </div>
                            <div class="p-4">
                                <p class="text-[10px] text-gray-400 truncate">{{ $vendor?->city }} · {{ $vendor?->location }}</p>
                                @if($discount > 0)
                                    <p class="text-[11px] text-gray-400 line-through mt-1">Rp {{ number_format($price, 0, ',', '.') }}</p>
                                    <p class="text-sm font-extrabold leading-tight" style="color: var(--sage-green)">Rp {{ number_format($final, 0, ',', '.') }}</p>
                                @else
                                    <p class="text-sm font-extrabold leading-tight mt-1" style="color: var(--sage-green)">Rp {{ number_format($price, 0, ',', '.') }}</p>
                                @endif
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

    @include('layout.footer')
@endsection

