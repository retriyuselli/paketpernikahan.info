<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Lupa Password - Makna Wedding</title>
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
                <div class="bg-white rounded-2xl shadow-sm p-8">
                    <div class="text-center mb-8">
                        <h1 class="text-2xl font-semibold text-dark mb-2 leading-snug">Lupa password?</h1>
                        <p class="text-sm text-gray-500">Masukkan email kamu dan kami akan mengirimkan link untuk reset password.</p>
                    </div>

                    @if ($errors->any())
                        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-6 text-sm">
                            {{ $errors->first() }}
                        </div>
                    @endif

                    @if (session('status'))
                        <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-6 text-sm">
                            {{ session('status') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('password.email') }}" class="space-y-5">
                        @csrf

                        <div>
                            <label for="email" class="block text-sm font-medium text-dark mb-1.5">Email</label>
                            <input type="email" name="email" id="email" placeholder="nama@email.com" required
                                   class="w-full rounded-xl border border-gray-200 px-4 py-2.5 text-sm text-dark outline-none transition focus:border-accent focus:ring-2 focus:ring-accent/20"
                                   value="{{ old('email') }}">
                            @error('email')
                                <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <button type="submit" class="w-full rounded-full bg-dark text-cream text-sm font-semibold py-3 px-4 transition hover:opacity-90">
                            Kirim Link Reset Password
                        </button>
                    </form>

                    <p class="text-center text-sm text-gray-500 mt-6">
                        <a href="{{ route('login') }}" class="text-accent font-medium no-underline hover:opacity-80 transition">Kembali ke login</a>
                    </p>
                </div>
            </div>
        </div>
    </body>
</html>
