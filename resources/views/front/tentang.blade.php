@extends('layout.app')

@section('title', 'Tentang Makna - Makna Wedding')

@section('body-class', 'bg-cream text-dark')

@section('content')
    @include('layout.header')

    @php
        $breadcrumbItems = [
            ['label' => 'Home', 'url' => route('home')],
            ['label' => 'Tentang Kami', 'url' => null],
        ];
    @endphp

    <section class="pt-3 lg:pt-3 lg:pb-2 bg-cream">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="pt-1 pb-4 lg:pt-1">
                <x-breadcrumb :items="$breadcrumbItems" />
            </div>
            <x-banner-ad mt="0" mb="1rem" />
        </div>
    </section>

    {{-- Hero --}}
    <section class="bg-white border-b border-gray-100">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-16 text-center">
            <span class="inline-block text-xs font-bold uppercase tracking-widest text-accent mb-3">Tentang Kami</span>
            <h1 class="text-3xl sm:text-4xl font-extrabold text-dark leading-tight mb-4">
                Makna Wedding & Event Planner 
            </h1>
            <p class="text-gray-500 text-base max-w-2xl mx-auto leading-relaxed">
                Platform wedding organizer & marketplace paket pernikahan terpercaya di Sumatera Selatan.
                Kami hadir untuk mewujudkan hari istimewa Anda dengan vendor-vendor terbaik pilihan.
            </p>
        </div>
    </section>

    {{-- About Content --}}
    <section class="py-14 bg-cream">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- Visi Misi --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-14 max-w-3xl mx-auto">
                <div class="bg-white rounded-2xl p-8 border border-gray-100 shadow-sm text-center flex flex-col items-center">
                    <div class="w-10 h-10 rounded-xl bg-accent/10 flex items-center justify-center mb-4">
                        <svg class="w-5 h-5 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                    </div>
                    <h2 class="text-lg font-bold text-dark mb-3">Visi</h2>
                    <p class="text-sm text-gray-500 leading-relaxed">
                        Menjadi platform pernikahan nomor satu di Sumatera Selatan yang menghubungkan calon pengantin
                        dengan vendor terpercaya, sehingga setiap pernikahan menjadi momen bermakna dan tak terlupakan.
                    </p>
                </div>
                <div class="bg-white rounded-2xl p-8 border border-gray-100 shadow-sm text-center flex flex-col items-center">
                    <div class="w-10 h-10 rounded-xl bg-accent/10 flex items-center justify-center mb-4">
                        <svg class="w-5 h-5 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <h2 class="text-lg font-bold text-dark mb-3">Misi</h2>
                    <ul class="text-sm text-gray-500 leading-relaxed space-y-2 inline-flex flex-col items-center">
                        <li class="flex items-start justify-center gap-2">
                            {{-- <span class="mt-1 w-1.5 h-1.5 rounded-full bg-accent shrink-0"></span> --}}
                            Menyediakan informasi vendor dan paket pernikahan yang transparan dan terpercaya.
                        </li>
                        <li class="flex items-start justify-center gap-2">
                            {{-- <span class="mt-1 w-1.5 h-1.5 rounded-full bg-accent shrink-0"></span> --}}
                            Membantu calon pengantin merencanakan pernikahan sesuai budget dan kebutuhan.
                        </li>
                        <li class="flex items-start justify-center gap-2">
                            {{-- <span class="mt-1 w-1.5 h-1.5 rounded-full bg-accent shrink-0"></span> --}}
                            Membangun ekosistem vendor lokal yang profesional dan berkualitas.
                        </li>
                    </ul>
                </div>
            </div>

            {{-- Kenapa Makna --}}
            <div class="mb-14">
                <h2 class="text-xl font-bold text-dark mb-8 text-center">Mengapa Memilih Makna Wedding & Event Planner?</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm text-center">
                        <div class="w-12 h-12 rounded-2xl bg-accent/10 flex items-center justify-center mx-auto mb-4">
                            <svg class="w-6 h-6 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                        </div>
                        <h3 class="text-sm font-bold text-dark mb-2">Vendor Terverifikasi</h3>
                        <p class="text-xs text-gray-500 leading-relaxed">Setiap vendor telah melalui proses seleksi dan verifikasi untuk memastikan kualitas layanan terbaik.</p>
                    </div>
                    <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm text-center">
                        <div class="w-12 h-12 rounded-2xl bg-accent/10 flex items-center justify-center mx-auto mb-4">
                            <svg class="w-6 h-6 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <h3 class="text-sm font-bold text-dark mb-2">Harga Transparan</h3>
                        <p class="text-xs text-gray-500 leading-relaxed">Semua harga paket ditampilkan secara jelas tanpa biaya tersembunyi. Bandingkan dan pilih sesuai budget Anda.</p>
                    </div>
                    <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm text-center">
                        <div class="w-12 h-12 rounded-2xl bg-accent/10 flex items-center justify-center mx-auto mb-4">
                            <svg class="w-6 h-6 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                            </svg>
                        </div>
                        <h3 class="text-sm font-bold text-dark mb-2">Dedikasi Penuh</h3>
                        <p class="text-xs text-gray-500 leading-relaxed">Tim kami berdedikasi membantu Anda menemukan paket pernikahan impian dengan pengalaman yang menyenangkan.</p>
                    </div>
                    <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm text-center">
                        <div class="w-12 h-12 rounded-2xl bg-accent/10 flex items-center justify-center mx-auto mb-4">
                            <svg class="w-6 h-6 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                            </svg>
                        </div>
                        <h3 class="text-sm font-bold text-dark mb-2">Review Nyata</h3>
                        <p class="text-xs text-gray-500 leading-relaxed">Ulasan dari pasangan yang telah menggunakan layanan membantu Anda membuat keputusan terbaik.</p>
                    </div>
                    <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm text-center">
                        <div class="w-12 h-12 rounded-2xl bg-accent/10 flex items-center justify-center mx-auto mb-4">
                            <svg class="w-6 h-6 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                        </div>
                        <h3 class="text-sm font-bold text-dark mb-2">Lokal & Terpercaya</h3>
                        <p class="text-xs text-gray-500 leading-relaxed">Berfokus pada vendor lokal Sumatera Selatan yang memahami budaya dan kebutuhan pernikahan daerah.</p>
                    </div>
                    <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm text-center">
                        <div class="w-12 h-12 rounded-2xl bg-accent/10 flex items-center justify-center mx-auto mb-4">
                            <svg class="w-6 h-6 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                            </svg>
                        </div>
                        <h3 class="text-sm font-bold text-dark mb-2">Support Responsif</h3>
                        <p class="text-xs text-gray-500 leading-relaxed">Tim support siap membantu menjawab pertanyaan Anda melalui WhatsApp maupun email kapan saja.</p>
                    </div>
                </div>
            </div>

            {{-- CTA --}}
            <div class="bg-accent/10 rounded-2xl p-10 text-center border border-accent/20">
                <h2 class="text-xl font-bold text-dark mb-3">Siap Merencanakan Pernikahan Impian?</h2>
                <p class="text-sm text-gray-500 mb-6 max-w-xl mx-auto leading-relaxed">
                    Temukan vendor dan paket pernikahan terbaik sesuai budget dan selera Anda. Ribuan pilihan tersedia untuk hari istimewa Anda.
                </p>
                <div class="flex flex-col sm:flex-row items-center justify-center gap-3">
                    <a href="{{ route('vendor') }}"
                       class="px-6 py-2.5 rounded-full bg-accent text-white text-sm font-bold tracking-wide transition hover:opacity-90">
                        Jelajahi Vendor
                    </a>
                    <a href="https://wa.me/6282297962600" target="_blank" rel="noopener noreferrer"
                       class="px-6 py-2.5 rounded-full bg-white border border-gray-200 text-dark text-sm font-bold tracking-wide transition hover:bg-gray-50">
                        Hubungi Kami
                    </a>
                </div>
            </div>

        </div>
    </section>

    @include('layout.footer')
@endsection
