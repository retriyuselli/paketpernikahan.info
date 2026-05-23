@extends('layout.app')

@section('title', 'Halaman Tidak Ditemukan | Makna Wedding')
@section('meta-description', 'Halaman yang kamu cari tidak ditemukan. Kembali ke beranda Makna Wedding.')
@section('body-class', 'bg-cream text-dark')

@section('content')
    @include('layout.header')

    <section class="min-h-[70vh] flex items-center bg-cream">
        <x-ui.container>
            <div class="max-w-lg mx-auto text-center py-20">

                {{-- Ilustrasi angka 404 --}}
                <div class="flex flex-col items-center gap-3 mb-8">
                    <div class="w-20 h-20 rounded-full bg-white border border-gray-200 shadow flex items-center justify-center">
                        <svg class="w-9 h-9 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                  d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <span class="text-[120px] sm:text-[160px] font-extrabold leading-none select-none"
                          style="color: #C8D5B9; letter-spacing: -0.05em;">404</span>
                </div>

                <h1 class="text-2xl sm:text-3xl font-extrabold text-dark mb-3">
                    Halaman Tidak Ditemukan
                </h1>
                <p class="text-sm text-gray-500 leading-relaxed mb-8">
                    Halaman yang kamu cari mungkin sudah dipindahkan, dihapus,<br class="hidden sm:block">
                    atau link yang kamu gunakan tidak valid.
                </p>

                {{-- CTA Buttons --}}
                <div class="flex flex-col sm:flex-row items-center justify-center gap-3">
                    <a href="{{ route('home') }}"
                       class="inline-flex items-center gap-2 px-6 py-3 rounded-2xl bg-accent text-white text-sm font-bold hover:opacity-90 transition shadow-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                        </svg>
                        Kembali ke Beranda
                    </a>
                    <a href="{{ route('store') }}"
                       class="inline-flex items-center gap-2 px-6 py-3 rounded-2xl border border-gray-200 bg-white text-sm font-bold text-dark hover:border-gray-300 transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                        </svg>
                        Lihat Paket
                    </a>
                </div>

                {{-- Divider --}}
                <div class="mt-12 pt-8 border-t border-gray-200">
                    <p class="text-xs text-gray-400 mb-4 uppercase tracking-widest font-semibold">Atau coba halaman ini</p>
                    <div class="flex flex-wrap justify-center gap-2">
                        <a href="{{ route('vendor') }}"
                           class="px-4 py-2 rounded-full text-xs font-semibold bg-white border border-gray-200 text-dark hover:border-accent hover:text-accent transition">
                            Vendor
                        </a>
                        <a href="{{ route('blog.index') }}"
                           class="px-4 py-2 rounded-full text-xs font-semibold bg-white border border-gray-200 text-dark hover:border-accent hover:text-accent transition">
                            Blog
                        </a>
                        <a href="{{ route('home') }}#real-wedding"
                           class="px-4 py-2 rounded-full text-xs font-semibold bg-white border border-gray-200 text-dark hover:border-accent hover:text-accent transition">
                            Real Wedding
                        </a>
                    </div>
                </div>

            </div>
        </x-ui.container>
    </section>

    @include('layout.footer')
@endsection
