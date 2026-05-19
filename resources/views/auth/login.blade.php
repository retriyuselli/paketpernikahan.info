<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Masuk - Makna Wedding</title>
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=poppins:200,300,400,500,600,700" rel="stylesheet" />
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="bg-cream font-sans font-light">
        <div class="min-h-screen flex items-center justify-center px-4 py-12">
            <div class="w-full max-w-md">
                <!-- Back to Home -->
                <div class="mb-4">
                    <a href="{{ route('home') }}" class="inline-flex items-center gap-1.5 text-sm text-gray-500 hover:text-dark transition no-underline">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                        </svg>
                        Kembali ke Beranda
                    </a>
                </div>

                <!-- Logo -->
                <div class="text-center mb-8">
                    <a href="{{ route('home') }}" class="inline-block">
                        <img src="{{ asset(config('app.logo_url')) }}" alt="Makna Wedding" class="h-10 mx-auto">
                    </a>
                </div>

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
                        <a href="{{ route('auth.google', ['redirect' => request('redirect')]) }}" class="w-full flex items-center justify-center gap-2.5 py-3 px-4 border border-gray-200 rounded-xl bg-white text-sm text-dark font-medium transition hover:bg-gray-50 no-underline">
                            <svg width="18" height="18" viewBox="0 0 24 24">
                                <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                                <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                                <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                                <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
                            </svg>
                            Lanjutkan dengan Google
                        </a>
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
    </body>
</html>
