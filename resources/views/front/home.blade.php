@extends('layout.app')

@section('title', 'Makna Wedding - Wedding Organizer & Paket Pernikahan')

@section('body-class', 'bg-cream text-dark')

@section('content')
        @include('layout.header')

        <!-- Hero Section -->
        <section style="background-color: var(--cream); background-image: linear-gradient(rgba(156,175,136,0.08) 1px, transparent 1px), linear-gradient(90deg, rgba(156,175,136,0.08) 1px, transparent 1px); background-size: 40px 40px;">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-stretch">
                <div>
                    <h1 class="text-4xl lg:text-5xl font-bold text-gray-900 leading-tight mt-15">
                        Jangan menunda <span style="color:var(--sage-green)">momen spesial</span> Anda
                    </h1>
                    <p class="text-sm text-gray-600 mb-8">
                        Makna Wedding membantu mewujudkan pernikahan impian Anda dengan paket lengkap dan terjangkau
                    </p>
                    <a href="#packages" class="inline-block text-white font-semibold py-3 px-8 rounded-lg hover:shadow-lg transition" style="background: linear-gradient(to right, var(--sage-green), #7d9469)">
                        Lihat Paket Kami
                    </a>
                </div>
                <div class="rounded-lg p-8 lg:p-12 h-80 lg:h-96 flex items-center justify-center relative">
                    <!-- Animated floating circles with images -->
                    <div class="absolute inset-0 flex items-center justify-center">
                        <!-- Circle 1 - Flowers -->
                        <div class="absolute w-16 h-16 rounded-full overflow-hidden shadow-lg animate-float bg-gradient-to-br from-accent to-pink-200" style="animation-delay: 0s; left: 5%; animation-duration: 17s; bottom: -80px;">
                            <img src="https://images.unsplash.com/photo-1490490849894-425cda7c9f27?w=100&h=100&fit=crop" alt="Wedding flowers" class="w-full h-full object-cover">
                        </div>
                        
                        <!-- Circle 2 - Couple -->
                        <div class="absolute w-20 h-20 rounded-full overflow-hidden shadow-lg animate-float bg-gradient-to-br from-accent to-accent" style="animation-delay: 0.5s; left: 15%; animation-duration: 19s; bottom: 20px;">
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
                        <div class="absolute w-16 h-16 rounded-full overflow-hidden shadow-lg animate-float bg-gradient-to-br from-accent to-rose-300" style="animation-delay: 0.8s; left: 35%; animation-duration: 17.4s; bottom: 80px;">
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
                        <div class="absolute w-14 h-14 rounded-full overflow-hidden shadow-lg animate-float bg-gradient-to-br from-rose-300 to-accent" style="animation-delay: 0.6s; left: 55%; animation-duration: 19.6s; bottom: 40px;">
                            <img src="https://images.unsplash.com/photo-1492684223066-81342ee5ff30?w=100&h=100&fit=crop" alt="Wedding celebration" class="w-full h-full object-cover">
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Partner/Vendor Marquee -->
        <section class="py-8 overflow-hidden" style="background-color: var(--sage-green)">
            <div class="relative flex">
                <div class="flex gap-4 animate-marquee whitespace-nowrap">
                    <!-- Set 1 -->
                    <div class="inline-flex items-center gap-3 bg-white rounded-2xl px-5 py-3 shadow-md min-w-max">
                        <span class="text-2xl">📸</span>
                        <div><p class="font-bold text-gray-900 text-sm">Fotografer</p><p class="text-xs text-gray-500">Abadikan Momen</p></div>
                    </div>
                    <div class="inline-flex items-center gap-3 bg-white rounded-2xl px-5 py-3 shadow-md min-w-max">
                        <span class="text-2xl">🎂</span>
                        <div><p class="font-bold text-gray-900 text-sm">Wedding Cake</p><p class="text-xs text-gray-500">Catering & Kue</p></div>
                    </div>
                    <div class="inline-flex items-center gap-3 bg-white rounded-2xl px-5 py-3 shadow-md min-w-max">
                        <span class="text-2xl">💐</span>
                        <div><p class="font-bold text-gray-900 text-sm">Dekorasi Bunga</p><p class="text-xs text-gray-500">Floral Arrangement</p></div>
                    </div>
                    <div class="inline-flex items-center gap-3 bg-white rounded-2xl px-5 py-3 shadow-md min-w-max">
                        <span class="text-2xl">🎵</span>
                        <div><p class="font-bold text-gray-900 text-sm">Live Music</p><p class="text-xs text-gray-500">Hiburan Pernikahan</p></div>
                    </div>
                    <div class="inline-flex items-center gap-3 bg-white rounded-2xl px-5 py-3 shadow-md min-w-max">
                        <span class="text-2xl">💄</span>
                        <div><p class="font-bold text-gray-900 text-sm">MUA</p><p class="text-xs text-gray-500">Make Up Artist</p></div>
                    </div>
                    <div class="inline-flex items-center gap-3 bg-white rounded-2xl px-5 py-3 shadow-md min-w-max">
                        <span class="text-2xl">🏨</span>
                        <div><p class="font-bold text-gray-900 text-sm">Hotel</p><p class="text-xs text-gray-500">Venue Mewah</p></div>
                    </div>
                    <div class="inline-flex items-center gap-3 bg-white rounded-2xl px-5 py-3 shadow-md min-w-max">
                        <span class="text-2xl">🎬</span>
                        <div><p class="font-bold text-gray-900 text-sm">Videografer</p><p class="text-xs text-gray-500">Cinematic Wedding</p></div>
                    </div>
                    <div class="inline-flex items-center gap-3 bg-white rounded-2xl px-5 py-3 shadow-md min-w-max">
                        <span class="text-2xl">💍</span>
                        <div><p class="font-bold text-gray-900 text-sm">Cincin Nikah</p><p class="text-xs text-gray-500">Jewelry Partner</p></div>
                    </div>
                    <div class="inline-flex items-center gap-3 bg-white rounded-2xl px-5 py-3 shadow-md min-w-max">
                        <span class="text-2xl">🍽️</span>
                        <div><p class="font-bold text-gray-900 text-sm">Catering</p><p class="text-xs text-gray-500">Sajian Terbaik</p></div>
                    </div>
                    <div class="inline-flex items-center gap-3 bg-white rounded-2xl px-5 py-3 shadow-md min-w-max">
                        <span class="text-2xl">🚗</span>
                        <div><p class="font-bold text-gray-900 text-sm">Wedding Car</p><p class="text-xs text-gray-500">Transportasi Pengantin</p></div>
                    </div>
                    <!-- Set 2 (duplikat untuk seamless loop) -->
                    <div class="inline-flex items-center gap-3 bg-white rounded-2xl px-5 py-3 shadow-md min-w-max">
                        <span class="text-2xl">📸</span>
                        <div><p class="font-bold text-gray-900 text-sm">Fotografer</p><p class="text-xs text-gray-500">Abadikan Momen</p></div>
                    </div>
                    <div class="inline-flex items-center gap-3 bg-white rounded-2xl px-5 py-3 shadow-md min-w-max">
                        <span class="text-2xl">🎂</span>
                        <div><p class="font-bold text-gray-900 text-sm">Wedding Cake</p><p class="text-xs text-gray-500">Catering & Kue</p></div>
                    </div>
                    <div class="inline-flex items-center gap-3 bg-white rounded-2xl px-5 py-3 shadow-md min-w-max">
                        <span class="text-2xl">💐</span>
                        <div><p class="font-bold text-gray-900 text-sm">Dekorasi Bunga</p><p class="text-xs text-gray-500">Floral Arrangement</p></div>
                    </div>
                    <div class="inline-flex items-center gap-3 bg-white rounded-2xl px-5 py-3 shadow-md min-w-max">
                        <span class="text-2xl">🎵</span>
                        <div><p class="font-bold text-gray-900 text-sm">Live Music</p><p class="text-xs text-gray-500">Hiburan Pernikahan</p></div>
                    </div>
                    <div class="inline-flex items-center gap-3 bg-white rounded-2xl px-5 py-3 shadow-md min-w-max">
                        <span class="text-2xl">💄</span>
                        <div><p class="font-bold text-gray-900 text-sm">MUA</p><p class="text-xs text-gray-500">Make Up Artist</p></div>
                    </div>
                    <div class="inline-flex items-center gap-3 bg-white rounded-2xl px-5 py-3 shadow-md min-w-max">
                        <span class="text-2xl">🏨</span>
                        <div><p class="font-bold text-gray-900 text-sm">Hotel</p><p class="text-xs text-gray-500">Venue Mewah</p></div>
                    </div>
                    <div class="inline-flex items-center gap-3 bg-white rounded-2xl px-5 py-3 shadow-md min-w-max">
                        <span class="text-2xl">🎬</span>
                        <div><p class="font-bold text-gray-900 text-sm">Videografer</p><p class="text-xs text-gray-500">Cinematic Wedding</p></div>
                    </div>
                    <div class="inline-flex items-center gap-3 bg-white rounded-2xl px-5 py-3 shadow-md min-w-max">
                        <span class="text-2xl">💍</span>
                        <div><p class="font-bold text-gray-900 text-sm">Cincin Nikah</p><p class="text-xs text-gray-500">Jewelry Partner</p></div>
                    </div>
                    <div class="inline-flex items-center gap-3 bg-white rounded-2xl px-5 py-3 shadow-md min-w-max">
                        <span class="text-2xl">🍽️</span>
                        <div><p class="font-bold text-gray-900 text-sm">Catering</p><p class="text-xs text-gray-500">Sajian Terbaik</p></div>
                    </div>
                    <div class="inline-flex items-center gap-3 bg-white rounded-2xl px-5 py-3 shadow-md min-w-max">
                        <span class="text-2xl">🚗</span>
                        <div><p class="font-bold text-gray-900 text-sm">Wedding Car</p><p class="text-xs text-gray-500">Transportasi Pengantin</p></div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Package Section -->
        <section class="py-16 bg-cream" id="packages">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-2xl font-bold" style="color: var(--dark-gray)">Wedding Package</h2>
                    <a href="#" class="text-sm font-medium hover:underline" style="color: var(--sage-green)">Lihat Semua</a>
                </div>

                <div class="flex gap-5 overflow-x-auto pb-4 scrollbar-hide">

                    <!-- Card 1 - No badge -->
                    <div class="flex-none w-64 bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden cursor-pointer hover:shadow-md transition">
                        <div class="relative">
                            <img src="https://images.unsplash.com/photo-1519741497674-611481863552?w=400&h=280&fit=crop" alt="Gold Package" class="w-full h-48 object-cover">
                            <span class="absolute bottom-2 left-2 bg-black/50 text-white text-xs px-2 py-0.5 rounded-full flex items-center gap-1">
                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/></svg>
                                Palembang, ID
                            </span>
                        </div>
                        <div class="p-4">
                            <p class="font-bold text-gray-900 text-base leading-snug mb-0.5">Gold Package</p>
                            <p class="text-xs text-gray-500 mb-3">by <span class="font-medium text-gray-700">Makna Wedding</span> — Paket Gedung</p>
                            <p class="font-bold text-base mb-3" style="color:var(--sage-green)">IDR 15.000.000</p>
                            <div class="flex flex-nowrap gap-1.5 overflow-hidden">
                                <span class="text-[10px] border border-gray-300 rounded-full px-3 py-1 text-gray-600">Prewedding</span>
                                <span class="text-[10px] border border-gray-300 rounded-full px-3 py-1 text-gray-600">Wedding</span>
                                <span class="text-[10px] border border-gray-300 rounded-full px-3 py-1 text-gray-600">+1</span>
                            </div>
                        </div>
                    </div>

                    <!-- Card 2 - Hemat badge -->
                    <div class="flex-none w-64 bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden cursor-pointer hover:shadow-md transition">
                        <div class="relative">
                            <img src="https://images.unsplash.com/photo-1519167758481-83f550bb49b3?w=400&h=280&fit=crop" alt="Hotel Package 500 pax" class="w-full h-48 object-cover">
                            <span class="absolute top-2 left-2 text-white text-xs font-bold px-2.5 py-1 rounded-full leading-tight text-center" style="background-color:var(--sage-green)">
                                Hemat<br>630rb
                            </span>
                            <span class="absolute bottom-2 left-2 bg-black/50 text-white text-xs px-2 py-0.5 rounded-full flex items-center gap-1">
                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/></svg>
                                Palembang, ID
                            </span>
                        </div>
                        <div class="p-4">
                            <p class="font-bold text-gray-900 text-base leading-snug mb-0.5">Aston Grand Ballroom — 500 Pax</p>
                            <p class="text-xs text-gray-500 mb-3">by <span class="font-medium text-gray-700">Makna Wedding</span> — Paket Hotel</p>
                            <p class="text-xs text-gray-400 line-through mb-0.5">IDR 214.480.000</p>
                            <p class="font-bold text-base mb-3" style="color:var(--sage-green)">IDR 213.850.000</p>
                            <div class="flex flex-nowrap gap-1.5 overflow-hidden">
                                <span class="text-[10px] border border-gray-300 rounded-full px-3 py-1 text-gray-600">500 pax</span>
                                <span class="text-[10px] border border-gray-300 rounded-full px-3 py-1 text-gray-600">Hotel</span>
                                <span class="text-[10px] border border-gray-300 rounded-full px-3 py-1 text-gray-600">+1</span>
                            </div>
                        </div>
                    </div>

                    <!-- Card 3 - No badge -->
                    <div class="flex-none w-64 bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden cursor-pointer hover:shadow-md transition">
                        <div class="relative">
                            <img src="https://images.unsplash.com/photo-1578985545062-69928b1d9587?w=400&h=280&fit=crop" alt="Catering Package" class="w-full h-48 object-cover">
                            <span class="absolute bottom-2 left-2 bg-black/50 text-white text-xs px-2 py-0.5 rounded-full flex items-center gap-1">
                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/></svg>
                                Palembang, ID
                            </span>
                        </div>
                        <div class="p-4">
                            <p class="font-bold text-gray-900 text-base leading-snug mb-0.5">Catering Package — 1000 Pax</p>
                            <p class="text-xs text-gray-500 mb-3">by <span class="font-medium text-gray-700">Makna Wedding</span> — Catering & Kue</p>
                            <p class="font-bold text-base mb-3" style="color:var(--sage-green)">IDR 85.000.000</p>
                            <div class="flex flex-nowrap gap-1.5 overflow-hidden">
                                <span class="text-[10px] border border-gray-300 rounded-full px-3 py-1 text-gray-600">1000 pax</span>
                                <span class="text-[10px] border border-gray-300 rounded-full px-3 py-1 text-gray-600">Catering</span>
                            </div>
                        </div>
                    </div>

                    <!-- Card 4 - Hemat + Harga Terbaik badge -->
                    <div class="flex-none w-64 bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden cursor-pointer hover:shadow-md transition">
                        <div class="relative">
                            <img src="https://images.unsplash.com/photo-1606800052052-a08af7148866?w=400&h=280&fit=crop" alt="Beston Hotel 1000 pax" class="w-full h-48 object-cover">
                            <span class="absolute top-2 left-2 text-white text-xs font-bold px-2.5 py-1 rounded-full leading-tight text-center" style="background-color:var(--sage-green)">
                                Hemat<br>6jt
                            </span>
                            <span class="absolute top-2 right-2 text-white text-xs font-semibold px-2.5 py-1 rounded-full flex items-center gap-1" style="background-color:var(--soft-pink); color:var(--dark-gray)">
                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                Harga Terbaik
                            </span>
                            <span class="absolute bottom-2 left-2 bg-black/50 text-white text-xs px-2 py-0.5 rounded-full flex items-center gap-1">
                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/></svg>
                                Palembang, ID
                            </span>
                        </div>
                        <div class="p-4">
                            <p class="font-bold text-gray-900 text-base leading-snug mb-0.5">Beston Hotel — 1000 Pax</p>
                            <p class="text-xs text-gray-500 mb-3">by <span class="font-medium text-gray-700">Makna Wedding</span> — Aula & Function Hall</p>
                            <p class="text-xs text-gray-400 line-through mb-0.5">IDR 299.000.000</p>
                            <p class="font-bold text-base mb-3" style="color:var(--sage-green)">IDR 293.000.000</p>
                            <div class="flex flex-nowrap gap-1.5 overflow-hidden">
                                <span class="text-[10px] border border-gray-300 rounded-full px-3 py-1 text-gray-600">1000 pax</span>
                                <span class="text-[10px] border border-gray-300 rounded-full px-3 py-1 text-gray-600">PROMO</span>
                                <span class="text-[10px] border border-gray-300 rounded-full px-3 py-1 text-gray-600">+1</span>
                            </div>
                        </div>
                    </div>

                    <!-- Card 5 - No badge -->
                    <div class="flex-none w-64 bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden cursor-pointer hover:shadow-md transition">
                        <div class="relative">
                            <img src="https://images.unsplash.com/photo-1519046904884-53103b34b206?w=400&h=280&fit=crop" alt="Prewedding Package" class="w-full h-48 object-cover">
                            <span class="absolute bottom-2 left-2 bg-black/50 text-white text-xs px-2 py-0.5 rounded-full flex items-center gap-1">
                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/></svg>
                                Palembang, ID
                            </span>
                        </div>
                        <div class="p-4">
                            <p class="font-bold text-gray-900 text-base leading-snug mb-0.5">Prewedding Outdoor Package</p>
                            <p class="text-xs text-gray-500 mb-3">by <span class="font-medium text-gray-700">Makna Wedding</span> — Foto & Video Pernikahan</p>
                            <p class="font-bold text-base mb-3" style="color:var(--sage-green)">IDR 35.000.000</p>
                            <div class="flex flex-nowrap gap-1.5 overflow-hidden">
                                <span class="text-[10px] border border-gray-300 rounded-full px-3 py-1 text-gray-600">Photo</span>
                                <span class="text-[10px] border border-gray-300 rounded-full px-3 py-1 text-gray-600">Video</span>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </section>

        <!-- Venue Section -->
        <section class="py-16" style="background-color: var(--light-sage);" id="venues">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-2xl font-bold" style="color: var(--dark-gray)">Venue di Palembang</h2>
                    <a href="#" class="text-sm font-medium hover:underline" style="color: var(--sage-green)">Lihat Semua</a>
                </div>

                <div class="flex gap-5 overflow-x-auto pb-4 scrollbar-hide">

                    <!-- Venue Card 1 -->
                    <div class="flex-none w-64 bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden cursor-pointer hover:shadow-md transition">
                        <div class="relative">
                            <img src="https://images.unsplash.com/photo-1519167758481-83f550bb49b3?w=400&h=280&fit=crop" alt="Aston Hotel" class="w-full h-48 object-cover">
                            <span class="absolute bottom-2 left-2 bg-black/50 text-white text-xs px-2 py-0.5 rounded-full flex items-center gap-1">
                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/></svg>
                                Palembang, ID
                            </span>
                        </div>
                        <div class="p-4">
                            <p class="font-bold text-gray-900 text-base leading-snug mb-0.5">Aston Grand Ballroom</p>
                            <p class="text-xs text-gray-500 mb-3">by <span class="font-medium text-gray-700">Makna Wedding</span> — Hotel</p>
                            <p class="font-bold text-base mb-3" style="color:var(--sage-green)">IDR 213.850.000</p>
                            <div class="flex flex-nowrap gap-1.5 overflow-hidden">
                                <span class="text-[10px] border border-gray-300 rounded-full px-3 py-1 text-gray-600">500 pax</span>
                                <span class="text-[10px] border border-gray-300 rounded-full px-3 py-1 text-gray-600">Hotel</span>
                                <span class="text-[10px] border border-gray-300 rounded-full px-3 py-1 text-gray-600">+1</span>
                            </div>
                        </div>
                    </div>

                    <!-- Venue Card 2 -->
                    <div class="flex-none w-64 bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden cursor-pointer hover:shadow-md transition">
                        <div class="relative">
                            <img src="https://images.unsplash.com/photo-1606800052052-a08af7148866?w=400&h=280&fit=crop" alt="Beston Hotel" class="w-full h-48 object-cover">
                            <span class="absolute top-2 right-2 text-white text-xs font-semibold px-2.5 py-1 rounded-full flex items-center gap-1" style="background-color:var(--soft-pink); color:var(--dark-gray)">
                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                Harga Terbaik
                            </span>
                            <span class="absolute bottom-2 left-2 bg-black/50 text-white text-xs px-2 py-0.5 rounded-full flex items-center gap-1">
                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/></svg>
                                Palembang, ID
                            </span>
                        </div>
                        <div class="p-4">
                            <p class="font-bold text-gray-900 text-base leading-snug mb-0.5">Beston Hotel</p>
                            <p class="text-xs text-gray-500 mb-3">by <span class="font-medium text-gray-700">Makna Wedding</span> — Aula & Function Hall</p>
                            <p class="font-bold text-base mb-3" style="color:var(--sage-green)">IDR 293.000.000</p>
                            <div class="flex flex-nowrap gap-1.5 overflow-hidden">
                                <span class="text-[10px] border border-gray-300 rounded-full px-3 py-1 text-gray-600">1000 pax</span>
                                <span class="text-[10px] border border-gray-300 rounded-full px-3 py-1 text-gray-600">Hotel</span>
                                <span class="text-[10px] border border-gray-300 rounded-full px-3 py-1 text-gray-600">+1</span>
                            </div>
                        </div>
                    </div>

                    <!-- Venue Card 3 -->
                    <div class="flex-none w-64 bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden cursor-pointer hover:shadow-md transition">
                        <div class="relative">
                            <img src="https://images.unsplash.com/photo-1519741497674-611481863552?w=400&h=280&fit=crop" alt="Gedung Serbaguna" class="w-full h-48 object-cover">
                            <span class="absolute bottom-2 left-2 bg-black/50 text-white text-xs px-2 py-0.5 rounded-full flex items-center gap-1">
                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/></svg>
                                Palembang, ID
                            </span>
                        </div>
                        <div class="p-4">
                            <p class="font-bold text-gray-900 text-base leading-snug mb-0.5">Gedung Serbaguna Jakabaring</p>
                            <p class="text-xs text-gray-500 mb-3">by <span class="font-medium text-gray-700">Makna Wedding</span> — Gedung</p>
                            <p class="font-bold text-base mb-3" style="color:var(--sage-green)">IDR 45.000.000</p>
                            <div class="flex flex-nowrap gap-1.5 overflow-hidden">
                                <span class="text-[10px] border border-gray-300 rounded-full px-3 py-1 text-gray-600">800 pax</span>
                                <span class="text-[10px] border border-gray-300 rounded-full px-3 py-1 text-gray-600">Gedung</span>
                            </div>
                        </div>
                    </div>

                    <!-- Venue Card 4 -->
                    <div class="flex-none w-64 bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden cursor-pointer hover:shadow-md transition">
                        <div class="relative">
                            <img src="https://images.unsplash.com/photo-1578985545062-69928b1d9587?w=400&h=280&fit=crop" alt="The Zuri Hotel" class="w-full h-48 object-cover">
                            <span class="absolute top-2 left-2 text-white text-xs font-bold px-2.5 py-1 rounded-full leading-tight text-center" style="background-color:var(--sage-green)">
                                Hemat<br>2jt
                            </span>
                            <span class="absolute bottom-2 left-2 bg-black/50 text-white text-xs px-2 py-0.5 rounded-full flex items-center gap-1">
                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/></svg>
                                Palembang, ID
                            </span>
                        </div>
                        <div class="p-4">
                            <p class="font-bold text-gray-900 text-base leading-snug mb-0.5">The Zuri Hotel Palembang</p>
                            <p class="text-xs text-gray-500 mb-3">by <span class="font-medium text-gray-700">Makna Wedding</span> — Hotel</p>
                            <p class="text-xs text-gray-400 line-through mb-0.5">IDR 125.000.000</p>
                            <p class="font-bold text-base mb-3" style="color:var(--sage-green)">IDR 123.000.000</p>
                            <div class="flex flex-nowrap gap-1.5 overflow-hidden">
                                <span class="text-[10px] border border-gray-300 rounded-full px-3 py-1 text-gray-600">300 pax</span>
                                <span class="text-[10px] border border-gray-300 rounded-full px-3 py-1 text-gray-600">Hotel</span>
                                <span class="text-[10px] border border-gray-300 rounded-full px-3 py-1 text-gray-600">+1</span>
                            </div>
                        </div>
                    </div>

                    <!-- Venue Card 5 -->
                    <div class="flex-none w-64 bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden cursor-pointer hover:shadow-md transition">
                        <div class="relative">
                            <img src="https://images.unsplash.com/photo-1519046904884-53103b34b206?w=400&h=280&fit=crop" alt="Swiss-Belhotel" class="w-full h-48 object-cover">
                            <span class="absolute bottom-2 left-2 bg-black/50 text-white text-xs px-2 py-0.5 rounded-full flex items-center gap-1">
                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/></svg>
                                Palembang, ID
                            </span>
                        </div>
                        <div class="p-4">
                            <p class="font-bold text-gray-900 text-base leading-snug mb-0.5">Swiss-Belhotel Palembang</p>
                            <p class="text-xs text-gray-500 mb-3">by <span class="font-medium text-gray-700">Makna Wedding</span> — Hotel</p>
                            <p class="font-bold text-base mb-3" style="color:var(--sage-green)">IDR 180.000.000</p>
                            <div class="flex flex-nowrap gap-1.5 overflow-hidden">
                                <span class="text-[10px] border border-gray-300 rounded-full px-3 py-1 text-gray-600">400 pax</span>
                                <span class="text-[10px] border border-gray-300 rounded-full px-3 py-1 text-gray-600">Hotel</span>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </section>

        <!-- Venue Review Video Section -->
        <section class="py-16 bg-cream" id="venue-reviews">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-2xl font-bold" style="color: var(--dark-gray)">Venue Review Videos</h2>
                    <a href="#" class="text-sm font-medium hover:underline" style="color: var(--sage-green)">Lihat Semua</a>
                </div>

                <div class="flex gap-4 overflow-x-auto pb-4 scrollbar-hide">

                    <!-- Video Card 1 -->
                    <div class="flex-none rounded-2xl overflow-hidden cursor-pointer relative group" style="width: calc((100% - 4 * 1rem) / 5); min-width: 140px; aspect-ratio: 9 / 16;">
                        <img src="https://picsum.photos/seed/ballroom1/300/533" alt="Aston Ballroom Review" class="w-full h-full object-cover">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/20 to-transparent"></div>
                        <!-- Play button -->
                        <div class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-200">
                            <div class="w-14 h-14 bg-white/90 rounded-full flex items-center justify-center shadow-lg"><svg class="w-6 h-6 text-gray-800 ml-1" fill="currentColor" viewBox="0 0 20 20"><path d="M6.3 2.841A1.5 1.5 0 004 4.11V15.89a1.5 1.5 0 002.3 1.269l9.344-5.89a1.5 1.5 0 000-2.538L6.3 2.84z"/></svg></div>
                        </div>
                        <!-- Text overlay -->
                        <div class="absolute bottom-4 left-4 right-4 text-white">
                            <p class="text-[10px] uppercase tracking-widest opacity-80 mb-1">Introducing</p>
                            <p class="font-bold text-sm leading-tight">ASTON GRAND BALLROOM</p>
                            <p class="text-[10px] opacity-70 mt-1">at Aston Palembang</p>
                        </div>
                    </div>

                    <!-- Video Card 2 -->
                    <div class="flex-none rounded-2xl overflow-hidden cursor-pointer relative group" style="width: calc((100% - 4 * 1rem) / 5); min-width: 140px; aspect-ratio: 9 / 16;">
                        <img src="https://picsum.photos/seed/ballroom2/300/533" alt="Beston Hotel Review" class="w-full h-full object-cover">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/20 to-transparent"></div>
                        <div class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-200">
                            <div class="w-14 h-14 bg-white/90 rounded-full flex items-center justify-center shadow-lg"><svg class="w-6 h-6 text-gray-800 ml-1" fill="currentColor" viewBox="0 0 20 20"><path d="M6.3 2.841A1.5 1.5 0 004 4.11V15.89a1.5 1.5 0 002.3 1.269l9.344-5.89a1.5 1.5 0 000-2.538L6.3 2.84z"/></svg></div>
                        </div>
                        <div class="absolute bottom-4 left-4 right-4 text-white">
                            <p class="text-[10px] uppercase tracking-widest opacity-80 mb-1">Introducing</p>
                            <p class="font-bold text-sm leading-tight">BESTON BALLROOM</p>
                            <p class="text-[10px] opacity-70 mt-1">at Beston Hotel Palembang</p>
                        </div>
                    </div>

                    <!-- Video Card 3 -->
                    <div class="flex-none rounded-2xl overflow-hidden cursor-pointer relative group" style="width: calc((100% - 4 * 1rem) / 5); min-width: 140px; aspect-ratio: 9 / 16;">
                        <img src="https://picsum.photos/seed/ballroom3/300/533" alt="Swiss-Belhotel Review" class="w-full h-full object-cover">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/20 to-transparent"></div>
                        <div class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-200">
                            <div class="w-14 h-14 bg-white/90 rounded-full flex items-center justify-center shadow-lg"><svg class="w-6 h-6 text-gray-800 ml-1" fill="currentColor" viewBox="0 0 20 20"><path d="M6.3 2.841A1.5 1.5 0 004 4.11V15.89a1.5 1.5 0 002.3 1.269l9.344-5.89a1.5 1.5 0 000-2.538L6.3 2.84z"/></svg></div>
                        </div>
                        <div class="absolute bottom-4 left-4 right-4 text-white">
                            <p class="text-[10px] uppercase tracking-widest opacity-80 mb-1">Introducing</p>
                            <p class="font-bold text-sm leading-tight">SWISS-BELHOTEL BALLROOM</p>
                            <p class="text-[10px] opacity-70 mt-1">at Swiss-Belhotel Palembang</p>
                        </div>
                    </div>

                    <!-- Video Card 4 -->
                    <div class="flex-none rounded-2xl overflow-hidden cursor-pointer relative group" style="width: calc((100% - 4 * 1rem) / 5); min-width: 140px; aspect-ratio: 9 / 16;">
                        <img src="https://picsum.photos/seed/ballroom4/300/533" alt="The Zuri Review" class="w-full h-full object-cover">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/20 to-transparent"></div>
                        <div class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-200">
                            <div class="w-14 h-14 bg-white/90 rounded-full flex items-center justify-center shadow-lg"><svg class="w-6 h-6 text-gray-800 ml-1" fill="currentColor" viewBox="0 0 20 20"><path d="M6.3 2.841A1.5 1.5 0 004 4.11V15.89a1.5 1.5 0 002.3 1.269l9.344-5.89a1.5 1.5 0 000-2.538L6.3 2.84z"/></svg></div>
                        </div>
                        <div class="absolute bottom-4 left-4 right-4 text-white">
                            <p class="text-[10px] uppercase tracking-widest opacity-80 mb-1">Introducing</p>
                            <p class="font-bold text-sm leading-tight">THE ZURI GRAND HALL</p>
                            <p class="text-[10px] opacity-70 mt-1">at The Zuri Hotel Palembang</p>
                        </div>
                    </div>

                    <!-- Video Card 5 -->
                    <div class="flex-none rounded-2xl overflow-hidden cursor-pointer relative group" style="width: calc((100% - 4 * 1rem) / 5); min-width: 140px; aspect-ratio: 9 / 16;">
                        <img src="https://picsum.photos/seed/ballroom5/300/533" alt="Jakabaring Convention Review" class="w-full h-full object-cover">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/20 to-transparent"></div>
                        <div class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-200">
                            <div class="w-14 h-14 bg-white/90 rounded-full flex items-center justify-center shadow-lg"><svg class="w-6 h-6 text-gray-800 ml-1" fill="currentColor" viewBox="0 0 20 20"><path d="M6.3 2.841A1.5 1.5 0 004 4.11V15.89a1.5 1.5 0 002.3 1.269l9.344-5.89a1.5 1.5 0 000-2.538L6.3 2.84z"/></svg></div>
                        </div>
                        <div class="absolute bottom-4 left-4 right-4 text-white">
                            <p class="text-[10px] uppercase tracking-widest opacity-80 mb-1">Introducing</p>
                            <p class="font-bold text-sm leading-tight">JAKABARING CONVENTION CENTER</p>
                            <p class="text-[10px] opacity-70 mt-1">at Jakabaring Palembang</p>
                        </div>
                    </div>

                    <!-- Video Card 6 -->
                    <div class="flex-none rounded-2xl overflow-hidden cursor-pointer relative group" style="width: calc((100% - 4 * 1rem) / 5); min-width: 140px; aspect-ratio: 9 / 16;">
                        <img src="https://picsum.photos/seed/ballroom6/300/533" alt="Garden Venue Review" class="w-full h-full object-cover">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/20 to-transparent"></div>
                        <div class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-200">
                            <div class="w-14 h-14 bg-white/90 rounded-full flex items-center justify-center shadow-lg"><svg class="w-6 h-6 text-gray-800 ml-1" fill="currentColor" viewBox="0 0 20 20"><path d="M6.3 2.841A1.5 1.5 0 004 4.11V15.89a1.5 1.5 0 002.3 1.269l9.344-5.89a1.5 1.5 0 000-2.538L6.3 2.84z"/></svg></div>
                        </div>
                        <div class="absolute bottom-4 left-4 right-4 text-white">
                            <p class="text-[10px] uppercase tracking-widest opacity-80 mb-1">Introducing</p>
                            <p class="font-bold text-sm leading-tight">GARDEN BALLROOM</p>
                            <p class="text-[10px] opacity-70 mt-1">at Novotel Palembang</p>
                        </div>
                    </div>

                </div>
            </div>
        </section>

        <!-- Vendor Event & Promo Section -->
        <section class="py-16 bg-white" id="vendor-promo">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-2xl font-bold" style="color: var(--dark-gray)">Vendor Event dan Promo</h2>
                    <a href="#" class="text-sm font-medium hover:underline" style="color: var(--sage-green)">Lihat Semua</a>
                </div>

                <div class="relative">
                    <div class="flex gap-4 overflow-x-auto pb-2 scrollbar-hide" id="vendor-promo-scroll">

                        <!-- Vendor Card 1 -->
                        <div class="flex-none w-56 bg-white border border-gray-200 rounded-2xl overflow-hidden cursor-pointer hover:shadow-md transition relative">
                            <div class="absolute top-3 left-3 z-10">
                                <span class="text-[10px] font-bold uppercase tracking-wide text-white px-2.5 py-1 rounded-full" style="background: var(--soft-pink); color: #c0195e;">VENDOR PROMO</span>
                            </div>
                            <div class="flex flex-col items-center px-6 pt-10 pb-5">
                                <div class="w-24 h-24 rounded-full overflow-hidden border-2 border-gray-100 mb-4">
                                    <img src="https://picsum.photos/seed/vendor1/200/200" alt="Aston Palembang" class="w-full h-full object-cover">
                                </div>
                                <p class="font-bold text-sm text-center uppercase text-gray-900 leading-snug mb-1">ASTON GRAND BALLROOM</p>
                                <p class="text-xs text-gray-500 text-center mb-2">Hotel Wedding Venue</p>
                                <p class="text-xs text-gray-500 flex items-center gap-1">
                                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/></svg>
                                    Palembang
                                </p>
                            </div>
                        </div>

                        <!-- Vendor Card 2 -->
                        <div class="flex-none w-56 bg-white border border-gray-200 rounded-2xl overflow-hidden cursor-pointer hover:shadow-md transition relative">
                            <div class="absolute top-3 left-3 z-10">
                                <span class="text-[10px] font-bold uppercase tracking-wide text-white px-2.5 py-1 rounded-full" style="background: var(--soft-pink); color: #c0195e;">VENDOR PROMO</span>
                            </div>
                            <div class="flex flex-col items-center px-6 pt-10 pb-5">
                                <div class="w-24 h-24 rounded-full overflow-hidden border-2 border-gray-100 mb-4">
                                    <img src="https://picsum.photos/seed/vendor2/200/200" alt="The Zuri Hotel" class="w-full h-full object-cover">
                                </div>
                                <p class="font-bold text-sm text-center uppercase text-gray-900 leading-snug mb-1">THE ZURI PALEMBANG</p>
                                <p class="text-xs text-gray-500 text-center mb-2">Hotel Wedding Venue</p>
                                <p class="text-xs text-gray-500 flex items-center gap-1">
                                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/></svg>
                                    Palembang
                                </p>
                            </div>
                        </div>

                        <!-- Vendor Card 3 -->
                        <div class="flex-none w-56 bg-white border border-gray-200 rounded-2xl overflow-hidden cursor-pointer hover:shadow-md transition relative">
                            <div class="absolute top-3 left-3 z-10">
                                <span class="text-[10px] font-bold uppercase tracking-wide text-white px-2.5 py-1 rounded-full" style="background: var(--soft-pink); color: #c0195e;">VENDOR PROMO</span>
                            </div>
                            <div class="flex flex-col items-center px-6 pt-10 pb-5">
                                <div class="w-24 h-24 rounded-full overflow-hidden border-2 border-gray-100 mb-4">
                                    <img src="https://picsum.photos/seed/vendor3/200/200" alt="Swiss-Belhotel" class="w-full h-full object-cover">
                                </div>
                                <p class="font-bold text-sm text-center uppercase text-gray-900 leading-snug mb-1">SWISS-BELHOTEL PALEMBANG</p>
                                <p class="text-xs text-gray-500 text-center mb-2">Hotel Wedding Venue</p>
                                <p class="text-xs text-gray-500 flex items-center gap-1">
                                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/></svg>
                                    Palembang
                                </p>
                            </div>
                        </div>

                        <!-- Vendor Card 4 -->
                        <div class="flex-none w-56 bg-white border border-gray-200 rounded-2xl overflow-hidden cursor-pointer hover:shadow-md transition relative">
                            <div class="absolute top-3 left-3 z-10">
                                <span class="text-[10px] font-bold uppercase tracking-wide text-white px-2.5 py-1 rounded-full" style="background: var(--soft-pink); color: #c0195e;">VENDOR PROMO</span>
                            </div>
                            <div class="flex flex-col items-center px-6 pt-10 pb-5">
                                <div class="w-24 h-24 rounded-full overflow-hidden border-2 border-gray-100 mb-4">
                                    <img src="https://picsum.photos/seed/vendor4/200/200" alt="Makna Wedding Organizer" class="w-full h-full object-cover">
                                </div>
                                <p class="font-bold text-sm text-center uppercase text-gray-900 leading-snug mb-1">MAKNA WEDDING ORGANIZER</p>
                                <p class="text-xs text-gray-500 text-center mb-2">Wedding Planner & Organizer</p>
                                <p class="text-xs text-gray-500 flex items-center gap-1">
                                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/></svg>
                                    Palembang
                                </p>
                            </div>
                        </div>

                        <!-- View All Card -->
                        <div class="flex-none w-56 bg-white border border-gray-200 rounded-2xl cursor-pointer hover:shadow-md transition flex flex-col items-center justify-center gap-3 py-10">
                            <div class="w-14 h-14 rounded-full border-2 border-gray-400 flex items-center justify-center">
                                <svg class="w-6 h-6 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                            </div>
                            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide text-center">VIEW ALL EVENT PROMO</p>
                        </div>

                    </div>

                    <!-- Arrow prev -->
                    <button onclick="document.getElementById('vendor-promo-scroll').scrollBy({left: -300, behavior: 'smooth'})"
                            class="absolute left-0 top-1/2 -translate-y-1/2 -translate-x-4 w-10 h-10 bg-white rounded-full shadow-md flex items-center justify-center hover:shadow-lg transition z-10">
                        <svg class="w-5 h-5 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                    </button>
                    <!-- Arrow next -->
                    <button onclick="document.getElementById('vendor-promo-scroll').scrollBy({left: 300, behavior: 'smooth'})"
                            class="absolute right-0 top-1/2 -translate-y-1/2 translate-x-4 w-10 h-10 bg-white rounded-full shadow-md flex items-center justify-center hover:shadow-lg transition z-10">
                        <svg class="w-5 h-5 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </button>
                </div>

            </div>
        </section>

        <!-- Real Wedding Section -->
        <section class="py-16" style="background: var(--cream)" id="real-wedding">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-2xl font-bold" style="color: var(--dark-gray)">Real Wedding</h2>
                    <a href="#" class="text-sm font-medium hover:underline" style="color: var(--sage-green)">Lihat Semua</a>
                </div>

                <div class="relative">
                    <div class="flex gap-4 overflow-x-auto pb-2 scrollbar-hide" id="real-wedding-scroll">

                        <!-- Wedding Card 1 -->
                        <div class="flex-none rounded-2xl overflow-hidden cursor-pointer relative group" style="width: calc((100% - 4 * 1rem) / 5); min-width: 160px; aspect-ratio: 3 / 4;">
                            <img src="https://picsum.photos/seed/wedding1/400/533" alt="Reza & Aulia" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
                            <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/10 to-transparent"></div>
                            <div class="absolute bottom-4 left-4 right-4 text-white">
                                <span class="inline-block text-[9px] border border-white/70 rounded-full px-2 py-0.5 mb-2 bg-black/20 backdrop-blur-sm">Editor's Collection</span>
                                <p class="font-bold text-base leading-tight tracking-wide uppercase">REZA & AULIA</p>
                            </div>
                        </div>

                        <!-- Wedding Card 2 -->
                        <div class="flex-none rounded-2xl overflow-hidden cursor-pointer relative group" style="width: calc((100% - 4 * 1rem) / 5); min-width: 160px; aspect-ratio: 3 / 4;">
                            <img src="https://picsum.photos/seed/wedding2/400/533" alt="Bagas & Tiara" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
                            <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/10 to-transparent"></div>
                            <div class="absolute bottom-4 left-4 right-4 text-white">
                                <span class="inline-block text-[9px] border border-white/70 rounded-full px-2 py-0.5 mb-2 bg-black/20 backdrop-blur-sm">Editor's Collection</span>
                                <p class="font-bold text-base leading-tight tracking-wide uppercase">BAGAS & TIARA</p>
                            </div>
                        </div>

                        <!-- Wedding Card 3 -->
                        <div class="flex-none rounded-2xl overflow-hidden cursor-pointer relative group" style="width: calc((100% - 4 * 1rem) / 5); min-width: 160px; aspect-ratio: 3 / 4;">
                            <img src="https://picsum.photos/seed/wedding3/400/533" alt="Dimas & Sari" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
                            <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/10 to-transparent"></div>
                            <div class="absolute bottom-4 left-4 right-4 text-white">
                                <span class="inline-block text-[9px] border border-white/70 rounded-full px-2 py-0.5 mb-2 bg-black/20 backdrop-blur-sm">Editor's Collection</span>
                                <p class="font-bold text-base leading-tight tracking-wide uppercase">DIMAS & SARI</p>
                                <p class="text-[11px] opacity-70 mt-1">Hotel Santika Palembang</p>
                            </div>
                        </div>

                        <!-- Wedding Card 4 -->
                        <div class="flex-none rounded-2xl overflow-hidden cursor-pointer relative group" style="width: calc((100% - 4 * 1rem) / 5); min-width: 160px; aspect-ratio: 3 / 4;">
                            <img src="https://picsum.photos/seed/wedding4/400/533" alt="Andi & Dewi" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
                            <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/10 to-transparent"></div>
                            <div class="absolute bottom-4 left-4 right-4 text-white">
                                <span class="inline-block text-[9px] border border-white/70 rounded-full px-2 py-0.5 mb-2 bg-black/20 backdrop-blur-sm">Editor's Collection</span>
                                <p class="font-bold text-base leading-tight tracking-wide uppercase">ANDI & DEWI</p>
                                <p class="text-[11px] opacity-70 mt-1">Aston Palembang</p>
                            </div>
                        </div>

                        <!-- Wedding Card 5 -->
                        <div class="flex-none rounded-2xl overflow-hidden cursor-pointer relative group" style="width: calc((100% - 4 * 1rem) / 5); min-width: 160px; aspect-ratio: 3 / 4;">
                            <img src="https://picsum.photos/seed/wedding5/400/533" alt="Fajar & Nisa" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
                            <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/10 to-transparent"></div>
                            <div class="absolute bottom-4 left-4 right-4 text-white">
                                <span class="inline-block text-[9px] border border-white/70 rounded-full px-2 py-0.5 mb-2 bg-black/20 backdrop-blur-sm">Editor's Collection</span>
                                <p class="font-bold text-base leading-tight tracking-wide uppercase">FAJAR & NISA</p>
                            </div>
                        </div>

                        <!-- Wedding Card 6 -->
                        <div class="flex-none rounded-2xl overflow-hidden cursor-pointer relative group" style="width: calc((100% - 4 * 1rem) / 5); min-width: 160px; aspect-ratio: 3 / 4;">
                            <img src="https://picsum.photos/seed/wedding6/400/533" alt="Hendra & Putri" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
                            <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/10 to-transparent"></div>
                            <div class="absolute bottom-4 left-4 right-4 text-white">
                                <span class="inline-block text-[9px] border border-white/70 rounded-full px-2 py-0.5 mb-2 bg-black/20 backdrop-blur-sm">Editor's Collection</span>
                                <p class="font-bold text-base leading-tight tracking-wide uppercase">HENDRA & PUTRI</p>
                                <p class="text-[11px] opacity-70 mt-1">The Zuri Hotel Palembang</p>
                            </div>
                        </div>

                    </div>

                    <!-- Arrow next -->
                    <button onclick="document.getElementById('real-wedding-scroll').scrollBy({left: 300, behavior: 'smooth'})"
                            class="absolute right-0 top-1/2 -translate-y-1/2 translate-x-4 w-10 h-10 bg-white rounded-full shadow-md flex items-center justify-center hover:shadow-lg transition z-10">
                        <svg class="w-5 h-5 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </button>
                </div>

            </div>
        </section>

        <!-- CTA Banner -->
        <section class="relative overflow-hidden py-10" style="background: var(--sage-green)">
            <!-- Decorative shapes -->
            <div class="absolute -top-10 -right-10 w-64 h-64 rounded-full opacity-20" style="background: var(--light-sage)"></div>
            <div class="absolute -bottom-16 right-32 w-80 h-80 rounded-full opacity-15" style="background: var(--light-sage)"></div>

            <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col lg:flex-row items-center gap-8">

                <!-- Left: Title -->
                <div class="flex-shrink-0 lg:w-64">
                    <p class="font-bold text-lg leading-snug" style="color: var(--cream)">Persiapkan Pernikahan dengan Beragam Kemudahan &amp; Penawaran Ekslusif</p>
                </div>

                <!-- Middle: Features -->
                <div class="flex flex-wrap lg:flex-nowrap gap-6 flex-1 justify-center">
                    <div class="flex items-center gap-2" style="color: var(--cream)">
                        <div class="w-8 h-8 rounded-full flex items-center justify-center flex-shrink-0" style="background: rgba(250,243,231,0.25)">
                            <svg class="w-4 h-4" style="color: var(--cream)" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                        </div>
                        <p class="text-xs font-medium leading-snug">Vendor &amp; Produk<br>Pernikahan Terlengkap</p>
                    </div>
                    <div class="flex items-center gap-2" style="color: var(--cream)">
                        <div class="w-8 h-8 rounded-full flex items-center justify-center flex-shrink-0" style="background: rgba(250,243,231,0.25)">
                            <svg class="w-4 h-4" style="color: var(--cream)" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd"/></svg>
                        </div>
                        <p class="text-xs font-medium leading-snug">Sesuaikan Pesanan<br>dengan Impian Anda</p>
                    </div>
                    <div class="flex items-center gap-2" style="color: var(--cream)">
                        <div class="w-8 h-8 rounded-full flex items-center justify-center flex-shrink-0" style="background: rgba(250,243,231,0.25)">
                            <svg class="w-4 h-4" style="color: var(--cream)" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5 2a2 2 0 00-2 2v14l3.5-2 3.5 2 3.5-2 3.5 2V4a2 2 0 00-2-2H5zm2.5 3a1.5 1.5 0 100 3 1.5 1.5 0 000-3zm6.207.293a1 1 0 00-1.414 0l-6 6a1 1 0 101.414 1.414l6-6a1 1 0 000-1.414zM12.5 10a1.5 1.5 0 100 3 1.5 1.5 0 000-3z" clip-rule="evenodd"/></svg>
                        </div>
                        <p class="text-xs font-medium leading-snug">Promo Eksklusif &amp;<br>Hadiah Menarik</p>
                    </div>
                    <div class="flex items-center gap-2" style="color: var(--cream)">
                        <div class="w-8 h-8 rounded-full flex items-center justify-center flex-shrink-0" style="background: rgba(250,243,231,0.25)">
                            <svg class="w-4 h-4" style="color: var(--cream)" fill="currentColor" viewBox="0 0 20 20"><path d="M8.433 7.418c.155-.103.346-.196.567-.267v1.698a2.305 2.305 0 01-.567-.267C8.07 8.34 8 8.114 8 8c0-.114.07-.34.433-.582zM11 12.849v-1.698c.22.071.412.164.567.267.364.243.433.468.433.582 0 .114-.07.34-.433.582a2.305 2.305 0 01-.567.267z"/><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-13a1 1 0 10-2 0v.092a4.535 4.535 0 00-1.676.662C6.602 6.234 6 7.009 6 8c0 .99.602 1.765 1.324 2.246.48.32 1.054.545 1.676.662v1.941c-.391-.127-.68-.317-.843-.504a1 1 0 10-1.51 1.31c.562.649 1.413 1.077 2.353 1.253V15a1 1 0 102 0v-.092a4.535 4.535 0 001.676-.662C13.398 13.766 14 12.991 14 12c0-.99-.602-1.765-1.324-2.246A4.535 4.535 0 0011 9.092V7.151c.391.127.68.317.843.504a1 1 0 101.511-1.31c-.563-.649-1.413-1.077-2.354-1.253V5z" clip-rule="evenodd"/></svg>
                        </div>
                        <p class="text-xs font-medium leading-snug">Cicilan 0% hingga 24<br>bulan</p>
                    </div>
                </div>

                <!-- Right: CTA Button -->
                <div class="flex-shrink-0">
                    <a href="#" class="inline-block px-6 py-3 rounded-xl font-semibold text-sm transition hover:opacity-90" style="background: var(--cream); color: var(--dark-gray)">
                        Daftar Sekarang
                    </a>
                </div>

            </div>
        </section>

        <!-- Blog Section -->
        <section class="py-16" style="background: var(--light-sage)" id="blog">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

                <div class="flex items-center justify-between mb-8">
                    <h2 class="text-2xl font-bold" style="color: var(--dark-gray)">Jangan Lewatkan Blog Post Ini</h2>
                    <a href="#" class="text-sm font-medium hover:underline" style="color: var(--sage-green)">Lihat Semua</a>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                    <!-- Featured Posts (2 cards side by side) -->
                    <div class="lg:col-span-2 grid grid-cols-1 sm:grid-cols-2 gap-6">

                        <!-- Blog Card 1 -->
                        <div class="bg-white rounded-2xl overflow-hidden cursor-pointer hover:shadow-md transition group">
                            <div class="overflow-hidden">
                                <img src="https://picsum.photos/seed/blog1/600/400" alt="Blog 1" class="w-full h-48 object-cover transition-transform duration-500 group-hover:scale-105">
                            </div>
                            <div class="p-4">
                                <div class="flex items-center gap-2 mb-2">
                                    <span class="text-xs font-semibold" style="color: var(--sage-green)">Relationship Tips</span>
                                    <span class="text-xs text-gray-400">· Mar 17, 2026 | 35 views</span>
                                </div>
                                <p class="font-bold text-gray-900 text-sm leading-snug hover:underline">8 Etika Silaturahmi ke Rumah Calon Mertua demi Beri Kesan Positif</p>
                            </div>
                        </div>

                        <!-- Blog Card 2 -->
                        <div class="bg-white rounded-2xl overflow-hidden cursor-pointer hover:shadow-md transition group">
                            <div class="overflow-hidden">
                                <img src="https://picsum.photos/seed/blog2/600/400" alt="Blog 2" class="w-full h-48 object-cover transition-transform duration-500 group-hover:scale-105">
                            </div>
                            <div class="p-4">
                                <div class="flex items-center gap-2 mb-2">
                                    <span class="text-xs font-semibold" style="color: var(--sage-green)">Wedding Ideas</span>
                                    <span class="text-xs text-gray-400">· Mar 16, 2026 | 30 views</span>
                                </div>
                                <p class="font-bold text-gray-900 text-sm leading-snug hover:underline">Begini Cara Atur THR untuk DP Vendor tanpa Ganggu Budget Lebaran</p>
                            </div>
                        </div>

                    </div>

                    <!-- Artikel Terpopuler Sidebar -->
                    <div class="lg:col-span-1">
                        <h3 class="text-base font-bold mb-4" style="color: var(--dark-gray)">Artikel Terpopuler</h3>
                        <div class="flex flex-col gap-4">

                            <!-- Popular Item 1 -->
                            <a href="#" class="flex gap-3 group cursor-pointer">
                                <img src="https://picsum.photos/seed/popular1/160/120" alt="Popular 1" class="w-16 h-14 rounded-xl object-cover flex-shrink-0">
                                <div>
                                    <p class="text-[11px] font-semibold mb-0.5" style="color: var(--sage-green)">Wedding Ideas <span class="text-gray-400 font-normal">· 577271 views</span></p>
                                    <p class="text-xs font-semibold text-gray-800 leading-snug group-hover:underline">12 Tahap dalam Susunan Acara Lamaran Pernikahan</p>
                                </div>
                            </a>

                            <div class="border-t border-gray-200"></div>

                            <!-- Popular Item 2 -->
                            <a href="#" class="flex gap-3 group cursor-pointer">
                                <img src="https://picsum.photos/seed/popular2/160/120" alt="Popular 2" class="w-16 h-14 rounded-xl object-cover flex-shrink-0">
                                <div>
                                    <p class="text-[11px] font-semibold mb-0.5" style="color: var(--sage-green)">Wedding Ideas <span class="text-gray-400 font-normal">· 560122 views</span></p>
                                    <p class="text-xs font-semibold text-gray-800 leading-snug group-hover:underline">18 Ide Unik dan Romantis untuk Melamar Sang Kekasih</p>
                                </div>
                            </a>

                            <div class="border-t border-gray-200"></div>

                            <!-- Popular Item 3 -->
                            <a href="#" class="flex gap-3 group cursor-pointer">
                                <img src="https://picsum.photos/seed/popular3/160/120" alt="Popular 3" class="w-16 h-14 rounded-xl object-cover flex-shrink-0">
                                <div>
                                    <p class="text-[11px] font-semibold mb-0.5" style="color: var(--sage-green)">Wedding Ideas <span class="text-gray-400 font-normal">· 443946 views</span></p>
                                    <p class="text-xs font-semibold text-gray-800 leading-snug group-hover:underline">Panduan Rangkaian Prosesi Pernikahan Adat Jawa Beserta Makna di Balik Setiap Ritualnya</p>
                                </div>
                            </a>

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
