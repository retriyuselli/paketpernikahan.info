@props([
    'vendor',
    'vendorData',
    'widthClass' => 'w-56',
])

@php
    $coverImage = $vendor->cover_image_url ?: (optional($vendor->galleries->first())->image_url ?? 'https://picsum.photos/seed/' . $vendor->id . '/350/260');
    $promoLabels = collect((array) ($vendor->promo ?? []))
        ->map(fn ($promo) => \App\Enums\VendorPromo::from($promo)->label())
        ->values();
    $badgeLabels = collect((array) ($vendor->badge ?? []))
        ->map(fn ($badge) => \App\Enums\VendorBadge::from($badge)->label())
        ->values();
    $visibleBadgeLabels = $badgeLabels->take(2);
    $extraBadges = max($badgeLabels->count() - 2, 0);
    $package = $vendor->cheapestPackage;
    $packagePrice = $package ? $package->price : null;
    $packageDiscount = (int) ($package ? ($package->discount ?? 0) : 0);
    $packageFinal = $package ? max(((int) $packagePrice) - $packageDiscount, 0) : null;
    $priceStartText = is_numeric($vendor->price_start) ? number_format((int) $vendor->price_start, 0, ',', '.') : null;
    $commentsCount = (int) ($vendor->comments_count ?? 0);
    $ratingValue = (float) ($vendor->rating ?? 0);
    $photosCount = (int) ($vendor->galleries_count ?? 0);
    $likesCount = (int) ($vendor->likes ?? 0);
@endphp

<div
    data-vendor-preview-open
    data-vendor='@json($vendorData)'
    {{ $attributes->class([
        'group block flex-none cursor-pointer overflow-hidden rounded-2xl border border-gray-200 bg-white transition hover:border-gray-300',
        $widthClass,
    ]) }}>
    <div class="relative aspect-square overflow-hidden">
        <img
            src="{{ $coverImage }}"
            alt="{{ $vendor->name }}{{ $vendor->city ? ' di ' . $vendor->city : '' }}"
            loading="lazy"
            class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-105">

        @if($promoLabels->isNotEmpty())
            <div class="absolute left-2 top-2 flex flex-col gap-1">
                @foreach($promoLabels as $promoLabel)
                    <span class="rounded-full bg-accent px-2.5 py-1 text-[10px] font-bold uppercase tracking-wide text-white">{{ $promoLabel }}</span>
                @endforeach
            </div>
        @endif

        @if($badgeLabels->isNotEmpty())
            <div class="absolute bottom-0 left-0 right-0 flex items-center gap-1 bg-accent-gradient px-2 py-1.5">
                <svg class="h-3 w-3 shrink-0 text-white" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                @foreach($visibleBadgeLabels as $badgeLabel)
                    <span class="text-[9px] font-bold uppercase tracking-wide text-white">{{ $badgeLabel }}</span>
                @endforeach
                @if($extraBadges > 0)
                    <span class="text-[9px] font-bold uppercase tracking-wide text-white/70">+{{ $extraBadges }}</span>
                @endif
                <svg class="ml-auto h-3 w-3 shrink-0 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </div>
        @endif

        @if($vendor->city)
            <div class="absolute left-0 right-0 z-10 flex justify-center {{ $badgeLabels->isNotEmpty() ? 'bottom-8' : 'bottom-2' }}">
                <span class="flex items-center gap-1 rounded-full bg-backdrop-45 px-2.5 py-0.5 text-[10px] font-semibold text-white backdrop-blur-[2px]">
                    <svg class="h-2.5 w-2.5 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/></svg>
                    {{ $vendor->city }}
                </span>
            </div>
        @endif
    </div>

    <div class="p-2">
        <p class="text-[12px] font-medium leading-tight text-gray-900 sm:text-[13px]">{{ $vendor->name }}</p>

        @if($package)
            <div class="mb-2 mt-1 flex items-center gap-1.5">
                <span class="text-[9px] text-gray-400">Start</span>
                @if($packageDiscount > 0)
                    <span class="text-[10px] text-gray-400 line-through"><span class="text-[9px]">Rp</span> {{ number_format($packagePrice, 0, ',', '.') }}</span>
                    <span class="font-bold text-dark"><span class="text-[9px]">Rp</span> <span class="text-[11px]">{{ number_format($packageFinal, 0, ',', '.') }}</span></span>
                @else
                    <span class="font-semibold text-dark"><span class="text-[9px]">Rp</span> <span class="text-[13px]">{{ number_format($packagePrice, 0, ',', '.') }}</span></span>
                @endif
            </div>
        @else
            <div class="mb-2 mt-1 flex items-center gap-1.5">
                <span class="text-[9px] text-gray-400">Start</span>
                <span class="font-semibold text-dark">
                    @if($priceStartText)
                        <span class="text-[9px]">Rp</span> <span class="text-[11px]">{{ $priceStartText }}</span>
                    @else
                        <span class="text-[11px]">{{ $vendor->price_start ?: '—' }}</span>
                    @endif
                </span>
            </div>
        @endif

        @if($commentsCount >= 1 || $ratingValue >= 1 || $photosCount >= 1 || $likesCount >= 1)
            <div class="flex flex-wrap items-center gap-2 text-[10px] text-gray-500">
                @if($commentsCount >= 1)
                    <span class="flex items-center gap-0.5">
                        <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                        {{ $commentsCount }}
                    </span>
                @endif
                @if($ratingValue >= 1)
                    <span class="flex items-center gap-0.5 font-semibold text-rating">
                        <svg class="h-3 w-3" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                        {{ number_format($ratingValue, 1) }}
                    </span>
                @endif
                @if($photosCount >= 1)
                    <span class="flex items-center gap-0.5">
                        <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        {{ $photosCount }}
                    </span>
                @endif
                @if($likesCount >= 1)
                    <span class="flex items-center gap-0.5">
                        <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                        {{ $likesCount }}
                    </span>
                @endif
            </div>
        @endif
    </div>
</div>