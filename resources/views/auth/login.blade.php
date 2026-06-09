@extends('layout.app')

@section('title', 'Masuk - Makna Wedding')

@section('content')
    @include('layout.header')

    <div class="min-h-[calc(100vh-60px)] flex items-center justify-center px-4 py-10"
         style="padding-bottom: max(env(safe-area-inset-bottom, 0px), 40px)">
        <div class="w-full max-w-md">

            <!-- Card -->
            <div class="bg-white rounded-2xl shadow-sm p-8">
                <div class="text-center mb-6">
                    <h1 class="text-2xl font-semibold text-dark mb-2 leading-snug">Masuk ke akun kamu</h1>
                    <p class="text-sm text-gray-500">Selamat datang kembali, masukkan kredensial kamu.</p>
                </div>

                @if (session('status'))
                    <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-6 text-sm">
                        {{ session('status') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('login.post') }}" class="space-y-4">
                    @csrf
                    <input type="hidden" name="redirect" value="{{ request('redirect') }}">

                    <div>
                        <label for="email" class="text-sm font-medium text-dark block mb-1.5">Email</label>
                        <input type="email" name="email" id="email" placeholder="nama@email.com" required
                               class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm text-dark outline-none transition focus:border-accent focus:ring-2 focus:ring-accent/20"
                               value="{{ old('email') }}">
                        @error('email')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <div class="flex items-center justify-between mb-1.5">
                            <label for="password" class="text-sm font-medium text-dark">Password</label>
                            <a href="{{ route('password.request') }}" class="text-sm text-accent no-underline hover:opacity-80 transition">Lupa password?</a>
                        </div>
                        <input type="password" name="password" id="password" placeholder="••••••••" required
                               class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm text-dark outline-none transition focus:border-accent focus:ring-2 focus:ring-accent/20">
                        @error('password')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex items-center">
                        <input type="checkbox" name="remember" id="remember" class="h-4 w-4 rounded border-gray-300 accent-dark">
                        <label for="remember" class="ml-2 text-sm text-gray-600 font-light">Ingat saya</label>
                    </div>

                    <button type="submit" class="w-full bg-dark hover:opacity-90 text-cream text-sm font-semibold py-3 px-4 rounded-full transition">
                        Masuk
                    </button>

                    <p class="text-center text-xs text-gray-400 mt-3">
                        Dengan masuk, Anda menyetujui <a href="#" data-tos-open class="text-accent no-underline hover:opacity-80">Syarat & Ketentuan</a> kami yang melarang konten berbahaya dan pengguna yang melanggar.
                    </p>
                </form>

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
                            <p class="mb-4">Pengguna dapat melaporkan konten yang dianggap tidak pantas melalui tombol <em>"Laporkan"</em> yang tersedia. Tim kami berkomitmen untuk menindaklanjuti setiap laporan dalam waktu <strong>24 jam</strong>.</p>

                            <h3 class="text-sm font-semibold text-dark mb-2">3. Pemblokiran Pengguna</h3>
                            <p class="mb-4">Pengguna yang terbukti melanggar ketentuan ini dapat diblokir dari platform secara permanen tanpa pemberitahuan sebelumnya.</p>

                            <h3 class="text-sm font-semibold text-dark mb-2">4. Tanggung Jawab Pengguna</h3>
                            <p class="mb-4">Setiap pengguna bertanggung jawab penuh atas konten yang mereka buat. Makna Wedding berhak menghapus konten yang melanggar ketentuan ini kapan saja.</p>

                            <h3 class="text-sm font-semibold text-dark mb-2">5. Penghentian Akun</h3>
                            <p>Kami berhak menangguhkan atau menghapus akun yang terbukti melanggar Syarat & Ketentuan ini tanpa pemberitahuan terlebih dahulu.</p>
                        </div>
                        <div class="px-6 py-4 border-t border-gray-100 flex justify-end">
                            <button type="button" data-tos-close class="bg-dark text-cream text-sm font-semibold py-2 px-6 rounded-full transition hover:opacity-90">Tutup</button>
                        </div>
                    </div>
                </div>
                <script>
                    document.addEventListener('click', function (e) {
                        if (e.target.closest('[data-tos-open]')) {
                            e.preventDefault();
                            var el = document.getElementById('tos-modal');
                            if (el) { el.classList.remove('hidden'); el.classList.add('flex'); }
                        }
                        if (e.target.closest('[data-tos-close]')) {
                            var el = document.getElementById('tos-modal');
                            if (el) { el.classList.add('hidden'); el.classList.remove('flex'); }
                        }
                        if (e.target === document.getElementById('tos-modal')) {
                            var el = document.getElementById('tos-modal');
                            if (el) { el.classList.add('hidden'); el.classList.remove('flex'); }
                        }
                    });
                    document.addEventListener('keydown', function(e) {
                        if (e.key === 'Escape') {
                            var el = document.getElementById('tos-modal');
                            if (el) { el.classList.add('hidden'); el.classList.remove('flex'); }
                        }
                    });
                </script>

                <div class="relative my-6">
                    <div class="absolute inset-0 flex items-center">
                        <div class="w-full border-t border-gray-100"></div>
                    </div>
                    <div class="relative flex justify-center">
                        <span class="px-2 bg-white text-sm text-gray-400">Atau lanjutkan dengan</span>
                    </div>
                </div>

                <div class="flex flex-col gap-3">
                    <a href="{{ route('auth.google', ['redirect' => request('redirect')]) }}" class="w-full flex items-center justify-center gap-2.5 py-3 px-4 border border-gray-200 rounded-xl bg-white text-sm text-dark font-medium transition hover:bg-gray-50 no-underline">
                        <svg width="18" height="18" viewBox="0 0 24 24">
                            <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                            <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                            <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                            <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
                        </svg>
                        Lanjutkan dengan Google
                    </a>

                    {{-- Shown only inside iOS app via JS --}}
                    <button id="apple-signin-btn" type="button" style="display:none"
                        class="w-full flex items-center justify-center gap-2.5 py-3 px-4 border border-gray-200 rounded-xl bg-black text-sm text-white font-medium transition hover:bg-gray-900">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M18.71 19.5c-.83 1.24-1.71 2.45-3.05 2.47-1.34.03-1.77-.79-3.29-.79-1.53 0-2 .77-3.27.82-1.31.05-2.3-1.32-3.14-2.53C4.25 17 2.94 12.45 4.7 9.39c.87-1.52 2.43-2.48 4.12-2.51 1.28-.02 2.5.87 3.29.87.78 0 2.26-1.07 3.8-.91.65.03 2.47.26 3.64 1.98-.09.06-2.17 1.28-2.15 3.81.03 3.02 2.65 4.03 2.68 4.04-.03.07-.42 1.44-1.38 2.83M13 3.5c.73-.83 1.94-1.46 2.94-1.5.13 1.17-.34 2.35-1.04 3.19-.69.85-1.83 1.51-2.95 1.42-.15-1.15.41-2.35 1.05-3.11z"/>
                        </svg>
                        Lanjutkan dengan Apple
                    </button>
                </div>

                <p class="text-center text-sm text-gray-500 mt-6">
                    Belum punya akun? <a href="{{ route('register') }}" class="text-accent font-medium no-underline hover:opacity-80">Daftar</a>
                </p>
                <p class="text-center text-sm text-gray-500 mt-3">
                    Ingin bergabung sebagai vendor? <a href="{{ route('join.vendor.signup') }}" class="text-accent font-medium no-underline hover:opacity-80">Join Vendor</a>
                </p>
            </div>
        </div>
    </div>
@endsection
