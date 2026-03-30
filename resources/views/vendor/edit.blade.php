@extends('layout.app')

@section('title', 'Edit Vendor: ' . $vendor->name . ' - Makna Wedding')

@section('body-class', 'bg-cream text-dark')

@section('extra-head')
    <style>
        .vendor-form-scope input[type="text"],
        .vendor-form-scope input[type="email"],
        .vendor-form-scope input[type="tel"],
        .vendor-form-scope input[type="url"],
        .vendor-form-scope input[type="number"],
        .vendor-form-scope input[type="date"],
        .vendor-form-scope input[type="time"],
        .vendor-form-scope input[type="password"],
        .vendor-form-scope select {
            height: 44px;
            box-sizing: border-box;
        }

        .vendor-form-scope input[type="file"] {
            height: 44px;
            box-sizing: border-box;
        }
    </style>
@endsection

@section('content')
    @include('layout.header')

    @php
        $venueCategories = ['gedung', 'hotel', 'venue', 'rumah', 'wo'];
        $selectedCategory = old('category', $vendor->category);
        $isVenueCategory = in_array($selectedCategory, $venueCategories, true);
        $editUser = auth()->user();
        $editIsAdmin = $editUser && $editUser->hasRole(['super_admin', 'admin']);
        $canToggleActive = $editIsAdmin || (bool) $vendor->is_profile_complete;
        $activeChecked = $canToggleActive ? old('is_active', $vendor->is_active) : false;
    @endphp

    <!-- Breadcrumb -->
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 pt-6 pb-2">
        <nav class="flex items-center gap-2 text-xs" style="color: var(--dark-gray)">
            <a href="{{ route('home') }}" class="hover:text-accent transition">Home</a>
            <svg class="w-3 h-3 opacity-40" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            <a href="{{ route('vendor') }}" class="hover:text-accent transition">Vendor</a>
            <svg class="w-3 h-3 opacity-40" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            <a href="{{ route('vendor.detail', $vendor) }}" class="hover:text-accent transition">{{ $vendor->name }}</a>
            <svg class="w-3 h-3 opacity-40" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            <span class="font-semibold opacity-60">Edit</span>
        </nav>
    </div>

    <div class="vendor-form-scope max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-6 pb-16">

        <!-- Page Header -->
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-xl font-bold" style="color: var(--dark-gray)">Edit Vendor</h1>
                <p class="text-sm text-gray-500 mt-0.5">{{ $vendor->name }}</p>
            </div>
            <a href="{{ route('vendor.detail', $vendor) }}"
               class="inline-flex items-center gap-1.5 text-xs font-semibold px-3 py-2 rounded-lg border border-gray-200 bg-white hover:border-gray-300 transition"
               style="color: var(--dark-gray)">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Kembali ke Detail
            </a>
        </div>

        @if(session('success'))
        {{-- Success Modal --}}
        <div id="success-modal" class="fixed inset-0 z-[9999] flex items-center justify-center p-4" style="background: rgba(0,0,0,0.4)">
            <div class="bg-white rounded-2xl shadow-2xl w-full max-w-sm p-8 flex flex-col items-center text-center gap-4 animate-bounce-in">
                <div class="w-16 h-16 rounded-full flex items-center justify-center" style="background:#f0fdf4">
                    <svg class="w-8 h-8" style="color:#22c55e" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                    </svg>
                </div>
                <div>
                    <h3 class="text-lg font-bold mb-1" style="color: var(--dark-gray)">Perubahan Tersimpan!</h3>
                    <p class="text-sm text-gray-500">{{ session('success') }}</p>
                    <p class="text-sm font-medium mt-1" style="color: var(--sage-green)">Terima kasih 🎉</p>
                </div>
                <a href="{{ route('vendor.detail', $vendor) }}"
                   class="w-full py-2.5 rounded-xl text-sm font-bold text-white transition hover:opacity-90 mt-1 text-center block"
                   style="background: var(--sage-green)">
                    Oke, Mengerti
                </a>
            </div>
        </div>
        <script>
        document.addEventListener('DOMContentLoaded', function() {
            const modal = document.getElementById('success-modal');
            if (modal) {
                modal.addEventListener('click', function(e) { if (e.target === this) this.remove(); });
            }
        });
        </script>
        @endif

        @if($errors->any())
        <div class="mb-5 bg-red-50 border border-red-200 text-red-700 rounded-xl px-4 py-3 text-sm">
            <p class="font-semibold mb-1">Terdapat kesalahan pada form:</p>
            <ul class="list-disc list-inside space-y-0.5">
                @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        {{-- ── Tab Navigation ── --}}
        <div class="mb-5">
            <div class="flex gap-1 p-1 rounded-2xl" style="background: var(--cream); border: 1px solid #e5e7eb">
                <button type="button" onclick="switchTab('tab-info')"
                        id="btn-tab-info"
                        class="tab-btn flex-1 flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl text-sm font-semibold transition-all">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    <span>Informasi Utama</span>
                </button>
                <button type="button" onclick="switchTab('tab-paket')"
                        id="btn-tab-paket"
                        class="tab-btn flex-1 flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl text-sm font-semibold transition-all">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                    <span>Paket Pernikahan</span>
                </button>
                <button type="button" onclick="switchTab('tab-media')"
                        id="btn-tab-media"
                        class="tab-btn flex-1 flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl text-sm font-semibold transition-all">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    <span>Media</span>
                </button>
            </div>
        </div>

        <form action="{{ route('vendor.update', $vendor) }}" method="POST" enctype="multipart/form-data">
            @csrf

            {{-- ══════════════════════════════════════════ --}}
            {{-- TAB 1 — Informasi Utama                   --}}
            {{-- ══════════════════════════════════════════ --}}
            <div id="tab-info" class="tab-panel space-y-5">

                @php
                    $profileProgress = $vendor->computeProfileProgress();
                @endphp

                <div class="bg-white rounded-2xl border border-gray-100 px-5 py-4">
                    <div class="flex items-center justify-between gap-3">
                        <div>
                            <p class="text-xs uppercase tracking-widest text-gray-400">Kelengkapan Profil</p>
                            <p class="text-sm font-bold mt-1" style="color: var(--dark-gray)">{{ $profileProgress['percent'] }}%</p>
                        </div>
                        <p class="text-xs text-gray-400">{{ $profileProgress['done'] }}/{{ $profileProgress['total'] }} terisi</p>
                    </div>
                    <div class="mt-3 w-full h-2 rounded-full bg-gray-100 overflow-hidden">
                        <div class="h-full rounded-full" style="width: {{ $profileProgress['percent'] }}%; background: var(--sage-green)"></div>
                    </div>
                    @if($profileProgress['percent'] < 100 && !empty($profileProgress['missing']))
                        <div class="mt-3 text-xs text-gray-500">
                            <span class="font-semibold text-gray-600">Yang perlu dilengkapi:</span>
                            {{ implode(', ', $profileProgress['missing']) }}
                        </div>
                    @endif
                </div>

                {{-- Status Aktif (always visible at top) --}}
                <div class="bg-white rounded-2xl border border-gray-100 px-5 py-4 flex items-center gap-3">
                    <label class="relative inline-flex items-center {{ $canToggleActive ? 'cursor-pointer' : 'cursor-not-allowed opacity-60' }}">
                        <input type="hidden" name="is_active" value="0">
                        <input type="checkbox" name="is_active" value="1"
                               @checked($activeChecked)
                               @disabled(!$canToggleActive)
                               class="sr-only peer">
                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-sage" style="--sage: var(--sage-green)"></div>
                        <span class="ml-2 text-sm font-medium text-gray-700">Vendor Aktif</span>
                    </label>
                    <p class="text-xs text-gray-400">
                        @if(!$canToggleActive)
                            Lengkapi profil vendor terlebih dahulu untuk mengaktifkan.
                        @else
                            (Jika tidak aktif, vendor tidak akan muncul di halaman listing)
                        @endif
                    </p>
                </div>

                {{-- Informasi Utama --}}
                <div class="bg-white rounded-2xl border border-gray-100 overflow-hidden">
                    <div class="px-5 py-3 border-b border-gray-100" style="background: var(--cream)">
                        <h2 class="text-sm font-bold" style="color: var(--dark-gray)">Informasi Utama</h2>
                    </div>
                    <div class="p-5 grid grid-cols-1 sm:grid-cols-2 gap-4">

                        {{-- Nama --}}
                        <div>
                            <label class="block text-xs font-semibold text-gray-500 mb-1">Nama Vendor <span class="text-red-400">*</span></label>
                            <input type="text" name="name" value="{{ old('name', $vendor->name) }}"
                                   class="w-full rounded-xl border border-gray-200 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-offset-0 @error('name') border-red-300 @enderror"
                                   style="--tw-ring-color: var(--sage-green)" required>
                            @error('name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>

                        {{-- Kategori --}}
                        <div>
                            <label class="block text-xs font-semibold text-gray-500 mb-1">Kategori <span class="text-red-400">*</span></label>
                            <select id="vendor-category" name="category"
                                    class="w-full rounded-xl border border-gray-200 px-3 py-2 text-sm focus:outline-none focus:ring-2 @error('category') border-red-300 @enderror"
                                    style="--tw-ring-color: var(--sage-green)">
                                @foreach(\App\Models\CategoryVendor::orderBy('sort_order')->get() as $cat)
                                <option value="{{ $cat->slug }}" @selected(old('category', $vendor->category) === $cat->slug)>{{ $cat->name }}</option>
                                @endforeach
                            </select>
                            @error('category')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>

                        {{-- Tipe --}}
                        <div id="venue-category-only-fields" class="{{ $isVenueCategory ? '' : 'hidden' }}">
                            <label class="block text-xs font-semibold text-gray-500 mb-1">Tipe Venue</label>
                            <select name="type"
                                    class="w-full rounded-xl border border-gray-200 px-3 py-2 text-sm focus:outline-none focus:ring-2"
                                    style="--tw-ring-color: var(--sage-green)">
                                <option value="">— Pilih Tipe —</option>
                                @foreach(['Indoor', 'Outdoor', 'Indoor & Outdoor'] as $t)
                                <option value="{{ $t }}" @selected(old('type', $vendor->type) === $t)>{{ $t }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Alamat --}}
                        <div class="sm:col-span-2">
                            <label class="block text-xs font-semibold text-gray-500 mb-1">Alamat Lengkap <span class="text-red-400">*</span></label>
                            <input type="text" name="location" value="{{ old('location', $vendor->location) }}"
                                   class="w-full rounded-xl border border-gray-200 px-3 py-2 text-sm focus:outline-none focus:ring-2 @error('location') border-red-300 @enderror"
                                   style="--tw-ring-color: var(--sage-green)" required>
                            @error('location')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>

                        {{-- Provinsi --}}
                        <div>
                            <label class="block text-xs font-semibold text-gray-500 mb-1">Provinsi</label>
                            <select name="province"
                                    class="w-full rounded-xl border border-gray-200 px-3 py-2 text-sm focus:outline-none focus:ring-2"
                                    style="--tw-ring-color: var(--sage-green)">
                                <option value="">— Pilih Provinsi —</option>
                                @foreach(\App\Enums\ProvinsiEnum::toArray() as $val => $label)
                                <option value="{{ $val }}" @selected(old('province', $vendor->province) === $val)>{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Kota --}}
                        <div>
                            <label class="block text-xs font-semibold text-gray-500 mb-1">Kota / Kabupaten</label>
                            <input type="text" name="city" value="{{ old('city', $vendor->city) }}"
                                   placeholder="Nama kota"
                                   class="w-full rounded-xl border border-gray-200 px-3 py-2 text-sm focus:outline-none focus:ring-2"
                                   style="--tw-ring-color: var(--sage-green)">
                        </div>

                        {{-- Deskripsi --}}
                        <div class="sm:col-span-2">
                            <label class="block text-xs font-semibold text-gray-500 mb-1">Deskripsi</label>
                            <textarea name="description" rows="4"
                                      class="w-full rounded-xl border border-gray-200 px-3 py-2 text-sm focus:outline-none focus:ring-2 resize-none"
                                      style="--tw-ring-color: var(--sage-green)">{{ old('description', $vendor->description) }}</textarea>
                        </div>

                    </div>
                </div>

                {{-- Kontak --}}
                <div class="bg-white rounded-2xl border border-gray-100 overflow-hidden">
                    <div class="px-5 py-3 border-b border-gray-100" style="background: var(--cream)">
                        <h2 class="text-sm font-bold" style="color: var(--dark-gray)">Kontak</h2>
                    </div>
                    <div class="p-5 grid grid-cols-1 sm:grid-cols-3 gap-4">

                        <div>
                            <label class="block text-xs font-semibold text-gray-500 mb-1">No. Telepon / WhatsApp</label>
                            <input type="text" name="phone" value="{{ old('phone', $vendor->phone) }}"
                                   placeholder="08xx-xxxx-xxxx"
                                   class="w-full rounded-xl border border-gray-200 px-3 py-2 text-sm focus:outline-none focus:ring-2"
                                   style="--tw-ring-color: var(--sage-green)">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-500 mb-1">Email</label>
                            <input type="email" name="email" value="{{ old('email', $vendor->email) }}"
                                   placeholder="vendor@email.com"
                                   class="w-full rounded-xl border border-gray-200 px-3 py-2 text-sm focus:outline-none focus:ring-2"
                                   style="--tw-ring-color: var(--sage-green)">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-500 mb-1">Instagram</label>
                            <div class="flex items-center rounded-xl border border-gray-200 overflow-hidden focus-within:ring-2" style="--tw-ring-color: var(--sage-green)">
                                <span class="px-3 py-2 text-sm text-gray-400 bg-gray-50 border-r border-gray-200">@</span>
                                <input type="text" name="instagram" value="{{ old('instagram', $vendor->instagram) }}"
                                       placeholder="username"
                                       class="flex-1 px-3 py-2 text-sm focus:outline-none bg-transparent">
                            </div>
                        </div>

                    </div>
                </div>

                {{-- Detail Venue --}}
                <div id="venue-detail-section" class="bg-white rounded-2xl border border-gray-100 overflow-hidden {{ $isVenueCategory ? '' : 'hidden' }}">
                    <div class="px-5 py-3 border-b border-gray-100" style="background: var(--cream)">
                        <h2 class="text-sm font-bold" style="color: var(--dark-gray)">Detail Venue</h2>
                    </div>
                    <div class="p-5 grid grid-cols-1 sm:grid-cols-3 gap-4">

                        <div>
                            <label class="block text-xs font-semibold text-gray-500 mb-1">Kapasitas</label>
                            <input type="text" name="capacity" value="{{ old('capacity', $vendor->capacity) }} Pax"
                                   placeholder="500 – 1.500 tamu"
                                   class="w-full rounded-xl border border-gray-200 px-3 py-2 text-sm focus:outline-none focus:ring-2"
                                   style="--tw-ring-color: var(--sage-green)">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-500 mb-1">Tipe Venue</label>
                            <input type="text" name="venue_type" value="{{ old('venue_type', $vendor->venue_type) }}"
                                   placeholder="Indoor & Outdoor"
                                   class="w-full rounded-xl border border-gray-200 px-3 py-2 text-sm focus:outline-none focus:ring-2"
                                   style="--tw-ring-color: var(--sage-green)">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-500 mb-1">Pengalaman</label>
                            <input type="text" name="experience" value="{{ old('experience', $vendor->experience) }}"
                                   placeholder="10+ Tahun"
                                   class="w-full rounded-xl border border-gray-200 px-3 py-2 text-sm focus:outline-none focus:ring-2"
                                   style="--tw-ring-color: var(--sage-green)">
                        </div>

                        <div class="sm:col-span-3">
                            <label class="block text-xs font-semibold text-gray-500 mb-1">Fasilitas</label>
                            <input type="text" name="facilities" value="{{ old('facilities', $vendor->facilities) }}"
                                   placeholder="AC, Parkir, Lift, Kamar Mandi"
                                   class="w-full rounded-xl border border-gray-200 px-3 py-2 text-sm focus:outline-none focus:ring-2"
                                   style="--tw-ring-color: var(--sage-green)">
                        </div>

                    </div>
                </div>

                {{-- Harga & Statistik --}}
                <div class="bg-white rounded-2xl border border-gray-100 overflow-hidden">
                    <div class="px-5 py-3 border-b border-gray-100" style="background: var(--cream)">
                        <h2 class="text-sm font-bold" style="color: var(--dark-gray)">Harga & Statistik</h2>
                    </div>
                    <div class="p-5 grid grid-cols-2 sm:grid-cols-4 gap-4">

                        <div class="col-span-2">
                            <label class="block text-xs font-semibold text-gray-500 mb-1">Harga Mulai (angka saja)</label>
                            <div class="flex items-center rounded-xl border border-gray-200 overflow-hidden focus-within:ring-2" style="--tw-ring-color: var(--sage-green)">
                                <span class="px-3 py-2 text-xs text-gray-400 bg-gray-50 border-r border-gray-200 whitespace-nowrap">Rp.</span>
                                @php
                                    $derivedStart = $vendor->computePriceStartFromPackages();
                                @endphp
                                <input type="text"
                                       value="{{ $derivedStart !== null ? number_format($derivedStart, 0, ',', '.') : '' }}"
                                       placeholder="{{ $derivedStart === null ? 'Buat minimal 1 paket' : '0' }}"
                                       class="flex-1 px-3 py-2 text-sm focus:outline-none bg-transparent"
                                       readonly
                                       disabled>
                            </div>
                            <p class="text-[10px] text-gray-400 mt-1">Nilai ini otomatis diambil dari harga paket termurah.</p>
                        </div>

                        <div>   
                            <label class="block text-xs font-semibold text-gray-500 mb-1">Rating</label>
                            <input type="number" name="rating" value="{{ old('rating', $vendor->rating) }}"
                                   step="0.1" min="0" max="5" placeholder="4.8"
                                   class="w-full rounded-xl border border-gray-200 px-3 py-2 text-sm bg-gray-50 text-gray-500 cursor-not-allowed"
                                   readonly
                                   disabled
                                   style="--tw-ring-color: var(--sage-green)">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-500 mb-1">Event Selesai</label>
                            <input type="number" name="events_done" value="{{ old('events_done', $vendor->events_done) }}"
                                   min="0" placeholder="0"
                                   class="w-full rounded-xl border border-gray-200 px-3 py-2 text-sm focus:outline-none focus:ring-2"
                                   style="--tw-ring-color: var(--sage-green)">
                        </div>

                    </div>
                </div>

                <div class="bg-white rounded-2xl border border-gray-100 overflow-hidden">
                    <div class="px-5 py-3 border-b border-gray-100" style="background: var(--cream)">
                        <h2 class="text-sm font-bold" style="color: var(--dark-gray)">Promo</h2>
                    </div>
                    <div class="p-5">
                        <label class="block text-xs font-semibold text-gray-500 mb-2">Promo</label>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                            @foreach(\App\Enums\VendorPromo::cases() as $promo)
                            <label class="flex items-center gap-2 cursor-pointer text-sm">
                                <input type="checkbox" name="promo[]" value="{{ $promo->value }}"
                                       @checked(in_array($promo->value, old('promo', $vendor->promo ?? [])))
                                       class="rounded border-gray-300"
                                       style="accent-color: var(--sage-green)">
                                <span>{{ $promo->label() }}</span>
                            </label>
                            @endforeach
                        </div>
                    </div>
                </div>

                {{-- Submit (Tab 1) --}}
                <div class="flex items-center justify-end gap-3 pt-1">
                    <a href="{{ route('vendor.detail', $vendor) }}"
                       class="px-5 py-2.5 rounded-xl text-sm font-semibold border border-gray-200 bg-white hover:border-gray-300 transition"
                       style="color: var(--dark-gray)">
                        Batal
                    </a>
                    <button type="submit"
                            class="inline-flex items-center gap-2 px-6 py-2.5 rounded-xl text-sm font-bold transition hover:opacity-90"
                            style="background-color: var(--sage-green); color: var(--cream)">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        Simpan Perubahan
                    </button>
                </div>

            </div>{{-- /tab-info --}}

            {{-- ══════════════════════════════════════════ --}}
            {{-- TAB 2 — Paket Pernikahan                  --}}
            {{-- ══════════════════════════════════════════ --}}
            <div id="tab-paket" class="tab-panel hidden">

                <div class="rounded-2xl border border-gray-100 bg-white shadow-sm overflow-hidden">
                    <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0" style="background: var(--light-sage)">
                                <svg class="w-4 h-4" style="color: var(--sage-green)" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                            </div>
                            <div>
                                <h2 class="text-sm font-bold" style="color: var(--dark-gray)">Paket Pernikahan</h2>
                                <p class="text-xs text-gray-400 mt-0.5">{{ $vendor->packages->count() }} paket terdaftar</p>
                            </div>
                        </div>
                        <button type="button"
                                onclick="openPkgModal(null)"
                                class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl text-xs font-bold transition hover:opacity-90 text-white"
                                style="background: var(--sage-green)">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                            Tambah Paket
                        </button>
                    </div>
                    <div class="p-5">

                        <div id="pkg-list" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                            @forelse($vendor->packages->sortBy('sort_order') as $pkg)
                            <div class="pkg-card relative rounded-2xl overflow-hidden border border-gray-100 shadow-sm flex flex-col"
                                 data-id="{{ $pkg->id }}">
                                {{-- Colored accent bar --}}
                                <div class="h-1.5 w-full" style="background: {{ $pkg->card_color }}"></div>

                                <div class="p-4 flex flex-col flex-1 gap-3">
                                    {{-- Header: name + status --}}
                                    <div class="flex items-start justify-between gap-2">
                                        <div>
                                            <p class="font-bold text-sm leading-tight" style="color: var(--dark-gray)">{{ $pkg->name }}</p>
                                            <p class="text-base font-bold mt-0.5" style="color: var(--sage-green)">{{ $pkg->price }}</p>
                                            @if($pkg->discount > 0)
                                            <p class="text-xs text-gray-400">Diskon Rp {{ number_format($pkg->discount, 0, ',', '.') }}</p>
                                            @endif
                                        </div>
                                        @if(!$pkg->is_active)
                                        <span class="text-xs px-2 py-0.5 rounded-full bg-gray-100 text-gray-400 flex-shrink-0">Nonaktif</span>
                                        @else
                                        <span class="text-xs px-2 py-0.5 rounded-full flex-shrink-0" style="background:#f0fdf4;color:#15803d">Aktif</span>
                                        @endif
                                    </div>

                                    {{-- Info rows --}}
                                    <div class="space-y-1.5 flex-1">
                                        @if($pkg->max_guests)
                                        <div class="flex items-center gap-2 text-xs text-gray-500">
                                            <svg class="w-3.5 h-3.5 flex-shrink-0 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                            {{ $pkg->max_guests }} Pax
                                        </div>
                                        @endif
                                        @if(!empty($pkg->items))
                                        <div class="flex flex-wrap gap-1 mt-1">
                                            @foreach(array_slice($pkg->items, 0, 4) as $item)
                                            <span class="text-xs px-2 py-0.5 rounded-full" style="background: var(--cream); color: var(--dark-gray)">{{ $item }}</span>
                                            @endforeach
                                            @if(count($pkg->items) > 4)
                                            <span class="text-xs px-2 py-0.5 rounded-full bg-gray-100 text-gray-400">+{{ count($pkg->items) - 4 }} lainnya</span>
                                            @endif
                                        </div>
                                        @endif
                                    </div>

                                    {{-- Actions --}}
                                    <div class="flex items-center gap-2 pt-2 border-t border-gray-100 mt-auto">
                                        <button type="button"
                                                onclick='openPkgModal({{ $pkg->id }}, @json($pkg->name), {{ $pkg->price_raw }}, {{ $pkg->discount }}, @json($pkg->max_guests ?? ''), @json(implode("\n", $pkg->items ?? [])), @json($pkg->card_color), @json($pkg->card_text_color), {{ $pkg->sort_order }}, {{ $pkg->is_active ? 'true' : 'false' }})'
                                                class="flex-1 flex items-center justify-center gap-1.5 px-3 py-1.5 text-xs font-semibold rounded-xl border border-gray-200 bg-white hover:bg-gray-50 transition"
                                                style="color: var(--dark-gray)">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                            Edit
                                        </button>
                                        <button type="button"
                                                onclick="deletePkg({{ $pkg->id }}, this)"
                                                class="flex-1 flex items-center justify-center gap-1.5 px-3 py-1.5 text-xs font-semibold rounded-xl border transition"
                                                style="border-color:#fecdd3;background:#fff1f2;color:#f87171">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                            Hapus
                                        </button>
                                    </div>
                                </div>
                            </div>
                            @empty
                            <div class="sm:col-span-2 lg:col-span-3 flex flex-col items-center justify-center py-12 text-center" id="pkg-empty-notice">
                                <div class="w-14 h-14 rounded-2xl flex items-center justify-center mb-3" style="background: var(--cream)">
                                    <svg class="w-7 h-7" style="color: var(--sage-green); opacity: 0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                                </div>
                                <p class="text-sm font-semibold text-gray-400">Belum ada paket</p>
                                <p class="text-xs text-gray-300 mt-0.5">Klik tombol "Tambah Paket" di atas untuk memulai</p>
                            </div>
                            @endforelse
                        </div>

                    </div>
                </div>

            </div>{{-- /tab-paket --}}

            {{-- ══════════════════════════════════════════ --}}
            {{-- TAB 3 — Media                             --}}
            {{-- ══════════════════════════════════════════ --}}
            <div id="tab-media" class="tab-panel hidden">

                {{-- Foto Cover --}}
                <div class="bg-white rounded-2xl border border-gray-100 overflow-hidden">
                    <div class="px-5 py-3 border-b border-gray-100" style="background: var(--cream)">
                        <h2 class="text-sm font-bold" style="color: var(--dark-gray)">Foto Cover</h2>
                    </div>
                    <div class="p-5">
                        <div class="flex flex-col sm:flex-row gap-5 items-start">
                            @php $coverImages2 = array_values(array_filter((array)($vendor->cover_image ?? []))); @endphp
                            @if(count($coverImages2))
                            <div class="flex-shrink-0">
                                <p class="text-xs text-gray-400 mb-1.5">Cover saat ini ({{ count($coverImages2) }} foto) — hover untuk hapus:</p>
                                <div class="flex flex-wrap gap-2" id="existing-covers2">
                                    @foreach($coverImages2 as $ci2 => $cp2)
                                    @php $imgUrl2 = str_starts_with($cp2, 'http') ? $cp2 : \Illuminate\Support\Facades\Storage::url($cp2); @endphp
                                    <div class="relative group" id="cover-item2-{{ $ci2 }}">
                                        <img src="{{ $imgUrl2 }}" alt="Cover {{ $ci2 + 1 }}"
                                             class="w-24 h-20 object-cover rounded-xl border border-gray-200">
                                        <input type="hidden" name="cover_image_keep[]" value="{{ $cp2 }}" id="keep2-{{ $ci2 }}">
                                        <button type="button"
                                                onclick="removeCover2({{ $ci2 }})"
                                                class="absolute -top-1.5 -right-1.5 w-5 h-5 rounded-full flex items-center justify-center text-white text-xs font-bold opacity-0 group-hover:opacity-100 transition"
                                                style="background: #f87171"
                                                title="Hapus foto ini">
                                            ✕
                                        </button>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                            @endif
                            <div class="flex-1">
                                <label class="block text-xs font-semibold text-gray-500 mb-1.5">
                                    Ganti Foto Cover <span class="font-normal text-gray-400">(minimal 5 foto, maks. 1MB/file)</span>
                                </label>
                                <label for="cover_upload2"
                                       class="flex flex-col items-center justify-center gap-2 w-full h-32 rounded-xl border-2 border-dashed cursor-pointer transition"
                                       style="border-color: var(--light-sage); background: var(--cream)"
                                       id="upload-label2"
                                       onmouseenter="this.style.borderColor='var(--sage-green)'"
                                       onmouseleave="this.style.borderColor='var(--light-sage)'">
                                    <svg class="w-7 h-7" style="color: var(--sage-green)" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                                    </svg>
                                    <span class="text-xs font-medium" style="color: var(--sage-green)">Klik untuk pilih foto (minimal 5 foto)</span>
                                    <span class="text-xs text-gray-400">JPG, PNG, WEBP · maks. 1MB/file</span>
                                    <input type="file" name="cover_image[]" accept="image/*" multiple
                                           class="hidden"
                                           id="cover_upload2"
                                           onchange="previewCover2(this)">
                                </label>
                                <div id="cover-preview-wrap2" class="mt-3 hidden">
                                    <p class="text-xs text-gray-400 mb-1">Preview baru:</p>
                                    <div id="cover-preview-grid2" class="flex flex-wrap gap-2"></div>
                                </div>
                                @error('cover_image')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                                @error('cover_image.*')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Galeri Foto & Video --}}
                <div class="bg-white rounded-2xl border border-gray-100 overflow-hidden mt-4">
                    <div class="px-5 py-3 border-b border-gray-100 flex items-center justify-between" style="background: var(--cream)">
                        <h2 class="text-sm font-bold" style="color: var(--dark-gray)">Galeri Foto & Video</h2>
                        <button type="button" onclick="openGalleryModal()"
                                class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-xl text-xs font-bold transition hover:opacity-90"
                                style="background: var(--sage-green); color: #fff">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                            </svg>
                            Tambah Item
                        </button>
                    </div>
                    <div class="p-5">
                        <div id="gallery-list" class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-3">
                            @forelse($vendor->galleries->sortBy('sort_order') as $gal)
                            <div class="gallery-card relative group rounded-xl overflow-hidden border border-gray-100 shadow-sm"
                                 data-id="{{ $gal->id }}"
                                 data-caption="{{ $gal->caption ?? '' }}"
                                 data-video-url="{{ $gal->video_url ?? '' }}"
                                 data-image-url="{{ $gal->image_url ?? '' }}"
                                 style="aspect-ratio: 9/16">
                                @if($gal->image_url)
                                <img src="{{ $gal->image_url }}"
                                     alt="{{ $gal->caption ?? 'Galeri' }}"
                                     class="w-full h-full object-cover">
                                @else
                                <div class="w-full h-full flex items-center justify-center bg-gray-50">
                                    <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                                @endif
                                @if(!empty($gal->video_url))
                                <div class="absolute inset-0 flex items-center justify-center pointer-events-none gal-video-badge">
                                    <div class="w-8 h-8 rounded-full bg-black/60 flex items-center justify-center">
                                        <svg class="w-4 h-4 ml-0.5 text-white" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M8 5v14l11-7z"/>
                                        </svg>
                                    </div>
                                </div>
                                @endif
                                @if($gal->caption)
                                <div class="absolute bottom-0 left-0 right-0 px-2 py-1.5 bg-gradient-to-t from-black/70 to-transparent pointer-events-none gal-caption-overlay">
                                    <p class="text-white text-[11px] truncate">{{ $gal->caption }}</p>
                                </div>
                                @endif
                                {{-- Action buttons (visible on hover) --}}
                                <div class="absolute top-1.5 left-1.5 right-1.5 flex items-center justify-between opacity-0 group-hover:opacity-100 transition-opacity">
                                    <button type="button"
                                            onclick="openGalleryEditModal(this.closest('.gallery-card'))"
                                            class="w-6 h-6 rounded-full flex items-center justify-center text-white transition hover:scale-110"
                                            style="background: rgba(0,0,0,0.55)"
                                            title="Edit">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </button>
                                    <button type="button"
                                            onclick="deleteGallery({{ $gal->id }}, this)"
                                            class="w-6 h-6 rounded-full flex items-center justify-center text-white text-xs font-bold transition hover:scale-110"
                                            style="background: rgba(248,113,113,0.9)"
                                            title="Hapus">✕</button>
                                </div>
                            </div>
                            @empty
                            <div id="gallery-empty-notice"
                                 class="col-span-2 sm:col-span-3 lg:col-span-4 flex flex-col items-center justify-center py-10 text-center">
                                <div class="w-12 h-12 rounded-2xl flex items-center justify-center mb-3" style="background: var(--cream)">
                                    <svg class="w-6 h-6" style="color: var(--sage-green); opacity:0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                                <p class="text-sm font-semibold text-gray-400">Belum ada foto galeri</p>
                                <p class="text-xs text-gray-300 mt-0.5">Klik "Tambah Item" untuk mulai mengunggah.</p>
                            </div>
                            @endforelse
                        </div>
                    </div>
                </div>

                {{-- Submit (Tab Media) --}}
                <div class="flex items-center justify-end gap-3 pt-5">
                    <a href="{{ route('vendor.detail', $vendor) }}"
                       class="px-5 py-2.5 rounded-xl text-sm font-semibold border border-gray-200 bg-white hover:border-gray-300 transition"
                       style="color: var(--dark-gray)">
                        Batal
                    </a>
                    <button type="submit"
                            class="inline-flex items-center gap-2 px-6 py-2.5 rounded-xl text-sm font-bold transition hover:opacity-90"
                            style="background-color: var(--sage-green); color: var(--cream)">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        Simpan Perubahan
                    </button>
                </div>

            </div>{{-- /tab-media --}}

        </form>
    </div>

    <style>
    .tab-btn {
        color: var(--dark-gray);
        background: transparent;
    }
    .tab-btn.active {
        background: white;
        color: var(--sage-green);
        box-shadow: 0 1px 4px rgba(0,0,0,0.08);
    }
    </style>

    <script>
    // ── Tab switching ─────────────────────────────────────────────
    const TAB_IDS = ['tab-info', 'tab-paket', 'tab-media'];

    function switchTab(id) {
        TAB_IDS.forEach(function(t) {
            document.getElementById(t).classList.add('hidden');
            document.getElementById('btn-' + t).classList.remove('active');
        });
        document.getElementById(id).classList.remove('hidden');
        document.getElementById('btn-' + id).classList.add('active');
        // persist in sessionStorage so page reload keeps tab
        sessionStorage.setItem('editVendorTab', id);
    }

    document.addEventListener('DOMContentLoaded', function() {
        // Restore tab: if errors on form, stay on info tab; else check session
        @if($errors->any())
        switchTab('tab-info');
        @else
        var saved = sessionStorage.getItem('editVendorTab') || 'tab-info';
        switchTab(saved);
        @endif

        var venueCategories = @json($venueCategories);
        var categoryEl = document.getElementById('vendor-category');
        var venueFieldsEl = document.getElementById('venue-category-only-fields');
        var venueDetailEl = document.getElementById('venue-detail-section');

        function toggleVenueFields() {
            if (!categoryEl) return;
            var isVenue = venueCategories.indexOf(categoryEl.value) !== -1;
            if (venueFieldsEl) venueFieldsEl.classList.toggle('hidden', !isVenue);
            if (venueDetailEl) venueDetailEl.classList.toggle('hidden', !isVenue);
        }

        if (categoryEl) {
            categoryEl.addEventListener('change', toggleVenueFields);
            toggleVenueFields();
        }
    });

    const MAX_FILE_SIZE = 1 * 1024 * 1024; // 1MB

    function checkFileSizes(files) {
        return Array.from(files).filter(f => f.size > MAX_FILE_SIZE);
    }

    function showFileSizeModal(oversized) {
        const names = oversized.map(f => `• ${f.name} (${(f.size / 1024 / 1024).toFixed(2)} MB)`).join('<br>');
        document.getElementById('file-size-modal-body').innerHTML = names;
        document.getElementById('file-size-modal').classList.remove('hidden');
    }

    function closeFileSizeModal() {
        document.getElementById('file-size-modal').classList.add('hidden');
    }

    function previewCover2(input) {
        const oversized = checkFileSizes(input.files);
        if (oversized.length > 0) {
            showFileSizeModal(oversized);
            input.value = '';
            return;
        }
        const wrap = document.getElementById('cover-preview-wrap2');
        const grid = document.getElementById('cover-preview-grid2');
        if (input.files && input.files.length > 0) {
            grid.innerHTML = '';
            Array.from(input.files).forEach(file => {
                const reader = new FileReader();
                reader.onload = e => {
                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.className = 'w-24 h-20 object-cover rounded-xl border border-gray-200';
                    grid.appendChild(img);
                };
                reader.readAsDataURL(file);
            });
            wrap.classList.remove('hidden');
        } else {
            wrap.classList.add('hidden');
        }
    }

    function removeCover2(index) {
        const item = document.getElementById('cover-item2-' + index);
        const keep = document.getElementById('keep2-' + index);
        if (item) item.remove();
        if (keep) keep.remove();
    }

    // Toggle switch styling
    document.querySelectorAll('.peer-checked\\:bg-sage').forEach(el => {
        const input = el.previousElementSibling;
        if (input && input.type === 'checkbox') {
            const update = () => {
                if (input.checked) {
                    el.style.backgroundColor = 'var(--sage-green)';
                } else {
                    el.style.backgroundColor = '';
                }
            };
            update();
            input.addEventListener('change', update);
        }
    });
    </script>

    {{-- ── File Size Error Modal ── --}}
    <div id="file-size-modal" class="hidden fixed inset-0 z-[9999] flex items-center justify-center p-4" style="background: rgba(0,0,0,0.45)">
        <div class="bg-white rounded-2xl shadow-2xl max-w-sm w-full p-6 flex flex-col items-center gap-4 text-center">
            <div class="w-14 h-14 rounded-full flex items-center justify-center" style="background:#fef2f2">
                <svg class="w-7 h-7" style="color:#f87171" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
                </svg>
            </div>
            <h3 class="text-base font-bold" style="color:#444">File Terlalu Besar</h3>
            <p class="text-sm text-gray-500">File berikut melebihi batas maksimal <strong>1MB</strong>:</p>
            <p id="file-size-modal-body" class="text-xs text-left w-full bg-gray-50 rounded-lg p-3" style="color:#f87171; line-height:1.8"></p>
            <p class="text-xs text-gray-400">Silakan kompres gambar terlebih dahulu menggunakan tools seperti <strong>TinyPNG</strong> atau <strong>Squoosh</strong>.</p>
            <button onclick="closeFileSizeModal()"
                    class="w-full py-2.5 rounded-xl text-sm font-semibold text-white transition hover:opacity-90"
                    style="background: var(--sage-green)">
                Mengerti
            </button>
        </div>
    </div>

    {{-- ── Package Modal ── --}}
    <div id="pkg-modal" class="hidden fixed inset-0 z-[9990] flex items-start justify-center p-4 overflow-y-auto" style="background: rgba(0,0,0,0.45)">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg my-8">
            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
                <h3 id="pkg-modal-title" class="text-base font-bold" style="color: var(--dark-gray)">Tambah Paket</h3>
                <button type="button" onclick="closePkgModal()" class="text-gray-400 hover:text-gray-600 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            <div class="p-6 space-y-4">
                <input type="hidden" id="pkg-id">

                <div>
                    <label class="block text-xs font-semibold mb-1.5" style="color: var(--dark-gray)">Nama Paket <span style="color:#f87171">*</span></label>
                    <input type="text" id="pkg-name" placeholder="Contoh: Paket Silver"
                           class="w-full px-3.5 py-2 text-sm rounded-xl border border-gray-200 focus:outline-none focus:border-sage-green transition"
                           style="color: var(--dark-gray)">
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold mb-1.5" style="color: var(--dark-gray)">Harga (Rp) <span style="color:#f87171">*</span></label>
                        <input type="text" id="pkg-price-raw" placeholder="35.000.000"
                               class="w-full px-3.5 py-2 text-sm rounded-xl border border-gray-200 focus:outline-none transition pkg-price-fmt"
                               style="color: var(--dark-gray)">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold mb-1.5" style="color: var(--dark-gray)">Diskon (Rp)</label>
                        <input type="text" id="pkg-discount" placeholder="0"
                               class="w-full px-3.5 py-2 text-sm rounded-xl border border-gray-200 focus:outline-none transition pkg-price-fmt"
                               style="color: var(--dark-gray)">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold mb-1.5" style="color: var(--dark-gray)">Kapasitas Tamu</label>
                    <input type="text" id="pkg-max-guests" placeholder="Contoh: Maks. 300 tamu"
                           class="w-full px-3.5 py-2 text-sm rounded-xl border border-gray-200 focus:outline-none transition"
                           style="color: var(--dark-gray)">
                </div>

                <div>
                    <label class="block text-xs font-semibold mb-1.5" style="color: var(--dark-gray)">Item Paket <span class="font-normal text-gray-400">(satu per baris)</span></label>
                    <textarea id="pkg-items" rows="5" placeholder="Gedung 6 jam&#10;Katering 300 tamu&#10;Dekorasi pelaminan"
                              class="w-full px-3.5 py-2 text-sm rounded-xl border border-gray-200 focus:outline-none transition resize-none"
                              style="color: var(--dark-gray)"></textarea>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold mb-1.5" style="color: var(--dark-gray)">Warna Kartu</label>
                        <div class="flex items-center gap-2">
                            <input type="color" id="pkg-card-color" value="#C8D5B9"
                                   class="w-9 h-9 rounded-lg border border-gray-200 cursor-pointer p-0.5">
                            <input type="text" id="pkg-card-color-text" value="#C8D5B9" maxlength="7"
                                   class="flex-1 px-3 py-2 text-xs rounded-xl border border-gray-200 focus:outline-none transition font-mono"
                                   style="color: var(--dark-gray)">
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold mb-1.5" style="color: var(--dark-gray)">Warna Teks</label>
                        <div class="flex items-center gap-2">
                            <input type="color" id="pkg-text-color" value="#444444"
                                   class="w-9 h-9 rounded-lg border border-gray-200 cursor-pointer p-0.5">
                            <input type="text" id="pkg-text-color-text" value="#444444" maxlength="7"
                                   class="flex-1 px-3 py-2 text-xs rounded-xl border border-gray-200 focus:outline-none transition font-mono"
                                   style="color: var(--dark-gray)">
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold mb-1.5" style="color: var(--dark-gray)">Urutan Tampil</label>
                        <input type="number" id="pkg-sort-order" value="0" min="0"
                               class="w-full px-3.5 py-2 text-sm rounded-xl border border-gray-200 focus:outline-none transition"
                               style="color: var(--dark-gray)">
                    </div>
                    <div class="flex flex-col justify-end pb-1">
                        <label class="flex items-center gap-3 cursor-pointer select-none">
                            <span class="text-xs font-semibold" style="color: var(--dark-gray)">Aktif</span>
                            <div class="relative">
                                <input type="checkbox" id="pkg-is-active" class="sr-only" checked>
                                <div id="pkg-toggle-track"
                                     onclick="togglePkgActive()"
                                     class="w-10 h-5 rounded-full cursor-pointer transition-colors duration-200"
                                     style="background: var(--sage-green)">
                                    <div id="pkg-toggle-thumb"
                                         class="absolute top-0.5 left-0.5 w-4 h-4 bg-white rounded-full shadow transition-transform duration-200"
                                         style="transform: translateX(20px)"></div>
                                </div>
                            </div>
                        </label>
                    </div>
                </div>

                <div id="pkg-error" class="hidden text-xs rounded-lg px-3 py-2" style="background:#fff1f2;color:#f87171"></div>
            </div>
            <div class="flex items-center justify-end gap-3 px-6 py-4 border-t border-gray-100">
                <button type="button" onclick="closePkgModal()"
                        class="px-5 py-2 rounded-xl text-sm font-semibold border border-gray-200 bg-white hover:bg-gray-50 transition"
                        style="color: var(--dark-gray)">
                    Batal
                </button>
                <button type="button" id="pkg-save-btn" onclick="savePkg()"
                        class="inline-flex items-center gap-2 px-6 py-2 rounded-xl text-sm font-bold transition hover:opacity-90"
                        style="background: var(--sage-green); color: #fff">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                    Simpan Paket
                </button>
            </div>
        </div>
    </div>

    {{-- ── Gallery Modal ── --}}
    <div id="gallery-modal" class="hidden fixed inset-0 z-[9991] flex items-center justify-center p-4" style="background: rgba(0,0,0,0.45)">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md">
            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
                <h3 id="gal-modal-title" class="text-base font-bold" style="color: var(--dark-gray)">Tambah Item Galeri</h3>
                <button type="button" onclick="closeGalleryModal()" class="text-gray-400 hover:text-gray-600 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            <div class="p-6 space-y-4">
                <input type="hidden" id="gal-id">

                {{-- Current image preview (edit mode only) --}}
                <div id="gal-current-wrap" class="hidden">
                    <p class="text-xs font-semibold mb-1.5" style="color: var(--dark-gray)">Foto saat ini:</p>
                    <img id="gal-current-img" src="" alt="Foto saat ini"
                         class="w-full max-h-36 object-contain rounded-xl border border-gray-100 bg-gray-50">
                </div>

                {{-- Image upload --}}
                <div>
                    <label class="block text-xs font-semibold mb-1.5" style="color: var(--dark-gray)">
                        <span id="gal-photo-label">Foto</span>
                        <span id="gal-photo-required" style="color:#f87171"> *</span>
                        <span id="gal-photo-optional" class="hidden font-normal text-gray-400"> (opsional — biarkan kosong untuk tidak mengubah)</span>
                        <span class="font-normal text-gray-400"> (maks. 2MB, JPG/PNG/WEBP)</span>
                    </label>
                    <label for="gal-image-input"
                           class="flex flex-col items-center justify-center gap-2 w-full h-28 rounded-xl border-2 border-dashed cursor-pointer transition"
                           id="gal-upload-label"
                           style="border-color: var(--light-sage); background: var(--cream)"
                           onmouseenter="this.style.borderColor='var(--sage-green)'"
                           onmouseleave="this.style.borderColor='var(--light-sage)'">
                        <svg id="gal-upload-icon" class="w-6 h-6" style="color: var(--sage-green)" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                        </svg>
                        <span id="gal-upload-text" class="text-xs font-medium" style="color: var(--sage-green)">Klik untuk pilih foto</span>
                        <input type="file" id="gal-image-input" accept="image/jpeg,image/png,image/webp" class="hidden"
                               onchange="previewGalleryImage(this)">
                    </label>
                    <div id="gal-preview-wrap" class="mt-2 hidden">
                        <img id="gal-preview-img" class="w-full max-h-40 object-contain rounded-xl border border-gray-100">
                    </div>
                </div>

                {{-- Caption --}}
                <div>
                    <label class="block text-xs font-semibold mb-1.5" style="color: var(--dark-gray)">
                        Caption <span class="font-normal text-gray-400">(opsional)</span>
                    </label>
                    <input type="text" id="gal-caption" placeholder="Contoh: Ruang pesta utama"
                           class="w-full px-3.5 py-2 text-sm rounded-xl border border-gray-200 focus:outline-none focus:border-sage-green transition"
                           style="color: var(--dark-gray)" maxlength="255">
                </div>

                {{-- Video URL --}}
                <div>
                    <label class="block text-xs font-semibold mb-1.5" style="color: var(--dark-gray)">
                        URL Video YouTube <span class="font-normal text-gray-400">(opsional)</span>
                    </label>
                    <input type="url" id="gal-video-url" placeholder="https://youtube.com/watch?v=..."
                           class="w-full px-3.5 py-2 text-sm rounded-xl border border-gray-200 focus:outline-none transition"
                           style="color: var(--dark-gray)" maxlength="255">
                </div>

                <div id="gal-error" class="hidden text-xs rounded-lg px-3 py-2" style="background:#fff1f2;color:#f87171"></div>
            </div>
            <div class="flex items-center justify-end gap-3 px-6 py-4 border-t border-gray-100">
                <button type="button" onclick="closeGalleryModal()"
                        class="px-5 py-2 rounded-xl text-sm font-semibold border border-gray-200 bg-white hover:bg-gray-50 transition"
                        style="color: var(--dark-gray)">
                    Batal
                </button>
                <button type="button" id="gal-save-btn" onclick="saveGallery()"
                        class="inline-flex items-center gap-2 px-6 py-2 rounded-xl text-sm font-bold transition hover:opacity-90"
                        style="background: var(--sage-green); color: #fff">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                    Simpan
                </button>
            </div>
        </div>
    </div>

    <script>
    // Format price inputs with thousand dots; strip before submit
    document.querySelectorAll('input.price-fmt').forEach(function(input) {
        input.addEventListener('input', function() {
            var raw = this.value.replace(/\./g, '').replace(/\D/g, '');
            this.value = raw ? parseInt(raw, 10).toLocaleString('id-ID') : '';
        });
        input.closest('form').addEventListener('submit', function() {
            input.value = input.value.replace(/\./g, '');
        }, { once: true });
    });

    // ── Package Modal ──────────────────────────────────────────────
    const PKG_STORE_URL  = '{{ route('vendor.packages.store', $vendor) }}';
    const PKG_UPDATE_URL = '{{ route('vendor.packages.update', ['vendor' => $vendor->slug, 'package' => '__ID__']) }}';
    const PKG_DELETE_URL = '{{ route('vendor.packages.destroy', ['vendor' => $vendor->slug, 'package' => '__ID__']) }}';
    const CSRF_TOKEN     = '{{ csrf_token() }}';

    // Sync color picker ↔ text input
    document.getElementById('pkg-card-color').addEventListener('input', function() {
        document.getElementById('pkg-card-color-text').value = this.value;
    });
    document.getElementById('pkg-card-color-text').addEventListener('input', function() {
        if (/^#[0-9a-fA-F]{6}$/.test(this.value)) {
            document.getElementById('pkg-card-color').value = this.value;
        }
    });
    document.getElementById('pkg-text-color').addEventListener('input', function() {
        document.getElementById('pkg-text-color-text').value = this.value;
    });
    document.getElementById('pkg-text-color-text').addEventListener('input', function() {
        if (/^#[0-9a-fA-F]{6}$/.test(this.value)) {
            document.getElementById('pkg-text-color').value = this.value;
        }
    });

    // Thousand-dot format on pkg price inputs
    document.querySelectorAll('.pkg-price-fmt').forEach(function(input) {
        input.addEventListener('input', function() {
            var raw = this.value.replace(/\./g, '').replace(/\D/g, '');
            this.value = raw ? parseInt(raw, 10).toLocaleString('id-ID') : '';
        });
    });

    function openPkgModal(id, name, priceRaw, discount, maxGuests, items, cardColor, textColor, sortOrder, isActive) {
        document.getElementById('pkg-id').value          = id ?? '';
        document.getElementById('pkg-name').value        = name ?? '';
        document.getElementById('pkg-price-raw').value   = priceRaw ? parseInt(priceRaw).toLocaleString('id-ID') : '';
        document.getElementById('pkg-discount').value    = discount ? parseInt(discount).toLocaleString('id-ID') : '';
        document.getElementById('pkg-max-guests').value  = maxGuests ?? '';
        document.getElementById('pkg-items').value       = items ?? '';
        document.getElementById('pkg-card-color').value  = cardColor ?? '#C8D5B9';
        document.getElementById('pkg-card-color-text').value = cardColor ?? '#C8D5B9';
        document.getElementById('pkg-text-color').value  = textColor ?? '#444444';
        document.getElementById('pkg-text-color-text').value = textColor ?? '#444444';
        document.getElementById('pkg-sort-order').value  = sortOrder ?? 0;

        const pkgActive = (isActive === true || isActive === 'true');
        document.getElementById('pkg-is-active').checked = pkgActive;
        document.getElementById('pkg-toggle-track').style.background = pkgActive ? 'var(--sage-green)' : '#d1d5db';
        document.getElementById('pkg-toggle-thumb').style.transform   = pkgActive ? 'translateX(20px)' : 'translateX(0)';

        document.getElementById('pkg-modal-title').textContent = id ? 'Edit Paket' : 'Tambah Paket';
        document.getElementById('pkg-error').classList.add('hidden');
        document.getElementById('pkg-modal').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function closePkgModal() {
        document.getElementById('pkg-modal').classList.add('hidden');
        document.body.style.overflow = '';
    }

    function togglePkgActive() {
        const check = document.getElementById('pkg-is-active');
        check.checked = !check.checked;
        document.getElementById('pkg-toggle-track').style.background = check.checked ? 'var(--sage-green)' : '#d1d5db';
        document.getElementById('pkg-toggle-thumb').style.transform   = check.checked ? 'translateX(20px)' : 'translateX(0)';
    }

    function parsePkgPrice(str) {
        return parseInt(str.replace(/\./g, '').replace(/\D/g, '') || '0', 10);
    }

    async function savePkg() {
        const id       = document.getElementById('pkg-id').value;
        const name     = document.getElementById('pkg-name').value.trim();
        const priceRaw = parsePkgPrice(document.getElementById('pkg-price-raw').value);
        const discount = parsePkgPrice(document.getElementById('pkg-discount').value);
        const errEl    = document.getElementById('pkg-error');

        if (!name) {
            errEl.textContent = 'Nama paket wajib diisi.';
            errEl.classList.remove('hidden');
            return;
        }
        if (!priceRaw) {
            errEl.textContent = 'Harga wajib diisi.';
            errEl.classList.remove('hidden');
            return;
        }
        errEl.classList.add('hidden');

        const btn = document.getElementById('pkg-save-btn');
        btn.disabled = true;
        btn.textContent = 'Menyimpan…';

        const payload = {
            name:             name,
            price_raw:        priceRaw,
            discount:         discount,
            max_guests:       document.getElementById('pkg-max-guests').value.trim(),
            items:            document.getElementById('pkg-items').value,
            card_color:       document.getElementById('pkg-card-color').value,
            card_text_color:  document.getElementById('pkg-text-color').value,
            sort_order:       parseInt(document.getElementById('pkg-sort-order').value || '0', 10),
            is_active:        document.getElementById('pkg-is-active').checked ? 1 : 0,
        };

        let url, method;
        if (id) {
            url    = PKG_UPDATE_URL.replace('__ID__', id);
            method = 'PUT';
        } else {
            url    = PKG_STORE_URL;
            method = 'POST';
        }

        try {
            const res = await fetch(url, {
                method: method,
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF_TOKEN, 'Accept': 'application/json' },
                body: JSON.stringify(payload),
            });
            const data = await res.json();
            if (!res.ok) {
                const msgs = data.errors ? Object.values(data.errors).flat().join(', ') : (data.message || 'Terjadi kesalahan.');
                errEl.textContent = msgs;
                errEl.classList.remove('hidden');
                return;
            }
            closePkgModal();
            location.reload();
        } catch (e) {
            errEl.textContent = 'Koneksi gagal. Coba lagi.';
            errEl.classList.remove('hidden');
        } finally {
            btn.disabled = false;
            btn.innerHTML = '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg> Simpan Paket';
        }
    }

    async function deletePkg(id, btn) {
        if (!confirm('Hapus paket ini?')) return;
        btn.disabled = true;
        const url = PKG_DELETE_URL.replace('__ID__', id);
        try {
            const res = await fetch(url, {
                method: 'DELETE',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF_TOKEN, 'Accept': 'application/json' },
                body: JSON.stringify({}),
            });
            if (res.ok) {
                const card = btn.closest('.pkg-card');
                if (card) card.remove();
                const list = document.getElementById('pkg-list');
                if (list && list.querySelectorAll('.pkg-card').length === 0) {
                    const div = document.createElement('div');
                    div.id = 'pkg-empty-notice';
                    div.className = 'sm:col-span-2 lg:col-span-3 flex flex-col items-center justify-center py-12 text-center';
                    div.innerHTML = '<div class="w-14 h-14 rounded-2xl flex items-center justify-center mb-3" style="background: var(--cream)"><svg class="w-7 h-7" style="color: var(--sage-green); opacity: 0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg></div><p class="text-sm font-semibold text-gray-400">Belum ada paket</p><p class="text-xs text-gray-300 mt-0.5">Klik tombol "Tambah Paket" di atas untuk memulai</p>';
                    list.appendChild(div);
                }
            }
        } catch (e) {
            alert('Gagal menghapus paket.');
        } finally {
            btn.disabled = false;
        }
    }

    // Close modal on backdrop click
    document.getElementById('pkg-modal').addEventListener('click', function(e) {
        if (e.target === this) closePkgModal();
    });
    </script>

    <script>
    // Close gallery modal on backdrop click
    document.getElementById('gallery-modal').addEventListener('click', function(e) {
        if (e.target === this) closeGalleryModal();
    });

    // ── Gallery AJAX ──────────────────────────────────────────────
    const GAL_STORE_URL  = '{{ route('vendor.gallery.store', $vendor) }}';
    const GAL_UPDATE_URL = '{{ route('vendor.gallery.update', ['vendor' => $vendor->slug, 'gallery' => '__ID__']) }}';
    const GAL_DELETE_URL = '{{ route('vendor.gallery.destroy', ['vendor' => $vendor->slug, 'gallery' => '__ID__']) }}';

    function _resetGalleryModal() {
        document.getElementById('gal-id').value = '';
        document.getElementById('gal-image-input').value = '';
        document.getElementById('gal-caption').value = '';
        document.getElementById('gal-video-url').value = '';
        document.getElementById('gal-preview-wrap').classList.add('hidden');
        document.getElementById('gal-current-wrap').classList.add('hidden');
        document.getElementById('gal-upload-icon').classList.remove('hidden');
        document.getElementById('gal-upload-text').textContent = 'Klik untuk pilih foto';
        document.getElementById('gal-error').classList.add('hidden');
    }

    function openGalleryModal() {
        _resetGalleryModal();
        document.getElementById('gal-modal-title').textContent = 'Tambah Item Galeri';
        document.getElementById('gal-photo-required').classList.remove('hidden');
        document.getElementById('gal-photo-optional').classList.add('hidden');
        document.getElementById('gallery-modal').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function openGalleryEditModal(card) {
        _resetGalleryModal();
        const id       = card.dataset.id;
        const caption  = card.dataset.caption  || '';
        const videoUrl = card.dataset.videoUrl || '';
        const imageUrl = card.dataset.imageUrl || '';

        document.getElementById('gal-id').value        = id;
        document.getElementById('gal-caption').value   = caption;
        document.getElementById('gal-video-url').value = videoUrl;

        if (imageUrl) {
            document.getElementById('gal-current-img').src = imageUrl;
            document.getElementById('gal-current-wrap').classList.remove('hidden');
        }

        document.getElementById('gal-modal-title').textContent = 'Edit Item Galeri';
        document.getElementById('gal-photo-required').classList.add('hidden');
        document.getElementById('gal-photo-optional').classList.remove('hidden');
        document.getElementById('gallery-modal').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function closeGalleryModal() {
        document.getElementById('gallery-modal').classList.add('hidden');
        document.body.style.overflow = '';
    }

    function previewGalleryImage(input) {
        const file = input.files[0];
        if (!file) return;
        if (file.size > 2 * 1024 * 1024) {
            document.getElementById('gal-error').textContent = 'Ukuran file melebihi 2MB.';
            document.getElementById('gal-error').classList.remove('hidden');
            input.value = '';
            return;
        }
        document.getElementById('gal-error').classList.add('hidden');
        const reader = new FileReader();
        reader.onload = function(e) {
            const img = document.getElementById('gal-preview-img');
            img.src = e.target.result;
            document.getElementById('gal-preview-wrap').classList.remove('hidden');
            document.getElementById('gal-upload-icon').classList.add('hidden');
            document.getElementById('gal-upload-text').textContent = file.name;
        };
        reader.readAsDataURL(file);
    }

    function _buildGalleryCardInnerHTML(g) {
        return `
            <img src="${g.image_url}" alt="${g.caption || 'Galeri'}" class="w-full h-full object-cover">
            ${g.video_url ? `<div class="absolute inset-0 flex items-center justify-center pointer-events-none gal-video-badge"><div class="w-8 h-8 rounded-full bg-black/60 flex items-center justify-center"><svg class="w-4 h-4 ml-0.5 text-white" fill="currentColor" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg></div></div>` : ''}
            ${g.caption ? `<div class="absolute bottom-0 left-0 right-0 px-2 py-1.5 bg-gradient-to-t from-black/70 to-transparent pointer-events-none gal-caption-overlay"><p class="text-white text-[11px] truncate">${g.caption}</p></div>` : ''}
            <div class="absolute top-1.5 left-1.5 right-1.5 flex items-center justify-between opacity-0 group-hover:opacity-100 transition-opacity">
                <button type="button" onclick="openGalleryEditModal(this.closest('.gallery-card'))"
                        class="w-6 h-6 rounded-full flex items-center justify-center text-white transition hover:scale-110"
                        style="background: rgba(0,0,0,0.55)" title="Edit">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                </button>
                <button type="button" onclick="deleteGallery(${g.id}, this)"
                        class="w-6 h-6 rounded-full flex items-center justify-center text-white text-xs font-bold transition hover:scale-110"
                        style="background: rgba(248,113,113,0.9)" title="Hapus">✕</button>
            </div>`;
    }

    async function saveGallery() {
        const galId     = document.getElementById('gal-id').value;
        const isEdit    = !!galId;
        const fileInput = document.getElementById('gal-image-input');
        const errEl     = document.getElementById('gal-error');

        if (!isEdit && !fileInput.files.length) {
            errEl.textContent = 'Pilih foto terlebih dahulu.';
            errEl.classList.remove('hidden');
            return;
        }
        errEl.classList.add('hidden');

        const formData = new FormData();
        formData.append('_token', CSRF_TOKEN);
        if (fileInput.files.length) {
            formData.append('image', fileInput.files[0]);
        }
        formData.append('caption',   document.getElementById('gal-caption').value.trim());
        formData.append('video_url', document.getElementById('gal-video-url').value.trim());

        const url = isEdit
            ? GAL_UPDATE_URL.replace('__ID__', galId)
            : GAL_STORE_URL;

        const btn = document.getElementById('gal-save-btn');
        btn.disabled = true;
        btn.textContent = 'Menyimpan…';

        try {
            const res  = await fetch(url, { method: 'POST', headers: { 'X-CSRF-TOKEN': CSRF_TOKEN, 'Accept': 'application/json' }, body: formData });
            const data = await res.json();
            if (!res.ok) {
                const msgs = data.errors ? Object.values(data.errors).flat().join(', ') : (data.message || 'Terjadi kesalahan.');
                errEl.textContent = msgs;
                errEl.classList.remove('hidden');
                return;
            }

            const g = data.gallery;
            closeGalleryModal();

            if (isEdit) {
                // Update existing card in-place
                const card = document.querySelector(`.gallery-card[data-id="${galId}"]`);
                if (card) {
                    card.dataset.caption  = g.caption  || '';
                    card.dataset.videoUrl = g.video_url || '';
                    card.dataset.imageUrl = g.image_url || '';
                    card.innerHTML = _buildGalleryCardInnerHTML(g);
                }
            } else {
                // Append new card
                const list  = document.getElementById('gallery-list');
                const empty = document.getElementById('gallery-empty-notice');
                if (empty) empty.remove();

                const card = document.createElement('div');
                card.className = 'gallery-card relative group rounded-xl overflow-hidden border border-gray-100 shadow-sm';
                card.dataset.id       = g.id;
                card.dataset.caption  = g.caption  || '';
                card.dataset.videoUrl = g.video_url || '';
                card.dataset.imageUrl = g.image_url || '';
                card.style.aspectRatio = '9/16';
                card.innerHTML = _buildGalleryCardInnerHTML(g);
                list.appendChild(card);
            }
        } catch (e) {
            errEl.textContent = 'Koneksi gagal. Coba lagi.';
            errEl.classList.remove('hidden');
        } finally {
            btn.disabled = false;
            btn.innerHTML = '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg> Simpan';
        }
    }

    async function deleteGallery(id, btn) {
        if (!confirm('Hapus foto ini dari galeri?')) return;
        btn.disabled = true;
        const url = GAL_DELETE_URL.replace('__ID__', id);
        try {
            const res = await fetch(url, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF_TOKEN, 'Accept': 'application/json' },
                body: JSON.stringify({ _method: 'DELETE', _token: CSRF_TOKEN }),
            });
            if (res.ok) {
                const card = btn.closest('.gallery-card');
                if (card) card.remove();
                const list = document.getElementById('gallery-list');
                if (list && list.querySelectorAll('.gallery-card').length === 0) {
                    const div = document.createElement('div');
                    div.id = 'gallery-empty-notice';
                    div.className = 'col-span-2 sm:col-span-3 lg:col-span-4 flex flex-col items-center justify-center py-10 text-center';
                    div.innerHTML = '<div class="w-12 h-12 rounded-2xl flex items-center justify-center mb-3" style="background: var(--cream)"><svg class="w-6 h-6" style="color: var(--sage-green); opacity:0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg></div><p class="text-sm font-semibold text-gray-400">Belum ada foto galeri</p><p class="text-xs text-gray-300 mt-0.5">Klik "Tambah Item" untuk mulai mengunggah.</p>';
                    list.appendChild(div);
                }
            }
        } catch (e) {
            alert('Gagal menghapus item galeri.');
        } finally {
            btn.disabled = false;
        }
    }
    </script>

    @include('layout.footer')

@endsection
