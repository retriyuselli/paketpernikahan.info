<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Verifikasi Email - Paket Pernikahan</title>
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=poppins:200,300,400,500,600,700" rel="stylesheet" />
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <style>
            * { font-family: 'Poppins', sans-serif; font-weight: 300; }
            :root {
                --sage-green: #9CAF88;
                --soft-pink: #F9D5E5;
                --cream: #FAF3E7;
                --dark-gray: #444444;
            }
        </style>
    </head>
    <body style="background: var(--cream); min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: 1.5rem;">
        <div style="background: white; border-radius: 1.25rem; width: 100%; max-width: 28rem; padding: 2.5rem; box-shadow: 0 4px 24px rgba(0,0,0,0.07); text-align: center;">

            <!-- Icon -->
            <div style="width: 4rem; height: 4rem; background: var(--soft-pink); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1.5rem;">
                <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#9CAF88" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/>
                    <polyline points="22,6 12,13 2,6"/>
                </svg>
            </div>

            <h1 style="font-size: 1.25rem; font-weight: 600; color: var(--dark-gray); margin-bottom: 0.75rem;">Cek Email Anda</h1>

            <p style="font-size: 0.875rem; color: #6B7280; line-height: 1.6; margin-bottom: 0.5rem;">
                Kami telah mengirimkan link verifikasi ke:
            </p>
            <p style="font-size: 0.9rem; font-weight: 500; color: var(--dark-gray); margin-bottom: 1.5rem;">
                {{ auth()->user()->email }}
            </p>

            <p style="font-size: 0.8125rem; color: #6B7280; line-height: 1.6; margin-bottom: 1.75rem;">
                Klik link di email tersebut untuk mengaktifkan akun Anda. Periksa juga folder <strong>Spam</strong> jika tidak menemukan email masuk.
            </p>

            @if(session('resent'))
                <div style="background: #ECFDF5; border: 1px solid #A7F3D0; border-radius: 0.5rem; padding: 0.75rem 1rem; font-size: 0.8125rem; color: #065F46; margin-bottom: 1.25rem;">
                    ✓ Link verifikasi baru telah dikirim.
                </div>
            @endif

            <!-- Resend Form -->
            <form method="POST" action="{{ route('verification.send') }}">
                @csrf
                <button type="submit"
                    style="width: 100%; background: var(--sage-green); color: white; border: none; padding: 0.7rem 1rem; border-radius: 0.5rem; font-size: 0.9rem; cursor: pointer; transition: background 0.15s; font-family: 'Poppins', sans-serif;"
                    onmouseover="this.style.background='#7A9068'"
                    onmouseout="this.style.background='#9CAF88'">
                    Kirim Ulang Email Verifikasi
                </button>
            </form>

            <form method="POST" action="{{ route('logout') }}" style="margin-top: 1rem;">
                @csrf
                <button type="submit" style="background: none; border: none; font-size: 0.8125rem; color: #9CA3AF; cursor: pointer; font-family: 'Poppins', sans-serif;"
                    onmouseover="this.style.color='#444444'" onmouseout="this.style.color='#9CA3AF'">
                    Keluar dari akun ini
                </button>
            </form>
        </div>
    </body>
</html>
