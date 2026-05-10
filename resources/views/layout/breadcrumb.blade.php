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

<x-banner-ad />
