@extends('layout.app')

@section('title', 'Join Vendor — Makna Wedding')

@section('content')
    @include('layout.header')

    <section class="py-10" style="background-color: var(--cream)">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-6">
                <h1 class="text-2xl text-black font-bold" style="color: var(--dark-gray)">Join Vendor</h1>
                <p class="text-sm text-black mt-1">Ajukan vendor Anda untuk dapat mengelola paket dan pembayaran.</p>
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
                        @php
                            $appCats = is_array($application->categories) && count($application->categories) ? $application->categories : ($application->category ? [$application->category] : []);
                            $appCatsLabel = collect($appCats)->map(fn ($c) => $categoryMap[$c] ?? $c)->join(', ');
                        @endphp
                        @if($appCatsLabel)<div class="mt-1"><span class="font-semibold">Kategori:</span> {{ $appCatsLabel }}</div>@endif
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
                                @php
                                    $oldCats = old('categories');
                                    if (!is_array($oldCats)) {
                                        $oldCats = old('category') ? [old('category')] : [];
                                    }
                                @endphp
                                <div class="relative" id="join-vendor-category-select">
                                    <button type="button"
                                            id="join-vendor-category-button"
                                            class="w-full h-11 border border-gray-200 rounded-xl px-3.5 text-sm flex items-center justify-between gap-3 hover:border-gray-300 transition bg-white"
                                            style="color: var(--dark-gray)">
                                        <span class="text-sm opacity-60" style="color: var(--dark-gray)" id="join-vendor-category-placeholder">Pilih kategori vendor</span>
                                        <svg class="w-4 h-4 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                        </svg>
                                    </button>

                                    <div id="join-vendor-category-dropdown" class="absolute left-0 right-0 mt-2 bg-white border border-gray-200 rounded-2xl shadow-lg p-3 z-20 hidden">
                                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 max-h-60 overflow-auto">
                                            @foreach(($categories ?? collect()) as $cat)
                                                @php $checked = in_array($cat->slug, $oldCats, true); @endphp
                                                <label class="flex items-center gap-2.5 px-3 py-2 rounded-xl hover:bg-gray-50 transition cursor-pointer">
                                                    <input type="checkbox"
                                                           name="categories[]"
                                                           value="{{ $cat->slug }}"
                                                           class="h-4 w-4 rounded border-gray-300 text-[var(--sage-green)] focus:ring-[var(--sage-green)]"
                                                           data-label="{{ $cat->name }}"
                                                           {{ $checked ? 'checked' : '' }}>
                                                    <span class="text-sm font-semibold text-gray-700">{{ $cat->name }}</span>
                                                </label>
                                            @endforeach
                                        </div>
                                        <div class="flex items-center justify-between gap-3 pt-3 mt-3 border-t border-gray-100">
                                            <button type="button" id="join-vendor-category-clear" class="text-xs font-bold px-3 py-2 rounded-lg bg-gray-50 hover:bg-gray-100 transition" style="color: var(--dark-gray)">
                                                Reset
                                            </button>
                                            <button type="button" id="join-vendor-category-done" class="text-xs font-bold px-3 py-2 rounded-lg transition hover:opacity-90" style="background: var(--sage-green); color: #fff">
                                                Selesai
                                            </button>
                                        </div>
                                    </div>

                                    <div id="join-vendor-category-chips" class="mt-2 flex flex-wrap gap-2"></div>
                                </div>
                                @error('categories')
                                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                                @enderror
                                @error('categories.*')
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
        (function () {
            var root = document.getElementById('join-vendor-category-select');
            if (!root) return;

            var btn = document.getElementById('join-vendor-category-button');
            var dd = document.getElementById('join-vendor-category-dropdown');
            var chips = document.getElementById('join-vendor-category-chips');
            var placeholder = document.getElementById('join-vendor-category-placeholder');
            var done = document.getElementById('join-vendor-category-done');
            var clear = document.getElementById('join-vendor-category-clear');
            var inputs = dd ? dd.querySelectorAll('input[type="checkbox"][name="categories[]"]') : [];

            function selected() {
                var list = [];
                inputs.forEach(function (el) {
                    if (el.checked) list.push({ value: el.value, label: el.getAttribute('data-label') || el.value, el: el });
                });
                return list;
            }

            function render() {
                var s = selected();
                chips.innerHTML = '';
                if (s.length === 0) {
                    placeholder.textContent = 'Pilih kategori vendor';
                    placeholder.classList.add('opacity-60');
                    placeholder.classList.remove('opacity-100', 'font-semibold');
                    return;
                }
                placeholder.textContent = s.length === 1 ? s[0].label : (s.length + ' kategori dipilih');
                placeholder.classList.remove('opacity-60');
                placeholder.classList.add('opacity-100', 'font-semibold');

                s.forEach(function (it) {
                    var chip = document.createElement('button');
                    chip.type = 'button';
                    chip.className = 'inline-flex items-center gap-1.5 text-xs font-bold px-3 py-1.5 rounded-full border border-gray-200 bg-white hover:bg-gray-50 transition';
                    chip.style.color = 'var(--dark-gray)';
                    chip.innerHTML = '<span class="truncate max-w-[160px]">' + String(it.label).replace(/</g, '&lt;').replace(/>/g, '&gt;') + '</span>' +
                        '<span class="text-gray-400">×</span>';
                    chip.addEventListener('click', function () {
                        it.el.checked = false;
                        render();
                    });
                    chips.appendChild(chip);
                });
            }

            function open() {
                dd.classList.remove('hidden');
            }

            function close() {
                dd.classList.add('hidden');
            }

            btn.addEventListener('click', function () {
                if (dd.classList.contains('hidden')) open();
                else close();
            });

            done.addEventListener('click', function () {
                close();
            });

            clear.addEventListener('click', function () {
                inputs.forEach(function (el) { el.checked = false; });
                render();
            });

            inputs.forEach(function (el) {
                el.addEventListener('change', render);
            });

            document.addEventListener('click', function (e) {
                if (!dd || dd.classList.contains('hidden')) return;
                if (root.contains(e.target)) return;
                close();
            });

            document.addEventListener('keydown', function (e) {
                if (e.key === 'Escape') close();
            });

            render();
        })();

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
