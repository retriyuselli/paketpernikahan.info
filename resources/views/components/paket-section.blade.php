@props(['packages', 'title' => 'Paket Pernikahan Lainnya ...', 'moreUrl' => null])

<section class="py-10 bg-cream">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between mb-5">
            <h2 class="text-xl font-extrabold text-dark">{{ $title }}</h2>
            @if($moreUrl)
                <a href="{{ $moreUrl }}" class="text-xs font-medium hover:underline text-accent">Lainnya</a>
            @endif
        </div>

        @if($packages->isEmpty())
            <div class="bg-white rounded-2xl border border-gray-100 p-10 text-center">
                <p class="text-sm text-gray-500">Belum ada paket tersedia.</p>
            </div>
        @else
            <div class="grid grid-cols-2 lg:grid-cols-6 gap-4">
                @foreach($packages->take(6) as $pkg)
                    @php
                        $vendor   = $pkg->vendor;
                        $price    = (int) ($pkg->price ?? 0);
                        $discount = (int) ($pkg->discount ?? 0);
                        $final    = max($price - $discount, 0);
                        $cover    = $pkg->image_url;
                        if (!$cover && $vendor && is_array($vendor->cover_image ?? null) && count($vendor->cover_image) > 0) {
                            $cover = $vendor->cover_image[0];
                        }
                        $cover = $cover ?: 'https://picsum.photos/seed/rv-pkg-' . $pkg->id . '/800/600';
                    @endphp
                    <a href="{{ route('store.package.show', $pkg) }}"
                       class="bg-white rounded-2xl border border-gray-100 overflow-hidden hover:border-gray-200 hover:shadow-sm transition">
                        <div class="relative ar-4x5">
                            <img src="{{ $cover }}" alt="{{ $pkg->name }}" class="w-full h-full object-cover">
                            <div class="absolute inset-0 bg-linear-to-t from-black/90 via-black/30 to-transparent"></div>
                            @if($discount > 0)
                                <span class="absolute top-3 left-3 text-[10px] font-bold px-2 py-1 rounded-full bg-white/90 border border-gray-200 text-dark">
                                    Promo
                                </span>
                            @endif
                            <div class="absolute bottom-0 left-0 right-0 p-3">
                                <p class="text-white text-xs font-bold leading-snug">{{ $pkg->name }}</p>
                                <p class="text-white/80 text-[10px] mt-1">{{ $vendor?->name }}</p>
                            </div>
                        </div>
                        <div class="p-4">
                            <p class="text-[10px] text-gray-400 truncate">{{ $vendor?->city }} · {{ $vendor?->location }}</p>
                            @if($discount > 0)
                                <p class="text-[11px] text-gray-400 line-through mt-1">Rp {{ number_format($price, 0, ',', '.') }}</p>
                                <p class="text-sm font-extrabold leading-tight text-accent">Rp {{ number_format($final, 0, ',', '.') }}</p>
                            @else
                                <p class="text-sm font-extrabold leading-tight mt-1 text-accent">Rp {{ number_format($price, 0, ',', '.') }}</p>
                            @endif
                            <p class="text-[10px] text-gray-400 truncate">{{ $vendor?->name }}</p>
                        </div>
                    </a>
                @endforeach
            </div>
        @endif
    </div>
</section>
