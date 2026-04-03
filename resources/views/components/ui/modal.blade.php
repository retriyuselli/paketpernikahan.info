@props([
    'id',
    'size' => 'md',
    'backdropClass' => 'bg-backdrop-45',
    'panelClass' => 'bg-white rounded-3xl shadow-2xl border border-gray-100 w-full overflow-hidden',
])

@php
    $sizeClass = match ($size) {
        'sm' => 'max-w-sm',
        'md' => 'max-w-md',
        'lg' => 'max-w-lg',
        'xl' => 'max-w-xl',
        '2xl' => 'max-w-2xl',
        '3xl' => 'max-w-3xl',
        '4xl' => 'max-w-4xl',
        default => 'max-w-md',
    };

    $outerClass = 'hidden fixed inset-0 z-50 p-4 overflow-y-auto';
@endphp

<div id="{{ $id }}" {{ $attributes->class($outerClass) }}>
    <div class="absolute inset-0 {{ $backdropClass }}"></div>
    <div class="relative {{ $panelClass }} {{ $sizeClass }}">
        {{ $slot }}
    </div>
</div>
