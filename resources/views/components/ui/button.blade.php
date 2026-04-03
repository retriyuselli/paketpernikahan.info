@props([
    'href' => null,
    'type' => 'button',
    'variant' => 'primary',
    'size' => 'md',
])

@php
    $base = 'inline-flex items-center justify-center gap-2 transition focus:outline-none focus:ring-accent disabled:opacity-60 disabled:pointer-events-none';

    $variantClass = match ($variant) {
        'primary' => 'bg-accent text-white hover:opacity-90',
        'secondary' => 'bg-white text-gray-700 border border-gray-200 hover:bg-gray-50',
        'ghost' => 'bg-gray-50 text-dark hover:bg-gray-100',
        'ghost-danger' => 'bg-gray-50 text-red-500 hover:bg-gray-100',
        'dark' => 'bg-dark text-white hover:opacity-90',
        'danger' => 'bg-red-600 text-white hover:bg-red-700',
        default => 'bg-accent text-white hover:opacity-90',
    };

    $sizeClass = match ($size) {
        'xs' => 'px-3 py-1.5 rounded-full text-xs font-bold',
        'compact' => 'px-4 py-2 rounded-lg text-xs font-bold',
        'sm' => 'px-4 py-2 rounded-xl text-sm font-semibold',
        'md' => 'px-4 py-2.5 rounded-xl text-sm font-semibold',
        'lg' => 'px-5 py-3 rounded-2xl text-base font-semibold',
        default => 'px-4 py-2.5 rounded-xl text-sm font-semibold',
    };

    $classes = trim("{$base} {$variantClass} {$sizeClass}");
@endphp

@if ($href)
    <a href="{{ $href }}" {{ $attributes->class($classes) }}>
        {{ $slot }}
    </a>
@else
    <button type="{{ $type }}" {{ $attributes->class($classes) }}>
        {{ $slot }}
    </button>
@endif
