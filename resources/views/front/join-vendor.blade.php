@extends('layout.app')

@section('title', 'Join Vendor — Makna Wedding')

@section('content')
    @include('layout.header')

    <section class="py-10" style="background-color: var(--cream)">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-6">
                <h1 class="text-2xl font-bold" style="color: var(--dark-gray)">Join Vendor</h1>
                <p class="text-sm text-gray-500 mt-1">Ajukan vendor Anda untuk dapat mengelola paket dan pembayaran.</p>
            </div>

            @if (session('join_vendor_success'))
                <div class="mb-4 text-xs font-semibold px-3 py-2 rounded-xl bg-green-50 text-green-700 border border-green-100">
                    {{ session('join_vendor_success') }}
                </div>
            @endif

            @if (session('join_vendor_error'))
                <div class="mb-4 text-xs font-semibold px-3 py-2 rounded-xl bg-red-50 text-red-700 border border-red-100">
                    {{ session('join_vendor_error') }}
                </div>
            @endif

            @if($application && $application->status === 'pending')
                <div class="bg-white rounded-2xl border border-gray-100 p-6">
                    <div class="flex items-center justify-between gap-3">
                        <div>
                            <p class="text-xs uppercase tracking-widest text-gray-400">Status</p>
                            <p class="text-lg font-bold" style="color: var(--dark-gray)">Menunggu verifikasi</p>
                            <p class="text-sm text-gray-500 mt-1">Admin sedang memproses pengajuan Anda.</p>
                        </div>
                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-bold bg-yellow-50 text-yellow-700">Pending</span>
                    </div>
                    <div class="mt-4 text-xs text-gray-600">
                        <div><span class="font-semibold">Nama usaha:</span> {{ $application->business_name }}</div>
                        @if($application->category)<div class="mt-1"><span class="font-semibold">Kategori:</span> {{ ($categoryMap[$application->category] ?? $application->category) }}</div>@endif
                        @if($application->province)<div class="mt-1"><span class="font-semibold">Provinsi:</span> {{ $application->province }}</div>@endif
                        @if($application->city)<div class="mt-1"><span class="font-semibold">Kota / Kabupaten:</span> {{ $application->city }}</div>@endif
                        @if($application->logo_vendor)<div class="mt-3"><span class="font-semibold">Logo:</span><div class="mt-2 w-14 h-14 rounded-xl overflow-hidden border border-gray-100 bg-gray-50"><img src="{{ asset('storage/' . ltrim($application->logo_vendor, '/')) }}" alt="Logo Vendor" class="w-full h-full object-cover"></div></div>@endif
                    </div>
                </div>
            @elseif($application && $application->status === 'approved')
                <div class="bg-white rounded-2xl border border-gray-100 p-6">
                    <div class="flex items-center justify-between gap-3">
                        <div>
                            <p class="text-xs uppercase tracking-widest text-gray-400">Status</p>
                            <p class="text-lg font-bold" style="color: var(--dark-gray)">Disetujui</p>
                            <p class="text-sm text-gray-500 mt-1">Anda sudah bisa mengelola vendor di dashboard.</p>
                        </div>
                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-bold bg-green-50 text-green-700">Approved</span>
                    </div>
                    <div class="mt-4">
                        <a href="{{ url('/dashboard/vendor/vendors') }}" class="inline-flex items-center justify-center px-4 py-2 rounded-xl text-sm font-bold transition hover:opacity-90" style="background: var(--sage-green); color: #fff">
                            Buka Vendor Saya
                        </a>
                    </div>
                </div>
            @else
                <div class="bg-white rounded-2xl border border-gray-100 p-6">
                    <form method="POST" action="{{ route('join.vendor.store') }}" enctype="multipart/form-data" class="space-y-4">
                        @csrf

                        <div>
                            <label class="block text-xs font-semibold text-gray-500 mb-1">Nama usaha</label>
                            <input type="text" name="business_name" maxlength="255" value="{{ old('business_name') }}"
                                   placeholder="Contoh: Grand Ballroom Sriwijaya"
                                   class="w-full h-11 border border-gray-200 rounded-xl px-3.5 py-2.5 text-sm focus:outline-none focus:border-gray-400 transition">
                            @error('business_name')
                                <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-500 mb-1">Kategori</label>
                                <select name="category" class="w-full h-11 border border-gray-200 rounded-xl px-3.5 py-2.5 text-sm focus:outline-none focus:border-gray-400 transition" required>
                                    <option value="">Pilih kategori vendor</option>
                                    @foreach(($categories ?? collect()) as $cat)
                                        <option value="{{ $cat->slug }}" {{ old('category') === $cat->slug ? 'selected' : '' }}>
                                            {{ $cat->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('category')
                                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-semibold text-gray-500 mb-1">Provinsi</label>
                                <select id="join-vendor-province" name="province" class="w-full h-11 border border-gray-200 rounded-xl px-3.5 py-2.5 text-sm focus:outline-none focus:border-gray-400 transition" required>
                                    <option value="">Pilih provinsi</option>
                                    @foreach(($provinces ?? []) as $pVal => $pLabel)
                                        <option value="{{ $pVal }}" {{ old('province') === $pVal ? 'selected' : '' }}>{{ $pLabel }}</option>
                                    @endforeach
                                </select>
                                @error('province')
                                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-500 mb-1">Kota / Kabupaten</label>
                                <select id="join-vendor-city" name="city" class="w-full h-11 border border-gray-200 rounded-xl px-3.5 py-2.5 text-sm focus:outline-none focus:border-gray-400 transition" required disabled>
                                    <option value="">Pilih provinsi terlebih dahulu</option>
                                </select>
                                @error('city')
                                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-500 mb-1">Logo Vendor (opsional)</label>
                                <input type="file" name="logo_vendor" accept=".jpg,.jpeg,.png,.webp"
                                       class="w-full border border-gray-200 rounded-xl px-3.5 py-2.5 text-sm focus:outline-none focus:border-gray-400 transition bg-white">
                                <p class="text-[10px] text-gray-400 mt-1.5">Format: JPG/PNG/WEBP, maks 2MB.</p>
                                @error('logo_vendor')
                                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-500 mb-1">Lokasi</label>
                            <input type="text" name="location" maxlength="255" value="{{ old('location') }}"
                                   placeholder="Contoh: Jl. Sudirman No. 12"
                                   class="w-full h-11 border border-gray-200 rounded-xl px-3.5 py-2.5 text-sm focus:outline-none focus:border-gray-400 transition" required>
                            @error('location')
                                <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-semibold text-gray-500 mb-1">WhatsApp (opsional)</label>
                                <input type="text" name="phone" maxlength="30" value="{{ old('phone') }}"
                                       placeholder="Contoh: 6281234567890"
                                       class="w-full h-11 border border-gray-200 rounded-xl px-3.5 py-2.5 text-sm focus:outline-none focus:border-gray-400 transition">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-500 mb-1">Email (opsional)</label>
                                <input type="text" name="email" maxlength="120" value="{{ old('email', $user->email) }}"
                                       placeholder="Contoh: email@vendor.com"
                                       class="w-full h-11 border border-gray-200 rounded-xl px-3.5 py-2.5 text-sm focus:outline-none focus:border-gray-400 transition">
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-500 mb-1">Instagram (opsional)</label>
                            <input type="text" name="instagram" maxlength="120" value="{{ old('instagram') }}"
                                   placeholder="Contoh: @namavendor"
                                   class="w-full h-11 border border-gray-200 rounded-xl px-3.5 py-2.5 text-sm focus:outline-none focus:border-gray-400 transition">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-500 mb-1">Deskripsi singkat (opsional)</label>
                            <textarea name="note" rows="4" maxlength="2000"
                                      placeholder="Ceritakan singkat tentang layanan, area, dan jam operasional (opsional)"
                                      class="w-full border border-gray-200 rounded-xl px-3.5 py-2.5 text-sm focus:outline-none focus:border-gray-400 transition resize-none">{{ old('note') }}</textarea>
                        </div>

                        <div class="flex items-center justify-end gap-2">
                            <a href="{{ route('home') }}" class="text-xs font-bold px-4 py-2 rounded-lg bg-gray-50 hover:bg-gray-100 transition" style="color: var(--dark-gray)">
                                Kembali
                            </a>
                            <button type="submit" class="text-xs font-bold px-4 py-2 rounded-lg transition hover:opacity-90" style="background: var(--sage-green); color: #fff">
                                Kirim Pengajuan
                            </button>
                        </div>
                    </form>
                </div>
            @endif
        </div>
    </section>

    <script>
    document.addEventListener('DOMContentLoaded', function () {
        var provinceEl = document.getElementById('join-vendor-province');
        var cityEl = document.getElementById('join-vendor-city');
        if (!provinceEl || !cityEl) return;

        var oldCity = @json(old('city'));

        function setCityOptions(cities, placeholderText) {
            cityEl.innerHTML = '';
            var opt = document.createElement('option');
            opt.value = '';
            opt.textContent = placeholderText || 'Pilih kota / kabupaten';
            cityEl.appendChild(opt);

            (cities || []).forEach(function (c) {
                var o = document.createElement('option');
                o.value = c;
                o.textContent = c;
                if (oldCity && oldCity === c) o.selected = true;
                cityEl.appendChild(o);
            });

            cityEl.disabled = !(cities && cities.length);
        }

        function loadCities() {
            var province = provinceEl.value;
            if (!province) {
                setCityOptions([], 'Pilih provinsi terlebih dahulu');
                return;
            }
            fetch(@json(route('join.vendor.cities')) + '?province=' + encodeURIComponent(province), {
                headers: { 'Accept': 'application/json' },
                credentials: 'same-origin',
            })
            .then(function (r) { return r.json(); })
            .then(function (d) { setCityOptions(d.cities || []); })
            .catch(function () { setCityOptions([], 'Pilih kota / kabupaten'); });
        }

        provinceEl.addEventListener('change', function () {
            oldCity = '';
            loadCities();
        });

        loadCities();
    });
    </script>

    @include('layout.footer')
@endsection
