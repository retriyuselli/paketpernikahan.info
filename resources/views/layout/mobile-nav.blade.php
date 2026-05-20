@php
    $mnHome         = request()->routeIs('home');
    $mnVendor       = request()->routeIs('vendor') || request()->is('vendor*');
    $mnStore        = request()->routeIs('store') || request()->is('store*');
    $mnInspirations = request()->routeIs('real-wedding.*') || request()->routeIs('blog.*');
    $mnProfile      = request()->is('dashboard*') || request()->routeIs('dashboard.*');

    $mnActiveColor   = '#E8816A';
    $mnInactiveColor = '#9CA3AF';
@endphp

{{-- Bottom Navigation (only visible inside Capacitor iOS/Android app) --}}
<nav id="app-bottom-nav" aria-label="Navigasi utama"
     style="display:none; position:fixed; bottom:0; left:0; right:0; z-index:200;
            background:#fff; border-top:1px solid #EBEBEB;
            box-shadow:0 -2px 16px rgba(0,0,0,0.07);
            padding-bottom:env(safe-area-inset-bottom);">
    <div style="display:flex; align-items:stretch; height:60px;">

        {{-- Home --}}
        <a href="{{ route('home') }}"
           style="flex:1; display:flex; flex-direction:column; align-items:center; justify-content:center;
                  gap:3px; text-decoration:none; color:{{ $mnHome ? $mnActiveColor : $mnInactiveColor }};
                  -webkit-tap-highlight-color:transparent;">
            <svg width="26" height="26" viewBox="0 0 24 24"
                 fill="{{ $mnHome ? 'currentColor' : 'none' }}"
                 stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
                <path d="M3 9.5L12 3L21 9.5V20a1 1 0 01-1 1H14v-6h-4v6H4a1 1 0 01-1-1V9.5z"/>
            </svg>
            <span style="font-size:10px; font-weight:{{ $mnHome ? '600' : '500' }}; letter-spacing:0.2px;">Home</span>
        </a>

        {{-- Vendors --}}
        <a href="{{ route('vendor') }}"
           style="flex:1; display:flex; flex-direction:column; align-items:center; justify-content:center;
                  gap:3px; text-decoration:none; color:{{ $mnVendor ? $mnActiveColor : $mnInactiveColor }};
                  -webkit-tap-highlight-color:transparent;">
            <svg width="26" height="26" viewBox="0 0 24 24"
                 fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
                <rect x="8" y="2" width="8" height="4" rx="1"/>
                <path d="M7 4H5a2 2 0 00-2 2v13a2 2 0 002 2h14a2 2 0 002-2V6a2 2 0 00-2-2h-2"/>
                <path d="M9 12l2 2 4-4"/>
            </svg>
            <span style="font-size:10px; font-weight:{{ $mnVendor ? '600' : '500' }}; letter-spacing:0.2px;">Vendors</span>
        </a>

        {{-- Store --}}
        <a href="{{ route('store') }}"
           style="flex:1; display:flex; flex-direction:column; align-items:center; justify-content:center;
                  gap:3px; text-decoration:none; color:{{ $mnStore ? $mnActiveColor : $mnInactiveColor }};
                  -webkit-tap-highlight-color:transparent;">
            <svg width="26" height="26" viewBox="0 0 24 24"
                 fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
                <path d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4z"/>
                <line x1="3" y1="6" x2="21" y2="6"/>
                <path d="M16 10a4 4 0 01-8 0"/>
            </svg>
            <span style="font-size:10px; font-weight:{{ $mnStore ? '600' : '500' }}; letter-spacing:0.2px;">Store</span>
        </a>

        {{-- Inspirations --}}
        <a href="{{ route('real-wedding.index') }}"
           style="flex:1; display:flex; flex-direction:column; align-items:center; justify-content:center;
                  gap:3px; text-decoration:none; color:{{ $mnInspirations ? $mnActiveColor : $mnInactiveColor }};
                  -webkit-tap-highlight-color:transparent;">
            <svg width="26" height="26" viewBox="0 0 24 24"
                 fill="{{ $mnInspirations ? 'currentColor' : 'none' }}"
                 stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
                <path d="M20.84 4.61a5.5 5.5 0 00-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 00-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 000-7.78z"/>
            </svg>
            <span style="font-size:10px; font-weight:{{ $mnInspirations ? '600' : '500' }}; letter-spacing:0.2px;">Inspirations</span>
        </a>

        {{-- Profile --}}
        <a href="{{ url('/dashboard') }}"
           style="flex:1; display:flex; flex-direction:column; align-items:center; justify-content:center;
                  gap:3px; text-decoration:none; color:{{ $mnProfile ? $mnActiveColor : $mnInactiveColor }};
                  -webkit-tap-highlight-color:transparent;">
            <svg width="26" height="26" viewBox="0 0 24 24"
                 fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
                <path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/>
                <circle cx="12" cy="7" r="4"/>
            </svg>
            <span style="font-size:10px; font-weight:{{ $mnProfile ? '600' : '500' }}; letter-spacing:0.2px;">Profile</span>
        </a>

    </div>
</nav>

<script>
(function () {
    if (window.Capacitor && window.Capacitor.isNativePlatform && window.Capacitor.isNativePlatform()) {
        var nav = document.getElementById('app-bottom-nav');
        if (nav) nav.style.display = 'block';
        document.body.style.paddingBottom = 'calc(60px + env(safe-area-inset-bottom))';
        document.querySelectorAll('.app-breadcrumb').forEach(function (el) {
            var wrapper = el.parentElement;
            if (wrapper) wrapper.style.display = 'none';
        });
        document.querySelectorAll('.app-hide').forEach(function (el) {
            el.style.display = 'none';
        });
    }
})();
</script>
