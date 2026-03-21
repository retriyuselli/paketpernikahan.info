@extends('layout.app')

@section('title', 'Makna Wedding - Wedding Organizer & Paket Pernikahan')

@section('body-class', 'bg-white text-gray-900')

@section('content')
        @include('layout.header')

        <!-- Hero Section -->
        <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 lg:py-24">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                <div>
                    <h1 class="text-4xl lg:text-5xl font-bold text-gray-900 mb-4 leading-tight">
                        Jangan menunda <span class="text-red-600">momen spesial</span> Anda
                    </h1>
                    <p class="text-lg text-gray-600 mb-8">
                        Makna Wedding membantu mewujudkan pernikahan impian Anda dengan paket lengkap dan terjangkau
                    </p>
                    <a href="#packages" class="inline-block bg-gradient-to-r from-red-600 to-red-700 text-white font-semibold py-3 px-8 rounded-lg hover:shadow-lg transition">
                        Lihat Paket Kami
                    </a>
                </div>
                <div class="bg-gradient-to-br from-red-50 to-pink-50 rounded-lg p-8 lg:p-12 h-96 flex items-center justify-center">
                    <div class="text-center">
                        <span class="text-8xl">💒</span>
                        <p class="text-gray-600 mt-4">Wujudkan pernikahan impian Anda bersama kami</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Trusted By Section -->
        <section class="bg-white border-t border-b border-gray-200 py-16">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex flex-col lg:flex-row items-center justify-between gap-12">
                    <!-- Left Side - Text -->
                    <div class="lg:flex-shrink-0 lg:w-1/3">
                        <p class="text-sm font-semibold text-gray-900 tracking-wide">
                            DIPERCAYA OLEH<br/>RIBUAN PASANGAN<br/>DI SELURUH INDONESIA
                        </p>
                    </div>

                    <!-- Right Side - Logos -->
                    <div class="flex-1 flex items-center justify-center lg:justify-end flex-wrap gap-8 lg:gap-12">
                        <!-- Logo 1 -->
                        <div class="flex items-center">
                            <div class="text-gray-400 hover:text-gray-600 transition">
                                <p class="text-sm font-semibold">Sutan</p>
                            </div>
                        </div>

                        <!-- Logo 2 -->
                        <div class="flex items-center">
                            <div class="text-gray-400 hover:text-gray-600 transition">
                                <p class="text-sm font-semibold">Golden</p>
                            </div>
                        </div>

                        <!-- Logo 3 -->
                        <div class="flex items-center">
                            <div class="text-gray-400 hover:text-gray-600 transition">
                                <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 24 24">
                                    <circle cx="12" cy="12" r="10"/>
                                </svg>
                            </div>
                        </div>

                        <!-- Logo 4 -->
                        <div class="flex items-center">
                            <div class="text-gray-400 hover:text-gray-600 transition">
                                <p class="text-sm font-semibold">Novotel</p>
                            </div>
                        </div>

                        <!-- Logo 5 -->
                        <div class="flex items-center">
                            <div class="text-gray-400 hover:text-gray-600 transition">
                                <p class="text-sm font-semibold">Aston</p>
                            </div>
                        </div>

                        <!-- Logo 6 -->
                        <div class="flex items-center">
                            <div class="text-gray-400 hover:text-gray-600 transition">
                                <p class="text-sm font-semibold">Royal</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Wedding Packages -->
        <section id="packages" class="bg-gray-50 py-16 lg:py-24">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-12">
                    <h2 class="text-3xl lg:text-4xl font-bold text-gray-900 mb-4">Paket Pernikahan Kami</h2>
                    <p class="text-gray-600 text-lg">Pilih paket yang sesuai dengan kebutuhan dan budget Anda</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    <!-- Package 1 -->
                    <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition p-6">
                        <div class="text-4xl mb-4">🏛️</div>
                        <h3 class="text-xl font-bold text-gray-900 mb-2">Gedung</h3>
                        <p class="text-gray-600 text-sm mb-4">Suan Ballroom, Golden Hall, Royal Palace dan lainnya</p>
                        <div class="text-red-600 font-bold text-2xl">Mulai dari</div>
                        <p class="text-gray-600 mb-4">Rp 50 Juta</p>
                        <button class="w-full bg-red-50 text-red-600 font-semibold py-2 rounded hover:bg-red-100 transition">
                            Lihat Detail
                        </button>
                    </div>

                    <!-- Package 2 -->
                    <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition p-6">
                        <div class="text-4xl mb-4">🏨</div>
                        <h3 class="text-xl font-bold text-gray-900 mb-2">Hotel</h3>
                        <p class="text-gray-600 text-sm mb-4">Novotel, Aston, Grand Aston dan berbagai pilihan hotel</p>
                        <div class="text-red-600 font-bold text-2xl">Mulai dari</div>
                        <p class="text-gray-600 mb-4">Rp 30 Juta</p>
                        <button class="w-full bg-red-50 text-red-600 font-semibold py-2 rounded hover:bg-red-100 transition">
                            Lihat Detail
                        </button>
                    </div>

                    <!-- Package 3 -->
                    <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition p-6">
                        <div class="text-4xl mb-4">🏠</div>
                        <h3 class="text-xl font-bold text-gray-900 mb-2">Rumah Pribadi</h3>
                        <p class="text-gray-600 text-sm mb-4">Paket dekorasi dan entertainment untuk acara di rumah</p>
                        <div class="text-red-600 font-bold text-2xl">Mulai dari</div>
                        <p class="text-gray-600 mb-4">Rp 20 Juta</p>
                        <button class="w-full bg-red-50 text-red-600 font-semibold py-2 rounded hover:bg-red-100 transition">
                            Lihat Detail
                        </button>
                    </div>

                    <!-- Package 4 -->
                    <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition p-6">
                        <div class="text-4xl mb-4">💼</div>
                        <h3 class="text-xl font-bold text-gray-900 mb-2">WO Only</h3>
                        <p class="text-gray-600 text-sm mb-4">Hanya layanan wedding organizer tanpa venue</p>
                        <div class="text-red-600 font-bold text-2xl">Mulai dari</div>
                        <p class="text-gray-600 mb-4">Rp 10 Juta</p>
                        <button class="w-full bg-red-50 text-red-600 font-semibold py-2 rounded hover:bg-red-100 transition">
                            Lihat Detail
                        </button>
                    </div>
                </div>
            </div>
        </section>

        <!-- Blog Section -->
        <section class="py-16 lg:py-24">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-12">
                    <h2 class="text-3xl lg:text-4xl font-bold text-gray-900 mb-4">Blog Terbaru</h2>
                    <p class="text-gray-600 text-lg">Tips dan trik pernikahan dari expert kami</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <!-- Blog Post 1 -->
                    <article class="bg-white rounded-lg overflow-hidden shadow-md hover:shadow-lg transition">
                        <div class="bg-gradient-to-r from-red-100 to-pink-100 h-48 flex items-center justify-center">
                            <span class="text-6xl">💐</span>
                        </div>
                        <div class="p-6">
                            <div class="text-sm text-red-600 font-semibold mb-2">TIPS & TRIK</div>
                            <h3 class="text-xl font-bold text-gray-900 mb-3">Memilih Tema Pernikahan yang Tepat</h3>
                            <p class="text-gray-600 text-sm mb-4">Panduan lengkap memilih tema pernikahan sesuai kepribadian dan budget Anda</p>
                            <a href="#" class="text-red-600 font-semibold text-sm hover:text-red-700">Baca Selengkapnya →</a>
                        </div>
                    </article>

                    <!-- Blog Post 2 -->
                    <article class="bg-white rounded-lg overflow-hidden shadow-md hover:shadow-lg transition">
                        <div class="bg-gradient-to-r from-blue-100 to-indigo-100 h-48 flex items-center justify-center">
                            <span class="text-6xl">🎂</span>
                        </div>
                        <div class="p-6">
                            <div class="text-sm text-red-600 font-semibold mb-2">RESEP & MENU</div>
                            <h3 class="text-xl font-bold text-gray-900 mb-3">Menu Pernikahan yang Berkesan</h3>
                            <p class="text-gray-600 text-sm mb-4">Ide menu makanan dan minuman untuk resepsi pernikahan Anda</p>
                            <a href="#" class="text-red-600 font-semibold text-sm hover:text-red-700">Baca Selengkapnya →</a>
                        </div>
                    </article>

                    <!-- Blog Post 3 -->
                    <article class="bg-white rounded-lg overflow-hidden shadow-md hover:shadow-lg transition">
                        <div class="bg-gradient-to-r from-yellow-100 to-orange-100 h-48 flex items-center justify-center">
                            <span class="text-6xl">📸</span>
                        </div>
                        <div class="p-6">
                            <div class="text-sm text-red-600 font-semibold mb-2">FOTOGRAFI</div>
                            <h3 class="text-xl font-bold text-gray-900 mb-3">Poses Foto Pernikahan yang Sempurna</h3>
                            <p class="text-gray-600 text-sm mb-4">Tips dan trik pose foto untuk hasil yang Instagram-worthy</p>
                            <a href="#" class="text-red-600 font-semibold text-sm hover:text-red-700">Baca Selengkapnya →</a>
                        </div>
                    </article>
                </div>
            </div>
        </section>

        <!-- E-Book Section -->
        <section class="bg-gradient-to-r from-red-600 to-rose-500 text-white py-16 lg:py-24">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                    <div>
                        <h2 class="text-3xl lg:text-4xl font-bold mb-4">Panduan Lengkap Pernikahan</h2>
                        <p class="text-white text-opacity-90 text-lg mb-6">
                            Dapatkan e-book gratis berisi panduan lengkap merencanakan pernikahan dari awal hingga akhir
                        </p>
                        
                        <div class="space-y-3 mb-8">
                            <div class="flex items-start gap-3">
                                <span class="text-2xl">✓</span>
                                <span>100+ Tips Praktis Merencanakan Pernikahan</span>
                            </div>
                            <div class="flex items-start gap-3">
                                <span class="text-2xl">✓</span>
                                <span>Checklist Lengkap Sebelum Hari H</span>
                            </div>
                            <div class="flex items-start gap-3">
                                <span class="text-2xl">✓</span>
                                <span>Budget Planning & Negotiation Tips</span>
                            </div>
                            <div class="flex items-start gap-3">
                                <span class="text-2xl">✓</span>
                                <span>Template Surat Undangan & Seating Chart</span>
                            </div>
                            <div class="flex items-start gap-3">
                                <span class="text-2xl">✓</span>
                                <span>Panduan Vendor Selection & Kontrak</span>
                            </div>
                        </div>

                        <button class="bg-white text-red-600 font-semibold py-3 px-8 rounded-lg hover:bg-gray-100 transition">
                            Download E-Book Gratis
                        </button>
                    </div>

                    <div class="bg-white bg-opacity-10 rounded-lg p-8 lg:p-12 text-center">
                        <div class="text-9xl mb-4">📚</div>
                        <p class="text-lg text-opacity-90">E-book Format PDF</p>
                        <p class="text-sm text-opus-90 mt-2">+50 Halaman | Unlimited Download</p>
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
