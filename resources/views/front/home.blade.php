@extends('layout.app')

@section('title', 'Makna Wedding - Wedding Organizer & Paket Pernikahan')

@section('body-class', 'bg-white text-gray-900')

@section('content')
        @include('layout.header')

        <!-- Hero Section -->
        <section class="w-full py-16 lg:py-24" style="background-image: linear-gradient(rgba(200, 200, 200, 0.08) 1px, transparent 1px), linear-gradient(90deg, rgba(200, 200, 200, 0.08) 1px, transparent 1px); background-size: 40px 40px;">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-stretch">
                <div>
                    <h1 class="text-4xl lg:text-5xl font-bold text-gray-900 mb-4 leading-tight">
                        Jangan menunda <span class="text-red-600">momen spesial</span> Anda
                    </h1>
                    <p class="text-sm text-gray-600 mb-8">
                        Makna Wedding membantu mewujudkan pernikahan impian Anda dengan paket lengkap dan terjangkau
                    </p>
                    <a href="#packages" class="inline-block bg-gradient-to-r from-red-600 to-red-700 text-white font-semibold py-3 px-8 rounded-lg hover:shadow-lg transition">
                        Lihat Paket Kami
                    </a>
                </div>
                <div class="rounded-lg p-8 lg:p-12 h-80 lg:h-96 flex items-center justify-center relative">
                    <!-- Animated floating circles with images -->
                    <div class="absolute inset-0 flex items-center justify-center">
                        <!-- Circle 1 - Flowers -->
                        <div class="absolute w-16 h-16 rounded-full overflow-hidden shadow-lg animate-float bg-gradient-to-br from-pink-200 to-red-200" style="animation-delay: 0s; left: 5%; animation-duration: 17s; bottom: -80px;">
                            <img src="https://images.unsplash.com/photo-1490490849894-425cda7c9f27?w=100&h=100&fit=crop" alt="Wedding flowers" class="w-full h-full object-cover">
                        </div>
                        
                        <!-- Circle 2 - Couple -->
                        <div class="absolute w-20 h-20 rounded-full overflow-hidden shadow-lg animate-float bg-gradient-to-br from-red-300 to-pink-300" style="animation-delay: 0.5s; left: 15%; animation-duration: 19s; bottom: 20px;">
                            <img src="https://images.unsplash.com/photo-1519741497674-611481863552?w=120&h=120&fit=crop" alt="Happy couple" class="w-full h-full object-cover">
                        </div>  
                        
                        <!-- Circle 3 - Ring -->
                        <div class="absolute w-14 h-14 rounded-full overflow-hidden shadow-lg animate-float bg-gradient-to-br from-yellow-200 to-amber-300" style="animation-delay: 1s; right: 10%; animation-duration: 21s; bottom: -120px;">
                            <img src="https://images.unsplash.com/photo-1599643478518-a784e5dc4c8f?w=100&h=100&fit=crop" alt="Wedding ring" class="w-full h-full object-cover">
                        </div>
                        
                        <!-- Circle 4 - Cake -->
                        <div class="absolute w-16 h-16 rounded-full overflow-hidden shadow-lg animate-float bg-gradient-to-br from-amber-200 to-yellow-200" style="animation-delay: 1.5s; right: 20%; animation-duration: 18s; bottom: 180px;">
                            <img src="https://images.unsplash.com/photo-1578985545062-69928b1d9587?w=100&h=100&fit=crop" alt="Wedding cake" class="w-full h-full object-cover">
                        </div>
                        
                        <!-- Circle 5 - Celebration -->
                        <div class="absolute w-18 h-18 rounded-full overflow-hidden shadow-lg animate-float bg-gradient-to-br from-purple-300 to-blue-300" style="animation-delay: 0.3s; left: 25%; animation-duration: 20s; bottom: -40px;">
                            <img src="https://images.unsplash.com/photo-1517457373614-b7152f800fd1?w=120&h=120&fit=crop" alt="Wedding celebration" class="w-full h-full object-cover">
                        </div>

                        <!-- Circle 6 - Bride -->
                        <div class="absolute w-16 h-16 rounded-full overflow-hidden shadow-lg animate-float bg-gradient-to-br from-pink-300 to-rose-300" style="animation-delay: 0.8s; left: 35%; animation-duration: 17.4s; bottom: 80px;">
                            <img src="https://images.unsplash.com/photo-1531746020798-e6953c6e8e04?w=100&h=100&fit=crop" alt="Wedding bride" class="w-full h-full object-cover">
                        </div>

                        <!-- Circle 7 - Groom -->
                        <div class="absolute w-14 h-14 rounded-full overflow-hidden shadow-lg animate-float bg-gradient-to-br from-blue-300 to-slate-400" style="animation-delay: 1.2s; right: 30%; animation-duration: 20.6s; bottom: -100px;">
                            <img src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=100&h=100&fit=crop" alt="Wedding groom" class="w-full h-full object-cover">
                        </div>

                        <!-- Circle 8 - Church/Venue -->
                        <div class="absolute w-20 h-20 rounded-full overflow-hidden shadow-lg animate-float bg-gradient-to-br from-blue-200 to-cyan-200" style="animation-delay: 0.2s; left: 45%; animation-duration: 21.4s; bottom: 140px;">
                            <img src="https://images.unsplash.com/photo-1519046904884-53103b34b206?w=120&h=120&fit=crop" alt="Wedding venue" class="w-full h-full object-cover">
                        </div>

                        <!-- Circle 9 - Lights/Stars -->
                        <div class="absolute w-16 h-16 rounded-full overflow-hidden shadow-lg animate-float bg-gradient-to-br from-purple-400 to-indigo-500" style="animation-delay: 1.8s; right: 5%; animation-duration: 18.6s; bottom: -60px;">
                            <img src="https://images.unsplash.com/photo-1511379938547-c1f69b13d835?w=100&h=100&fit=crop" alt="Wedding lights" class="w-full h-full object-cover">
                        </div>

                        <!-- Circle 10 - Celebration -->
                        <div class="absolute w-14 h-14 rounded-full overflow-hidden shadow-lg animate-float bg-gradient-to-br from-rose-300 to-pink-300" style="animation-delay: 0.6s; left: 55%; animation-duration: 19.6s; bottom: 40px;">
                            <img src="https://images.unsplash.com/photo-1492684223066-81342ee5ff30?w=100&h=100&fit=crop" alt="Wedding celebration" class="w-full h-full object-cover">
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Footer -->
        <footer class="bg-gray-900 text-gray-300 py-12">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-8 mb-8">
                    <div>
                        <h3 class="text-white font-bold mb-4 text-xl">MW</h3>
                        <p class="text-sm">Wedding organizer terpercaya di Sumatera Selatan</p>
                    </div>
                    <div>
                        <h4 class="text-white font-semibold mb-4">Kategori</h4>
                        <ul class="text-sm space-y-2">
                            <li><a href="#" class="hover:text-white transition">Gedung</a></li>
                            <li><a href="#" class="hover:text-white transition">Hotel</a></li>
                            <li><a href="#" class="hover:text-white transition">Rumah</a></li>
                            <li><a href="#" class="hover:text-white transition">WO Only</a></li>
                        </ul>
                    </div>
                    <div>
                        <h4 class="text-white font-semibold mb-4">Layanan</h4>
                        <ul class="text-sm space-y-2">
                            <li><a href="#" class="hover:text-white transition">Paket Pernikahan</a></li>
                            <li><a href="#" class="hover:text-white transition">Promo Terbaru</a></li>
                            <li><a href="#" class="hover:text-white transition">Blog</a></li>
                            <li><a href="#" class="hover:text-white transition">Hubungi Kami</a></li>
                        </ul>
                    </div>
                    <div>
                        <h4 class="text-white font-semibold mb-4">Kontak</h4>
                        <ul class="text-sm space-y-2">
                            <li>office@makruwedding.id</li>
                            <li>+62 812-7893-2624</li>
                            <li>Palembang, Sumatera Selatan</li>
                        </ul>
                    </div>
                </div>
                
                <div class="border-t border-gray-800 pt-8 text-center text-sm">
                    <p>&copy; 2024 Makna Wedding. All rights reserved.</p>
                </div>
            </div>
        </footer>

    </body>
</html>
@endsection
