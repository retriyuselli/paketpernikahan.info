<!-- Hero Section -->
<section class="pb-16 bg-cream hero-grid-bg">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-stretch">
        <div>
            <h1 class="text-4xl lg:text-5xl font-bold text-gray-900 leading-tight mt-15">
                Jangan menunda <span class="text-accent">momen spesial</span> Anda
            </h1>
            <p class="text-sm text-gray-600 mb-6">
                Paket Pernikahan membantu mewujudkan pernikahan impian Anda dengan paket lengkap dan terjangkau
            </p>

            <!-- Category Pills -->
            <div class="flex flex-wrap gap-2 mb-4">
                <a href="{{ route('store') }}?kategori=gedung"
                   class="flex items-center gap-2 px-4 py-2 bg-white rounded-xl border border-gray-200 text-sm text-gray-700 hover:border-accent hover:text-accent transition shadow-sm">
                    <span>🏛️</span> Gedung
                </a>
                <a href="{{ route('store') }}?kategori=hotel"
                   class="flex items-center gap-2 px-4 py-2 bg-white rounded-xl border border-gray-200 text-sm text-gray-700 hover:border-accent hover:text-accent transition shadow-sm">
                    <span>🏨</span> Hotel
                </a>
                <a href="{{ route('store') }}?kategori=rumah"
                   class="flex items-center gap-2 px-4 py-2 bg-white rounded-xl border border-gray-200 text-sm text-gray-700 hover:border-accent hover:text-accent transition shadow-sm">
                    <span>🏠</span> Rumah
                </a>
                <a href="{{ route('store') }}?kategori=wo"
                   class="flex items-center gap-2 px-4 py-2 bg-white rounded-xl border border-gray-200 text-sm text-gray-700 hover:border-accent hover:text-accent transition shadow-sm">
                    <span>💼</span> WO Only
                </a>
            </div>

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
                    <button type="submit" class="flex-shrink-0 flex items-center justify-center w-10 h-10 rounded-xl bg-accent text-white transition hover:opacity-90">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </button>
                </div>
            </form>
        </div>
        <div class="hidden lg:flex rounded-lg p-8 lg:p-12 h-80 lg:h-96 items-center justify-center relative">
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
