@extends('layout.app')

@section('title', 'Edit Paket - ' . $vendor->name . ' - Makna Wedding')

@section('body-class', 'bg-cream text-dark')

@section('extra-head')
    <style>
        .items-row {
            display: flex;
            gap: 0.5rem;
            align-items: center;
        }
        .items-handle {
            width: 26px;
            height: 26px;
            border-radius: 10px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            font-weight: 700;
            color: rgb(107 114 128);
            background: rgb(249 250 251);
            border: 1px solid rgb(229 231 235);
            flex: 0 0 auto;
        }
        .items-input {
            flex: 1 1 auto;
            height: 40px;
            border-radius: 14px;
            border: 1px solid rgb(229 231 235);
            padding: 0 14px;
            font-size: 14px;
            color: var(--dark-gray);
        }
        .items-input:focus {
            outline: none;
            box-shadow: 0 0 0 2px rgb(167 243 208);
            border-color: rgb(110 231 183);
        }
        .items-btn {
            width: 36px;
            height: 36px;
            border-radius: 12px;
            border: 1px solid rgb(229 231 235);
            background: #fff;
            color: var(--dark-gray);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            flex: 0 0 auto;
        }
        .items-btn-danger {
            border-color: rgb(254 205 211);
            background: rgb(255 241 242);
            color: rgb(248 113 113);
        }
        .items-btn:hover { background: rgb(249 250 251); }
        .items-btn[disabled] { opacity: 0.45; cursor: not-allowed; }
        .items-add-btn {
            border: 1px solid rgb(229 231 235);
            background: #fff;
            color: var(--dark-gray);
            height: 38px;
            padding: 0 12px;
            border-radius: 14px;
            font-size: 12px;
            font-weight: 800;
        }
        .items-add-btn:hover { background: rgb(249 250 251); }
    </style>
@endsection

