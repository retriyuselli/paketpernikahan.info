<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Verifikasi Email - Makna Wedding</title>
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=poppins:200,300,400,500,600,700" rel="stylesheet" />
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="bg-cream font-sans font-light">
        <div class="min-h-screen flex items-center justify-center px-4 py-12">
            <div class="w-full max-w-md">
                <!-- Logo -->
                <div class="text-center mb-8">
                    <a href="{{ route('home') }}" class="inline-block">
                        <img src="{{ asset(config('app.logo_url')) }}" alt="Makna Wedding" class="h-10 mx-auto">
                    </a>
                </div>

                <!-- Card -->
                <div class="bg-white rounded-2xl shadow-sm p-8 text-center">
                    @if(session('email'))
                        <div class="bg-emerald-50 border border-emerald-200 rounded-xl px-4 py-3 text-sm text-emerald-800 mb-6 text-left">
                            <p class="font-semibold mb-0.5">Registrasi Berhasil!</p>
                            <p class="text-xs text-emerald-700">Silakan cek email <strong>{{ session('email') }}</strong> untuk mengaktifkan akun kamu.</p>
                        </div>
                    @endif

                    <!-- Icon -->
                    <div class="w-14 h-14 bg-accent-pink rounded-full flex items-center justify-center mx-auto mb-5">
                        <svg class="text-accent" width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/>
                            <polyline points="22,6 12,13 2,6"/>
                        </svg>
                    </div>

                    <h1 class="text-xl font-semibold text-dark mb-3">Cek Email Kamu</h1>

                    <p class="text-sm text-gray-500 leading-relaxed mb-1">
                        Kami telah mengirimkan link verifikasi ke:
                    </p>
                    <p class="text-sm font-semibold text-dark mb-5">
                        {{ auth()->user()->email }}
                    </p>

                    <p class="text-xs text-gray-500 leading-relaxed mb-7">
                        Klik link di email tersebut untuk mengaktifkan akun kamu. Periksa juga folder <strong>Spam</strong> jika tidak menemukan email masuk.
                    </p>

                    @if(session('resent'))
                        <div class="bg-emerald-50 border border-emerald-200 rounded-xl px-4 py-3 text-sm text-emerald-800 mb-5">
                            ✓ Link verifikasi baru telah dikirim.
                        </div>
                    @endif

                    <form method="POST" action="{{ route('verification.send') }}">
                        @csrf
                        <button type="submit" class="w-full bg-dark text-cream text-sm font-semibold py-3 px-4 rounded-full transition hover:opacity-90">
                            Kirim Ulang Email Verifikasi
                        </button>
                    </form>

                    <form method="POST" action="{{ route('logout') }}" class="mt-4">
                        @csrf
                        <button type="submit" class="text-xs text-gray-400 hover:text-dark transition">
                            Keluar dari akun ini
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </body>
</html>
