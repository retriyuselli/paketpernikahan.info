<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Reset Password - Makna Wedding</title>
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
                        <h1 class="text-2xl font-semibold text-dark mb-2 leading-snug">Reset Password</h1>
                        <p class="text-sm text-gray-500">Masukkan password baru kamu di bawah ini.</p>
                    </div>

                    @if ($errors->any())
                        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-6 text-sm">
                            {{ $errors->first() }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('password.update') }}" class="space-y-4">
                        @csrf
                        <input type="hidden" name="token" value="{{ $token }}">

                        <div>
                            <label for="email" class="block text-sm font-medium text-dark mb-1.5">Email</label>
                            <input type="email" name="email" id="email" placeholder="nama@email.com" required
                                   class="w-full px-4 py-2.5 border rounded-xl text-sm text-dark outline-none transition focus:border-accent focus:ring-2 focus:ring-accent/20 {{ $errors->has('email') ? 'border-red-300' : 'border-gray-200' }}"
                                   value="{{ old('email') }}">
                            @error('email')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="password" class="block text-sm font-medium text-dark mb-1.5">Password Baru</label>
                            <input type="password" name="password" id="password" placeholder="••••••••" required
                                   class="w-full px-4 py-2.5 border rounded-xl text-sm text-dark outline-none transition focus:border-accent focus:ring-2 focus:ring-accent/20 {{ $errors->has('password') ? 'border-red-300' : 'border-gray-200' }}">
                            @error('password')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="password_confirmation" class="block text-sm font-medium text-dark mb-1.5">Konfirmasi Password</label>
                            <input type="password" name="password_confirmation" id="password_confirmation" placeholder="••••••••" required
                                   class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm text-dark outline-none transition focus:border-accent focus:ring-2 focus:ring-accent/20">
                        </div>

                        <button type="submit" class="w-full bg-dark hover:opacity-90 text-cream text-sm font-semibold py-3 rounded-full transition">
                            Reset Password
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
