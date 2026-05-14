@props([
    'href',
    'name',
    'image',
    'price' => 0,
    'discount' => 0,
    'vendorName' => null,
    'location' => 'Indonesia',
    'rating' => null,
    'benefitPrimary' => 'Paket Pilihan',
    'benefitSecondary' => 'Gratis Konsultasi',
    'widthClass' => 'w-[calc((100%-0.375rem)/2)] sm:w-[44vw] lg:w-60',
    'aspectClass' => 'aspect-square',
])

@php
    $basePrice = (int) ($price ?? 0);
    $discountAmount = (int) ($discount ?? 0);
    $finalPrice = max($basePrice - $discountAmount, 0);
    $displayPrice = $finalPrice ?: $basePrice;
    $discountPercent = $basePrice > 0 && $discountAmount > 0
        ? (int) round(($discountAmount / $basePrice) * 100)
        : 0;
    $ratingText = filled($rating) ? number_format((float) $rating, 1) : null;
    $displayName = \Illuminate\Support\Str::title((string) $name);
@endphp

<a href="{{ $href }}"
   {{ $attributes->class([
       'flex-none snap-start overflow-hidden rounded-[10px] border border-gray-200 bg-white shadow-[0_2px_10px_rgba(15,23,42,0.08)] transition hover:border-gray-200 hover:shadow-sm',
       $widthClass,
   ]) }}>
    <div class="relative {{ $aspectClass }}">
        <img src="{{ $image }}" alt="{{ $name }}" class="h-full w-full object-cover">
        @if($discountPercent > 0)
            <span class="absolute right-0 top-0 rounded-bl-2xl bg-accent px-2.5 py-1.5 text-[11px] font-extrabold leading-none text-cream">
                {{ $discountPercent }}%
            </span>
        @endif
        <span class="absolute left-2 top-2 rounded-full bg-white/90 px-2 py-1 text-[10px] font-medium leading-none text-dark shadow-sm">
            {{ $location }}
        </span>
        <div class="absolute inset-x-0 bottom-0 flex items-center gap-1.5 px-2 py-1.5 text-dark" style="background: linear-gradient(90deg, var(--soft-pink), var(--light-sage), var(--sage-green));">
            <span class="rounded-md bg-white/55 px-1.5 py-0.5 text-[9px] font-bold leading-none">{{ $benefitPrimary }}</span>
            <span class="rounded-md bg-white/55 px-1.5 py-0.5 text-[9px] font-bold leading-none">{{ $benefitSecondary }}</span>
        </div>
    </div>

    <div class="space-y-0.5 p-3 sm:space-y-1">
        <p class="text-[12px] font-medium leading-tight text-gray-900 sm:text-[13px] sm:min-h-11">{{ $displayName }}</p>

        @if($discountAmount > 0)
            <p class="text-[11px] text-gray-400 line-through">Rp{{ number_format($basePrice, 0, ',', '.') }}</p>
        @endif

        <p class="font-extrabold leading-none text-accent"><span class="text-[11px]">Rp</span><span class="text-[15px]">{{ number_format($displayPrice, 0, ',', '.') }}</span></p>

        {{-- @if($discountAmount > 0)
            <div class="flex flex-wrap gap-1">
                <span class="rounded-xs border border-transparent bg-accent-pink px-1.5 py-0.5 text-[10px] font-medium text-dark">Harga Diskon</span>
            </div>
        @endif --}}

        @if(filled($vendorName))
            <p class="truncate text-[11px] text-gray-500">{{ $vendorName }}</p>
        @endif

        <div class="flex items-center gap-1.5 text-[11px] text-gray-500">
            @if($ratingText)
                <span class="flex items-center gap-1 text-accent">
                    <svg class="h-3.5 w-3.5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                    <span>{{ $ratingText }}</span>
                </span>
            @endif
        </div>
    </div>
</a>