<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Reset Password - Makna Wedding</title>
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=poppins:400,500,600,700" rel="stylesheet" />
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <style>
            * {
                font-family: 'Poppins', ui-sans-serif, system-ui, sans-serif;
            }
        </style>
    </head>
    <body class="bg-gray-100">
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
                        <h1 class="text-4xl font-bold text-gray-900 mb-3">Reset password</h1>
                        <p class="text-gray-600 text-base">Enter your new password below to reset your account.</p>
                    </div>

                    @if ($errors->any())
                        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-6">
                            {{ $errors->first() }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('password.update') }}" class="space-y-5">
                        @csrf
                        <input type="hidden" name="token" value="{{ $token }}">
                        
                        <!-- Email Field -->
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                            <input type="email" name="email" id="email" placeholder="Enter your email" required class="w-full px-4 py-3 border border-gray-300 rounded-lg text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent transition @error('email') border-red-500 @enderror" value="{{ old('email') }}">
                            @error('email')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Password Field -->
                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Password</label>
                            <input type="password" name="password" id="password" placeholder="Enter new password" required class="w-full px-4 py-3 border border-gray-300 rounded-lg text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent transition @error('password') border-red-500 @enderror">
                            @error('password')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Confirm Password Field -->
                        <div>
                            <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">Confirm Password</label>
                            <input type="password" name="password_confirmation" id="password_confirmation" placeholder="Confirm new password" required class="w-full px-4 py-3 border border-gray-300 rounded-lg text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent transition">
                        </div>

                        <!-- Reset Button -->
                        <button type="submit" class="w-full bg-red-600 hover:bg-red-700 text-white font-semibold py-3 rounded-full transition duration-200">
                            Reset Password
                        </button>
                    </form>

                    <!-- Back to Login -->
                    <p class="text-center text-gray-600 text-sm mt-6">
                        <a href="{{ route('login') }}" class="text-blue-600 hover:text-blue-700 font-semibold">Back to login</a>
                    </p>
                </div>
            </div>
        </div>
    </body>
</html>
