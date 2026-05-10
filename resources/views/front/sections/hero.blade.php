<!-- Hero Section -->
<section class="pb-5 bg-cream hero-grid-bg overflow-hidden">
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
