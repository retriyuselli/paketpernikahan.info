@props(['packages', 'title' => 'Paket Pernikahan Lainnya ...', 'moreUrl' => null, 'sectionClass' => 'py-10 bg-cream'])

<section class="{{ $sectionClass }}">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between mb-5">
            <h2 class="text-base font-bold text-dark">{{ $title }}</h2>
            @if($moreUrl)
                <a href="{{ $moreUrl }}" class="text-xs font-medium hover:underline text-accent">Lihat</a>
            @endif
        </div>

        @if($packages->isEmpty())
            <div class="bg-white rounded-2xl border border-gray-100 p-10 text-center">
                <p class="text-sm text-gray-500">Belum ada paket tersedia.</p>
            </div>
        @else
            <div class="grid grid-cols-2 lg:grid-cols-6 gap-1.5 sm:gap-2 lg:gap-3">
                @foreach($packages->take(6) as $pkg)
                    @php
                        $vendor = $pkg->vendor;
                        $price = (int) ($pkg->price ?? 0);
                        $discount = (int) ($pkg->discount ?? 0);
                        $cover = $pkg->image_url;
                        if (!$cover && $vendor && is_array($vendor->cover_image ?? null) && count($vendor->cover_image) > 0) {
                            $cover = $vendor->cover_image[0];
                        }
                        $cover = $cover ?: 'https://picsum.photos/seed/rv-pkg-' . $pkg->id . '/800/600';
                        $items = $pkg->items;
                        $vendorName = $vendor ? $vendor->name : null;
                        $vendorLocation = $vendor && $vendor->city ? $vendor->city : 'Indonesia';
                        $vendorRating = $vendor ? ($vendor->rating ?? null) : null;
                        $primaryBenefit = $discount > 0 ? 'Harga Diskon' : 'Paket Pilihan';
                        $secondaryBenefit = !empty($items[0]) ? \Illuminate\Support\Str::limit($items[0], 16) : 'Gratis Konsultasi';
                    @endphp
                    <x-package-card
                        :href="route('store.package.show', $pkg)"
                        :name="$pkg->name"
                        :image="$cover"
                        :price="$price"
                        :discount="$discount"
                        :vendor-name="$vendorName"
                        :location="$vendorLocation"
                        :rating="$vendorRating"
                        :benefit-primary="$primaryBenefit"
                        :benefit-secondary="$secondaryBenefit"
                        width-class="w-full"
                    />
                @endforeach
            </div>
        @endif
    </div>
</section>
