<nav class="flex items-center gap-2 text-xs flex-wrap font-semibold text-dark">
    @foreach(($items ?? []) as $i => $item)
        @if($i > 0)
            <svg class="w-3 h-3 opacity-30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
        @endif

        @php
            $label = (string) ($item['label'] ?? '');
            $url = $item['url'] ?? null;
        @endphp

        @if($url)
            <a href="{{ $url }}" class="opacity-70 hover:opacity-100 transition">{{ $label }}</a>
        @else
            <span class="font-bold truncate max-w-[220px] sm:max-w-none">{{ $label }}</span>
        @endif
    @endforeach
</nav>

@php
    $breadcrumbBannerAd = \App\Models\HomeAd::where('is_active', true)
        ->where('type', 'banner')
        ->orderBy('sort_order')
        ->first();
@endphp

@if($breadcrumbBannerAd)
<div id="breadcrumb-banner-wrap"
     data-delay="{{ $breadcrumbBannerAd->delay_seconds ?? 5 }}"
     style="overflow:hidden; max-height:200px; opacity:0; margin-top:1rem;
            transition: opacity 0.7s ease, max-height 0.7s ease, margin-top 0.7s ease;">
    <div class="relative rounded-2xl overflow-hidden w-full max-w-182 mx-auto aspect-728/90">
        <a href="{{ $breadcrumbBannerAd->link_url ?: '#' }}" class="block w-full h-full">
            @if($breadcrumbBannerAd->image_url)
            <img src="{{ $breadcrumbBannerAd->image_url }}"
                 alt="{{ $breadcrumbBannerAd->title }}"
                 class="w-full h-full object-cover">
            @endif
            <div class="">
                <div>
                    <p class="text-base font-bold leading-snug text-dark">{{ $breadcrumbBannerAd->title }}</p>
                    @if($breadcrumbBannerAd->caption)
                        <p class="text-xs text-dark opacity-70 mt-0.5">{{ $breadcrumbBannerAd->caption }}</p>
                    @endif
                </div>
            </div>
        </a>
        <button type="button" onclick="dismissBreadcrumbBanner()"
                class="absolute top-1.5 right-2 w-5 h-5 rounded-full bg-black/20 hover:bg-black/40 flex items-center justify-center transition"
                aria-label="Tutup">
            <svg class="w-2.5 h-2.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>
        <span class="absolute bottom-1.5 right-2 text-[9px] font-semibold uppercase tracking-widest opacity-40 text-dark pointer-events-none">Iklan</span>
    </div>
</div>

<script>
(function () {
    var wrap = document.getElementById('breadcrumb-banner-wrap');
    if (!wrap) return;

    var delay = (parseInt(wrap.dataset.delay, 10) || 5) * 1000;

    function dismiss() {
        wrap.style.opacity = '0';
        wrap.style.maxHeight = '0';
        wrap.style.marginTop = '0';
    }

    window.dismissBreadcrumbBanner = dismiss;

    // Fade in setelah halaman render (double rAF agar transition terpicu)
    requestAnimationFrame(function () {
        requestAnimationFrame(function () {
            wrap.style.opacity = '1';
        });
    });

    // Fade out setelah fade-in selesai (700ms) + durasi tampil (delay)
    setTimeout(dismiss, 700 + delay);
})();
</script>
@endif
