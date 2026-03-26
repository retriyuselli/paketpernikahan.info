<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Forgot Password - Makna Wedding</title>
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
                        <h1 style="font-size: 1.5rem; font-weight: 500; color: #111827; margin-bottom: 0.5rem; line-height: 1.3;">Forgot password?</h1>
                        <p style="font-size: 0.875rem; color: #6B7280;">No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.</p>
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
                            <label for="email" style="font-size: 0.875rem; font-weight: 300; color: #374151; display: block; margin-bottom: 0.375rem;">Email</label>
                            <input type="email" name="email" id="email" placeholder="Enter your email" required style="width: 100%; padding: 0.625rem 1rem; border: 1px solid #D1D5DB; border-radius: 0.5rem; font-size: 0.875rem; color: #111827; outline: none; transition: border-color 0.15s;" onfocus="this.style.borderColor='#EF4444'; this.style.boxShadow='0 0 0 2px rgba(239,68,68,0.2)'" onblur="this.style.borderColor='#D1D5DB'; this.style.boxShadow='none'" value="{{ old('email') }}">
                            @error('email')
                                <p style="font-size: 0.75rem; color: #EF4444; margin-top: 0.25rem;">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Send Button -->
                        <button type="submit" style="width: 100%; background-color: #DC2626; color: white; font-size: 0.9375rem; font-weight: 300; padding: 0.75rem 1rem; border-radius: 9999px; border: none; cursor: pointer; transition: background-color 0.15s;" onmouseover="this.style.backgroundColor='#B91C1C'" onmouseout="this.style.backgroundColor='#DC2626'">
                            Email Password Reset Link
                        </button>
                    </form>

                    <!-- Back to Login -->
                    <p style="text-align: center; font-size: 0.875rem; color: #6B7280; margin-top: 1.5rem;">
                        <a href="{{ route('login') }}" style="color: #2563EB; font-weight: 300; text-decoration: none;" onmouseover="this.style.color='#1D4ED8'" onmouseout="this.style.color='#2563EB'">Back to login</a>
                    </p>
                </div>
            </div>
        </div>
    </body>
</html>
