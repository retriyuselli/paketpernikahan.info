@extends('layout.app')

@section('title', 'Vendor - Makna Wedding')

@section('body-class', 'bg-cream text-dark')

@section('content')
    @include('layout.header')

    <x-highlight-section
        :real-weddings="$realWeddings ?? collect()"
        :featured-blogs="$homeFeaturedBlogs ?? collect()"
        :popular-blogs="$homePopularBlogs ?? collect()"
        :home-ad="$homeAd ?? null"
    />

    <div class="px-4 sm:px-6 lg:px-8">
        <x-banner-ad />
    </div>

    <!-- Search Bar -->
    {{-- <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-8 pb-2">
        <form action="{{ route('vendor') }}" method="GET" class="flex flex-wrap gap-2 items-center">
            <select name="category" class="flex-1 min-w-40 py-3 px-4 rounded-2xl border border-gray-200 bg-white text-sm focus:outline-none focus:border-gray-400 transition appearance-none cursor-pointer text-dark">
                <option value="">Semua Kategori</option>
                @foreach ($categories as $cat)
                <option value="{{ $cat->slug }}" {{ request('category') === $cat->slug ? 'selected' : '' }}>{{ $cat->name }}</option>
                @endforeach
            </select>
            <select name="province" class="flex-1 min-w-40 py-3 px-4 rounded-2xl border border-gray-200 bg-white text-sm focus:outline-none focus:border-gray-400 transition appearance-none cursor-pointer text-dark">
                <option value="">Semua Provinsi</option>
                @foreach ($provinces as $prov)
                <option value="{{ $prov }}" {{ request('province') === $prov ? 'selected' : '' }}>{{ $prov }}</option>
                @endforeach
            </select>
            <select id="city-select" name="city"
                    class="flex-1 min-w-40 py-3 px-4 rounded-2xl border border-gray-200 bg-white text-sm focus:outline-none focus:border-gray-400 transition appearance-none cursor-pointer disabled:opacity-40 disabled:cursor-not-allowed text-dark">
                <option value="">Pilih Provinsi Dulu</option>
            </select>

            <select name="price" class="flex-1 min-w-40 py-3 px-4 rounded-2xl border border-gray-200 bg-white text-sm focus:outline-none focus:border-gray-400 transition appearance-none cursor-pointer text-dark">
                <option value="">Semua Harga</option>
                <option value="0-5000000" {{ request('price') === '0-5000000' ? 'selected' : '' }}>Di bawah Rp 5 Juta</option>
                <option value="5000000-15000000" {{ request('price') === '5000000-15000000' ? 'selected' : '' }}>Rp 5 – 15 Juta</option>
                <option value="15000000-50000000" {{ request('price') === '15000000-50000000' ? 'selected' : '' }}>Rp 15 – 50 Juta</option>
                <option value="50000000-99999999" {{ request('price') === '50000000-99999999' ? 'selected' : '' }}>Di atas Rp 50 Juta</option>
            </select>
            <button type="submit" class="px-6 py-3 rounded-2xl text-sm font-semibold transition hover:opacity-90 shrink-0 bg-dark text-cream">
                Cari Vendor
            </button>
            @if (request()->hasAny(['category', 'province', 'city', 'price', 'q']))
            <a href="{{ route('vendor') }}" class="px-6 py-3 rounded-2xl text-sm font-semibold border border-dark text-dark transition hover:bg-gray-50 shrink-0">
                Reset
            </a>
            @endif
        </form>
    </div> --}}

    <script>
    document.addEventListener('DOMContentLoaded', function () {
        const citiesByProvince = @json($citiesByProvince);
        const provinceSelect   = document.querySelector('select[name="province"]');
        const citySelect       = document.getElementById('city-select');
        const savedCity        = @json(request('city'));

        function populateCities(province) {
            citySelect.innerHTML = '';
            if (!province || !citiesByProvince[province] || !citiesByProvince[province].length) {
                citySelect.disabled = true;
                citySelect.add(new Option('Pilih Provinsi dulu', ''));
                return;
            }
            citySelect.disabled = false;
            citySelect.add(new Option('Semua Kota', ''));
            citiesByProvince[province].forEach(function (city) {
                const opt = new Option(city, city);
                if (city === savedCity) opt.selected = true;
                citySelect.add(opt);
            });
        }

        provinceSelect.addEventListener('change', function () {
            populateCities(this.value);
        });

        // Restore state on page load (after filter submit)
        if (provinceSelect.value) {
            populateCities(provinceSelect.value);
        } else {
            citySelect.disabled = true;
        }
    });
    </script>

    <div class="max-w-7xl mx-auto px-4 pt-4 pb-10 sm:px-6 lg:px-8 lg:py-10">

        @if(!isset($categories) || $categories->isEmpty())
            <div class="bg-white rounded-2xl border border-gray-100 p-10 text-center flex flex-col items-center">
                <div class="w-16 h-16 rounded-full bg-gray-50 flex items-center justify-center mb-4">
                    <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10l9-6 9 6v10a1 1 0 01-1 1h-5v-6H9v6H4a1 1 0 01-1-1V10z"/>
                    </svg>
                </div>
                <h3 class="text-base font-bold mb-1 text-dark">Belum ada vendor yang tersedia</h3>
                <p class="text-sm text-gray-500 max-w-md">
                    Saat ini belum ada vendor yang aktif dan profilnya lengkap untuk ditampilkan.
                </p>
            </div>
        @else
            @foreach ($categories as $cat)
        <div class="mb-2.5 sm:mb-8 lg:mb-12">

            <!-- Category Title -->
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-sm font-bold text-dark">{{ $cat->name }}</h2>
                <a href="{{ route('vendor', ['category' => $cat->slug]) }}" class="text-xs font-bold hover:underline text-accent">Lihat</a>
            </div>

            <!-- Scrollable Row -->
            <div class="relative">
                <!-- Left Arrow -->
                {{-- <button type="button" data-scroll-sibling="next" data-scroll-by="-300"
                        class="absolute left-0 top-1/3 -translate-y-1/2 -translate-x-3 w-9 h-9 bg-white rounded-full shadow-md flex items-center justify-center hover:shadow-lg transition z-10">
                    <svg class="w-4 h-4 text-dark" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                </button> --}}

                <div class="flex gap-1.5 sm:gap-2 overflow-x-auto scroll-smooth pb-2 scrollbar-hide -mx-4 px-3 sm:mx-0 sm:px-0">
                    @foreach ($cat->vendors as $i => $v)
                    @php
                    $vData = [
                        'name'           => $v->name,
                        'city'           => $v->city,
                        'location'       => $v->location,
                        'rating'         => $v->rating,
                        'likes'          => $v->likes,
                        'comments_count' => $v->comments_count,
                        'description'    => $v->description,
                        'cover'          => $v->cover_image_url ?: (optional($v->galleries->first())->image_url ?? 'https://picsum.photos/seed/'.$v->id.'/800/600'),
                        'detail_url'     => route('vendor.detail', $v->slug),
                        'wa_url'         => 'https://wa.me/'.preg_replace('/[^0-9]/', '', $v->phone ?? ''),
                        'pkg_price'  => optional($v->cheapestPackage)->price,
                        'pkg_discount'   => optional($v->cheapestPackage)->discount ?? 0,
                        'pkg_name'       => optional($v->cheapestPackage)->name,
                        'price_start'    => is_numeric($v->price_start) ? 'Rp ' . number_format((int) $v->price_start, 0, ',', '.') : ($v->price_start ?: '—'),
                    ];
                    @endphp
                    <x-vendor-card
                        :vendor="$v"
                        :vendor-data="$vData"
                        width-class="w-[calc(50%-0.1875rem)] sm:w-[44vw] lg:w-56"
                        :class="$loop->index >= 2 ? 'hidden lg:flex' : ''"
                    />
                    @endforeach
                </div>

                <!-- Right Arrow -->
                {{-- <button type="button" data-scroll-sibling="prev" data-scroll-by="300"
                        class="absolute right-0 top-1/3 -translate-y-1/2 translate-x-3 w-9 h-9 bg-white rounded-full shadow-md flex items-center justify-center hover:shadow-lg transition z-10">
                    <svg class="w-4 h-4 text-dark" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </button> --}}
            </div>

        </div>
            @endforeach
        @endif

    </div>

    @include('layout.footer')

    <!-- Vendor Quick Preview Modal -->
    <div id="vendor-preview-modal"
            class="fixed inset-0 z-9999 hidden items-center justify-center p-4">
        <div data-vendor-preview-backdrop class="absolute inset-0 bg-black/50 backdrop-blur-sm"></div>
        <div class="relative bg-white rounded-3xl shadow-2xl w-full max-w-sm overflow-hidden max-h-[92vh] overflow-y-auto">

            <!-- Cover Image -->
            <div class="relative ar-16x9">
                <img id="vp-cover" src="" alt="" class="w-full h-full object-cover">
                <button type="button" data-vendor-preview-close
                        class="absolute top-3 right-3 w-8 h-8 rounded-full flex items-center justify-center transition bg-backdrop-45">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <!-- Body -->
            <div class="p-5">
                <h2 id="vp-name" class="text-lg font-bold leading-snug mb-1 text-dark"></h2>
                <p class="flex items-center gap-1 text-xs text-gray-500 mb-3">
                    <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    <span id="vp-location"></span>
                </p>

                <!-- Stats -->
                <div class="flex gap-4 text-xs text-gray-500 mb-4 pb-4 border-b border-gray-100">
                    <span class="flex items-center gap-1">
                        <svg class="w-3.5 h-3.5 text-rating" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                        <strong id="vp-rating"></strong>
                    </span>
                    <span class="flex items-center gap-1">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                        <span id="vp-likes"></span> Suka
                    </span>
                    <span class="flex items-center gap-1">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                        <span id="vp-comments"></span>
                    </span>
                </div>

                <!-- Price -->
                <div class="rounded-xl p-3 mb-4 bg-cream">
                    <p class="text-[10px] uppercase tracking-widest text-gray-400 mb-1">Harga Mulai</p>
                    <p id="vp-price" class="text-base font-bold text-accent"></p>
                </div>

                <!-- Actions -->
                <div class="flex flex-col gap-2">
                    <a id="vp-detail" href="#"
                       class="flex items-center justify-center gap-2 w-full py-3 rounded-xl text-sm font-bold transition hover:opacity-90 bg-accent text-cream">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0m2.458-4.243A11 11 0 011.542 12 11 11 0 0122.458 7.757"/></svg>
                        Lihat Selengkapnya
                    </a>
                    <a id="vp-wa" href="#" target="_blank"
                       class="flex items-center justify-center gap-2 w-full py-3 rounded-xl text-sm font-bold transition hover:opacity-90 btn-wa">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                        Chat WhatsApp
                    </a>
                </div>
            </div>
        </div>
    </div>

    <script>
    function openVendorPreview(el) {
        const v = JSON.parse(el.dataset.vendor);

        document.getElementById('vp-cover').src = v.cover || '';
        document.getElementById('vp-cover').alt = v.name;
        document.getElementById('vp-name').textContent = v.name || '';
        document.getElementById('vp-location').textContent = v.city || v.location || '';
        document.getElementById('vp-rating').textContent = v.rating || '-';
        document.getElementById('vp-likes').textContent = v.likes || 0;
        document.getElementById('vp-comments').textContent = v.comments_count || 0;

        const priceEl = document.getElementById('vp-price');
        const fmtRp = (raw) => raw > 0 ? 'Rp ' + parseInt(raw).toLocaleString('id-ID') : '—';
        if (v.pkg_price) {
            if (v.pkg_discount > 0) {
                const discounted = Number(v.pkg_price) - Number(v.pkg_discount);
                priceEl.innerHTML = '<span class="line-through text-gray-400 text-xs">' + fmtRp(v.pkg_price) + '</span><br>' +
                    'Rp ' + discounted.toLocaleString('id-ID');
            } else {
                priceEl.textContent = fmtRp(v.pkg_price);
            }
        } else {
            priceEl.textContent = v.price_start || '—';
        }

        document.getElementById('vp-wa').href     = v.wa_url || '#';
        document.getElementById('vp-detail').href = v.detail_url || '#';

        var modal = document.getElementById('vendor-preview-modal');
        if (modal) {
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }
        document.body.style.overflow = 'hidden';
    }

    function closeVendorPreview() {
        var modal = document.getElementById('vendor-preview-modal');
        if (modal) {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }
        document.body.style.overflow = '';
    }

    document.addEventListener('click', function (e) {
        var scrollBtn = e.target.closest('[data-scroll-by]');
        if (scrollBtn) {
            var by = parseInt(scrollBtn.getAttribute('data-scroll-by') || '0', 10);
            if (Number.isFinite(by) && by !== 0) {
                var targetId = scrollBtn.getAttribute('data-scroll-target');
                var el = null;
                if (targetId) el = document.getElementById(targetId);
                if (!el) {
                    var sibling = scrollBtn.getAttribute('data-scroll-sibling');
                    if (sibling === 'next') el = scrollBtn.nextElementSibling;
                    if (sibling === 'prev') el = scrollBtn.previousElementSibling;
                }
                if (el && typeof el.scrollBy === 'function') {
                    el.scrollBy({ left: by, behavior: 'smooth' });
                }
            }
        }

        var openBtn = e.target.closest('[data-vendor-preview-open]');
        if (openBtn) {
            openVendorPreview(openBtn);
            return;
        }

        if (e.target.closest('[data-vendor-preview-close]') || e.target.closest('[data-vendor-preview-backdrop]')) {
            closeVendorPreview();
        }
    });

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') closeVendorPreview();
    });
    </script>

@endsection
