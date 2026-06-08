@extends('layout.app')

@section('title', 'Daftar - Makna Wedding')

@php
    $num1 = rand(1, 20);
    $num2 = rand(1, 20);
@endphp

@section('content')
    @include('layout.header')

    <div class="min-h-[calc(100vh-60px)] flex items-center justify-center px-4 py-10"
         style="padding-bottom: max(env(safe-area-inset-bottom, 0px), 40px)">
        <div class="w-full max-w-[38rem]">

            <!-- Card -->
            <div class="bg-white rounded-2xl shadow-sm p-8">
                <div class="text-center mb-6">
                    <h1 class="text-2xl font-semibold text-dark mb-1.5">Buat akun kamu</h1>
                    <p class="text-sm text-gray-500">Mulai perjalananmu — daftar untuk mendapatkan akses.</p>
                </div>

                <form method="POST" action="{{ route('register.post') }}">
                    @csrf

                    <!-- Row 1: Nama + Email -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="text-sm font-medium text-dark block mb-1.5">Nama Lengkap</label>
                            <input type="text" name="name" placeholder="Nama lengkap kamu" required
                                class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm text-dark outline-none transition focus:border-accent focus:ring-2 focus:ring-accent/20"
                                value="{{ old('name') }}">
                            @error('name')
                                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="text-sm font-medium text-dark block mb-1.5">Alamat Email</label>
                            <input type="email" name="email" placeholder="nama@email.com" required
                                class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm text-dark outline-none transition focus:border-accent focus:ring-2 focus:ring-accent/20"
                                value="{{ old('email') }}">
                            @error('email')
                                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Row 2: Password + Konfirmasi -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="text-sm font-medium text-dark block mb-1.5">Password</label>
                            <input type="password" name="password" placeholder="••••••••" required
                                class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm text-dark outline-none transition focus:border-accent focus:ring-2 focus:ring-accent/20">
                            @error('password')
                                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="text-sm font-medium text-dark block mb-1.5">Konfirmasi Password</label>
                            <input type="password" name="password_confirmation" placeholder="••••••••" required
                                class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm text-dark outline-none transition focus:border-accent focus:ring-2 focus:ring-accent/20">
                        </div>
                    </div>

                    <!-- CAPTCHA -->
                    <div class="mb-4">
                        <label class="text-sm font-medium text-dark block mb-1.5">
                            Berapa hasil dari
                            <span class="text-accent font-semibold">{{ $num1 }}</span>
                            +
                            <span class="text-accent font-semibold">{{ $num2 }}</span>
                            ?
                        </label>
                        <input type="number" name="captcha" id="captcha" placeholder="Jawaban" required
                            class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm text-dark outline-none transition focus:border-accent focus:ring-2 focus:ring-accent/20">
                        <input type="hidden" name="captcha_answer" value="{{ $num1 + $num2 }}">
                        @error('captcha')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- ToS + Kebijakan Privasi -->
                    <div class="flex items-start mb-5">
                        <input type="checkbox" name="agree" id="agree" required
                            class="h-4 w-4 rounded border-gray-300 accent-dark shrink-0 mt-0.5">
                        <label for="agree" class="ml-2 text-sm text-gray-600 font-light">
                            Saya telah membaca dan menyetujui <a href="#" data-tos-open class="text-accent no-underline hover:opacity-80">Syarat & Ketentuan</a> serta <a href="#" data-privacy-open class="text-accent no-underline hover:opacity-80">Kebijakan Privasi</a>
                        </label>
                    </div>

                    <!-- ToS Modal -->
                    <div id="tos-modal" class="hidden fixed inset-0 z-50 items-center justify-center bg-black/40 p-4">
                        <div class="relative w-full max-w-[40rem] bg-white rounded-2xl max-h-[80vh] flex flex-col shadow-2xl">
                            <div class="px-6 pt-6 pb-4 border-b border-gray-100 flex items-center justify-between">
                                <h2 class="text-lg font-semibold text-dark m-0">Syarat & Ketentuan</h2>
                                <button type="button" data-tos-close class="text-gray-400 hover:text-dark text-xl leading-none p-1">&#10005;</button>
                            </div>
                            <div class="p-6 overflow-y-auto text-sm text-gray-600 font-light leading-relaxed">
                                <p class="mb-4">Dengan mendaftar dan menggunakan aplikasi Makna Wedding, Anda menyetujui syarat dan ketentuan berikut ini.</p>

                                <h3 class="text-sm font-semibold text-dark mb-2">1. Konten yang Tidak Diperbolehkan</h3>
                                <p class="mb-4">Makna Wedding <strong>tidak mentoleransi</strong> konten yang menyinggung, berbahaya, atau melanggar hukum. Pengguna dilarang keras memposting konten yang:</p>
                                <ul class="list-disc pl-5 mb-4 space-y-1 text-xs">
                                    <li>Mengandung ujaran kebencian, diskriminasi, atau ancaman</li>
                                    <li>Bersifat vulgar, pornografi, atau tidak pantas</li>
                                    <li>Menyebarkan informasi palsu atau menyesatkan</li>
                                    <li>Melanggar privasi atau hak orang lain</li>
                                    <li>Merupakan spam atau promosi yang tidak diminta</li>
                                </ul>

                                <h3 class="text-sm font-semibold text-dark mb-2">2. Pelaporan Konten</h3>
                                <p class="mb-4">Pengguna dapat melaporkan konten yang dianggap tidak pantas melalui tombol <em>"Laporkan"</em> yang tersedia. Tim kami berkomitmen untuk menindaklanjuti setiap laporan dalam waktu <strong>24 jam</strong> dengan menghapus konten dan/atau memblokir pengguna yang melanggar.</p>

                                <h3 class="text-sm font-semibold text-dark mb-2">3. Pemblokiran Pengguna</h3>
                                <p class="mb-4">Pengguna yang terbukti melanggar ketentuan ini dapat diblokir dari platform secara permanen tanpa pemberitahuan sebelumnya.</p>

                                <h3 class="text-sm font-semibold text-dark mb-2">4. Tanggung Jawab Pengguna</h3>
                                <p class="mb-4">Setiap pengguna bertanggung jawab penuh atas konten yang mereka buat, termasuk ulasan vendor. Makna Wedding berhak menghapus konten yang melanggar ketentuan ini kapan saja.</p>

                                <h3 class="text-sm font-semibold text-dark mb-2">5. Penghentian Akun</h3>
                                <p>Kami berhak menangguhkan atau menghapus akun yang terbukti melanggar Syarat & Ketentuan ini tanpa pemberitahuan terlebih dahulu.</p>
                            </div>
                            <div class="px-6 py-4 border-t border-gray-100 flex justify-end">
                                <button type="button" data-tos-close class="bg-dark text-cream text-sm font-semibold py-2 px-6 rounded-full transition hover:opacity-90">Tutup</button>
                            </div>
                        </div>
                    </div>

                    <!-- Privacy Modal -->
                    <div id="privacy-modal" class="hidden fixed inset-0 z-50 items-center justify-center bg-black/40 p-4">
                        <div class="relative w-full max-w-[40rem] bg-white rounded-2xl max-h-[80vh] flex flex-col shadow-2xl">
                            <div class="px-6 pt-6 pb-4 border-b border-gray-100 flex items-center justify-between">
                                <h2 class="text-lg font-semibold text-dark m-0">Kebijakan Privasi</h2>
                                <button type="button" data-privacy-close class="text-gray-400 hover:text-dark text-xl leading-none p-1">&#10005;</button>
                            </div>
                            <div class="p-6 overflow-y-auto text-sm text-gray-600 font-light leading-relaxed">
                                <p class="mb-6">Terima kasih atas kunjungan Anda di Makna Wedding. Kami menghargai privasi Anda dan berkomitmen untuk melindungi data pribadi Anda.</p>
                                <h3 class="text-sm font-semibold text-dark mb-2">Pengumpulan Informasi Pribadi</h3>
                                <p class="mb-5">Kami mengumpulkan informasi pribadi yang Anda berikan secara sukarela, seperti nama, alamat email, dan informasi kontak lainnya.</p>
                                <h3 class="text-sm font-semibold text-dark mb-2">Penggunaan Informasi Pribadi</h3>
                                <p class="mb-5">Kami menggunakan informasi pribadi Anda untuk menyediakan layanan yang diminta. Kami tidak akan menjual atau menyewakan informasi pribadi Anda kepada pihak ketiga.</p>
                                <h3 class="text-sm font-semibold text-dark mb-2">Keamanan Informasi Pribadi</h3>
                                <p class="mb-5">Kami mengambil tindakan yang tepat untuk melindungi informasi pribadi Anda dari akses yang tidak sah.</p>
                                <h3 class="text-sm font-semibold text-dark mb-2">Penggunaan Cookies</h3>
                                <p>Kami menggunakan cookies untuk meningkatkan pengalaman pengguna Anda di situs web kami.</p>
                            </div>
                            <div class="px-6 py-4 border-t border-gray-100 flex justify-end">
                                <button type="button" data-privacy-close class="bg-dark text-cream text-sm font-semibold py-2 px-6 rounded-full transition hover:opacity-90">Tutup</button>
                            </div>
                        </div>
                    </div>
                    <script>
                        function openModal(id) {
                            var el = document.getElementById(id);
                            if (el) { el.classList.remove('hidden'); el.classList.add('flex'); }
                        }
                        function closeModal(id) {
                            var el = document.getElementById(id);
                            if (el) { el.classList.add('hidden'); el.classList.remove('flex'); }
                        }
                        document.addEventListener('click', function (e) {
                            if (e.target.closest('[data-tos-open]'))     { e.preventDefault(); openModal('tos-modal'); return; }
                            if (e.target.closest('[data-tos-close]'))    { closeModal('tos-modal'); return; }
                            if (e.target.closest('[data-privacy-open]')) { e.preventDefault(); openModal('privacy-modal'); return; }
                            if (e.target.closest('[data-privacy-close]')){ closeModal('privacy-modal'); return; }
                            if (e.target === document.getElementById('tos-modal'))     closeModal('tos-modal');
                            if (e.target === document.getElementById('privacy-modal')) closeModal('privacy-modal');
                        });
                        document.addEventListener('keydown', function(e) {
                            if (e.key === 'Escape') { closeModal('tos-modal'); closeModal('privacy-modal'); }
                        });
                    </script>

                    <!-- Tombol Daftar -->
                    <button type="submit" class="w-full bg-dark hover:opacity-90 text-cream text-sm font-semibold py-3 px-4 rounded-full transition">
                        Daftar
                    </button>
                </form>

                <div class="relative my-6">
                    <div class="absolute inset-0 flex items-center">
                        <div class="w-full border-t border-gray-100"></div>
                    </div>
                    <div class="relative flex justify-center">
                        <span class="px-2 bg-white text-sm text-gray-400">Atau lanjutkan dengan</span>
                    </div>
                </div>

                <div class="flex flex-col gap-3">
                    <a href="{{ route('auth.google') }}" class="w-full flex items-center justify-center gap-2.5 py-3 px-4 border border-gray-200 rounded-xl bg-white text-sm text-dark font-medium transition hover:bg-gray-50 no-underline">
                        <svg width="18" height="18" viewBox="0 0 24 24">
                            <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                            <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                            <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                            <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
                        </svg>
                        Daftar dengan Google
                    </a>
                </div>

                <p class="text-center text-sm text-gray-500 mt-6">
                    Sudah punya akun? <a href="{{ route('login') }}" class="text-accent font-medium no-underline hover:opacity-80">Masuk</a>
                </p>
            </div>
        </div>
    </div>
@endsection
