<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Verifikasi Email - Paket Pernikahan</title>
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=poppins:200,300,400,500,600,700" rel="stylesheet" />
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="bg-cream min-h-screen flex items-center justify-center p-6 font-sans font-light text-dark">
        <div class="bg-white rounded-2xl w-full max-w-md p-10 shadow-md text-center">

            <!-- Icon -->
            <div class="w-16 h-16 bg-accent-pink rounded-full flex items-center justify-center mx-auto mb-6">
                <svg class="text-accent" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/>
                    <polyline points="22,6 12,13 2,6"/>
                </svg>
            </div>

            <h1 class="text-xl font-semibold text-dark mb-3">Cek Email Anda</h1>

            <p class="text-sm text-gray-500 leading-relaxed mb-2">
                Kami telah mengirimkan link verifikasi ke:
            </p>
            <p class="text-sm font-medium text-dark mb-6">
                {{ auth()->user()->email }}
            </p>

            <p class="text-xs text-gray-500 leading-relaxed mb-7">
                Klik link di email tersebut untuk mengaktifkan akun Anda. Periksa juga folder <strong>Spam</strong> jika tidak menemukan email masuk.
            </p>

            @if(session('resent'))
                <div class="bg-emerald-50 border border-emerald-200 rounded-lg px-4 py-3 text-sm text-emerald-800 mb-5">
                    ✓ Link verifikasi baru telah dikirim.
                </div>
            @endif

            <!-- Resend Form -->
            <form method="POST" action="{{ route('verification.send') }}">
                @csrf
                <button type="submit" class="w-full bg-accent text-white border-0 py-3 px-4 rounded-lg text-sm font-medium cursor-pointer transition hover:bg-accent-dark">
                    Kirim Ulang Email Verifikasi
                </button>
            </form>

            <form method="POST" action="{{ route('logout') }}" class="mt-4">
                @csrf
                <button type="submit" class="bg-transparent border-0 text-xs text-gray-400 hover:text-dark transition cursor-pointer">
                    Keluar dari akun ini
                </button>
            </form>
        </div>
    </body>
</html>
