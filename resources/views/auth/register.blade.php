<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Register - Makna Wedding</title>
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=poppins:200,300,400,500,600,700" rel="stylesheet" />
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <style>
            * {
                font-family: 'Poppins', ui-sans-serif, system-ui, sans-serif;
                font-weight: 300;
            }
        </style>
    </head>
    <body class="bg-gray-100">
        @php
            $num1 = rand(1, 20);
            $num2 = rand(1, 20);
        @endphp
        <div class="min-h-screen flex items-center justify-center px-4 py-12">
            <div style="width: 100%; max-width: 38rem;">
                <!-- Logo -->
                <div class="text-center mb-8">
                    <a href="{{ route('home') }}" class="inline-flex items-center space-x-2 mb-6" style="text-decoration: none;">
                        <div class="w-10 h-10 bg-gray-900 rounded-full flex items-center justify-center text-white text-lg" style="font-weight:300;">👰</div>
                        <span class="text-xl text-gray-900" style="font-weight:300;">MAKNA WEDDING</span>
                    </a>
                </div>

                <!-- Card -->
                <div class="bg-white rounded-2xl shadow-sm p-8">
                    <div class="text-center mb-6">
                        <h1 style="font-size: 1.5rem; font-weight: 700; color: #111827; margin-bottom: 0.375rem;">Create your account</h1>
                        <p style="font-size: 0.875rem; color: #6B7280; font-weight: 300;">Start your journey — sign up to get access.</p>
                    </div>

                    <form method="POST" action="{{ url('/register') }}">
                        @csrf

                        <!-- Row 1: Full Name + Email -->
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1rem;">
                            <div>
                                <label style="font-size: 0.875rem; font-weight: 300; color: #374151; display: block; margin-bottom: 0.375rem;">Full Name</label>
                                <input type="text" name="name" placeholder="Your full name" required
                                    style="width: 100%; padding: 0.625rem 1rem; border: 1px solid #D1D5DB; border-radius: 0.5rem; font-size: 0.875rem; color: #111827; font-weight: 300; outline: none; box-sizing: border-box;"
                                    onfocus="this.style.borderColor='#EF4444'; this.style.boxShadow='0 0 0 2px rgba(239,68,68,0.2)'"
                                    onblur="this.style.borderColor='#D1D5DB'; this.style.boxShadow='none'"
                                    value="{{ old('name') }}">
                                @error('name')
                                    <p style="font-size: 0.75rem; color: #EF4444; margin-top: 0.25rem;">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label style="font-size: 0.875rem; font-weight: 300; color: #374151; display: block; margin-bottom: 0.375rem;">Email Address</label>
                                <input type="email" name="email" placeholder="Enter your email" required
                                    style="width: 100%; padding: 0.625rem 1rem; border: 1px solid #D1D5DB; border-radius: 0.5rem; font-size: 0.875rem; color: #111827; font-weight: 300; outline: none; box-sizing: border-box;"
                                    onfocus="this.style.borderColor='#EF4444'; this.style.boxShadow='0 0 0 2px rgba(239,68,68,0.2)'"
                                    onblur="this.style.borderColor='#D1D5DB'; this.style.boxShadow='none'"
                                    value="{{ old('email') }}">
                                @error('email')
                                    <p style="font-size: 0.75rem; color: #EF4444; margin-top: 0.25rem;">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Row 2: Password + Confirm Password -->
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1rem;">
                            <div>
                                <label style="font-size: 0.875rem; font-weight: 300; color: #374151; display: block; margin-bottom: 0.375rem;">Password</label>
                                <input type="password" name="password" placeholder="Create a password" required
                                    style="width: 100%; padding: 0.625rem 1rem; border: 1px solid #D1D5DB; border-radius: 0.5rem; font-size: 0.875rem; color: #111827; font-weight: 300; outline: none; box-sizing: border-box;"
                                    onfocus="this.style.borderColor='#EF4444'; this.style.boxShadow='0 0 0 2px rgba(239,68,68,0.2)'"
                                    onblur="this.style.borderColor='#D1D5DB'; this.style.boxShadow='none'">
                                @error('password')
                                    <p style="font-size: 0.75rem; color: #EF4444; margin-top: 0.25rem;">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label style="font-size: 0.875rem; font-weight: 300; color: #374151; display: block; margin-bottom: 0.375rem;">Confirm Password</label>
                                <input type="password" name="password_confirmation" placeholder="Confirm your password" required
                                    style="width: 100%; padding: 0.625rem 1rem; border: 1px solid #D1D5DB; border-radius: 0.5rem; font-size: 0.875rem; color: #111827; font-weight: 300; outline: none; box-sizing: border-box;"
                                    onfocus="this.style.borderColor='#EF4444'; this.style.boxShadow='0 0 0 2px rgba(239,68,68,0.2)'"
                                    onblur="this.style.borderColor='#D1D5DB'; this.style.boxShadow='none'">
                            </div>
                        </div>

                        <!-- CAPTCHA -->
                        <div style="margin-bottom: 1rem;">
                            <label style="font-size: 0.875rem; font-weight: 300; color: #374151; display: block; margin-bottom: 0.375rem;">
                                What is the result of
                                <span style="color: #EF4444; font-weight: 400;">{{ $num1 }}</span>
                                /
                                <span style="color: #F97316; font-weight: 400;">{{ $num2 }}</span>
                                ?
                            </label>
                            <input type="number" name="captcha" id="captcha" placeholder="Answer" required
                                style="width: 100%; padding: 0.625rem 1rem; border: 1px solid #D1D5DB; border-radius: 0.5rem; font-size: 0.875rem; color: #111827; font-weight: 300; outline: none; box-sizing: border-box;"
                                onfocus="this.style.borderColor='#EF4444'; this.style.boxShadow='0 0 0 2px rgba(239,68,68,0.2)'"
                                onblur="this.style.borderColor='#D1D5DB'; this.style.boxShadow='none'">
                            <input type="hidden" name="captcha_answer" value="{{ $num1 / $num2 }}">
                        </div>

                        <!-- Privacy Policy Checkbox -->
                        <div style="display: flex; align-items: center; margin-bottom: 1.25rem;">
                            <input type="checkbox" name="agree" id="agree" required
                                style="width: 1rem; height: 1rem; border: 1px solid #D1D5DB; border-radius: 0.25rem; accent-color: #EF4444; flex-shrink: 0;">
                            <label for="agree" style="margin-left: 0.5rem; font-size: 0.875rem; color: #374151; font-weight: 300;">
                        I agree with the <a href="#" onclick="document.getElementById('privacy-modal').style.display='flex'; return false;" style="color: #2563EB; text-decoration: none; font-weight: 300;">Privacy Policy</a>
                            </label>
                        </div>

                        <!-- Privacy Policy Modal -->
                        <div id="privacy-modal" style="display: none; position: fixed; inset: 0; z-index: 50; background: rgba(0,0,0,0.4); align-items: center; justify-content: center;" onclick="if(event.target===this){closePrivacyModal();}">
                            <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); width: 90%; max-width: 40rem; background: white; border-radius: 1rem; max-height: 80vh; display: flex; flex-direction: column; box-shadow: 0 20px 60px rgba(0,0,0,0.3);">
                                <!-- Modal Header -->
                                <div style="padding: 1.5rem 1.5rem 1rem; border-bottom: 1px solid #E5E7EB; display: flex; align-items: center; justify-content: space-between;">
                                    <h2 style="font-size: 1.125rem; font-weight: 700; color: #111827; margin: 0;">Kebijakan Privasi</h2>
                                    <button onclick="closePrivacyModal()" style="background: none; border: none; cursor: pointer; color: #6B7280; font-size: 1.25rem; line-height: 1; padding: 0.25rem;">&#10005;</button>
                                </div>
                                <!-- Modal Body -->
                                <div style="padding: 1.5rem; overflow-y: auto; font-size: 0.875rem; color: #374151; font-weight: 300; line-height: 1.7;">
                                    <p style="margin-bottom: 1.5rem;">Terima kasih atas kunjungan Anda di Makna Wedding. Kami menghargai privasi Anda dan berkomitmen untuk melindungi data pribadi Anda. Kebijakan privasi ini menjelaskan bagaimana kami mengumpulkan, menggunakan, dan melindungi informasi pribadi Anda saat Anda menggunakan situs web kami. Dengan menggunakan situs web kami, Anda menyetujui praktik yang dijelaskan dalam kebijakan privasi ini.</p>

                                    <h3 style="font-size: 1rem; font-weight: 700; color: #111827; margin-bottom: 0.75rem;">Pengumpulan Informasi Pribadi</h3>
                                    <p style="margin-bottom: 1.5rem;">Kami mengumpulkan informasi pribadi yang Anda berikan secara sukarela, seperti nama, alamat email, dan informasi kontak lainnya. Kami juga dapat mengumpulkan informasi teknis, seperti alamat IP Anda, jenis perangkat keras dan perangkat lunak yang Anda gunakan, dan informasi tentang bagaimana Anda menggunakan situs web kami.</p>

                                    <h3 style="font-size: 1rem; font-weight: 700; color: #111827; margin-bottom: 0.75rem;">Penggunaan Informasi Pribadi</h3>
                                    <p style="margin-bottom: 1.5rem;">Kami menggunakan informasi pribadi Anda untuk menyediakan layanan yang diminta, seperti mengirimkan newsletter atau informasi tentang acara dan produk kami. Kami juga dapat menggunakan informasi pribadi Anda untuk mengirim pesan pemasaran tentang produk dan layanan kami, kecuali jika Anda telah memilih untuk tidak menerima pesan semacam itu. Kami tidak akan menjual atau menyewakan informasi pribadi Anda kepada pihak ketiga.</p>

                                    <h3 style="font-size: 1rem; font-weight: 700; color: #111827; margin-bottom: 0.75rem;">Keamanan Informasi Pribadi</h3>
                                    <p style="margin-bottom: 1.5rem;">Kami mengambil tindakan yang tepat untuk melindungi informasi pribadi Anda dari akses yang tidak sah, penggunaan, atau pengungkapan. Kami menggunakan teknologi keamanan yang tepat untuk melindungi informasi pribadi Anda.</p>

                                    <h3 style="font-size: 1rem; font-weight: 700; color: #111827; margin-bottom: 0.75rem;">Penggunaan Cookies</h3>
                                    <p style="margin-bottom: 1.5rem;">Kami menggunakan cookies untuk mengumpulkan informasi tentang bagaimana Anda menggunakan situs web kami dan untuk meningkatkan pengalaman pengguna Anda. Cookie adalah file kecil yang disimpan oleh browser web Anda di hard drive perangkat Anda. Anda dapat mengatur browser web Anda untuk menolak cookies atau memberi tahu Anda saat cookie dikirim. Namun, jika Anda menolak cookie, Anda mungkin tidak dapat menggunakan beberapa bagian dari situs web kami.</p>

                                    <h3 style="font-size: 1rem; font-weight: 700; color: #111827; margin-bottom: 0.75rem;">Perubahan Kebijakan Privasi</h3>
                                    <p>Kami dapat memperbarui kebijakan privasi ini dari waktu ke waktu. Kami akan memberi tahu Anda tentang perubahan apa pun dengan memposting kebijakan privasi baru di halaman ini. Anda disarankan untuk meninjau kebijakan privasi ini secara berkala untuk setiap perubahan.</p>
                                </div>
                                <!-- Modal Footer -->
                                <div style="padding: 1rem 1.5rem; border-top: 1px solid #E5E7EB; display: flex; justify-content: flex-end;">
                                    <button onclick="closePrivacyModal()" style="background-color: #DC2626; color: white; font-size: 0.875rem; font-weight: 300; padding: 0.5rem 1.5rem; border-radius: 9999px; border: none; cursor: pointer;" onmouseover="this.style.backgroundColor='#B91C1C'" onmouseout="this.style.backgroundColor='#DC2626'">Tutup</button>
                                </div>
                            </div>
                        </div>
                        <script>
                            function closePrivacyModal() {
                                document.getElementById('privacy-modal').style.display = 'none';
                            }
                            document.addEventListener('keydown', function(e) {
                                if (e.key === 'Escape') closePrivacyModal();
                            });
                        </script>

                        <!-- Register Button -->
                        <button type="submit"
                            style="width: 100%; background-color: #DC2626; color: white; font-size: 0.9375rem; font-weight: 300; padding: 0.75rem 1rem; border-radius: 9999px; border: none; cursor: pointer; transition: background-color 0.15s;"
                            onmouseover="this.style.backgroundColor='#B91C1C'"
                            onmouseout="this.style.backgroundColor='#DC2626'">
                            Register
                        </button>
                    </form>

                    <!-- Divider -->
                    <div style="position: relative; margin: 1.5rem 0;">
                        <div style="position: absolute; inset: 0; display: flex; align-items: center;">
                            <div style="width: 100%; border-top: 1px solid #E5E7EB;"></div>
                        </div>
                        <div style="position: relative; display: flex; justify-content: center;">
                            <span style="padding: 0 0.5rem; background: white; font-size: 0.875rem; color: #9CA3AF; font-weight: 300;">Or continue with</span>
                        </div>
                    </div>

                    <!-- Social Buttons -->
                    <div style="display: flex; flex-direction: column; gap: 0.75rem;">
                        <button type="button"
                            style="width: 100%; display: flex; align-items: center; justify-content: center; gap: 0.625rem; padding: 0.75rem 1rem; border: 1px solid #D1D5DB; border-radius: 0.5rem; background: white; cursor: pointer; font-size: 0.875rem; color: #374151; font-weight: 300; transition: background 0.15s;"
                            onmouseover="this.style.background='#F9FAFB'" onmouseout="this.style.background='white'">
                            <svg width="20" height="20" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 0C5.374 0 0 5.373 0 12c0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23A11.509 11.509 0 0 1 12 5.803c1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576C20.566 21.797 24 17.3 24 12c0-6.627-5.373-12-12-12z"/>
                            </svg>
                            Register with GitHub
                        </button>

                        <button type="button"
                            style="width: 100%; display: flex; align-items: center; justify-content: center; gap: 0.625rem; padding: 0.75rem 1rem; border: 1px solid #D1D5DB; border-radius: 0.5rem; background: white; cursor: pointer; font-size: 0.875rem; color: #374151; font-weight: 300; transition: background 0.15s;"
                            onmouseover="this.style.background='#F9FAFB'" onmouseout="this.style.background='white'">
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
                    <p style="text-align: center; font-size: 0.875rem; color: #6B7280; margin-top: 1.5rem; font-weight: 300;">
                        Already have an account? <a href="{{ route('login') }}" style="color: #2563EB; font-weight: 300; text-decoration: none;" onmouseover="this.style.color='#1D4ED8'" onmouseout="this.style.color='#2563EB'">Sign in</a>
                    </p>
                </div>
            </div>
        </div>
    </body>
</html>
