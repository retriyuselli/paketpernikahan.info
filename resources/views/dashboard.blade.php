<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Dashboard - Makna Wedding</title>
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=poppins:400,500,600,700" rel="stylesheet" />
        <style>
            * {
                font-family: 'Poppins', ui-sans-serif, system-ui, sans-serif;
            }
        </style>
    </head>
    <body class="bg-gray-50">
        <div class="min-h-screen">
            <!-- Header -->
            <header class="bg-white shadow">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
                    <div class="flex items-center justify-between">
                        <a href="/" class="text-3xl font-bold">
                            <span class="text-red-600">M</span><span class="text-red-500">O</span>
                        </a>
                        <a href="{{ url('/') }}" class="text-gray-600 hover:text-gray-900">Kembali ke beranda</a>
                    </div>
                </div>
            </header>

            <!-- Main Content -->
            <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
                <div class="bg-white rounded-lg shadow p-8">
                    <h1 class="text-3xl font-bold text-gray-900 mb-4">Selamat datang di Dashboard!</h1>
                    <p class="text-gray-600 mb-6">Ini adalah area dashboard untuk pengguna terdaftar.</p>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="bg-gradient-to-br from-red-50 to-pink-50 rounded-lg p-6 border border-red-200">
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">Paket Saya</h3>
                            <p class="text-gray-600">Lihat paket pernikahan yang Anda pesan</p>
                        </div>

                        <div class="bg-gradient-to-br from-orange-50 to-yellow-50 rounded-lg p-6 border border-orange-200">
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">Pesanan</h3>
                            <p class="text-gray-600">Kelola pesanan dan pemesanan Anda</p>
                        </div>

                        <div class="bg-gradient-to-br from-blue-50 to-cyan-50 rounded-lg p-6 border border-blue-200">
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">Profil</h3>
                            <p class="text-gray-600">Perbarui informasi profil Anda</p>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </body>
</html>
