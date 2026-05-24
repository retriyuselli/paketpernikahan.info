@extends('layout.app')

@section('title', 'Kebijakan Privasi | Makna Wedding')
@section('meta-description', 'Kebijakan Privasi aplikasi dan website Makna Wedding — bagaimana kami mengumpulkan, menggunakan, dan melindungi data pribadi pengguna.')
@section('canonical-url', route('privacy-policy'))

@section('body-class', 'bg-cream text-dark')

@section('content')
    @include('layout.header')

    @php
        $breadcrumbItems = [
            ['label' => 'Home', 'url' => route('home')],
            ['label' => 'Kebijakan Privasi', 'url' => null],
        ];
        $lastUpdated = '24 Mei 2026';
    @endphp

    <section class="pt-3 pb-12 lg:pt-3 lg:pb-16 bg-cream">
        <x-ui.container>
            <div class="pt-1 pb-4 lg:pt-1">
                <x-breadcrumb :items="$breadcrumbItems" />
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

                {{-- Main Content --}}
                <div class="lg:col-span-8">

                    {{-- Header --}}
                    <div class="bg-white rounded-2xl border border-gray-100 p-6 mb-5">
                        <h1 class="text-2xl font-extrabold text-dark mb-2">Kebijakan Privasi</h1>
                        <p class="text-xs text-gray-400">Terakhir diperbarui: {{ $lastUpdated }}</p>
                        <p class="mt-4 text-sm text-gray-600 leading-relaxed">
                            Kebijakan Privasi ini menjelaskan bagaimana <strong>Makna Wedding & Event Planner</strong>
                            ("kami", "Makna Wedding") mengumpulkan, menggunakan, dan melindungi informasi pribadi Anda
                            saat menggunakan website <strong>paketpernikahan.co.id</strong> dan aplikasi mobile
                            <strong>Paket Pernikahan</strong> (iOS).
                        </p>
                        <p class="mt-3 text-sm text-gray-600 leading-relaxed">
                            Dengan menggunakan layanan kami, Anda menyetujui pengumpulan dan penggunaan data sesuai
                            kebijakan ini.
                        </p>
                    </div>

                    {{-- Sections --}}
                    @php
                        $sections = [
                            [
                                'icon' => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z',
                                'title' => '1. Informasi yang Kami Kumpulkan',
                                'content' => '
                                    <p class="text-sm text-gray-600 leading-relaxed mb-3">Kami mengumpulkan informasi berikut saat Anda menggunakan layanan kami:</p>
                                    <div class="space-y-3">
                                        <div>
                                            <p class="text-sm font-semibold text-dark mb-1">a. Informasi yang Anda berikan secara langsung</p>
                                            <ul class="list-disc list-inside text-sm text-gray-600 space-y-1 pl-2">
                                                <li>Nama lengkap dan alamat email (saat registrasi)</li>
                                                <li>Nomor telepon / WhatsApp</li>
                                                <li>Data booking pernikahan (tanggal acara, jumlah tamu, catatan)</li>
                                                <li>Ulasan dan penilaian vendor</li>
                                                <li>Pesan yang dikirim melalui fitur chat</li>
                                                <li>Foto bukti pembayaran</li>
                                            </ul>
                                        </div>
                                        <div>
                                            <p class="text-sm font-semibold text-dark mb-1">b. Informasi yang dikumpulkan otomatis</p>
                                            <ul class="list-disc list-inside text-sm text-gray-600 space-y-1 pl-2">
                                                <li>Alamat IP dan jenis perangkat</li>
                                                <li>Halaman yang dikunjungi dan durasi kunjungan</li>
                                                <li>Data penggunaan aplikasi (fitur yang digunakan)</li>
                                            </ul>
                                        </div>
                                        <div>
                                            <p class="text-sm font-semibold text-dark mb-1">c. Informasi dari pihak ketiga</p>
                                            <ul class="list-disc list-inside text-sm text-gray-600 space-y-1 pl-2">
                                                <li>Nama dan email dari akun Google (jika login dengan Google OAuth)</li>
                                            </ul>
                                        </div>
                                    </div>
                                ',
                            ],
                            [
                                'icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2',
                                'title' => '2. Cara Kami Menggunakan Informasi',
                                'content' => '
                                    <p class="text-sm text-gray-600 leading-relaxed mb-3">Informasi yang kami kumpulkan digunakan untuk:</p>
                                    <ul class="list-disc list-inside text-sm text-gray-600 space-y-1.5 pl-2">
                                        <li>Memproses dan mengelola booking pernikahan Anda</li>
                                        <li>Menghubungkan Anda dengan vendor yang relevan</li>
                                        <li>Mengirimkan notifikasi status booking dan pembayaran</li>
                                        <li>Meningkatkan kualitas layanan dan fitur platform</li>
                                        <li>Merespons pertanyaan dan permintaan dukungan</li>
                                        <li>Mencegah penipuan dan menjaga keamanan platform</li>
                                        <li>Memenuhi kewajiban hukum yang berlaku</li>
                                    </ul>
                                    <p class="text-sm text-gray-600 leading-relaxed mt-3">
                                        Kami <strong>tidak menjual</strong> data pribadi Anda kepada pihak ketiga untuk tujuan pemasaran.
                                    </p>
                                ',
                            ],
                            [
                                'icon' => 'M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z',
                                'title' => '3. Berbagi Informasi dengan Pihak Ketiga',
                                'content' => '
                                    <p class="text-sm text-gray-600 leading-relaxed mb-3">Kami dapat membagikan informasi Anda hanya dalam kondisi berikut:</p>
                                    <div class="space-y-3">
                                        <div>
                                            <p class="text-sm font-semibold text-dark mb-1">Vendor Pernikahan</p>
                                            <p class="text-sm text-gray-600">Nama, nomor telepon, dan detail booking Anda akan dibagikan kepada vendor yang Anda pilih untuk keperluan koordinasi acara.</p>
                                        </div>
                                        <div>
                                            <p class="text-sm font-semibold text-dark mb-1">Penyedia Layanan Teknis</p>
                                            <p class="text-sm text-gray-600">Kami menggunakan layanan pihak ketiga untuk operasional platform (hosting server, pengiriman email). Mereka hanya memproses data sesuai instruksi kami.</p>
                                        </div>
                                        <div>
                                            <p class="text-sm font-semibold text-dark mb-1">Google OAuth</p>
                                            <p class="text-sm text-gray-600">Jika Anda memilih masuk dengan Google, proses autentikasi dikelola oleh Google LLC sesuai <a href="https://policies.google.com/privacy" target="_blank" class="text-accent hover:underline">Kebijakan Privasi Google</a>. Kami hanya menerima nama dan alamat email Anda.</p>
                                        </div>
                                        <div>
                                            <p class="text-sm font-semibold text-dark mb-1">Kewajiban Hukum</p>
                                            <p class="text-sm text-gray-600">Kami dapat mengungkapkan informasi jika diwajibkan oleh hukum atau perintah pengadilan.</p>
                                        </div>
                                    </div>
                                ',
                            ],
                            [
                                'icon' => 'M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z',
                                'title' => '4. Keamanan Data',
                                'content' => '
                                    <p class="text-sm text-gray-600 leading-relaxed mb-3">
                                        Kami menerapkan langkah-langkah keamanan teknis dan organisasi untuk melindungi data Anda, termasuk:
                                    </p>
                                    <ul class="list-disc list-inside text-sm text-gray-600 space-y-1.5 pl-2">
                                        <li>Koneksi terenkripsi HTTPS/TLS untuk semua komunikasi data</li>
                                        <li>Password disimpan dalam bentuk hash (tidak dapat dibaca)</li>
                                        <li>Akses data dibatasi hanya untuk personel yang berwenang</li>
                                        <li>Aplikasi mobile menggunakan in-app browser yang aman untuk proses login</li>
                                    </ul>
                                    <p class="text-sm text-gray-600 leading-relaxed mt-3">
                                        Namun, tidak ada sistem yang 100% aman. Kami menyarankan Anda menggunakan password yang kuat dan tidak membagikan kredensial akun kepada siapapun.
                                    </p>
                                ',
                            ],
                            [
                                'icon' => 'M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z',
                                'title' => '5. Aplikasi Mobile (iOS)',
                                'content' => '
                                    <p class="text-sm text-gray-600 leading-relaxed mb-3">
                                        Aplikasi <strong>Paket Pernikahan</strong> di iOS mengakses website <strong>paketpernikahan.co.id</strong> secara langsung (live server mode). Selain data yang disebutkan di atas, aplikasi ini:
                                    </p>
                                    <ul class="list-disc list-inside text-sm text-gray-600 space-y-1.5 pl-2">
                                        <li><strong>Tidak mengakses</strong> kamera, mikrofon, atau galeri foto perangkat Anda</li>
                                        <li><strong>Tidak melacak</strong> lokasi perangkat Anda</li>
                                        <li>Menggunakan <strong>in-app browser</strong> yang aman untuk proses login Google OAuth</li>
                                        <li>Menggunakan <strong>deep link</strong> (<code class="text-xs bg-gray-100 px-1 py-0.5 rounded">paketpernikahan://</code>) untuk navigasi setelah autentikasi</li>
                                        <li>Data sesi disimpan di perangkat Anda dalam bentuk cookie browser yang aman</li>
                                    </ul>
                                ',
                            ],
                            [
                                'icon' => 'M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582 4-8 4s8 1.79 8 4',
                                'title' => '6. Penyimpanan dan Retensi Data',
                                'content' => '
                                    <p class="text-sm text-gray-600 leading-relaxed mb-3">
                                        Data Anda disimpan di server yang berlokasi di Indonesia. Kami menyimpan data selama:
                                    </p>
                                    <ul class="list-disc list-inside text-sm text-gray-600 space-y-1.5 pl-2">
                                        <li>Data akun: selama akun aktif atau hingga Anda meminta penghapusan</li>
                                        <li>Data booking: minimal 2 tahun untuk keperluan administrasi dan sengketa</li>
                                        <li>Data chat: 1 tahun sejak percakapan terakhir</li>
                                        <li>Log aktivitas: 90 hari</li>
                                    </ul>
                                ',
                            ],
                            [
                                'icon' => 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z',
                                'title' => '7. Hak Anda',
                                'content' => '
                                    <p class="text-sm text-gray-600 leading-relaxed mb-3">Sebagai pengguna, Anda memiliki hak untuk:</p>
                                    <ul class="list-disc list-inside text-sm text-gray-600 space-y-1.5 pl-2">
                                        <li><strong>Mengakses</strong> data pribadi yang kami simpan tentang Anda</li>
                                        <li><strong>Memperbarui</strong> data yang tidak akurat melalui halaman Pengaturan Akun</li>
                                        <li><strong>Menghapus</strong> akun dan data Anda dengan menghubungi kami</li>
                                        <li><strong>Menarik persetujuan</strong> atas penggunaan data tertentu</li>
                                        <li><strong>Mengajukan keberatan</strong> atas pemrosesan data Anda</li>
                                    </ul>
                                    <p class="text-sm text-gray-600 leading-relaxed mt-3">
                                        Untuk mengajukan permintaan, hubungi kami melalui email di <a href="mailto:hello@paketpernikahan.co.id" class="text-accent hover:underline">hello@paketpernikahan.co.id</a>.
                                    </p>
                                ',
                            ],
                            [
                                'icon' => 'M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9',
                                'title' => '8. Cookie',
                                'content' => '
                                    <p class="text-sm text-gray-600 leading-relaxed">
                                        Website kami menggunakan cookie untuk menjaga sesi login Anda dan mengingat preferensi. Cookie ini bersifat esensial untuk fungsi dasar platform. Anda dapat menonaktifkan cookie melalui pengaturan browser, namun beberapa fitur mungkin tidak berfungsi dengan baik.
                                    </p>
                                ',
                            ],
                            [
                                'icon' => 'M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z',
                                'title' => '9. Perubahan Kebijakan Privasi',
                                'content' => '
                                    <p class="text-sm text-gray-600 leading-relaxed">
                                        Kami dapat memperbarui Kebijakan Privasi ini sewaktu-waktu. Perubahan signifikan akan kami beritahukan melalui email atau notifikasi di aplikasi. Tanggal pembaruan terakhir akan selalu ditampilkan di bagian atas halaman ini. Penggunaan layanan kami setelah perubahan dianggap sebagai persetujuan Anda terhadap kebijakan yang diperbarui.
                                    </p>
                                ',
                            ],
                            [
                                'icon' => 'M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z',
                                'title' => '10. Hubungi Kami',
                                'content' => '
                                    <p class="text-sm text-gray-600 leading-relaxed mb-3">
                                        Jika Anda memiliki pertanyaan, kekhawatiran, atau permintaan terkait Kebijakan Privasi ini, silakan hubungi kami:
                                    </p>
                                    <div class="space-y-2 text-sm text-gray-600">
                                        <p><strong class="text-dark">Makna Wedding & Event Planner</strong></p>
                                        <p>Jl. Sintraman Jaya I No.2148, 20 Ilir D II, Kec. Kemuning<br>Palembang 30137, Sumatera Selatan</p>
                                        <p>Email: <a href="mailto:hello@paketpernikahan.co.id" class="text-accent hover:underline">hello@paketpernikahan.co.id</a></p>
                                        <p>WhatsApp: <a href="https://wa.me/6282297962600" target="_blank" class="text-accent hover:underline">0822-9796-2600</a></p>
                                    </div>
                                ',
                            ],
                        ];
                    @endphp

                    <div class="space-y-4">
                        @foreach($sections as $i => $section)
                        <div id="section-{{ $i + 1 }}" class="bg-white rounded-2xl border border-gray-100 p-6 scroll-mt-4">
                            <div class="flex items-center gap-3 mb-4">
                                <div class="w-8 h-8 rounded-xl bg-light-sage flex items-center justify-center shrink-0">
                                    <svg class="w-4 h-4 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $section['icon'] }}"/>
                                    </svg>
                                </div>
                                <h2 class="text-sm font-bold text-dark">{{ $section['title'] }}</h2>
                            </div>
                            <div>{!! $section['content'] !!}</div>
                        </div>
                        @endforeach
                    </div>

                </div>

                {{-- Sidebar --}}
                <div class="lg:col-span-4 space-y-5">

                    {{-- Quick Nav --}}
                    <div class="bg-white rounded-2xl border border-gray-100 p-5 sticky top-4">
                        <p class="text-xs font-extrabold uppercase tracking-widest text-gray-400 border-b border-gray-100 pb-3 mb-4">Daftar Isi</p>
                        <nav class="flex flex-col gap-2">
                            @foreach($sections as $i => $section)
                            <a href="#section-{{ $i + 1 }}"
                               class="text-xs text-gray-500 hover:text-accent transition py-1 border-l-2 border-transparent hover:border-accent pl-3">
                                {{ $section['title'] }}
                            </a>
                            @endforeach
                        </nav>

                        <div class="mt-5 pt-4 border-t border-gray-100 space-y-2">
                            <a href="{{ route('tentang') }}"
                               class="flex items-center justify-center gap-2 w-full py-2.5 rounded-xl border border-gray-200 bg-white hover:border-accent hover:text-accent transition text-xs font-semibold text-dark">
                                Tentang Kami
                            </a>
                            <a href="{{ route('kontak') }}"
                               class="flex items-center justify-center gap-2 w-full py-2.5 rounded-xl bg-accent text-white hover:opacity-90 transition text-xs font-semibold">
                                Hubungi Kami
                            </a>
                        </div>
                    </div>

                </div>

            </div>
        </x-ui.container>
    </section>

    @include('layout.footer')
@endsection
