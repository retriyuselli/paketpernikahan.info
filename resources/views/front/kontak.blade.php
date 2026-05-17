@extends('layout.app')

@section('title', 'Kontak - Makna Wedding')

@section('body-class', 'bg-cream text-dark')

@section('content')
    @include('layout.header')

    @php
        $breadcrumbItems = [
            ['label' => 'Home', 'url' => route('home')],
            ['label' => 'Kontak', 'url' => null],
        ];
    @endphp

    <section class="pt-3 lg:pt-3 lg:pb-2 bg-cream">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="pt-1 pb-4 lg:pt-1">
                <x-breadcrumb :items="$breadcrumbItems" />
            </div>
            <x-banner-ad mt="0" mb="1rem" />
        </div>
    </section>

    {{-- Hero --}}
    <section class="bg-white border-b border-gray-100">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-16 text-center">
            <span class="inline-block text-xs font-bold uppercase tracking-widest text-accent mb-3">Hubungi Kami</span>
            <h1 class="text-3xl sm:text-4xl font-extrabold text-dark leading-tight mb-4">
                Kontak Makna Wedding
            </h1>
            <p class="text-gray-500 text-base max-w-2xl mx-auto leading-relaxed">
                Ada pertanyaan, saran, atau ingin bekerja sama? Tim kami siap membantu Anda.
            </p>
        </div>
    </section>

    <section class="py-14 bg-cream">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-10">

                {{-- Info Kontak --}}
                <div class="space-y-6">
                    <h2 class="text-lg font-bold text-dark mb-2">Informasi Kontak</h2>

                    <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm flex items-start gap-4">
                        <div class="w-10 h-10 rounded-xl bg-accent/10 flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide mb-1">Email</p>
                            <a href="mailto:maknawedding@gmail.com" class="text-sm font-medium text-dark hover:text-accent transition">
                                maknawedding@gmail.com
                            </a>
                        </div>
                    </div>

                    <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm flex items-start gap-4">
                        <div class="w-10 h-10 rounded-xl bg-accent/10 flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 text-accent" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z"/>
                                <path d="M12 0C5.373 0 0 5.373 0 12c0 2.127.558 4.126 1.535 5.857L.057 23.43l5.752-1.507A11.953 11.953 0 0012 24c6.627 0 12-5.373 12-12S18.627 0 12 0zm0 21.818a9.81 9.81 0 01-5.007-1.373l-.36-.214-3.715.974.99-3.618-.234-.372A9.818 9.818 0 012.182 12C2.182 6.57 6.57 2.182 12 2.182S21.818 6.57 21.818 12 17.43 21.818 12 21.818z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide mb-1">WhatsApp</p>
                            <a href="https://wa.me/6282297962600" target="_blank" rel="noopener noreferrer" class="text-sm font-medium text-dark hover:text-accent transition">
                                +62 822-9796-2600
                            </a>
                        </div>
                    </div>

                    <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm flex items-start gap-4">
                        <div class="w-10 h-10 rounded-xl bg-accent/10 flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 text-accent" fill="currentColor" viewBox="0 0 24 24">
                                <rect x="2" y="2" width="20" height="20" rx="5" ry="5" fill="none" stroke="currentColor" stroke-width="2"/>
                                <path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z" fill="none" stroke="currentColor" stroke-width="2"/>
                                <circle cx="17.5" cy="6.5" r="1.5" fill="currentColor"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide mb-1">Instagram</p>
                            <a href="https://www.instagram.com/makna.wedding/" target="_blank" rel="noopener noreferrer" class="text-sm font-medium text-dark hover:text-accent transition">
                                @makna.wedding
                            </a>
                        </div>
                    </div>

                    <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm flex items-start gap-4">
                        <div class="w-10 h-10 rounded-xl bg-accent/10 flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide mb-1">Lokasi</p>
                            <p class="text-sm font-medium text-dark">Sumatera Selatan, Indonesia</p>
                        </div>
                    </div>
                </div>

                {{-- Form Kontak --}}
                <div class="bg-white rounded-2xl p-8 border border-gray-100 shadow-sm">
                    <h2 class="text-lg font-bold text-dark mb-6">Kirim Pesan</h2>

                    @if(session('kontak_success'))
                        <div class="mb-5 px-4 py-3 rounded-xl bg-accent/10 border border-accent/20 text-sm text-dark font-medium">
                            {{ session('kontak_success') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('kontak.send') }}" class="space-y-4">
                        @csrf
                        <div>
                            <label for="nama" class="block text-xs font-semibold text-gray-500 mb-1.5 uppercase tracking-wide">Nama</label>
                            <input type="text" id="nama" name="nama" required
                                   value="{{ old('nama') }}"
                                   placeholder="Nama lengkap Anda"
                                   class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-gray-400 transition @error('nama') border-red-400 @enderror">
                            @error('nama')
                                <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="email" class="block text-xs font-semibold text-gray-500 mb-1.5 uppercase tracking-wide">Email</label>
                            <input type="email" id="email" name="email" required
                                   value="{{ old('email') }}"
                                   placeholder="email@anda.com"
                                   class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-gray-400 transition @error('email') border-red-400 @enderror">
                            @error('email')
                                <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="subjek" class="block text-xs font-semibold text-gray-500 mb-1.5 uppercase tracking-wide">Subjek</label>
                            <input type="text" id="subjek" name="subjek" required
                                   value="{{ old('subjek') }}"
                                   placeholder="Subjek pesan Anda"
                                   class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-gray-400 transition @error('subjek') border-red-400 @enderror">
                            @error('subjek')
                                <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="pesan" class="block text-xs font-semibold text-gray-500 mb-1.5 uppercase tracking-wide">Pesan</label>
                            <textarea id="pesan" name="pesan" rows="5" required
                                      placeholder="Tuliskan pesan Anda di sini..."
                                      class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-gray-400 transition resize-none @error('pesan') border-red-400 @enderror">{{ old('pesan') }}</textarea>
                            @error('pesan')
                                <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <button type="submit"
                                class="w-full py-2.5 rounded-full bg-accent text-white text-sm font-bold tracking-wide transition hover:opacity-90">
                            Kirim Pesan
                        </button>
                    </form>
                </div>

            </div>
        </div>
    </section>

    @include('layout.footer')
@endsection
