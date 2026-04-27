<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Login - Makna Wedding</title>
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=poppins:200,300,400,500,600,700" rel="stylesheet" />
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="bg-gray-100 font-sans font-light">
        <div class="min-h-screen flex items-center justify-center px-4 py-12">
            <div class="w-full max-w-md">
                <!-- Logo Section -->
                <div class="text-center mb-8">
                    <a href="{{ route('home') }}" class="inline-flex items-center space-x-2 mb-6 no-underline">
                        {{-- <div class="w-10 h-10 bg-gray-900 rounded-full flex items-center justify-center text-white text-lg">👰</div> --}}
                        <span class="text-xl text-gray-900 font-extrabold">PAKET PERNIKAHAN</span>
                    </a>
                </div>

                <!-- Card -->
                <div class="bg-white rounded-2xl shadow-sm p-8">
                    <div class="text-center mb-6">
                        <h1 class="text-2xl font-medium text-gray-900 mb-2 leading-snug">Log in to your account</h1>
                        <p class="text-sm text-gray-500">Welcome back, enter your credentials to continue.</p>
                    </div>

                    @if (session('status'))
                        <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-6 text-sm">
                            {{ session('status') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('login.post') }}" class="space-y-4">
                        @csrf
                        <input type="hidden" name="redirect" value="{{ request('redirect') }}">
                        
                        <!-- Email Field -->
                        <div>
                            <label for="email" class="text-sm font-light text-gray-700 block mb-1.5">Email</label>
                            <input type="email" name="email" id="email" placeholder="nama@email.com" required
                                   class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm text-gray-900 outline-none transition focus:border-red-500 focus:ring-2 focus:ring-red-500/20"
                                   value="{{ old('email') }}">
                            @error('email')
                                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Password Field -->
                        <div>
                            <div class="flex items-center justify-between mb-1.5">
                                <label for="password" class="text-sm font-light text-gray-700">Password</label>
                                <a href="{{ route('password.request') }}" class="text-sm text-blue-600 font-light no-underline hover:text-blue-700">Forgot password?</a>
                            </div>
                            <input type="password" name="password" id="password" placeholder="password" required
                                   class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm text-gray-900 outline-none transition focus:border-red-500 focus:ring-2 focus:ring-red-500/20">
                            @error('password')
                                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Remember Me -->
                        <div class="flex items-center">
                            <input type="checkbox" name="remember" id="remember" class="h-4 w-4 rounded border-gray-300 accent-red-500">
                            <label for="remember" class="ml-2 text-sm text-gray-700 font-light">Remember Me</label>
                        </div>

                        <!-- Sign In Button -->
                        <button type="submit" class="w-full bg-red-600 hover:bg-red-700 text-white text-[15px] font-light py-3 px-4 rounded-full transition">
                            Sign In
                        </button>
                    </form>

                    <!-- Divider -->
                    <div class="relative my-6">
                        <div class="absolute inset-0 flex items-center">
                            <div class="w-full border-t border-gray-200"></div>
                        </div>
                        <div class="relative flex justify-center">
                            <span class="px-2 bg-white text-sm text-gray-400">Or continue with</span>
                        </div>
                    </div>

                    <!-- Social Login Buttons -->
                    <div class="flex flex-col gap-3">
                        <a href="{{ route('auth.google', ['redirect' => request('redirect')]) }}" class="w-full flex items-center justify-center gap-2.5 py-3 px-4 border border-gray-300 rounded-lg bg-white cursor-pointer text-sm text-gray-700 font-light transition hover:bg-gray-50 no-underline">
                            <svg width="20" height="20" viewBox="0 0 24 24">
                                <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                                <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                                <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                                <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
                            </svg>
                            Continue with Google
                        </a>
                    </div>

                    <!-- Sign Up Link -->
                    <p class="text-center text-sm text-gray-500 mt-6">
                        Don't have an account? <a href="{{ route('register') }}" class="text-blue-600 font-light no-underline hover:text-blue-700">Sign up</a>
                    </p>
                    <p class="text-center text-sm text-gray-500 mt-3">
                        Ingin bergabung sebagai vendor? <a href="{{ route('join.vendor.signup') }}" class="text-blue-600 font-light no-underline hover:text-blue-700">Join Vendor</a>
                    </p>
                </div>
            </div>
        </div>
    </body>
</html>
