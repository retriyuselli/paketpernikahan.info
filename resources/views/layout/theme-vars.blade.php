@php
    $activeTheme = \App\Http\Controllers\ThemeController::active();
@endphp
<style>
    :root {
        --sage-green:          {{ $activeTheme['sage-green'] }};
        --light-sage:          {{ $activeTheme['light-sage'] }};
        --soft-pink:           {{ $activeTheme['soft-pink'] }};
        --cream:               {{ $activeTheme['cream'] }};
        --dark-gray:           {{ $activeTheme['dark-gray'] }};
        --sidebar-active-text: {{ $activeTheme['sidebar-active-text'] }};
    }
</style>
