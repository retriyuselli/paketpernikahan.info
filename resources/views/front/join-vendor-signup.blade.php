@extends('layout.app')

@section('title', 'Join Vendor — Makna Wedding')

@section('content')
    @include('layout.header')

    <section class="py-10" style="background-color: var(--cream)">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-6">
                <h1 class="text-2xl font-bold" style="color: var(--dark-gray)">Join Vendor</h1>
                <p class="text-sm text-gray-500 mt-1">Buat akun atau login terlebih dulu, lalu ajukan vendor Anda.</p>
            </div>

            <div class="bg-white rounded-2xl border border-gray-100 p-6">
                <div class="space-y-3 text-sm text-gray-600">
                    <div class="flex items-start gap-3">
                        <span class="w-6 h-6 rounded-full flex items-center justify-center text-xs font-bold flex-shrink-0" style="background: var(--light-sage); color: var(--dark-gray)">1</span>
                        <div>Buat akun / Login</div>
                    </div>
                    <div class="flex items-start gap-3">
                        <span class="w-6 h-6 rounded-full flex items-center justify-center text-xs font-bold flex-shrink-0" style="background: var(--light-sage); color: var(--dark-gray)">2</span>
                        <div>Isi form pengajuan vendor</div>
                    </div>
                    <div class="flex items-start gap-3">
                        <span class="w-6 h-6 rounded-full flex items-center justify-center text-xs font-bold flex-shrink-0" style="background: var(--light-sage); color: var(--dark-gray)">3</span>
                        <div>Admin verifikasi → akun Anda jadi vendor</div>
                    </div>
                </div>

                <div class="mt-6 grid grid-cols-1 sm:grid-cols-3 gap-3">
                    <a href="{{ route('auth.google', ['redirect' => route('join.vendor')]) }}"
                       class="text-center text-sm font-bold px-4 py-3 rounded-xl transition hover:opacity-90"
                       style="background: var(--sage-green); color: #fff">
                        Lanjut dengan Google
                    </a>
                    <a href="{{ route('login', ['redirect' => route('join.vendor')]) }}"
                       class="text-center text-sm font-bold px-4 py-3 rounded-xl bg-gray-50 hover:bg-gray-100 transition"
                       style="color: var(--dark-gray)">
                        Login
                    </a>
                    <a href="{{ route('register', ['redirect' => route('join.vendor')]) }}"
                       class="text-center text-sm font-bold px-4 py-3 rounded-xl bg-gray-50 hover:bg-gray-100 transition"
                       style="color: var(--dark-gray)">
                        Daftar
                    </a>
                </div>

                <p class="text-[11px] text-gray-400 mt-4">
                    Setelah login, Anda akan diarahkan ke form pengajuan vendor.
                </p>
            </div>
        </div>
    </section>

    @include('layout.footer')
@endsection