@section('content')
    @include('layout.header')

    @php
        $itemsRaw = old('items');
        if ($itemsRaw === null) {
            $itemsRaw = implode("\n", $package->items ?? []);
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

        <form method="POST" action="{{ route('vendor.packages.update', ['vendor' => $vendor->slug, 'package' => $package->id]) }}" class="space-y-4">
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
                        <div>
                            <label class="block text-xs font-semibold text-gray-500 mb-2">Kategori Vendor</label>
                            <select id="category_vendor_id" name="category_vendor_id" data-vendor-category="{{ $vendor->category ?? '' }}" class="w-full h-11 rounded-xl border border-gray-200 px-4 text-sm focus:outline-none">
                                <option value="" data-slug="">Ikuti kategori vendor</option>
                                @foreach(($categoryVendors ?? []) as $cat)
                                    <option value="{{ $cat->id }}" data-slug="{{ $cat->slug }}" @selected((string) old('category_vendor_id', $package->category_vendor_id) === (string) $cat->id)>{{ $cat->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-500 mb-2">Harga (angka)</label>
                            @php
                                $priceRawValue = old('price_raw', $package->price_raw);
                                $priceRawDigits = preg_replace('/\D+/', '', (string) $priceRawValue);
                                $priceRawDigits = $priceRawDigits === '' ? '0' : $priceRawDigits;
                                $priceRawFormatted = number_format((int) $priceRawDigits, 0, ',', '.');
                            @endphp
                            <input type="hidden" name="price_raw" id="price_raw" value="{{ $priceRawDigits }}">
                            <input type="text" id="price_raw_display" value="{{ $priceRawFormatted }}"
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
                            <input type="number" name="dp_paket" value="{{ old('dp_paket', $package->dp_paket ?? 0) }}"
                                   class="w-full h-11 rounded-xl border border-gray-200 px-4 text-sm focus:outline-none"
                                   min="0">
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
                        $selectedCatId = old('category_vendor_id', $package->category_vendor_id);
                        $selectedCatSlug = null;
                        if ($selectedCatId) {
                            $selectedCat = collect($categoryVendors ?? [])->firstWhere('id', (int) $selectedCatId);
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

                    <div>
                        <div class="flex items-center justify-between gap-3 mb-2 mt-4 pt-4 border-t border-gray-100">
                            <label class="block text-xs font-semibold text-gray-500">Item Paket</label>
                            <div class="text-xs text-gray-400">Satu item per baris</div>
                        </div>
                        @php
                            $itemsLines = array_values(array_filter(array_map('trim', explode("\n", str_replace("\r", '', (string) $itemsRaw)))));
                        @endphp
                        <div id="pkg-items-editor" class="w-full rounded-2xl border border-gray-200 bg-white px-4 py-4">
                            <input type="hidden" name="items" id="pkg-items-raw" value="{{ $itemsRaw }}">

                            <div id="pkg-items-rows" class="space-y-2.5">
                                @forelse($itemsLines as $line)
                                    <div class="items-row" data-row>
                                        <div class="items-handle" data-handle>1</div>
                                        <input type="text" class="items-input" data-input value="{{ $line }}">
                                        <button type="button" class="items-btn" data-action="up">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"/>
                                            </svg>
                                        </button>
                                        <button type="button" class="items-btn" data-action="down">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                            </svg>
                                        </button>
                                        <button type="button" class="items-btn items-btn-danger" data-action="delete">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                            </svg>
                                        </button>
                                    </div>
                                @empty
                                    <div class="items-row" data-row>
                                        <div class="items-handle" data-handle>1</div>
                                        <input type="text" class="items-input" data-input value="">
                                        <button type="button" class="items-btn" data-action="up">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"/>
                                            </svg>
                                        </button>
                                        <button type="button" class="items-btn" data-action="down">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                            </svg>
                                        </button>
                                        <button type="button" class="items-btn items-btn-danger" data-action="delete">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                            </svg>
                                        </button>
                                    </div>
                                @endforelse
                            </div>

                            <template id="pkg-items-row-template">
                                <div class="items-row" data-row>
                                    <div class="items-handle" data-handle>1</div>
                                    <input type="text" class="items-input" data-input value="">
                                    <button type="button" class="items-btn" data-action="up">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"/>
                                        </svg>
                                    </button>
                                    <button type="button" class="items-btn" data-action="down">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                        </svg>
                                    </button>
                                    <button type="button" class="items-btn items-btn-danger" data-action="delete">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                        </svg>
                                    </button>
                                </div>
                            </template>

                            <div class="flex items-center justify-between gap-3 mt-3">
                                <button type="button" class="items-add-btn" id="pkg-items-add">Tambah item</button>
                                <button type="button" class="items-add-btn" id="pkg-items-remove-empty">Hapus kosong</button>
                            </div>
                        </div>
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

            var priceRawDisplay = document.getElementById('price_raw_display');
            var priceRawHidden = document.getElementById('price_raw');
            if (priceRawDisplay && priceRawHidden) {
                attachThousandsFormatter(priceRawDisplay, priceRawHidden);
            }

            var discountDisplay = document.getElementById('discount_display');
            var discountHidden = document.getElementById('discount');
            if (discountDisplay && discountHidden) {
                attachThousandsFormatter(discountDisplay, discountHidden);
            }

            var venueSection = document.getElementById('pkg-venue-detail');
            var categorySelect = document.getElementById('category_vendor_id');
            function isVenueCategory(slug) {
                slug = String(slug || '').toLowerCase();
                return ['rumah', 'hotel', 'venue', 'gedung'].indexOf(slug) !== -1;
            }
            function refreshVenueSection() {
                if (!venueSection || !categorySelect) return;
                var slug = '';
                if (categorySelect.value) {
                    var opt = categorySelect.options[categorySelect.selectedIndex];
                    slug = opt ? (opt.getAttribute('data-slug') || '') : '';
                }
                if (!slug) {
                    slug = categorySelect.getAttribute('data-vendor-category') || '';
                }
                if (isVenueCategory(slug)) {
                    venueSection.classList.remove('hidden');
                } else {
                    venueSection.classList.add('hidden');
                }
            }
            if (categorySelect && venueSection) {
                categorySelect.addEventListener('change', refreshVenueSection);
                refreshVenueSection();
            }

            var editor = document.getElementById('pkg-items-editor');
            if (!editor) return;
            var rows = document.getElementById('pkg-items-rows');
            var tpl = document.getElementById('pkg-items-row-template');
            var hidden = document.getElementById('pkg-items-raw');
            var addBtn = document.getElementById('pkg-items-add');
            var removeEmptyBtn = document.getElementById('pkg-items-remove-empty');
            if (!rows || !tpl || !hidden || !addBtn || !removeEmptyBtn) return;

            function normalizeLine(s) {
                return String(s || '').replace(/\r/g, '').trim();
            }

            function allRowEls() {
                return Array.from(rows.querySelectorAll('[data-row]'));
            }

            function allInputs() {
                return Array.from(rows.querySelectorAll('input[data-input]'));
            }

            function syncHidden() {
                var lines = allInputs()
                    .map(function (inp) { return normalizeLine(inp.value); })
                    .filter(function (l) { return l.length > 0; });
                hidden.value = lines.join('\n');
            }

            function refreshUi() {
                var rowEls = allRowEls();
                rowEls.forEach(function (row, idx) {
                    var h = row.querySelector('[data-handle]');
                    if (h) h.textContent = String(idx + 1);
                    var up = row.querySelector('[data-action="up"]');
                    var down = row.querySelector('[data-action="down"]');
                    if (up) up.disabled = idx === 0;
                    if (down) down.disabled = idx === rowEls.length - 1;
                });
                syncHidden();
            }

            function addRowAfter(afterRowEl, initialValue) {
                var frag = tpl.content.cloneNode(true);
                var newRow = frag.querySelector('[data-row]');
                var input = frag.querySelector('input[data-input]');
                if (input) input.value = initialValue || '';
                if (!afterRowEl) {
                    rows.appendChild(frag);
                } else {
                    afterRowEl.insertAdjacentElement('afterend', newRow);
                }
                refreshUi();
                var inputs = allInputs();
                var idx = Math.min(inputs.length - 1, afterRowEl ? allRowEls().indexOf(afterRowEl) + 1 : inputs.length - 1);
                var target = inputs[idx];
                if (target) target.focus();
            }

            function ensureOneRow() {
                if (allRowEls().length) return;
                addRowAfter(null, '');
            }

            addBtn.addEventListener('click', function () {
                var last = allRowEls().slice(-1)[0] || null;
                addRowAfter(last, '');
            });

            removeEmptyBtn.addEventListener('click', function () {
                allRowEls().forEach(function (row) {
                    var inp = row.querySelector('input[data-input]');
                    if (!inp) return;
                    if (!normalizeLine(inp.value).length && allRowEls().length > 1) row.remove();
                });
                ensureOneRow();
                refreshUi();
            });

            rows.addEventListener('click', function (e) {
                var btn = e.target.closest && e.target.closest('[data-action]');
                if (!btn) return;
                var action = btn.getAttribute('data-action');
                var row = btn.closest('[data-row]');
                if (!row) return;

                if (action === 'delete') {
                    if (allRowEls().length === 1) {
                        var inp = row.querySelector('input[data-input]');
                        if (inp) inp.value = '';
                    } else {
                        var prev = row.previousElementSibling;
                        var next = row.nextElementSibling;
                        row.remove();
                        var target = (prev && prev.querySelector('input[data-input]')) || (next && next.querySelector('input[data-input]'));
                        if (target) target.focus();
                    }
                    refreshUi();
                    return;
                }

                if (action === 'up') {
                    var prevRow = row.previousElementSibling;
                    if (!prevRow) return;
                    prevRow.insertAdjacentElement('beforebegin', row);
                    var inpUp = row.querySelector('input[data-input]');
                    if (inpUp) inpUp.focus();
                    refreshUi();
                    return;
                }

                if (action === 'down') {
                    var nextRow = row.nextElementSibling;
                    if (!nextRow) return;
                    nextRow.insertAdjacentElement('afterend', row);
                    var inpDown = row.querySelector('input[data-input]');
                    if (inpDown) inpDown.focus();
                    refreshUi();
                }
            });

            rows.addEventListener('input', function (e) {
                if (!e.target || e.target.getAttribute('data-input') === null) return;
                syncHidden();
            });

            rows.addEventListener('keydown', function (e) {
                var target = e.target;
                if (!target || target.getAttribute('data-input') === null) return;
                var row = target.closest('[data-row]');
                if (!row) return;

                if (e.key === 'Enter') {
                    e.preventDefault();
                    addRowAfter(row, '');
                    return;
                }

                if (e.key === 'Backspace' && !normalizeLine(target.value).length && allRowEls().length > 1) {
                    e.preventDefault();
                    var prevRow = row.previousElementSibling;
                    var nextRow = row.nextElementSibling;
                    row.remove();
                    var focusEl = (prevRow && prevRow.querySelector('input[data-input]')) || (nextRow && nextRow.querySelector('input[data-input]'));
                    if (focusEl) focusEl.focus();
                    refreshUi();
                }
            });

            rows.addEventListener('paste', function (e) {
                var target = e.target;
                if (!target || target.getAttribute('data-input') === null) return;
                var text = (e.clipboardData || window.clipboardData)?.getData?.('text') || '';
                if (!text || text.indexOf('\n') === -1) return;
                e.preventDefault();

                var lines = String(text)
                    .replace(/\r/g, '')
                    .split('\n')
                    .map(function (l) { return normalizeLine(l); })
                    .filter(function (l) { return l.length > 0; });
                if (!lines.length) return;

                var row = target.closest('[data-row]');
                if (!row) return;

                var current = normalizeLine(target.value);
                if (!current.length) {
                    target.value = lines.shift() || '';
                } else {
                    target.value = (current + ' ' + (lines.shift() || '')).trim();
                }

                var cursor = row;
                lines.forEach(function (l) {
                    var frag = tpl.content.cloneNode(true);
                    var newRow = frag.querySelector('[data-row]');
                    var inp = frag.querySelector('input[data-input]');
                    if (inp) inp.value = l;
                    cursor.insertAdjacentElement('afterend', newRow);
                    cursor = newRow;
                });

                refreshUi();
                var lastInput = cursor.querySelector('input[data-input]');
                if (lastInput) lastInput.focus();
            });

            var form = editor.closest('form');
            if (form) {
                form.addEventListener('submit', function () {
                    refreshUi();
                });
            }

            ensureOneRow();
            refreshUi();
        });
    </script>
@endsection
