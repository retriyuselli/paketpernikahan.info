@props(['mt' => '1rem', 'mb' => '0'])

@php
    $ad  = \App\Models\HomeAd::where('is_active', true)
               ->where('type', 'banner')
               ->orderBy('sort_order')
               ->first();
    $uid = 'banner-' . uniqid();
@endphp

@if($ad)
<div id="{{ $uid }}"
     data-delay="{{ $ad->delay_seconds ?? 5 }}"
     style="overflow:hidden; max-height:200px; opacity:0;
            margin-top:{{ $mt }}; margin-bottom:{{ $mb }};
            transition: opacity 0.7s ease, max-height 0.7s ease,
                        margin-top 0.7s ease, margin-bottom 0.7s ease;">
    <div class="relative overflow-hidden w-full max-w-182 mx-auto aspect-728/90">
        <a href="{{ $ad->link_url ?: '#' }}" class="block w-full h-full">
            @if($ad->image_url)
            <img src="{{ $ad->image_url }}" alt="{{ $ad->title }}" class="w-full h-full object-cover">
            @endif
        </a>
        <button type="button"
                onclick="(function(w){w.style.opacity='0';w.style.maxHeight='0';w.style.marginTop='0';w.style.marginBottom='0';})(document.getElementById('{{ $uid }}'))"
                class="absolute top-1.5 right-2 w-5 h-5 rounded-full bg-black/20 hover:bg-black/40 flex items-center justify-center transition"
                aria-label="Tutup">
            <svg class="w-2.5 h-2.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>
    </div>
</div>

<script>
(function () {
    var w = document.getElementById('{{ $uid }}');
    if (!w) return;
    var d = (parseInt(w.dataset.delay, 10) || 5) * 1000;
    function out() {
        w.style.opacity      = '0';
        w.style.maxHeight    = '0';
        w.style.marginTop    = '0';
        w.style.marginBottom = '0';
    }
    requestAnimationFrame(function () {
        requestAnimationFrame(function () { w.style.opacity = '1'; });
    });
    setTimeout(out, 700 + d);
})();
</script>
@endif
