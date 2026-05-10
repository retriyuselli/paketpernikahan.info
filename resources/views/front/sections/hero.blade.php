<!-- Hero Section -->
<section class="relative pb-5 bg-cream hero-grid-bg overflow-hidden">

    <!-- Decorative: large golden orb top-right -->
    <div class="absolute -top-40 -right-40 w-150 h-150 rounded-full pointer-events-none"
         style="background: radial-gradient(circle, rgba(201,168,76,0.13) 0%, rgba(232,213,163,0.07) 45%, transparent 70%);"></div>

    <!-- Decorative: soft orb bottom-left -->
    <div class="absolute -bottom-24 -left-24 w-80 h-80 rounded-full pointer-events-none"
         style="background: radial-gradient(circle, rgba(201,168,76,0.09) 0%, transparent 70%);"></div>

    <!-- Decorative: concentric rings near center divider (lg only) -->
    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 pointer-events-none hidden lg:block">
        <div class="w-64 h-64 rounded-full border border-[#C9A84C]/10"></div>
    </div>
    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 pointer-events-none hidden lg:block">
        <div class="w-44 h-44 rounded-full border border-[#C9A84C]/15"></div>
    </div>

    <!-- Decorative: dot grid cluster left (lg only) -->
    <div class="absolute left-8 top-1/2 -translate-y-1/2 pointer-events-none hidden lg:grid grid-cols-4 gap-2.5">
        @for($i = 0; $i < 16; $i++)
            <div class="w-1 h-1 rounded-full" style="background: rgba(201,168,76,{{ 0.1 + ($i % 4) * 0.06 }});"></div>
        @endfor
    </div>

    <!-- Decorative: small floating rings top-right area (lg only) -->
    <div class="absolute top-8 right-[47%] w-14 h-14 rounded-full border-2 border-[#C9A84C]/20 pointer-events-none hidden lg:block"></div>
    <div class="absolute top-14 right-[44%] w-7 h-7 rounded-full border-2 border-[#C9A84C]/25 pointer-events-none hidden lg:block"></div>

    <!-- Decorative: sparkle icons -->
    <svg class="absolute top-10 left-1/3 w-5 h-5 pointer-events-none hidden lg:block" style="color: rgba(201,168,76,0.35);" fill="currentColor" viewBox="0 0 24 24">
        <path d="M12 2l1.5 4.5L18 8l-4.5 1.5L12 14l-1.5-4.5L6 8l4.5-1.5z"/>
    </svg>
    <svg class="absolute bottom-12 right-[48%] w-3 h-3 pointer-events-none hidden lg:block" style="color: rgba(201,168,76,0.4);" fill="currentColor" viewBox="0 0 24 24">
        <path d="M12 2l1.5 4.5L18 8l-4.5 1.5L12 14l-1.5-4.5L6 8l4.5-1.5z"/>
    </svg>

    <!-- Decorative: subtle bottom wave -->
    <svg class="absolute bottom-0 left-0 w-full pointer-events-none" viewBox="0 0 1440 40" preserveAspectRatio="none" fill="none" xmlns="http://www.w3.org/2000/svg">
        <path d="M0 20 Q360 0 720 20 Q1080 40 1440 20" stroke="rgba(201,168,76,0.2)" stroke-width="1.5" fill="none"/>
    </svg>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 lg:gap-12 items-center">

            <!-- Left: Text + Search -->
            <div class="pt-4 lg:pt-0 pb-6 lg:pb-10">
                <h1 class="text-3xl sm:text-4xl lg:text-5xl font-bold text-gray-900 leading-tight mt-0">
                    Jangan menunda <span class="text-accent">momen spesial</span> Anda
                </h1>
                <p class="text-sm text-gray-600 mb-6">
                    Paket Pernikahan membantu mewujudkan pernikahan impian Anda dengan paket lengkap dan terjangkau
                </p>

                <!-- Search Bar -->
                <form action="{{ route('store') }}" method="GET">
                    <div class="flex items-center bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden pr-2 py-2 pl-5 gap-3">
                        <input
                            type="text"
                            name="q"
                            placeholder="Temukan paket pernikahan impian Anda"
                            class="flex-1 text-sm text-gray-700 placeholder-gray-400 focus:outline-none bg-transparent"
                            autocomplete="off"
                        >
                        <button type="submit" class="shrink-0 flex items-center justify-center w-10 h-10 rounded-xl bg-accent text-white transition hover:opacity-90">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </button>
                    </div>
                </form>
            </div>

            <!-- Right: Team Image + Floating Circles -->
            <div class="hidden lg:flex items-end justify-center relative lg:h-120">
                <img src="{{ asset('images/business-support.png') }}" alt="Tim Business Support Makna Wedding"
                     class="h-full w-auto object-contain object-bottom mix-blend-multiply relative z-10">
                <!-- Animated floating circles with images -->
                <div class="absolute inset-0 flex items-center justify-center">
                    @foreach($heroCircles as $circle)
                    <div class="absolute rounded-full overflow-hidden shadow-lg animate-float hero-circle {{ $circle->position_side === 'left' ? 'hero-circle-left' : 'hero-circle-right' }}"
                         style="--hero-size: {{ $circle->size_px }}px; --hero-from: {{ $circle->color_from }}; --hero-to: {{ $circle->color_to }}; --hero-delay: {{ $circle->animation_delay }}s; --hero-duration: {{ $circle->animation_duration }}s; --hero-bottom: {{ $circle->position_bottom }}; --hero-x: {{ $circle->position_x }};">
                        <img src="{{ $circle->asset_url }}" alt="{{ $circle->alt }}" class="w-full h-full object-cover">
                    </div>
                    @endforeach
                </div>
            </div>

        </div>
    </div>
</section>
