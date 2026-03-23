@extends('layout.app')

@section('title', $vendor->name . ' - Makna Wedding')

@section('body-class', 'bg-cream text-dark')

@section('content')
    @include('layout.header')


    <!-- Breadcrumb -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-6 pb-2">
        <nav class="flex items-center gap-2 text-xs" style="color: var(--dark-gray)">
            <a href="{{ route('home') }}" class="hover:text-accent transition">Home</a>
            <svg class="w-3 h-3 opacity-40" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            <a href="{{ route('vendor') }}" class="hover:text-accent transition">Vendor</a>
            <svg class="w-3 h-3 opacity-40" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            <span class="font-semibold opacity-60">{{ $vendor->name }}</span>
        </nav>
    </div>

    <!-- Hero Section -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4"
        x-data="{
            mainSrc: '{{ $vendor->cover_image_url ?? optional($vendor->galleries->first())->image_path ?? 'https://picsum.photos/seed/'.$vendor->slug.'-hero/1200/700' }}',
            sideSrcs: [
                @for ($i = 1; $i <= 4; $i++)
                    '{{ optional($vendor->galleries->get($i))->image_path ?? 'https://picsum.photos/seed/'.$vendor->slug.'-side'.$i.'/400/400' }}'{{ $i < 4 ? ',' : '' }}
                @endfor
            ],
            swap(index) {
                let prev = this.mainSrc;
                this.mainSrc = this.sideSrcs[index];
                this.sideSrcs[index] = prev;
            }
        }">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 items-stretch">

            <!-- Main Photo -->
            <div class="lg:col-span-2 rounded-2xl overflow-hidden relative" style="aspect-ratio: 16/9;">
                <img :src="mainSrc" loading="lazy"
                     alt="{{ $vendor->name }}"
                     class="w-full h-full object-cover transition-all duration-500">
                <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent pointer-events-none"></div>
                <!-- Stats overlay bottom -->
                <div class="absolute bottom-4 left-4 flex items-center gap-3">
                    <span class="flex items-center gap-1 bg-black/40 backdrop-blur-sm text-white text-xs px-2.5 py-1 rounded-full">
                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                        {{ $vendor->rating }}
                    </span>
                    <span class="text-white text-xs bg-black/40 backdrop-blur-sm px-2.5 py-1 rounded-full">{{ $vendor->approvedReviews->count() }} Ulasan</span>
                    <span class="text-white text-xs bg-black/40 backdrop-blur-sm px-2.5 py-1 rounded-full">{{ $vendor->galleries->count() }} Foto</span>
                </div>
            </div>

            <!-- Side Photos Grid -->
            <div class="hidden lg:grid grid-cols-2 gap-2 self-stretch">
                <template x-for="(src, index) in sideSrcs" :key="index">
                    <div class="rounded-xl overflow-hidden min-h-0 cursor-pointer" @click="swap(index)">
                        <img :src="src"
                             :alt="'Foto ' + (index + 1)"
                             class="w-full h-full object-cover hover:scale-105 hover:brightness-90 transition-all duration-300">
                    </div>
                </template>
            </div>

        </div>
    </section>

    <!-- Content -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-16">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

            <!-- Left: Main Content -->
            <div class="lg:col-span-2 space-y-8">

                <!-- Vendor Name + Type -->
                <div class="flex items-start justify-between gap-4 pt-2">
                    <div>
                        <h1 class="text-2xl font-bold leading-tight mb-1" style="color: var(--dark-gray)">{{ $vendor->name }}</h1>
                        <span class="text-sm font-medium" style="color: var(--sage-green)">{{ $vendor->type }}</span>
                        <div class="flex items-center gap-1.5 mt-2 text-xs" style="color: var(--dark-gray); opacity: .65">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            {{ $vendor->location }}
                        </div>
                    </div>
                    <button class="flex-shrink-0 w-10 h-10 rounded-full border border-gray-200 flex items-center justify-center hover:bg-red-50 transition group">
                        <svg class="w-5 h-5 text-gray-400 group-hover:text-red-400 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                    </button>
                </div>

                <!-- Stats Bar -->
                <div class="flex items-center gap-5 py-4 border-y border-gray-100 text-sm">
                    <div class="flex items-center gap-1.5">
                        <svg class="w-4 h-4" style="color: #f59e0b" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                        <span class="font-bold">{{ $vendor->rating }}</span>
                        <span class="text-gray-400">({{ $vendor->approvedReviews->count() }})</span>
                    </div>
                    <div class="w-px h-4 bg-gray-200"></div>
                    <div class="flex items-center gap-1.5 text-gray-500">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        {{ $vendor->galleries->count() }} Foto
                    </div>
                    <div class="w-px h-4 bg-gray-200"></div>
                    <div class="flex items-center gap-1.5 text-gray-500">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                        {{ $vendor->likes }} Suka
                    </div>
                    <div class="w-px h-4 bg-gray-200"></div>
                    <div class="flex items-center gap-1.5 text-gray-500">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                        {{ $vendor->comments_count }} Komentar
                    </div>
                </div>

                <!-- About -->
                <div>
                    <h2 class="text-base font-bold mb-3" style="color: var(--dark-gray)">Tentang Vendor</h2>
                    <p class="text-sm leading-relaxed text-gray-600">{{ $vendor->description }}</p>
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-3 mt-4">
                        <div class="rounded-xl p-3 border border-gray-100 bg-white">
                            <p class="text-[10px] uppercase tracking-widest text-gray-400 mb-0.5">Kapasitas</p>
                            <p class="text-sm font-semibold" style="color: var(--dark-gray)">{{ $vendor->capacity }}</p>
                        </div>
                        <div class="rounded-xl p-3 border border-gray-100 bg-white">
                            <p class="text-[10px] uppercase tracking-widest text-gray-400 mb-0.5">Harga Mulai</p>
                            <p class="text-sm font-semibold" style="color: var(--sage-green)">{{ $vendor->price_start }}</p>
                        </div>
                        <div class="rounded-xl p-3 border border-gray-100 bg-white">
                            <p class="text-[10px] uppercase tracking-widest text-gray-400 mb-0.5">Pengalaman</p>
                            <p class="text-sm font-semibold" style="color: var(--dark-gray)">{{ $vendor->experience }}</p>
                        </div>
                        <div class="rounded-xl p-3 border border-gray-100 bg-white">
                            <p class="text-[10px] uppercase tracking-widest text-gray-400 mb-0.5">Event Selesai</p>
                            <p class="text-sm font-semibold" style="color: var(--dark-gray)">{{ $vendor->events_done }}+ Acara</p>
                        </div>
                        <div class="rounded-xl p-3 border border-gray-100 bg-white">
                            <p class="text-[10px] uppercase tracking-widest text-gray-400 mb-0.5">Tipe Venue</p>
                            <p class="text-sm font-semibold" style="color: var(--dark-gray)">{{ $vendor->venue_type }}</p>
                        </div>
                        <div class="rounded-xl p-3 border border-gray-100 bg-white">
                            <p class="text-[10px] uppercase tracking-widest text-gray-400 mb-0.5">Fasilitas</p>
                            <p class="text-sm font-semibold" style="color: var(--dark-gray)">{{ $vendor->facilities }}</p>
                        </div>
                    </div>
                </div>

                <!-- Packages -->
                <div>
                    <h2 class="text-base font-bold mb-4" style="color: var(--dark-gray)">Paket & Harga</h2>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        @foreach ($vendor->packages as $pkg)
                        <div class="rounded-2xl p-5 flex flex-col cursor-pointer group ring-2 ring-transparent hover:ring-white/50 transition"
                             style="background-color: {{ $pkg->card_color }}; color: {{ $pkg->card_text_color }}"
                             onclick="openPackageModal({{ json_encode($pkg->only(['id','name','price','max_guests','card_color','card_text_color','items'])) }})">
                            <p class="text-xs font-bold uppercase tracking-widest mb-1 opacity-70">{{ $pkg->name }}</p>
                            <p class="text-xl font-bold leading-tight mb-1">{{ $pkg->price }}</p>
                            <p class="text-xs mb-4 opacity-70">{{ $pkg->max_guests }}</p>
                            @php $maxShow = 5; $total = count($pkg->items); $more = $total - $maxShow; @endphp
                            <ul class="space-y-1.5 flex-1 mb-4">
                                @foreach ($pkg->items as $idx => $item)
                                @if ($idx < $maxShow)
                                <li class="flex items-start gap-2 text-xs">
                                    <svg class="w-3.5 h-3.5 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                    {{ $item }}
                                </li>
                                @endif
                                @endforeach
                                @if ($more > 0)
                                <li class="flex items-center gap-1.5 text-xs font-semibold opacity-80 mt-1">
                                    <svg class="w-3.5 h-3.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                                    +{{ $more }} fasilitas lainnya
                                </li>
                                @endif
                            </ul>
                            <button type="button"
                                    class="block w-full text-center text-xs font-bold uppercase tracking-wider py-2 rounded-full transition hover:opacity-80"
                                    style="background-color: rgba(255,255,255,0.25); color: {{ $pkg->card_text_color }}">
                                Pilih Paket
                            </button>
                        </div>
                        @endforeach
                    </div>
                </div>

                <!-- Review Videos -->
                <div>
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-base font-bold" style="color: var(--dark-gray)">Venue Review Videos</h2>
                        <a href="#" class="text-xs font-semibold hover:opacity-70 transition" style="color: var(--sage-green)">Lihat Semua</a>
                    </div>
                    <!-- Horizontal scroll strip -->
                    <div class="flex gap-3 overflow-x-auto pb-2 snap-x snap-mandatory scrollbar-none" style="-ms-overflow-style:none;scrollbar-width:none;">
                        @foreach ($vendor->galleries as $idx => $g)
                        @php $hasVideo = !empty($g->video_url); @endphp
                        <div class="relative flex-shrink-0 rounded-2xl overflow-hidden cursor-pointer group snap-start"
                             style="width: 180px; height: 280px;"
                             @if($hasVideo) onclick="openVideoModal('{{ $g->video_url }}')" @endif>
                            <!-- Background image -->
                            <img src="{{ $g->image_path }}"
                                 alt="Review Video {{ $idx + 1 }}"
                                 class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
                            <!-- Dark gradient overlay -->
                            <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent"></div>
                            <!-- Play button -->
                            @if($hasVideo)
                            <div class="absolute inset-0 flex items-center justify-center">
                                <div class="w-12 h-12 rounded-full bg-white/90 group-hover:bg-white flex items-center justify-center shadow-lg transition">
                                    <svg class="w-5 h-5 ml-0.5" style="color: var(--dark-gray)" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M8 5v14l11-7z"/>
                                    </svg>
                                </div>
                            </div>
                            @endif
                            <!-- Label bottom -->
                            <div class="absolute bottom-0 left-0 right-0 p-3">
                                <p class="text-[9px] uppercase tracking-widest text-white/70 mb-0.5">Introducing</p>
                                <p class="text-xs font-bold uppercase leading-tight text-white">{{ $vendor->name }}</p>
                                <p class="text-[10px] text-white/60 mt-0.5">at {{ $vendor->location }}</p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                <!-- Reviews -->
                <div>
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-base font-bold" style="color: var(--dark-gray)">Ulasan</h2>
                        <div class="flex items-center gap-1">
                            <svg class="w-4 h-4" style="color: #f59e0b" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                            <span class="font-bold text-sm">{{ $vendor->rating }}</span>
                            <span class="text-xs text-gray-400">({{ $vendor->approvedReviews->count() }} ulasan)</span>
                        </div>
                    </div>

                    <div class="space-y-4">
                        @foreach ($vendor->approvedReviews as $rev)
                        <div class="bg-white rounded-2xl p-4 border border-gray-100">
                            <div class="flex items-center gap-3 mb-2">
                                <img src="{{ $rev->reviewer_avatar ?? 'https://picsum.photos/seed/rv-'.$rev->id.'/80/80' }}"
                                     alt="{{ $rev->reviewer_name }}"
                                     class="w-9 h-9 rounded-full object-cover flex-shrink-0">
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-bold leading-tight" style="color: var(--dark-gray)">{{ $rev->reviewer_name }}</p>
                                    <p class="text-[10px] text-gray-400">{{ $rev->reviewed_at->translatedFormat('d M Y') }}</p>
                                </div>
                                <div class="flex items-center gap-0.5">
                                    @for ($s = 1; $s <= 5; $s++)
                                    <svg class="w-3 h-3 {{ $s <= $rev->rating ? '' : 'opacity-20' }}" style="color: #f59e0b" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                    @endfor
                                </div>
                            </div>
                            <p class="text-sm text-gray-600 leading-relaxed">{{ $rev->body }}</p>
                        </div>
                        @endforeach
                    </div>

                    <button class="mt-4 w-full py-2.5 rounded-xl text-sm font-semibold border border-gray-200 bg-white hover:border-gray-300 transition" style="color: var(--dark-gray)">
                        Lihat Semua Ulasan ({{ $vendor->approvedReviews->count() }})
                    </button>
                </div>

            </div>

            <!-- Right: Sticky Sidebar -->
            <div class="lg:col-span-1">
                <div class="sticky top-24 space-y-4">

                    <!-- CTA Card -->
                    <div id="contact-cta" class="bg-white rounded-2xl border border-gray-100 p-5 shadow-sm">
                        <p class="text-[10px] uppercase tracking-widest text-gray-400 mb-1" id="sidebar-pkg-label">Harga Mulai</p>
                        <p class="text-2xl font-bold mb-0.5" id="sidebar-price" style="color: var(--sage-green)">{{ $vendor->price_start }}</p>
                        <p class="text-xs text-gray-400 mb-5" id="sidebar-pkg-sub">*Tergantung paket & tanggal</p>

                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $vendor->phone) }}"
                           target="_blank"
                           class="flex items-center justify-center gap-2 w-full py-3 rounded-xl text-sm font-bold transition hover:opacity-90 mb-3"
                           style="background-color: #25D366; color: #fff">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                            Chat WhatsApp
                        </a>

                        <button class="flex items-center justify-center gap-2 w-full py-3 rounded-xl text-sm font-bold transition hover:opacity-90 mb-4"
                                style="background-color: var(--sage-green); color: var(--cream)">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            Booking Sekarang
                        </button>

                        <div class="flex gap-2">
                            <button class="flex-1 flex items-center justify-center gap-1.5 py-2 rounded-xl text-xs font-semibold border border-gray-200 hover:border-gray-300 transition" style="color: var(--dark-gray)">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"/></svg>
                                Bagikan
                            </button>
                            <button class="flex-1 flex items-center justify-center gap-1.5 py-2 rounded-xl text-xs font-semibold border border-gray-200 hover:border-gray-300 transition" style="color: var(--dark-gray)">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"/></svg>
                                Simpan
                            </button>
                        </div>
                    </div>

                    <!-- Contact Info -->
                    <div class="bg-white rounded-2xl border border-gray-100 p-5">
                        <h3 class="text-sm font-bold mb-3" style="color: var(--dark-gray)">Informasi Kontak</h3>
                        <div class="space-y-3">
                            <div class="flex items-center gap-3 text-sm text-gray-600">
                                <div class="w-8 h-8 rounded-full flex items-center justify-center flex-shrink-0" style="background-color: var(--light-sage)">
                                    <svg class="w-4 h-4" style="color: var(--dark-gray)" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                                </div>
                                {{ $vendor->phone }}
                            </div>
                            <div class="flex items-center gap-3 text-sm text-gray-600">
                                <div class="w-8 h-8 rounded-full flex items-center justify-center flex-shrink-0" style="background-color: var(--light-sage)">
                                    <svg class="w-4 h-4" style="color: var(--dark-gray)" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                </div>
                                {{ $vendor->email }}
                            </div>
                            <div class="flex items-center gap-3 text-sm text-gray-600">
                                <div class="w-8 h-8 rounded-full flex items-center justify-center flex-shrink-0" style="background-color: var(--light-sage)">
                                    <svg class="w-4 h-4" style="color: var(--dark-gray)" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
                                </div>
                                {{ $vendor->instagram }}
                            </div>
                            <div class="flex items-start gap-3 text-sm text-gray-600">
                                <div class="w-8 h-8 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5" style="background-color: var(--light-sage)">
                                    <svg class="w-4 h-4" style="color: var(--dark-gray)" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                </div>
                                {{ $vendor->location }}
                            </div>
                        </div>
                    </div>

                    <!-- Map Placeholder -->
                    <div class="bg-white rounded-2xl border border-gray-100 overflow-hidden">
                        <div class="relative h-36 bg-gray-100 flex items-center justify-center">
                            <img src="https://picsum.photos/seed/map-{{ $vendor->slug }}/600/200" 
                                 alt="Lokasi" class="w-full h-full object-cover opacity-70">
                            <div class="absolute inset-0 flex items-center justify-center">
                                <div class="bg-white/90 rounded-xl px-3 py-2 flex items-center gap-2 shadow-sm">
                                    <svg class="w-4 h-4" style="color: var(--sage-green)" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/></svg>
                                    <span class="text-xs font-semibold" style="color: var(--dark-gray)">Lihat di Maps</span>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>

    <!-- YouTube Video Modal -->
    <div id="video-modal"
         class="fixed inset-0 z-50 flex items-center justify-center p-4 hidden"
         onclick="if(event.target===this) closeVideoModal()">
        <div class="absolute inset-0 bg-black/80 backdrop-blur-sm"></div>
        <div class="relative w-full max-w-3xl">
            <!-- Close button -->
            <button onclick="closeVideoModal()"
                    class="absolute -top-10 right-0 w-9 h-9 rounded-full bg-white/20 hover:bg-white/30 flex items-center justify-center transition">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
            <!-- YouTube iframe container (16:9) -->
            <div class="relative rounded-2xl overflow-hidden bg-black" style="aspect-ratio: 16/9;">
                <iframe id="video-iframe"
                        src=""
                        class="w-full h-full"
                        frameborder="0"
                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                        allowfullscreen></iframe>
            </div>
        </div>
    </div>

    <!-- Package Detail Modal -->
    <div id="package-modal"
         class="fixed inset-0 z-50 flex items-center justify-center p-4 hidden"
         onclick="if(event.target===this) closePackageModal()">
        <!-- Backdrop -->
        <div class="absolute inset-0 bg-black/50 backdrop-blur-sm"></div>

        <!-- Modal Box -->
        <div class="relative bg-white rounded-3xl shadow-2xl w-full max-w-md overflow-hidden"
             style="max-height: 90vh; overflow-y: auto;">

            <!-- Modal Header (colored) -->
            <div id="modal-header" class="p-6 pb-5">
                <button onclick="closePackageModal()"
                        class="absolute top-4 right-4 w-8 h-8 rounded-full bg-white/20 hover:bg-white/30 flex items-center justify-center transition">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
                <p id="modal-pkg-name" class="text-xs font-bold uppercase tracking-widest opacity-70 mb-1"></p>
                <p id="modal-price" class="text-3xl font-bold mb-0.5"></p>
                <p id="modal-guests" class="text-sm opacity-70"></p>
            </div>

            <!-- Modal Body -->
            <div class="p-6 space-y-5">

                <!-- Items List -->
                <div>
                    <p class="text-xs font-bold uppercase tracking-widest text-gray-400 mb-3">Yang Sudah Termasuk</p>
                    <ul id="modal-items"></ul>
                </div>

                <hr class="border-gray-100">

                <!-- Summary -->
                <div class="rounded-2xl p-4" style="background-color: var(--cream)">
                    <div class="flex items-center justify-between mb-1">
                        <span class="text-xs text-gray-500">Paket dipilih</span>
                        <span id="modal-summary-name" class="text-xs font-bold" style="color: var(--dark-gray)"></span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-xs text-gray-500">Total estimasi</span>
                        <span id="modal-summary-price" class="text-base font-bold" style="color: var(--sage-green)"></span>
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex flex-col gap-2 pt-1">
                    <button onclick="selectPackage()"
                            class="flex items-center justify-center gap-2 w-full py-3 rounded-xl text-sm font-bold transition hover:opacity-90"
                            style="background-color: var(--sage-green); color: var(--cream)">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        Pilih Paket Ini
                    </button>
                    <a id="modal-wa-link"
                       href="#"
                       target="_blank"
                       class="flex items-center justify-center gap-2 w-full py-3 rounded-xl text-sm font-bold transition hover:opacity-90"
                       style="background-color: #25D366; color: #fff">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                        </svg>
                        Tanya via WhatsApp
                    </a>
                    <button onclick="closePackageModal()"
                            class="w-full py-2.5 rounded-xl text-sm font-semibold border border-gray-200 hover:bg-gray-50 transition"
                            style="color: var(--dark-gray)">
                        Batal
                    </button>
                </div>

            </div>
        </div>
    </div>

    <script>
    // ── YouTube Video Modal ───────────────────────────────────
    function openVideoModal(url) {
        // Ekstrak video ID dari berbagai format URL YouTube
        const match = url.match(/(?:youtu\.be\/|youtube\.com\/(?:watch\?v=|embed\/|shorts\/))([A-Za-z0-9_-]{11})/);
        if (!match) return;
        const videoId = match[1];
        document.getElementById('video-iframe').src =
            'https://www.youtube.com/embed/' + videoId + '?autoplay=1&rel=0';
        document.getElementById('video-modal').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function closeVideoModal() {
        document.getElementById('video-modal').classList.add('hidden');
        document.getElementById('video-iframe').src = ''; // stop video
        document.body.style.overflow = '';
    }

    // ── Package Modal ─────────────────────────────────────────
    let currentPackage = null;
    const waNumber = '{{ preg_replace('/[^0-9]/', '', $vendor->phone) }}';
    const vendorName = '{{ addslashes($vendor->name) }}';

    function openPackageModal(pkg) {
        currentPackage = pkg;

        // Header color
        const header = document.getElementById('modal-header');
        header.style.backgroundColor = pkg.card_color;
        header.style.color = pkg.card_text_color;

        // Close button text
        header.querySelector('svg').style.color = pkg.card_text_color;

        // Fill data
        document.getElementById('modal-pkg-name').textContent    = pkg.name;
        document.getElementById('modal-price').textContent        = pkg.price;
        document.getElementById('modal-guests').textContent       = pkg.max_guests;
        document.getElementById('modal-summary-name').textContent  = pkg.name;
        document.getElementById('modal-summary-price').textContent = pkg.price;

        // Items list
        const ul = document.getElementById('modal-items');
        ul.innerHTML = '';
        const count = pkg.items.length;
        const cols3 = count > 15;
        const cols2 = !cols3 && count > 5;
        ul.className = cols3
            ? 'grid grid-cols-3 gap-x-3 gap-y-1.5'
            : cols2
                ? 'grid grid-cols-2 gap-x-4 gap-y-2'
                : 'space-y-2';
        pkg.items.forEach(item => {
            const textSize = cols3 ? 'text-[11px]' : cols2 ? 'text-xs' : 'text-sm';
            ul.innerHTML += `
                <li class="flex items-start gap-1.5 ${textSize} text-gray-700">
                    <svg class="w-3 h-3 mt-0.5 flex-shrink-0" style="color: var(--sage-green)" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                    </svg>
                    <span>${item}</span>
                </li>`;
        });

        // WA link
        const msg = encodeURIComponent(`Halo, saya tertarik dengan *${pkg.name}* (${pkg.price}) di *${vendorName}*. Boleh info lebih lanjut?`);
        document.getElementById('modal-wa-link').href = `https://wa.me/${waNumber}?text=${msg}`;

        // Show modal
        const modal = document.getElementById('package-modal');
        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function closePackageModal() {
        document.getElementById('package-modal').classList.add('hidden');
        document.body.style.overflow = '';
    }

    function selectPackage() {
        if (!currentPackage) return;

        // Update sidebar
        document.getElementById('sidebar-price').textContent    = currentPackage.price;
        document.getElementById('sidebar-pkg-label').textContent = currentPackage.name;
        document.getElementById('sidebar-pkg-sub').textContent   = currentPackage.max_guests;

        closePackageModal();

        // Scroll to sidebar CTA smoothly
        document.getElementById('contact-cta').scrollIntoView({ behavior: 'smooth', block: 'center' });
    }

    // Close on Escape key
    document.addEventListener('keydown', e => {
        if (e.key === 'Escape') {
            closePackageModal();
            closeVideoModal();
        }
    });
    </script>

    @include('layout.footer')

@endsection
