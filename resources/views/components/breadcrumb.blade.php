@props(['items' => []])

@php
    $breadcrumbSchema = [
        '@context'        => 'https://schema.org',
        '@type'           => 'BreadcrumbList',
        'itemListElement' => collect($items)->values()->map(function ($item, $i) {
            $entry = [
                '@type'    => 'ListItem',
                'position' => $i + 1,
                'name'     => (string) ($item['label'] ?? ''),
                'item'     => $item['url'] ?? request()->url(),
            ];
            return $entry;
        })->toArray(),
    ];
@endphp
<script type="application/ld+json">{!! json_encode($breadcrumbSchema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) !!}</script>

<nav class="app-breadcrumb flex items-center gap-2 text-[11px] font-semibold text-dark overflow-hidden min-w-0">
    @foreach($items as $i => $item)
        @if($i > 0)
            <svg class="w-3 h-3 opacity-30 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
        @endif

        @php
            $label = mb_convert_case((string) ($item['label'] ?? ''), MB_CASE_TITLE, 'UTF-8');
            $url = $item['url'] ?? null;
        @endphp

        @if($url)
            <a href="{{ $url }}" class="opacity-70 hover:opacity-100 transition shrink-0">{{ $label }}</a>
        @else
            <span class="font-bold truncate min-w-0">{{ $label }}</span>
        @endif
    @endforeach
</nav>
