@extends('layout.app')

@section('title', 'Edit Paket - ' . $vendor->name . ' - Makna Wedding')

@section('body-class', 'bg-cream text-dark')

@section('extra-head')
    <link href="https://cdn.quilljs.com/1.3.7/quill.snow.css" rel="stylesheet">
    <script src="https://cdn.quilljs.com/1.3.7/quill.min.js"></script>
    <style>
        .ql-container { font-size: 14px; border-radius: 0 0 12px 12px; min-height: 160px; }
        .ql-toolbar { border-radius: 12px 12px 0 0; background: rgb(249 250 251); }
        .ql-editor { min-height: 140px; line-height: 1.65; }
        #rte-quill-wrap { border: 1px solid rgb(229 231 235); border-radius: 14px; overflow: hidden; }
    </style>
@endsection

@section('content')
    @include('layout.header')

    @php
        $itemsRaw = old('items');
        if ($itemsRaw === null) {
            $itemsRaw = (string) ($package->item ?? '');
        }
    @endphp

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 pt-6 pb-2">
        <nav class="flex items-center gap-2 text-xs text-dark">
            <a href="{{ route('home') }}" class="hover:text-accent transition">Home</a>
            <svg class="w-3 h-3 opacity-40" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            <a href="{{ route('vendor') }}" class="hover:text-accent transition">Vendor</a>
            <svg class="w-3 h-3 opacity-40" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            <a href="{{ route('vendor.detail', $vendor) }}" class="hover:text-accent transition">{{ $vendor->name }}</a>
            <svg class="w-3 h-3 opacity-40" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            <a href="{{ route('vendor.edit', $vendor) }}" class="hover:text-accent transition">Edit</a>
            <svg class="w-3 h-3 opacity-40" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            <span class="font-semibold opacity-60">Edit Paket</span>
        </nav>
    </div>

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-6 pb-16">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-xl font-bold text-dark">Edit Paket</h1>
                <p class="text-sm text-gray-500 mt-0.5">{{ $vendor->name }}</p>
            </div>
            <a href="{{ route('vendor.edit', $vendor) }}"
               class="inline-flex items-center gap-1.5 text-xs font-semibold px-3 py-2 rounded-lg border border-gray-200 bg-white text-dark hover:border-gray-300 transition">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Kembali ke Paket
            </a>
        </div>

        @if(session('success'))
            <div class="mb-4 rounded-2xl border border-green-100 bg-green-50 px-4 py-3 text-sm text-green-700">
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="mb-4 rounded-2xl border border-red-100 bg-red-50 px-4 py-3 text-sm text-red-700">
                <ul class="list-disc pl-5 space-y-1">
                    @foreach($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('vendor.packages.update', ['vendor' => $vendor->slug, 'package' => $package->id]) }}" class="space-y-4" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="bg-white rounded-2xl border border-gray-100 overflow-hidden">
                <div class="px-5 py-3 border-b border-gray-100 bg-cream">
                    <h2 class="text-sm font-bold text-dark">Informasi Paket</h2>
                </div>
                <div class="p-5 space-y-4">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-gray-500 mb-2">Nama Paket</label>
                            <input type="text" name="name" value="{{ old('name', $package->name) }}"
                                   class="w-full h-11 rounded-xl border border-gray-200 px-4 text-sm focus:outline-none"
                                   required>
                        </div>
                        <div class="sm:col-span-2">
                            <label class="block text-xs font-semibold text-gray-500 mb-2">Kategori Vendor</label>
                            @php
                                $selectedCatIds = old('category_vendor_id', is_array($package->category_vendor_id) ? $package->category_vendor_id : []);
                                $selectedCatIds = is_array($selectedCatIds) ? array_map('intval', $selectedCatIds) : [];
                            @endphp
                            <div id="pkg-cat-pills" data-vendor-category="{{ $vendor->category ?? '' }}" class="relative">
                                {{-- Trigger button --}}
                                <button type="button" id="pkg-cat-trigger"
                                        class="w-full h-11 px-4 rounded-xl border border-gray-200 bg-white text-sm text-left flex items-center justify-between gap-2 focus:outline-none focus:border-accent transition">
                                    <span id="pkg-cat-label" class="truncate text-gray-400 italic">Ikuti kategori vendor</span>
                                    <svg class="w-4 h-4 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                    </svg>
                                </button>

                                {{-- Dropdown panel --}}
                                <div id="pkg-cat-dropdown"
                                     class="absolute left-0 right-0 mt-1 bg-white border border-gray-200 rounded-2xl shadow-lg z-20 hidden overflow-hidden">
                                    <div class="p-3 grid grid-cols-2 gap-1 max-h-60 overflow-y-auto">
                                        @foreach(($categoryVendors ?? []) as $cat)
                                            <label class="flex items-center gap-2 px-3 py-2 rounded-xl hover:bg-gray-50 cursor-pointer transition">
                                                <input type="checkbox"
                                                       name="category_vendor_id[]"
                                                       value="{{ $cat->id }}"
                                                       data-slug="{{ $cat->slug }}"
                                                       data-name="{{ $cat->name }}"
                                                       class="pkg-cat-check h-4 w-4 rounded border-gray-300 accent-accent"
                                                       {{ in_array((int) $cat->id, $selectedCatIds, true) ? 'checked' : '' }}>
                                                <span class="text-sm text-dark">{{ $cat->name }}</span>
                                            </label>
                                        @endforeach
                                    </div>
                                    <div class="border-t border-gray-100 px-3 py-2 flex justify-between items-center">
                                        <button type="button" id="pkg-cat-clear"
                                                class="text-xs text-gray-400 hover:text-accent transition">Reset</button>
                                        <button type="button" id="pkg-cat-done"
                                                class="text-xs font-bold px-3 py-1.5 rounded-lg bg-accent text-white hover:opacity-90 transition">Selesai</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-500 mb-2">Harga (angka)</label>
                            @php
                                $priceDigits = preg_replace('/\D+/', '', (string) old('price', $package->price));
                                $priceDigits = $priceDigits === '' ? '0' : $priceDigits;
                                $priceFormatted = number_format((int) $priceDigits, 0, ',', '.');
                            @endphp
                            <input type="hidden" name="price" id="price_hidden" value="{{ $priceDigits }}">
                            <input type="text" id="price_display" value="{{ $priceFormatted }}"
                                   inputmode="numeric" autocomplete="off"
                                   class="w-full h-11 rounded-xl border border-gray-200 px-4 text-sm focus:outline-none"
                                   required>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-500 mb-2">Diskon (angka)</label>
                            @php
                                $discountValue = old('discount', $package->discount);
                                $discountDigits = preg_replace('/\D+/', '', (string) $discountValue);
                                $discountDigits = $discountDigits === '' ? '0' : $discountDigits;
                                $discountFormatted = number_format((int) $discountDigits, 0, ',', '.');
                            @endphp
                            <input type="hidden" name="discount" id="discount" value="{{ $discountDigits }}">
                            <input type="text" id="discount_display" value="{{ $discountFormatted }}"
                                   inputmode="numeric" autocomplete="off"
                                   class="w-full h-11 rounded-xl border border-gray-200 px-4 text-sm focus:outline-none">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-500 mb-2">Down Payment (dp_paket)</label>
                            @php
                                $dpDigits = preg_replace('/\D+/', '', (string) old('dp_paket', $package->dp_paket ?? 0));
                                $dpDigits = $dpDigits === '' ? '0' : $dpDigits;
                                $dpFormatted = number_format((int) $dpDigits, 0, ',', '.');
                            @endphp
                            <input type="hidden" name="dp_paket" id="dp_paket_hidden" value="{{ $dpDigits }}">
                            <input type="text" id="dp_paket_display" value="{{ $dpFormatted }}"
                                   inputmode="numeric" autocomplete="off"
                                   class="w-full h-11 rounded-xl border border-gray-200 px-4 text-sm focus:outline-none">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-500 mb-2">Max Guests</label>
                            <input type="text" name="max_guests" value="{{ old('max_guests', $package->max_guests) }}"
                                   class="w-full h-11 rounded-xl border border-gray-200 px-4 text-sm focus:outline-none"
                                   placeholder="Mis. 200 Pax">
                        </div>
                    </div>

                    @php
                        $allowedVenueCategorySlugs = ['rumah', 'hotel', 'venue', 'gedung'];
                        $selIds = old('category_vendor_id', is_array($package->category_vendor_id) ? $package->category_vendor_id : []);
                        $selIds = is_array($selIds) ? array_map('intval', $selIds) : [];
                        $selectedCatId = $selIds[0] ?? null;
                        $selectedCatSlug = null;
                        if ($selectedCatId) {
                            $selectedCat = collect($categoryVendors ?? [])->firstWhere('id', $selectedCatId);
                            $selectedCatSlug = $selectedCat ? (string) $selectedCat->slug : null;
                        }
                        $effectiveCatSlug = $selectedCatSlug ?: (string) ($vendor->category ?? '');
                        $showVenueDetail = in_array($effectiveCatSlug, $allowedVenueCategorySlugs, true);
                    @endphp
                    <div id="pkg-venue-detail" class="mt-4 pt-4 border-t border-gray-100 {{ $showVenueDetail ? '' : 'hidden' }}">
                        <div class="flex items-center justify-between gap-3 mb-2">
                            <label class="block text-xs font-semibold text-gray-500">Detail Venue & Kapasitas</label>
                            <div class="text-xs text-gray-400">Isi jika paket berupa penyewaan venue</div>
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-semibold text-gray-500 mb-2">Tipe Venue</label>
                                <select name="type" class="w-full h-11 rounded-xl border border-gray-200 px-4 text-sm focus:outline-none">
                                    <option value="">-- Pilih Tipe --</option>
                                    @foreach(['Indoor', 'Outdoor', 'Semi Outdoor', 'Lainnya'] as $t)
                                        <option value="{{ $t }}" @selected(old('type', $package->type) === $t)>{{ $t }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-500 mb-2">Kapasitas (Orang)</label>
                                <input type="number" name="capacity" value="{{ old('capacity', $package->capacity) }}"
                                       class="w-full h-11 rounded-xl border border-gray-200 px-4 text-sm focus:outline-none"
                                       min="0">
                            </div>
                            <div class="sm:col-span-2">
                                <label class="block text-xs font-semibold text-gray-500 mb-2">Fasilitas (Pisahkan dengan koma)</label>
                                @php
                                    $facilitiesVal = old('facilities', is_array($package->facilities) ? implode(', ', $package->facilities) : '');
                                @endphp
                                <input type="text" name="facilities" value="{{ $facilitiesVal }}"
                                       class="w-full h-11 rounded-xl border border-gray-200 px-4 text-sm focus:outline-none"
                                       placeholder="Parkir Luas, Ruang Rias, AC, WiFi">
                            </div>
                        </div>
                    </div>

                    <div class="mt-4 pt-4 border-t border-gray-100">
                        <label class="block text-xs font-semibold text-gray-500 mb-2">Item Paket</label>
                        <input type="hidden" name="items" id="rte-hidden" value="{{ old('items', $itemsRaw) }}">
                        <div id="rte-quill-wrap">
                            <div id="rte-quill-editor"></div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Foto Paket --}}
            <div class="bg-white rounded-2xl border border-gray-100 overflow-hidden">
                <div class="px-5 py-3 border-b border-gray-100 bg-cream">
                    <h2 class="text-sm font-bold text-dark">Foto Paket</h2>
                </div>
                <div class="p-5">
                    @php
                        $currentImage = $package->image_url;
                    @endphp
                    @if($currentImage)
                        <div class="mb-3">
                            <p class="text-xs font-semibold text-gray-500 mb-2">Foto saat ini</p>
                            <img src="{{ $currentImage }}" alt="Foto Paket"
                                 class="w-full max-h-52 object-cover rounded-xl border border-gray-200">
                            <p class="text-xs text-gray-400 mt-1.5">Upload foto baru untuk mengganti.</p>
                        </div>
                    @endif
                    <label class="flex flex-col items-center justify-center w-full h-28 border-2 border-dashed border-gray-200 rounded-xl cursor-pointer hover:border-accent/50 transition bg-gray-50">
                        <svg class="w-7 h-7 text-gray-300 mb-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        <span id="pkg-img-label" class="text-xs text-gray-400">Klik untuk pilih foto (maks. 5 MB)</span>
                        <input type="file" name="image" id="pkg-img-input" accept="image/*" class="hidden">
                    </label>
                    <div id="pkg-img-preview-wrap" class="hidden mt-2">
                        <img id="pkg-img-preview" src="" alt="Preview" class="w-full max-h-52 object-cover rounded-xl border border-gray-200">
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl border border-gray-100 overflow-hidden">
                <div class="px-5 py-3 border-b border-gray-100 bg-cream">
                    <h2 class="text-sm font-bold text-dark">Tampilan Kartu</h2>
                </div>
                <div class="p-5 space-y-4">
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-gray-500 mb-2">Warna Kartu</label>
                            <input type="color" name="card_color" value="{{ old('card_color', $package->card_color ?? '#C8D5B9') }}"
                                   class="w-full h-11 rounded-xl border border-gray-200 px-2">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-500 mb-2">Warna Teks</label>
                            <input type="color" name="card_text_color" value="{{ old('card_text_color', $package->card_text_color ?? '#444444') }}"
                                   class="w-full h-11 rounded-xl border border-gray-200 px-2">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-500 mb-2">Urutan</label>
                            <input type="number" name="sort_order" value="{{ old('sort_order', $package->sort_order) }}"
                                   class="w-full h-11 rounded-xl border border-gray-200 px-4 text-sm"
                                   min="0">
                        </div>
                    </div>
                    <div class="flex items-center gap-2">
                        <input type="hidden" name="is_active" value="0">
                        <input id="is_active" type="checkbox" name="is_active" value="1" class="rounded border-gray-300 accent-accent"
                               @checked((bool) old('is_active', $package->is_active))>
                        <label for="is_active" class="text-sm text-dark">Aktifkan paket</label>
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-end gap-3 pt-1">
                <a href="{{ route('vendor.edit', $vendor) }}"
                   class="px-5 py-2.5 rounded-xl text-sm font-semibold border border-gray-200 bg-white text-dark hover:border-gray-300 transition">
                    Batal
                </a>
                <button type="submit"
                        class="inline-flex items-center gap-2 px-6 py-2.5 rounded-xl text-sm font-bold bg-accent text-cream transition hover:opacity-90">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Simpan Paket
                </button>
            </div>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            sessionStorage.setItem('editVendorTab', 'tab-paket');

            // Image preview
            document.getElementById('pkg-img-input').addEventListener('change', function () {
                const file = this.files[0];
                const previewWrap = document.getElementById('pkg-img-preview-wrap');
                const preview = document.getElementById('pkg-img-preview');
                const label = document.getElementById('pkg-img-label');
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function (e) {
                        preview.src = e.target.result;
                        previewWrap.classList.remove('hidden');
                    };
                    reader.readAsDataURL(file);
                    label.textContent = file.name;
                } else {
                    previewWrap.classList.add('hidden');
                    label.textContent = 'Klik untuk pilih foto (maks. 5 MB)';
                }
            });

            function digitsOnly(s) {
                return String(s || '').replace(/\D+/g, '');
            }
            function formatIdThousands(numStr) {
                numStr = digitsOnly(numStr);
                numStr = numStr.replace(/^0+(?=\d)/, '');
                if (!numStr) return '0';
                return numStr.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
            }
            function attachThousandsFormatter(displayEl, hiddenEl) {
                function sync() {
                    var digits = digitsOnly(displayEl.value);
                    digits = digits.replace(/^0+(?=\d)/, '');
                    if (digits === '') digits = '0';
                    hiddenEl.value = digits;
                    displayEl.value = formatIdThousands(digits);
                }
                displayEl.addEventListener('input', sync);
                displayEl.addEventListener('blur', sync);
                sync();
            }

            var priceDisplay = document.getElementById('price_display');
            var priceHidden = document.getElementById('price_hidden');
            if (priceDisplay && priceHidden) {
                attachThousandsFormatter(priceDisplay, priceHidden);
            }

            var discountDisplay = document.getElementById('discount_display');
            var discountHidden = document.getElementById('discount');
            if (discountDisplay && discountHidden) {
                attachThousandsFormatter(discountDisplay, discountHidden);
            }

            var dpDisplay = document.getElementById('dp_paket_display');
            var dpHidden = document.getElementById('dp_paket_hidden');
            if (dpDisplay && dpHidden) {
                attachThousandsFormatter(dpDisplay, dpHidden);
            }

            var venueSection = document.getElementById('pkg-venue-detail');
            var catContainer = document.getElementById('pkg-cat-pills');
            var catChecks    = catContainer ? Array.from(catContainer.querySelectorAll('.pkg-cat-check')) : [];
            var catTrigger   = document.getElementById('pkg-cat-trigger');
            var catDropdown  = document.getElementById('pkg-cat-dropdown');
            var catLabel     = document.getElementById('pkg-cat-label');
            var catDoneBtn   = document.getElementById('pkg-cat-done');
            var catClearBtn  = document.getElementById('pkg-cat-clear');

            function isVenueCategory(slug) {
                slug = String(slug || '').toLowerCase();
                return ['rumah', 'hotel', 'venue', 'gedung'].indexOf(slug) !== -1;
            }
            function updateCatLabel() {
                var names = catChecks.filter(function(cb) { return cb.checked; })
                                     .map(function(cb) { return cb.getAttribute('data-name') || ''; });
                if (names.length) {
                    catLabel.textContent = names.join(', ');
                    catLabel.classList.remove('text-gray-400', 'italic');
                    catLabel.classList.add('text-dark');
                } else {
                    catLabel.textContent = 'Ikuti kategori vendor';
                    catLabel.classList.add('text-gray-400', 'italic');
                    catLabel.classList.remove('text-dark');
                }
            }
            function refreshVenueSection() {
                if (!venueSection) return;
                var anyVenue = catChecks.some(function(cb) {
                    return cb.checked && isVenueCategory(cb.getAttribute('data-slug') || '');
                });
                if (!anyVenue && !catChecks.some(function(cb) { return cb.checked; })) {
                    anyVenue = isVenueCategory((catContainer && catContainer.getAttribute('data-vendor-category')) || '');
                }
                venueSection.classList.toggle('hidden', !anyVenue);
            }
            function closeCatDropdown() {
                if (catDropdown) catDropdown.classList.add('hidden');
            }
            if (catTrigger && catDropdown) {
                catTrigger.addEventListener('click', function(e) {
                    e.stopPropagation();
                    catDropdown.classList.toggle('hidden');
                });
                document.addEventListener('click', function(e) {
                    if (catContainer && !catContainer.contains(e.target)) closeCatDropdown();
                });
            }
            if (catDoneBtn) catDoneBtn.addEventListener('click', closeCatDropdown);
            if (catClearBtn) {
                catClearBtn.addEventListener('click', function() {
                    catChecks.forEach(function(cb) { cb.checked = false; });
                    updateCatLabel();
                    refreshVenueSection();
                });
            }
            catChecks.forEach(function(cb) {
                cb.addEventListener('change', function() {
                    updateCatLabel();
                    refreshVenueSection();
                });
            });
            updateCatLabel();
            refreshVenueSection();

            /* ── Quill Rich Text Editor ── */
            var rteHidden = document.getElementById('rte-hidden');
            var quillEl   = document.getElementById('rte-quill-editor');

            if (quillEl && rteHidden) {
                var quill = new Quill(quillEl, {
                    theme: 'snow',
                    modules: {
                        toolbar: [
                            ['bold', 'italic', 'underline', 'strike'],
                            [{ list: 'ordered' }, { list: 'bullet' }],
                            [{ indent: '-1' }, { indent: '+1' }],
                            ['clean']
                        ]
                    }
                });

                // Load existing HTML
                var initialHtml = rteHidden.value || '';
                if (initialHtml) {
                    quill.clipboard.dangerouslyPasteHTML(initialHtml);
                }

                // Sync to hidden on every change
                quill.on('text-change', function () {
                    rteHidden.value = quill.root.innerHTML;
                });

                // Sync on submit
                var form = quillEl.closest('form');
                if (form) {
                    form.addEventListener('submit', function () {
                        rteHidden.value = quill.root.innerHTML;
                    });
                }
            }


        });
    </script>
@endsection
