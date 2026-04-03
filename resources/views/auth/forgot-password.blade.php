<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Forgot Password - Makna Wedding</title>
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=poppins:200,300,400,500,600,700" rel="stylesheet" />
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="bg-gray-100 font-sans font-light">
        <div class="min-h-screen flex items-center justify-center px-4 py-12">
            <div class="w-full max-w-md">
                <!-- Logo Section -->
                <div class="text-center mb-8">
                    <div class="inline-flex items-center space-x-2 mb-6">
                        <div class="w-10 h-10 bg-gray-900 rounded-full flex items-center justify-center text-white text-lg font-bold">👰</div>
                        <span class="text-xl font-bold text-gray-900">MAKNA WEDDING</span>
                    </div>
                </div>

                <!-- Card -->
                <div class="bg-white rounded-2xl shadow-sm p-8">
                    <div class="text-center mb-8">
                        <h1 class="text-2xl font-medium text-gray-900 mb-2 leading-snug">Forgot password?</h1>
                        <p class="text-sm text-gray-500">No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.</p>
                    </div>

                    @if ($errors->any())
                        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-6">
                            {{ $errors->first() }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('password.email') }}" class="space-y-5">
                        @csrf
                        
                        <!-- Email Field -->
                        <div>
                            <label for="email" class="block text-sm text-gray-700 mb-1.5">Email</label>
                            <input type="email" name="email" id="email" placeholder="Enter your email" required
                                   class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm text-gray-900 outline-none transition focus:border-red-500 focus:ring-2 focus:ring-red-200"
                                   value="{{ old('email') }}">
                            @error('email')
                                <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Send Button -->
                        <button type="submit" class="w-full rounded-full bg-red-600 text-white text-[15px] font-light py-3 px-4 transition hover:bg-red-700">
                            Email Password Reset Link
                        </button>
                    </form>

                    <!-- Back to Login -->
                    <p class="text-center text-sm text-gray-500 mt-6">
                        <a href="{{ route('login') }}" class="text-blue-600 hover:text-blue-700 transition">Back to login</a>
                    </p>
                </div>
            </div>
        </div>
    </body>
</html>
