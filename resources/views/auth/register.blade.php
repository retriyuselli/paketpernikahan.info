<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Register - Makna Wedding</title>
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=poppins:200,300,400,500,600,700" rel="stylesheet" />
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="bg-gray-100 font-sans font-light">
        @php
            $num1 = rand(1, 20);
            $num2 = rand(1, 20);
        @endphp
        <div class="min-h-screen flex items-center justify-center px-4 py-12">
            <div class="w-full max-w-[38rem]">
                <!-- Logo -->
                <div class="text-center mb-8">
                    <a href="{{ route('home') }}" class="inline-flex items-center space-x-2 mb-6 no-underline">
                        {{-- <div class="w-10 h-10 bg-gray-900 rounded-full flex items-center justify-center text-white text-lg">👰</div> --}}
                        <span class="text-xl text-gray-900 font-extrabold">PAKET PERNIKAHAN</span>
                    </a>
                </div>

                <!-- Card -->
                <div class="bg-white rounded-2xl shadow-sm p-8">
                    <div class="text-center mb-6">
                        <h1 class="text-2xl font-bold text-gray-900 mb-1.5">Create your account</h1>
                        <p class="text-sm text-gray-500 font-light">Start your journey — sign up to get access.</p>
                    </div>

                    <form method="POST" action="{{ route('register.post') }}">
                        @csrf

                        <!-- Row 1: Full Name + Email -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <label class="text-sm font-light text-gray-700 block mb-1.5">Full Name</label>
                                <input type="text" name="name" placeholder="Your full name" required
                                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm text-gray-900 font-light outline-none box-border transition focus:border-red-500 focus:ring-2 focus:ring-red-500/20"
                                    value="{{ old('name') }}">
                                @error('name')
                                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label class="text-sm font-light text-gray-700 block mb-1.5">Email Address</label>
                                <input type="email" name="email" placeholder="Enter your email" required
                                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm text-gray-900 font-light outline-none box-border transition focus:border-red-500 focus:ring-2 focus:ring-red-500/20"
                                    value="{{ old('email') }}">
                                @error('email')
                                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Row 2: Password + Confirm Password -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <label class="text-sm font-light text-gray-700 block mb-1.5">Password</label>
                                <input type="password" name="password" placeholder="Create a password" required
                                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm text-gray-900 font-light outline-none box-border transition focus:border-red-500 focus:ring-2 focus:ring-red-500/20">
                                @error('password')
                                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label class="text-sm font-light text-gray-700 block mb-1.5">Confirm Password</label>
                                <input type="password" name="password_confirmation" placeholder="Confirm your password" required
                                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm text-gray-900 font-light outline-none box-border transition focus:border-red-500 focus:ring-2 focus:ring-red-500/20">
                            </div>
                        </div>

                        <!-- CAPTCHA -->
                        <div class="mb-4">
                            <label class="text-sm font-light text-gray-700 block mb-1.5">
                                What is the result of
                                <span class="text-red-500 font-normal">{{ $num1 }}</span>
                                +
                                <span class="text-orange-500 font-normal">{{ $num2 }}</span>
                                ?
                            </label>
                            <input type="number" name="captcha" id="captcha" placeholder="Answer" required
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm text-gray-900 font-light outline-none box-border transition focus:border-red-500 focus:ring-2 focus:ring-red-500/20">
                            <input type="hidden" name="captcha_answer" value="{{ $num1 + $num2 }}">
                            @error('captcha')
                                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Privacy Policy Checkbox -->
                        <div class="flex items-center mb-5">
                            <input type="checkbox" name="agree" id="agree" required
                                class="h-4 w-4 rounded border-gray-300 accent-red-500 shrink-0">
                            <label for="agree" class="ml-2 text-sm text-gray-700 font-light">
                                I agree with the <a href="#" data-privacy-open class="text-blue-600 no-underline font-light hover:text-blue-700">Privacy Policy</a>
                            </label>
                        </div>

                        <!-- Privacy Policy Modal -->
                        <div id="privacy-modal" class="hidden fixed inset-0 z-50 items-center justify-center bg-black/40 p-4">
                            <div class="relative w-full max-w-[40rem] bg-white rounded-2xl max-h-[80vh] flex flex-col shadow-2xl">
                                <!-- Modal Header -->
                                <div class="px-6 pt-6 pb-4 border-b border-gray-200 flex items-center justify-between">
                                    <h2 class="text-lg font-bold text-gray-900 m-0">Kebijakan Privasi</h2>
                                    <button type="button" data-privacy-close class="text-gray-500 hover:text-gray-700 text-xl leading-none p-1">&#10005;</button>
                                </div>
                                <!-- Modal Body -->
                                <div class="p-6 overflow-y-auto text-sm text-gray-700 font-light leading-relaxed">
                                    <p class="mb-6">Terima kasih atas kunjungan Anda di Makna Wedding. Kami menghargai privasi Anda dan berkomitmen untuk melindungi data pribadi Anda. Kebijakan privasi ini menjelaskan bagaimana kami mengumpulkan, menggunakan, dan melindungi informasi pribadi Anda saat Anda menggunakan situs web kami. Dengan menggunakan situs web kami, Anda menyetujui praktik yang dijelaskan dalam kebijakan privasi ini.</p>

                                    <h3 class="text-base font-bold text-gray-900 mb-3">Pengumpulan Informasi Pribadi</h3>
                                    <p class="mb-6">Kami mengumpulkan informasi pribadi yang Anda berikan secara sukarela, seperti nama, alamat email, dan informasi kontak lainnya. Kami juga dapat mengumpulkan informasi teknis, seperti alamat IP Anda, jenis perangkat keras dan perangkat lunak yang Anda gunakan, dan informasi tentang bagaimana Anda menggunakan situs web kami.</p>

                                    <h3 class="text-base font-bold text-gray-900 mb-3">Penggunaan Informasi Pribadi</h3>
                                    <p class="mb-6">Kami menggunakan informasi pribadi Anda untuk menyediakan layanan yang diminta, seperti mengirimkan newsletter atau informasi tentang acara dan produk kami. Kami juga dapat menggunakan informasi pribadi Anda untuk mengirim pesan pemasaran tentang produk dan layanan kami, kecuali jika Anda telah memilih untuk tidak menerima pesan semacam itu. Kami tidak akan menjual atau menyewakan informasi pribadi Anda kepada pihak ketiga.</p>

                                    <h3 class="text-base font-bold text-gray-900 mb-3">Keamanan Informasi Pribadi</h3>
                                    <p class="mb-6">Kami mengambil tindakan yang tepat untuk melindungi informasi pribadi Anda dari akses yang tidak sah, penggunaan, atau pengungkapan. Kami menggunakan teknologi keamanan yang tepat untuk melindungi informasi pribadi Anda.</p>

                                    <h3 class="text-base font-bold text-gray-900 mb-3">Penggunaan Cookies</h3>
                                    <p class="mb-6">Kami menggunakan cookies untuk mengumpulkan informasi tentang bagaimana Anda menggunakan situs web kami dan untuk meningkatkan pengalaman pengguna Anda. Cookie adalah file kecil yang disimpan oleh browser web Anda di hard drive perangkat Anda. Anda dapat mengatur browser web Anda untuk menolak cookies atau memberi tahu Anda saat cookie dikirim. Namun, jika Anda menolak cookie, Anda mungkin tidak dapat menggunakan beberapa bagian dari situs web kami.</p>

                                    <h3 class="text-base font-bold text-gray-900 mb-3">Perubahan Kebijakan Privasi</h3>
                                    <p>Kami dapat memperbarui kebijakan privasi ini dari waktu ke waktu. Kami akan memberi tahu Anda tentang perubahan apa pun dengan memposting kebijakan privasi baru di halaman ini. Anda disarankan untuk meninjau kebijakan privasi ini secara berkala untuk setiap perubahan.</p>
                                </div>
                                <!-- Modal Footer -->
                                <div class="px-6 py-4 border-t border-gray-200 flex justify-end">
                                    <button type="button" data-privacy-close class="bg-red-600 hover:bg-red-700 text-white text-sm font-light py-2 px-6 rounded-full transition">Tutup</button>
                                </div>
                            </div>
                        </div>
                        <script>
                            function openPrivacyModal() {
                                var el = document.getElementById('privacy-modal');
                                if (!el) return;
                                el.classList.remove('hidden');
                                el.classList.add('flex');
                            }
                            function closePrivacyModal() {
                                var el = document.getElementById('privacy-modal');
                                if (!el) return;
                                el.classList.add('hidden');
                                el.classList.remove('flex');
                            }
                            document.addEventListener('click', function (e) {
                                if (e.target.closest('[data-privacy-open]')) {
                                    e.preventDefault();
                                    openPrivacyModal();
                                    return;
                                }
                                if (e.target.closest('[data-privacy-close]')) {
                                    closePrivacyModal();
                                    return;
                                }
                                var modal = document.getElementById('privacy-modal');
                                if (modal && e.target === modal) closePrivacyModal();
                            });
                            document.addEventListener('keydown', function(e) {
                                if (e.key === 'Escape') closePrivacyModal();
                            });
                        </script>

                        <!-- Register Button -->
                        <button type="submit"
                            class="w-full bg-red-600 hover:bg-red-700 text-white text-[15px] font-light py-3 px-4 rounded-full transition">
                            Register
                        </button>
                    </form>

                    <!-- Divider -->
                    <div class="relative my-6">
                        <div class="absolute inset-0 flex items-center">
                            <div class="w-full border-t border-gray-200"></div>
                        </div>
                        <div class="relative flex justify-center">
                            <span class="px-2 bg-white text-sm text-gray-400 font-light">Or continue with</span>
                        </div>
                    </div>

                    <!-- Social Buttons -->
                    <div class="flex flex-col gap-3">
                        <button type="button"
                            class="w-full flex items-center justify-center gap-2.5 py-3 px-4 border border-gray-300 rounded-lg bg-white cursor-pointer text-sm text-gray-700 font-light transition hover:bg-gray-50">
                            <svg width="20" height="20" viewBox="0 0 24 24">
                                <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                                <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                                <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                                <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
                            </svg>
                            Register with Google
                        </button>
                    </div>

                    <!-- Sign In Link -->
                    <p class="text-center text-sm text-gray-500 mt-6 font-light">
                        Already have an account? <a href="{{ route('login') }}" class="text-blue-600 font-light no-underline hover:text-blue-700">Sign in</a>
                    </p>
                </div>
            </div>
        </div>
    </body>
</html>
