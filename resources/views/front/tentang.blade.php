@extends('layout.app')

@section('title', 'Tentang Makna Wedding & Event Planner - Wedding Organizer Palembang')
@section('meta-description', 'Kenali Makna Wedding & Event Planner, platform wedding organizer dan marketplace paket pernikahan terpercaya di Palembang, Sumatera Selatan. Vendor terverifikasi, harga transparan, konsultasi gratis.')
@section('canonical-url', route('tentang'))

@section('extra-head')
@php
$tentangFaqSchema = [
    '@context'   => 'https://schema.org',
    '@type'      => 'FAQPage',
    'mainEntity' => [
        [
            '@type'          => 'Question',
            'name'           => 'Apa itu Makna Wedding & Event Planner?',
            'acceptedAnswer' => [
                '@type' => 'Answer',
                'text'  => 'Makna Wedding & Event Planner adalah platform wedding organizer dan marketplace paket pernikahan terpercaya di Palembang, Sumatera Selatan. Kami menghubungkan calon pengantin dengan vendor-vendor pernikahan terverifikasi seperti fotografer, katering, dekorasi, dan wedding organizer terbaik di Palembang.',
            ],
        ],
        [
            '@type'          => 'Question',
            'name'           => 'Di mana lokasi Makna Wedding & Event Planner?',
            'acceptedAnswer' => [
                '@type' => 'Answer',
                'text'  => 'Makna Wedding & Event Planner berlokasi di Jl. Sintraman Jaya I No.2148, 20 Ilir D II, Kec. Kemuning, Palembang 30137, Sumatera Selatan. Anda dapat menghubungi kami melalui WhatsApp di 0822-9796-2600 atau mengunjungi langsung kantor kami.',
            ],
        ],
        [
            '@type'          => 'Question',
            'name'           => 'Apakah Makna Wedding hanya melayani Palembang?',
            'acceptedAnswer' => [
                '@type' => 'Answer',
                'text'  => 'Makna Wedding berfokus di Palembang dan seluruh Sumatera Selatan, namun beberapa vendor kami juga melayani kota-kota lain di Indonesia. Platform kami tersedia untuk siapa saja yang membutuhkan referensi vendor dan paket pernikahan terpercaya.',
            ],
        ],
        [
            '@type'          => 'Question',
            'name'           => 'Berapa harga paket pernikahan di Makna Wedding?',
            'acceptedAnswer' => [
                '@type' => 'Answer',
                'text'  => 'Harga paket pernikahan di Makna Wedding bervariasi mulai dari Rp 5.000.000 hingga ratusan juta rupiah, tergantung jenis layanan dan vendor yang Anda pilih. Semua harga ditampilkan secara transparan di halaman masing-masing vendor tanpa biaya tersembunyi.',
            ],
        ],
        [
            '@type'          => 'Question',
            'name'           => 'Bagaimana cara memesan paket pernikahan melalui Makna Wedding?',
            'acceptedAnswer' => [
                '@type' => 'Answer',
                'text'  => 'Cara memesan paket pernikahan di Makna Wedding sangat mudah: (1) Cari vendor atau paket yang sesuai kebutuhan Anda, (2) Klik tombol "Hubungi Vendor" atau "Pesan Sekarang", (3) Tim vendor akan menghubungi Anda melalui WhatsApp untuk diskusi lebih lanjut. Anda juga bisa berkonsultasi langsung dengan tim Makna Wedding.',
            ],
        ],
        [
            '@type'          => 'Question',
            'name'           => 'Apakah konsultasi pernikahan di Makna Wedding gratis?',
            'acceptedAnswer' => [
                '@type' => 'Answer',
                'text'  => 'Ya, konsultasi awal dengan tim Makna Wedding sepenuhnya gratis. Kami siap membantu Anda menemukan vendor wedding organizer terbaik di Palembang sesuai budget dan kebutuhan. Hubungi kami melalui WhatsApp di 0822-9796-2600.',
            ],
        ],
    ],
];
@endphp
{{-- safe: $tentangFaqSchema adalah array PHP hardcoded di atas, bukan user input --}}
<script type="application/ld+json">{!! json_encode($tentangFaqSchema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) !!}</script>
@endsection

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

            {{-- FAQ --}}
            <div class="mb-14">
                <h2 class="text-xl font-bold text-dark mb-2 text-center">Pertanyaan yang Sering Ditanyakan</h2>
                <p class="text-sm text-gray-500 text-center mb-8">Temukan jawaban atas pertanyaan umum seputar Makna Wedding & Event Planner.</p>
                <div class="max-w-3xl mx-auto space-y-3">
                    @php
                    $faqs = [
                        [
                            'q' => 'Apa itu Makna Wedding & Event Planner?',
                            'a' => 'Makna Wedding & Event Planner adalah platform <strong>wedding organizer</strong> dan marketplace paket pernikahan terpercaya di <strong>Palembang, Sumatera Selatan</strong>. Kami menghubungkan calon pengantin dengan vendor-vendor pernikahan terverifikasi — mulai dari fotografer, katering, dekorasi, hingga wedding organizer Palembang terbaik.',
                        ],
                        [
                            'q' => 'Di mana lokasi Makna Wedding & Event Planner?',
                            'a' => 'Kami berlokasi di Jl. Sintraman Jaya I No.2148, 20 Ilir D II, Kec. Kemuning, <strong>Palembang 30137</strong>, Sumatera Selatan. Anda bisa menghubungi kami melalui WhatsApp di <strong>0822-9796-2600</strong>.',
                        ],
                        [
                            'q' => 'Berapa harga paket pernikahan di Makna Wedding?',
                            'a' => 'Harga paket pernikahan bervariasi mulai dari <strong>Rp 5.000.000</strong> hingga ratusan juta rupiah, tergantung jenis layanan dan vendor yang dipilih. Semua harga ditampilkan secara <strong>transparan</strong> di halaman masing-masing vendor, tanpa biaya tersembunyi.',
                        ],
                        [
                            'q' => 'Bagaimana cara memesan paket pernikahan?',
                            'a' => 'Caranya mudah: (1) Cari vendor atau paket sesuai kebutuhan Anda, (2) Klik tombol "Hubungi Vendor" atau "Pesan Sekarang", (3) Tim vendor akan menghubungi Anda via WhatsApp untuk diskusi lebih lanjut. Anda juga bisa berkonsultasi langsung dengan tim Makna Wedding.',
                        ],
                        [
                            'q' => 'Apakah Makna Wedding hanya melayani Palembang?',
                            'a' => 'Makna Wedding berfokus di <strong>Palembang dan seluruh Sumatera Selatan</strong>, namun beberapa vendor kami juga melayani kota-kota lain di Indonesia. Platform kami terbuka untuk siapa saja yang membutuhkan referensi vendor pernikahan terpercaya.',
                        ],
                        [
                            'q' => 'Apakah konsultasi pernikahan gratis?',
                            'a' => 'Ya, konsultasi awal dengan tim Makna Wedding <strong>sepenuhnya gratis</strong>. Kami siap membantu Anda menemukan vendor dan paket pernikahan terbaik sesuai budget. Hubungi kami via WhatsApp di <strong>0822-9796-2600</strong>.',
                        ],
                    ];
                    @endphp

                    @foreach($faqs as $i => $faq)
                    <div x-data="{ open: {{ $i === 0 ? 'true' : 'false' }} }"
                         class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                        <button @click="open = !open"
                                class="w-full flex items-center justify-between gap-4 px-6 py-4 text-left focus:outline-none">
                            <span class="text-sm font-bold text-dark">{{ $faq['q'] }}</span>
                            <svg class="w-4 h-4 text-accent shrink-0 transition-transform duration-200"
                                 :class="{ 'rotate-180': open }"
                                 fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>
                        <div x-show="open"
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0 -translate-y-1"
                             x-transition:enter-end="opacity-100 translate-y-0"
                             x-transition:leave="transition ease-in duration-150"
                             x-transition:leave-start="opacity-100 translate-y-0"
                             x-transition:leave-end="opacity-0 -translate-y-1">
                            {{-- safe: $faqs hardcoded di atas dengan tag <strong> saja, bukan user input --}}
                            <p class="px-6 pb-5 text-sm text-gray-500 leading-relaxed">{!! $faq['a'] !!}</p>
                        </div>
                    </div>
                    @endforeach
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

    {{-- Instagram Feed --}}
    @if(isset($instagramPosts) && $instagramPosts->isNotEmpty())
    <section class="py-10 bg-cream">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between mb-5">
                <div>
                    <h2 class="text-base font-bold text-dark">Instagram Kami</h2>
                    <p class="text-xs text-gray-400 mt-0.5">@maknawedding</p>
                </div>
                <a href="https://instagram.com/maknawedding" target="_blank" rel="noopener"
                   class="inline-flex items-center gap-1.5 text-xs font-bold text-accent hover:underline">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
                    </svg>
                    Lihat di Instagram
                </a>
            </div>

            <div class="grid grid-cols-3 sm:grid-cols-4 lg:grid-cols-6 gap-1.5 sm:gap-2">
                @foreach($instagramPosts as $post)
                @php
                    $imgUrl = $post['media_type'] === 'VIDEO'
                        ? ($post['thumbnail_url'] ?? $post['media_url'])
                        : $post['media_url'];
                    $caption = \Illuminate\Support\Str::limit($post['caption'] ?? '', 80);
                @endphp
                <a href="{{ $post['permalink'] }}" target="_blank" rel="noopener"
                   class="group relative aspect-square overflow-hidden rounded-xl bg-gray-100 block">
                    <img src="{{ $imgUrl }}"
                         alt="{{ $caption ?: 'Instagram post' }}"
                         loading="lazy"
                         class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-105">
                    {{-- Video badge --}}
                    @if($post['media_type'] === 'VIDEO')
                    <span class="absolute top-1.5 right-1.5 w-5 h-5 bg-black/50 rounded-full flex items-center justify-center">
                        <svg class="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M6.3 2.841A1.5 1.5 0 004 4.11V15.89a1.5 1.5 0 002.3 1.269l9.344-5.89a1.5 1.5 0 000-2.538L6.3 2.84z"/>
                        </svg>
                    </span>
                    @endif
                    {{-- Hover overlay --}}
                    <div class="absolute inset-0 bg-black/0 group-hover:bg-black/30 transition-colors duration-200 flex items-center justify-center">
                        <svg class="w-6 h-6 text-white opacity-0 group-hover:opacity-100 transition-opacity" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
                        </svg>
                    </div>
                </a>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    @include('layout.footer')
@endsection
