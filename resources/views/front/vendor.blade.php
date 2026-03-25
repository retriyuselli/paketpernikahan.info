@extends('layout.app')

@section('title', 'Vendor - Makna Wedding')

@section('body-class', 'bg-cream text-dark')

@section('content')
    @include('layout.header')

    <!-- Highlight -->
    <section class="py-10" style="background-color: var(--cream)">
        <div class="px-4 sm:px-6 lg:px-8">

            <h2 class="text-xl font-bold mb-5" style="color: var(--dark-gray)">Highlights</h2>

            <div class="relative">
                <button onclick="document.getElementById('vendor-highlights-scroll').scrollBy({left: -400, behavior: 'smooth'})"
                        class="absolute left-0 top-1/2 -translate-y-1/2 -translate-x-4 w-10 h-10 bg-white rounded-full shadow-md flex items-center justify-center hover:shadow-lg transition z-10">
                    <svg class="w-5 h-5" style="color: var(--dark-gray)" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                </button>

                <div id="vendor-highlights-scroll" class="flex gap-2 overflow-x-auto scroll-smooth pb-2" style="scrollbar-width: none; -ms-overflow-style: none;">

                    <!-- Card 1 — Wedding Story -->
                    <div class="flex-none w-80 rounded-2xl overflow-hidden relative group cursor-pointer shadow-sm hover:shadow-md transition" style="aspect-ratio: 4/3;">
                        <img src="https://picsum.photos/seed/vh1/640/480" alt="Wedding Story" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/10 to-transparent"></div>
                        <div class="absolute bottom-0 left-0 right-0 p-4">
                            <p class="text-white text-xs mb-1 opacity-80">Wedding Story of <span class="font-bold">Rizky &amp; Nadya</span></p>
                            <a href="#" class="inline-block text-xs font-bold uppercase tracking-widest px-4 py-1.5 rounded-full transition hover:opacity-90" style="background-color: var(--sage-green); color: var(--cream)">More Info</a>
                        </div>
                    </div>

                    <!-- Card 2 — Promo Banner -->
                    <div class="flex-none w-80 rounded-2xl overflow-hidden relative group cursor-pointer shadow-sm hover:shadow-md transition" style="aspect-ratio: 4/3; background-color: var(--soft-pink);">
                        <img src="https://picsum.photos/seed/vh-promo/640/480" alt="Promo" class="w-full h-full object-cover opacity-30 transition-transform duration-500 group-hover:scale-105">
                        <div class="absolute inset-0 flex flex-col items-center justify-center text-center px-6 gap-3">
                            <p class="text-2xl font-bold leading-tight" style="color: var(--dark-gray)">Cari Vendor <span style="color: var(--sage-green)">Terpercaya</span><br>di Palembang</p>
                            <p class="text-xs" style="color: var(--dark-gray)">Ratusan vendor pernikahan lokal<br>siap melayani Anda</p>
                            <div class="flex gap-2 mt-2">
                                <a href="#" class="text-[10px] font-bold uppercase tracking-wider px-3 py-1.5 rounded-full transition hover:opacity-90" style="background-color: var(--sage-green); color: var(--cream)">Chat Vendor</a>
                                <a href="#" class="text-[10px] font-bold uppercase tracking-wider px-3 py-1.5 rounded-full transition hover:opacity-90" style="background-color: var(--dark-gray); color: var(--cream)">More Info</a>
                            </div>
                        </div>
                    </div>

                    <!-- Card 3 — Blog Article -->
                    <div class="flex-none w-80 rounded-2xl overflow-hidden relative group cursor-pointer shadow-sm hover:shadow-md transition" style="aspect-ratio: 4/3;">
                        <img src="https://picsum.photos/seed/vh3/640/480" alt="Tips Vendor" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/20 to-transparent"></div>
                        <div class="absolute bottom-0 left-0 right-0 p-4">
                            <p class="text-[10px] font-bold uppercase tracking-widest mb-1" style="color: var(--soft-pink)">Tips &amp; Tricks</p>
                            <p class="text-white text-sm font-bold leading-snug">7 Cara Memilih Vendor Pernikahan yang Tepat Agar Hari H Berjalan Lancar.</p>
                        </div>
                    </div>

                    <!-- Card 4 — Blog Article -->
                    <div class="flex-none w-80 rounded-2xl overflow-hidden relative group cursor-pointer shadow-sm hover:shadow-md transition" style="aspect-ratio: 4/3;">
                        <img src="https://picsum.photos/seed/vh4/640/480" alt="Venue Ideas" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/20 to-transparent"></div>
                        <div class="absolute bottom-0 left-0 right-0 p-4">
                            <p class="text-[10px] font-bold uppercase tracking-widest mb-1" style="color: var(--soft-pink)">Wedding Ideas</p>
                            <p class="text-white text-sm font-bold leading-snug">Venue Pernikahan Terbaik di Palembang yang Wajib Kamu Pertimbangkan.</p>
                        </div>
                    </div>

                    <!-- Card 5 — Wedding Story -->
                    <div class="flex-none w-80 rounded-2xl overflow-hidden relative group cursor-pointer shadow-sm hover:shadow-md transition" style="aspect-ratio: 4/3;">
                        <img src="https://picsum.photos/seed/vh5/640/480" alt="Wedding Story" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/10 to-transparent"></div>
                        <div class="absolute bottom-0 left-0 right-0 p-4">
                            <p class="text-white text-xs mb-1 opacity-80">Wedding Story of <span class="font-bold">Hendra &amp; Putri</span></p>
                            <a href="#" class="inline-block text-xs font-bold uppercase tracking-widest px-4 py-1.5 rounded-full transition hover:opacity-90" style="background-color: var(--sage-green); color: var(--cream)">More Info</a>
                        </div>
                    </div>

                    <!-- Card 6 — Blog Article -->
                    <div class="flex-none w-80 rounded-2xl overflow-hidden relative group cursor-pointer shadow-sm hover:shadow-md transition" style="aspect-ratio: 4/3;">
                        <img src="https://picsum.photos/seed/vh6/640/480" alt="Budget Tips" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/20 to-transparent"></div>
                        <div class="absolute bottom-0 left-0 right-0 p-4">
                            <p class="text-[10px] font-bold uppercase tracking-widest mb-1" style="color: var(--soft-pink)">Budget Guide</p>
                            <p class="text-white text-sm font-bold leading-snug">Tips Mengelola Budget Pernikahan Agar Tetap Hemat dan Berkesan.</p>
                        </div>
                    </div>

                    <!-- Card 7 — Wedding Story -->
                    <div class="flex-none w-80 rounded-2xl overflow-hidden relative group cursor-pointer shadow-sm hover:shadow-md transition" style="aspect-ratio: 4/3;">
                        <img src="https://picsum.photos/seed/vh7/640/480" alt="Wedding Story" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/10 to-transparent"></div>
                        <div class="absolute bottom-0 left-0 right-0 p-4">
                            <p class="text-white text-xs mb-1 opacity-80">Wedding Story of <span class="font-bold">Farhan &amp; Dewi</span></p>
                            <a href="#" class="inline-block text-xs font-bold uppercase tracking-widest px-4 py-1.5 rounded-full transition hover:opacity-90" style="background-color: var(--sage-green); color: var(--cream)">More Info</a>
                        </div>
                    </div>

                    <!-- Card 8 — Promo Banner -->
                    <div class="flex-none w-80 rounded-2xl overflow-hidden relative group cursor-pointer shadow-sm hover:shadow-md transition" style="aspect-ratio: 4/3; background-color: var(--light-sage);">
                        <img src="https://picsum.photos/seed/vh8/640/480" alt="Promo" class="w-full h-full object-cover opacity-25 transition-transform duration-500 group-hover:scale-105">
                        <div class="absolute inset-0 flex flex-col items-center justify-center text-center px-6 gap-3">
                            <p class="text-2xl font-bold leading-tight" style="color: var(--dark-gray)">Dapatkan <span style="color: var(--sage-green)">Harga Spesial</span><br>Vendor Impian</p>
                            <p class="text-xs" style="color: var(--dark-gray)">Bandingkan harga & pilih<br>paket terbaik untuk Anda</p>
                            <div class="flex gap-2 mt-2">
                                <a href="#" class="text-[10px] font-bold uppercase tracking-wider px-3 py-1.5 rounded-full transition hover:opacity-90" style="background-color: var(--sage-green); color: var(--cream)">Lihat Promo</a>
                            </div>
                        </div>
                    </div>

                    <!-- Card 9 — Blog Article -->
                    <div class="flex-none w-80 rounded-2xl overflow-hidden relative group cursor-pointer shadow-sm hover:shadow-md transition" style="aspect-ratio: 4/3;">
                        <img src="https://picsum.photos/seed/vh9/640/480" alt="Dekorasi" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/20 to-transparent"></div>
                        <div class="absolute bottom-0 left-0 right-0 p-4">
                            <p class="text-[10px] font-bold uppercase tracking-widest mb-1" style="color: var(--soft-pink)">Inspirasi Dekorasi</p>
                            <p class="text-white text-sm font-bold leading-snug">Tren Dekorasi Pernikahan 2026 yang Wajib Kamu Coba di Palembang.</p>
                        </div>
                    </div>

                    <!-- Card 10 — Wedding Story -->
                    <div class="flex-none w-80 rounded-2xl overflow-hidden relative group cursor-pointer shadow-sm hover:shadow-md transition" style="aspect-ratio: 4/3;">
                        <img src="https://picsum.photos/seed/vh10/640/480" alt="Wedding Story" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/10 to-transparent"></div>
                        <div class="absolute bottom-0 left-0 right-0 p-4">
                            <p class="text-white text-xs mb-1 opacity-80">Wedding Story of <span class="font-bold">Arif &amp; Sari</span></p>
                            <a href="#" class="inline-block text-xs font-bold uppercase tracking-widest px-4 py-1.5 rounded-full transition hover:opacity-90" style="background-color: var(--sage-green); color: var(--cream)">More Info</a>
                        </div>
                    </div>

                    <!-- Card 11 — Blog Article -->
                    <div class="flex-none w-80 rounded-2xl overflow-hidden relative group cursor-pointer shadow-sm hover:shadow-md transition" style="aspect-ratio: 4/3;">
                        <img src="https://picsum.photos/seed/vh11/640/480" alt="Katering" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/20 to-transparent"></div>
                        <div class="absolute bottom-0 left-0 right-0 p-4">
                            <p class="text-[10px] font-bold uppercase tracking-widest mb-1" style="color: var(--soft-pink)">Kuliner Wedding</p>
                            <p class="text-white text-sm font-bold leading-snug">Rekomendasi Katering Pernikahan Terlezat di Palembang untuk Hari Spesial.</p>
                        </div>
                    </div>

                    <!-- Card 12 — Wedding Story -->
                    <div class="flex-none w-80 rounded-2xl overflow-hidden relative group cursor-pointer shadow-sm hover:shadow-md transition" style="aspect-ratio: 4/3;">
                        <img src="https://picsum.photos/seed/vh12/640/480" alt="Wedding Story" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/10 to-transparent"></div>
                        <div class="absolute bottom-0 left-0 right-0 p-4">
                            <p class="text-white text-xs mb-1 opacity-80">Wedding Story of <span class="font-bold">Doni &amp; Ratna</span></p>
                            <a href="#" class="inline-block text-xs font-bold uppercase tracking-widest px-4 py-1.5 rounded-full transition hover:opacity-90" style="background-color: var(--sage-green); color: var(--cream)">More Info</a>
                        </div>
                    </div>

                    <!-- Card 13 — Blog Article -->
                    <div class="flex-none w-80 rounded-2xl overflow-hidden relative group cursor-pointer shadow-sm hover:shadow-md transition" style="aspect-ratio: 4/3;">
                        <img src="https://picsum.photos/seed/vh13/640/480" alt="Fotografer" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/20 to-transparent"></div>
                        <div class="absolute bottom-0 left-0 right-0 p-4">
                            <p class="text-[10px] font-bold uppercase tracking-widest mb-1" style="color: var(--soft-pink)">Foto & Video</p>
                            <p class="text-white text-sm font-bold leading-snug">5 Fotografer Pernikahan Terbaik Palembang yang Harus Kamu Tahu.</p>
                        </div>
                    </div>

                    <!-- Card 14 — Promo Banner -->
                    <div class="flex-none w-80 rounded-2xl overflow-hidden relative group cursor-pointer shadow-sm hover:shadow-md transition" style="aspect-ratio: 4/3; background-color: var(--soft-pink);">
                        <img src="https://picsum.photos/seed/vh14/640/480" alt="Promo" class="w-full h-full object-cover opacity-20 transition-transform duration-500 group-hover:scale-105">
                        <div class="absolute inset-0 flex flex-col items-center justify-center text-center px-6 gap-3">
                            <p class="text-2xl font-bold leading-tight" style="color: var(--dark-gray)">Konsultasi <span style="color: var(--sage-green)">Gratis</span><br>dengan WO Kami</p>
                            <p class="text-xs" style="color: var(--dark-gray)">Tim profesional kami siap<br>membantu merencanakan hari H</p>
                            <div class="flex gap-2 mt-2">
                                <a href="#" class="text-[10px] font-bold uppercase tracking-wider px-3 py-1.5 rounded-full transition hover:opacity-90" style="background-color: var(--dark-gray); color: var(--cream)">Hubungi Kami</a>
                            </div>
                        </div>
                    </div>

                    <!-- Card 15 — Wedding Story -->
                    <div class="flex-none w-80 rounded-2xl overflow-hidden relative group cursor-pointer shadow-sm hover:shadow-md transition" style="aspect-ratio: 4/3;">
                        <img src="https://picsum.photos/seed/vh15/640/480" alt="Wedding Story" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/10 to-transparent"></div>
                        <div class="absolute bottom-0 left-0 right-0 p-4">
                            <p class="text-white text-xs mb-1 opacity-80">Wedding Story of <span class="font-bold">Bayu &amp; Lestari</span></p>
                            <a href="#" class="inline-block text-xs font-bold uppercase tracking-widest px-4 py-1.5 rounded-full transition hover:opacity-90" style="background-color: var(--sage-green); color: var(--cream)">More Info</a>
                        </div>
                    </div>

                    <!-- Card 16 — Blog Article -->
                    <div class="flex-none w-80 rounded-2xl overflow-hidden relative group cursor-pointer shadow-sm hover:shadow-md transition" style="aspect-ratio: 4/3;">
                        <img src="https://picsum.photos/seed/vh16/640/480" alt="Gaun Pengantin" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/20 to-transparent"></div>
                        <div class="absolute bottom-0 left-0 right-0 p-4">
                            <p class="text-[10px] font-bold uppercase tracking-widest mb-1" style="color: var(--soft-pink)">Fashion Bride</p>
                            <p class="text-white text-sm font-bold leading-snug">Koleksi Gaun Pengantin Terbaru 2026 yang Bikin Kamu Semakin Memesona.</p>
                        </div>
                    </div>

                    <!-- Card 17 — Wedding Story -->
                    <div class="flex-none w-80 rounded-2xl overflow-hidden relative group cursor-pointer shadow-sm hover:shadow-md transition" style="aspect-ratio: 4/3;">
                        <img src="https://picsum.photos/seed/vh17/640/480" alt="Wedding Story" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/10 to-transparent"></div>
                        <div class="absolute bottom-0 left-0 right-0 p-4">
                            <p class="text-white text-xs mb-1 opacity-80">Wedding Story of <span class="font-bold">Reza &amp; Anisa</span></p>
                            <a href="#" class="inline-block text-xs font-bold uppercase tracking-widest px-4 py-1.5 rounded-full transition hover:opacity-90" style="background-color: var(--sage-green); color: var(--cream)">More Info</a>
                        </div>
                    </div>

                    <!-- Card 18 — Blog Article -->
                    <div class="flex-none w-80 rounded-2xl overflow-hidden relative group cursor-pointer shadow-sm hover:shadow-md transition" style="aspect-ratio: 4/3;">
                        <img src="https://picsum.photos/seed/vh18/640/480" alt="Undangan" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/20 to-transparent"></div>
                        <div class="absolute bottom-0 left-0 right-0 p-4">
                            <p class="text-[10px] font-bold uppercase tracking-widest mb-1" style="color: var(--soft-pink)">Undangan Digital</p>
                            <p class="text-white text-sm font-bold leading-snug">Tren Undangan Pernikahan Digital yang Elegan dan Mudah Dibagikan.</p>
                        </div>
                    </div>

                    <!-- Card 19 — Wedding Story -->
                    <div class="flex-none w-80 rounded-2xl overflow-hidden relative group cursor-pointer shadow-sm hover:shadow-md transition" style="aspect-ratio: 4/3;">
                        <img src="https://picsum.photos/seed/vh19/640/480" alt="Wedding Story" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/10 to-transparent"></div>
                        <div class="absolute bottom-0 left-0 right-0 p-4">
                            <p class="text-white text-xs mb-1 opacity-80">Wedding Story of <span class="font-bold">Aldi &amp; Maya</span></p>
                            <a href="#" class="inline-block text-xs font-bold uppercase tracking-widest px-4 py-1.5 rounded-full transition hover:opacity-90" style="background-color: var(--sage-green); color: var(--cream)">More Info</a>
                        </div>
                    </div>

                    <!-- Card 20 — Blog Article -->
                    <div class="flex-none w-80 rounded-2xl overflow-hidden relative group cursor-pointer shadow-sm hover:shadow-md transition" style="aspect-ratio: 4/3;">
                        <img src="https://picsum.photos/seed/vh20/640/480" alt="Honeymoon" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/20 to-transparent"></div>
                        <div class="absolute bottom-0 left-0 right-0 p-4">
                            <p class="text-[10px] font-bold uppercase tracking-widest mb-1" style="color: var(--soft-pink)">Honeymoon</p>
                            <p class="text-white text-sm font-bold leading-snug">10 Destinasi Honeymoon Romantis Favorit Pasangan Indonesia Tahun Ini.</p>
                        </div>
                    </div>

                </div>

                <button onclick="document.getElementById('vendor-highlights-scroll').scrollBy({left: 400, behavior: 'smooth'})"
                        class="absolute right-0 top-1/2 -translate-y-1/2 translate-x-4 w-10 h-10 bg-white rounded-full shadow-md flex items-center justify-center hover:shadow-lg transition z-10">
                    <svg class="w-5 h-5" style="color: var(--dark-gray)" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </button>
            </div>

        </div>
    </section>

    <!-- Advertising Banner -->
    <div class="px-4 sm:px-6 lg:px-8 py-4" style="background-color: var(--cream)">
        <div class="max-w-7xl mx-auto">
            <a href="#" class="block relative rounded-2xl overflow-hidden group" style="aspect-ratio: 970/90;">
                <img src="https://picsum.photos/seed/adsbanner/1200/120"
                     alt="Banner Iklan"
                     class="w-full h-full object-cover transition-opacity duration-300 group-hover:opacity-90">
                <div class="absolute inset-0 flex items-center justify-between px-8" style="background: linear-gradient(to right, rgba(249,213,229,0.75), rgba(156,175,136,0.75));">
                    <div>
                        <p class="text-xs font-bold uppercase tracking-widest mb-0.5" style="color: var(--dark-gray)">Sponsor</p>
                        <p class="text-base font-bold leading-snug" style="color: var(--dark-gray)">Nama Brand Sponsor — Tagline Promosi di Sini</p>
                    </div>
                    <span class="text-[10px] font-bold uppercase tracking-wider px-3 py-1.5 rounded-full flex-shrink-0" style="background-color: var(--dark-gray); color: var(--cream)">Pelajari →</span>
                </div>
                <span class="absolute top-1.5 right-2 text-[9px] font-semibold uppercase tracking-widest opacity-50" style="color: var(--dark-gray)">Iklan</span>
            </a>
        </div>
    </div>

    <!-- Search Bar -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-8 pb-2">
        <form action="{{ route('vendor') }}" method="GET" class="flex flex-wrap gap-2 items-center">
            <select name="category" class="flex-1 min-w-[160px] py-3 px-4 rounded-2xl border border-gray-200 bg-white text-sm focus:outline-none focus:border-gray-400 transition appearance-none cursor-pointer" style="color: var(--dark-gray);">
                <option value="">Semua Kategori</option>
                @foreach ($categories as $cat)
                <option value="{{ $cat->slug }}" {{ request('category') === $cat->slug ? 'selected' : '' }}>{{ $cat->name }}</option>
                @endforeach
            </select>
            <select name="province" class="flex-1 min-w-[160px] py-3 px-4 rounded-2xl border border-gray-200 bg-white text-sm focus:outline-none focus:border-gray-400 transition appearance-none cursor-pointer" style="color: var(--dark-gray);">
                <option value="">Semua Provinsi</option>
                @foreach ($provinces as $prov)
                <option value="{{ $prov }}" {{ request('province') === $prov ? 'selected' : '' }}>{{ $prov }}</option>
                @endforeach
            </select>
            <select id="city-select" name="city"
                    class="flex-1 min-w-[160px] py-3 px-4 rounded-2xl border border-gray-200 bg-white text-sm focus:outline-none focus:border-gray-400 transition appearance-none cursor-pointer disabled:opacity-40 disabled:cursor-not-allowed"
                    style="color: var(--dark-gray);">
                <option value="">Pilih Provinsi Dulu</option>
            </select>

            <select name="price" class="flex-1 min-w-[160px] py-3 px-4 rounded-2xl border border-gray-200 bg-white text-sm focus:outline-none focus:border-gray-400 transition appearance-none cursor-pointer" style="color: var(--dark-gray);">
                <option value="">Semua Harga</option>
                <option value="0-5000000" {{ request('price') === '0-5000000' ? 'selected' : '' }}>Di bawah Rp 5 Juta</option>
                <option value="5000000-15000000" {{ request('price') === '5000000-15000000' ? 'selected' : '' }}>Rp 5 – 15 Juta</option>
                <option value="15000000-50000000" {{ request('price') === '15000000-50000000' ? 'selected' : '' }}>Rp 15 – 50 Juta</option>
                <option value="50000000-99999999" {{ request('price') === '50000000-99999999' ? 'selected' : '' }}>Di atas Rp 50 Juta</option>
            </select>
            <button type="submit" class="px-6 py-3 rounded-2xl text-sm font-semibold transition hover:opacity-90 flex-shrink-0" style="background-color: var(--dark-gray); color: var(--cream)">
                Cari Vendor
            </button>
            @if (request()->hasAny(['category', 'province', 'city', 'price', 'q']))
            <a href="{{ route('vendor') }}" class="px-6 py-3 rounded-2xl text-sm font-semibold border transition hover:bg-gray-50 flex-shrink-0" style="border-color: var(--dark-gray); color: var(--dark-gray)">
                Reset
            </a>
            @endif
        </form>
    </div>

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

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

        @foreach ($categories as $cat)
        <div class="mb-12">

            <!-- Category Title -->
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-bold" style="color: var(--dark-gray)">{{ $cat->name }}</h2>
                <a href="#" class="text-xs font-semibold hover:underline" style="color: var(--sage-green)">Lihat Semua →</a>
            </div>

            <!-- Scrollable Row -->
            <div class="relative">
                <!-- Left Arrow -->
                <button onclick="this.nextElementSibling.scrollBy({left: -300, behavior: 'smooth'})"
                        class="absolute left-0 top-1/3 -translate-y-1/2 -translate-x-3 w-9 h-9 bg-white rounded-full shadow-md flex items-center justify-center hover:shadow-lg transition z-10">
                    <svg class="w-4 h-4" style="color: var(--dark-gray)" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                </button>

                <div class="flex gap-1 overflow-x-auto scroll-smooth pb-1" style="scrollbar-width: none; -ms-overflow-style: none;">
                    @foreach ($cat->vendors as $i => $v)
                    @php
                    $vData = [
                        'name'           => $v->name,
                        'type'           => $v->type,
                        'city'           => $v->city,
                        'location'       => $v->location,
                        'rating'         => $v->rating,
                        'likes'          => $v->likes,
                        'comments_count' => $v->comments_count,
                        'description'    => $v->description,
                        'cover'          => $v->cover_image_url ?: (optional($v->galleries->first())->image_url ?? 'https://picsum.photos/seed/'.$v->id.'/800/600'),
                        'detail_url'     => route('vendor.detail', $v->slug),
                        'wa_url'         => 'https://wa.me/'.preg_replace('/[^0-9]/', '', $v->phone ?? ''),
                        'pkg_price'      => optional($v->cheapestPackage)->price,
                        'pkg_price_raw'  => optional($v->cheapestPackage)->price_raw,
                        'pkg_discount'   => optional($v->cheapestPackage)->discount ?? 0,
                        'pkg_name'       => optional($v->cheapestPackage)->name,
                    ];
                    @endphp
                    <div onclick="openVendorPreview(this)" data-vendor='@json($vData)' class="flex-none w-56 cursor-pointer group border border-gray-200 rounded-2xl p-2 hover:border-gray-300 transition bg-white block">
                        <!-- Photo -->
                        <div class="relative rounded-xl overflow-hidden mb-2" style="aspect-ratio: 4/5;">
                            <img src="{{ $v->cover_image_url ?: (optional($v->galleries->first())->image_url ?? 'https://picsum.photos/seed/'.$v->id.'/350/260') }}"
                                 alt="{{ $v->name }}"
                                 class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
                            @if (!empty($v->promo))
                            <div class="absolute top-2 left-2 flex flex-col gap-1">
                                @foreach ((array) $v->promo as $p)
                                <span class="text-[10px] font-bold uppercase tracking-wide px-2.5 py-1 rounded-full text-white" style="background-color: var(--sage-green)">{{ \App\Enums\VendorPromo::from($p)->label() }}</span>
                                @endforeach
                            </div>
                            @endif
                            @if (!empty($v->badge))
                            <div class="absolute bottom-0 left-0 right-0 px-2 py-1.5 flex items-center gap-1"
                                 style="background: linear-gradient(to right, var(--soft-pink), var(--sage-green))">
                                <svg class="w-3 h-3 text-white flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                @php $allBadges = (array) $v->badge; $extraBadges = count($allBadges) - 2; @endphp
                                @foreach (array_slice($allBadges, 0, 2) as $b)
                                <span class="text-[9px] font-bold text-white uppercase tracking-wide">{{ \App\Enums\VendorBadge::from($b)->label() }}</span>
                                @endforeach
                                @if ($extraBadges > 0)
                                <span class="text-[9px] font-bold text-white/70 uppercase tracking-wide">+{{ $extraBadges }}</span>
                                @endif
                                <svg class="w-3 h-3 text-white ml-auto flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                            </div>
                            @endif
                            @if ($v->city)
                            <div class="absolute {{ !empty($v->badge) ? 'bottom-8' : 'bottom-2' }} left-0 right-0 flex justify-center z-10">
                                <span class="flex items-center gap-1 text-[10px] font-semibold text-white px-2.5 py-0.5 rounded-full" style="background: rgba(0,0,0,0.45); backdrop-filter: blur(2px);">
                                    <svg class="w-2.5 h-2.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/></svg>
                                    {{ $v->city }}
                                </span>
                            </div>
                            @endif
                        </div>

                        <!-- Info -->
                        <p class="font-bold text-sm leading-snug group-hover:underline" style="color: var(--dark-gray)">{{ $v->name }}</p>
                        <p class="text-xs mt-0.5" style="color: var(--sage-green)">{{ $v->type }}</p>
                        @php $pkg = $v->cheapestPackage; @endphp
                        @if ($pkg)
                        <div class="flex items-center gap-1.5 mt-1 mb-2">
                            <span class="text-[9px] text-gray-400">Mulai</span>
                            @if ($pkg->discount > 0)
                            <span class="text-[10px] line-through text-gray-400">{{ $pkg->price }}</span>
                            <span class="text-[11px] font-bold" style="color: var(--dark-gray)">Rp {{ number_format($pkg->price_raw - $pkg->discount, 0, ',', '.') }}</span>
                            @else
                            <span class="text-[11px] font-semibold" style="color: var(--dark-gray)">{{ $pkg->price }}</span>
                            @endif
                        </div>
                        @else
                        <div class="mb-2"></div>
                        @endif

                        <!-- Stats -->
                        <div class="flex items-center gap-2 text-[10px] text-gray-500 flex-wrap">
                            <span class="flex items-center gap-0.5">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                                {{ $v->comments_count }}
                            </span>
                            <span class="flex items-center gap-0.5">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>
                                {{ $v->rating ?? '-' }}
                            </span>
                            <span class="flex items-center gap-0.5">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                {{ $v->galleries_count ?? 0 }}
                            </span>
                            <span class="flex items-center gap-0.5">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                                {{ $v->likes }}
                            </span>
                            @if ($v->rating)
                            <span class="flex items-center gap-0.5 font-semibold" style="color: #f59e0b">
                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                {{ $v->rating }}
                            </span>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>

                <!-- Right Arrow -->
                <button onclick="this.previousElementSibling.scrollBy({left: 300, behavior: 'smooth'})"
                        class="absolute right-0 top-1/3 -translate-y-1/2 translate-x-3 w-9 h-9 bg-white rounded-full shadow-md flex items-center justify-center hover:shadow-lg transition z-10">
                    <svg class="w-4 h-4" style="color: var(--dark-gray)" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </button>
            </div>

        </div>
        @endforeach

    </div>

    @include('layout.footer')

    <!-- Vendor Quick Preview Modal -->
    <div id="vendor-preview-modal"
         class="fixed inset-0 z-[9999] flex items-center justify-center p-4 hidden"
         onclick="if(event.target===this) closeVendorPreview()">
        <div class="absolute inset-0 bg-black/50 backdrop-blur-sm"></div>
        <div class="relative bg-white rounded-3xl shadow-2xl w-full max-w-sm overflow-hidden" style="max-height: 92vh; overflow-y: auto;">

            <!-- Cover Image -->
            <div class="relative" style="aspect-ratio: 16/9;">
                <img id="vp-cover" src="" alt="" class="w-full h-full object-cover">
                <button onclick="closeVendorPreview()"
                        class="absolute top-3 right-3 w-8 h-8 rounded-full flex items-center justify-center transition"
                        style="background: rgba(0,0,0,0.45)">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <!-- Body -->
            <div class="p-5">
                <p id="vp-type" class="text-xs font-semibold mb-0.5" style="color: var(--sage-green)"></p>
                <h2 id="vp-name" class="text-lg font-bold leading-snug mb-1" style="color: var(--dark-gray)"></h2>
                <p class="flex items-center gap-1 text-xs text-gray-500 mb-3">
                    <svg class="w-3.5 h-3.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    <span id="vp-location"></span>
                </p>

                <!-- Stats -->
                <div class="flex gap-4 text-xs text-gray-500 mb-4 pb-4 border-b border-gray-100">
                    <span class="flex items-center gap-1">
                        <svg class="w-3.5 h-3.5" style="color: #f59e0b" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
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
                <div class="rounded-xl p-3 mb-4" style="background: var(--cream)">
                    <p class="text-[10px] uppercase tracking-widest text-gray-400 mb-1">Harga Mulai</p>
                    <p id="vp-price" class="text-base font-bold" style="color: var(--sage-green)"></p>
                </div>

                <!-- Actions -->
                <div class="flex flex-col gap-2">
                    <a id="vp-detail" href="#"
                       class="flex items-center justify-center gap-2 w-full py-3 rounded-xl text-sm font-bold transition hover:opacity-90"
                       style="background-color: var(--sage-green); color: var(--cream)">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0m2.458-4.243A11 11 0 011.542 12 11 11 0 0122.458 7.757"/></svg>
                        Lihat Selengkapnya
                    </a>
                    <a id="vp-wa" href="#" target="_blank"
                       class="flex items-center justify-center gap-2 w-full py-3 rounded-xl text-sm font-bold transition hover:opacity-90"
                       style="background-color: #25D366; color: #fff">
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
        document.getElementById('vp-type').textContent = v.type || '';
        document.getElementById('vp-name').textContent = v.name || '';
        document.getElementById('vp-location').textContent = v.city || v.location || '';
        document.getElementById('vp-rating').textContent = v.rating || '-';
        document.getElementById('vp-likes').textContent = v.likes || 0;
        document.getElementById('vp-comments').textContent = v.comments_count || 0;

        const priceEl = document.getElementById('vp-price');
        if (v.pkg_price) {
            if (v.pkg_discount > 0) {
                const discounted = Number(v.pkg_price_raw) - Number(v.pkg_discount);
                priceEl.innerHTML = '<span style="text-decoration:line-through;color:#9ca3af;font-size:.75rem">' + v.pkg_price + '</span><br>' +
                    'Rp ' + discounted.toLocaleString('id-ID');
            } else {
                priceEl.textContent = v.pkg_price;
            }
        } else {
            priceEl.textContent = '—';
        }

        document.getElementById('vp-wa').href     = v.wa_url || '#';
        document.getElementById('vp-detail').href = v.detail_url || '#';

        document.getElementById('vendor-preview-modal').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function closeVendorPreview() {
        document.getElementById('vendor-preview-modal').classList.add('hidden');
        document.body.style.overflow = '';
    }

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') closeVendorPreview();
    });
    </script>

@endsection
