@props([
    'title' => 'Highlights',
    'scrollId' => 'highlights-scroll',
    'realWeddings' => null,
    'featuredBlogs' => null,
    'popularBlogs' => null,
    'homeAd' => null,
    'items' => null,
    'titleWrapperClass' => 'max-w-7xl mx-auto px-4 sm:px-6 lg:px-8',
    'trackClass' => 'flex gap-4 overflow-x-auto scroll-smooth pb-2 scrollbar-hide px-4 sm:px-6 lg:px-8',
    'leftButtonClass' => 'absolute left-4 top-1/2 -translate-y-1/2 w-10 h-10 bg-white rounded-full shadow-md flex items-center justify-center hover:shadow-lg transition z-10',
    'rightButtonClass' => 'absolute right-4 top-1/2 -translate-y-1/2 w-10 h-10 bg-white rounded-full shadow-md flex items-center justify-center hover:shadow-lg transition z-10',
])

@php
    $hlRealWeddings = $realWeddings ?? collect();
    $hlBlogs = (($featuredBlogs ?? collect())->merge($popularBlogs ?? collect()))->unique('id');
    $adInserted = false;
@endphp

<section class="py-10 bg-cream">
    @if(filled($title))
        <div class="{{ $titleWrapperClass }}">
            <h2 class="text-xl font-bold mb-5 text-dark">{{ $title }}</h2>
        </div>
    @endif

    <div class="relative">
        <button type="button" data-scroll-target="{{ $scrollId }}" data-scroll-by="-400"
                class="{{ $leftButtonClass }}">
            <svg class="w-5 h-5 text-dark" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        </button>

        <div id="{{ $scrollId }}" class="{{ $trackClass }}">
            @if(collect($items)->isNotEmpty())
                @foreach ($items as $item)
                    @if (($item['type'] ?? null) === 'story')
                        <div class="flex-none w-80 rounded-2xl overflow-hidden relative group cursor-pointer shadow-sm hover:shadow-md transition ar-4x3">
                            <img src="{{ $item['image'] }}" alt="Wedding Story" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
                            <div class="absolute inset-0 bg-linear-to-t from-black/70 via-black/10 to-transparent"></div>
                            <div class="absolute bottom-0 left-0 right-0 p-4">
                                <p class="text-white text-xs mb-1 opacity-80">Wedding Story of <span class="font-bold">{{ $item['title'] }}</span></p>
                                <a href="{{ $item['url'] ?? '#' }}" class="inline-block text-xs font-bold uppercase tracking-widest px-4 py-1.5 rounded-full transition hover:opacity-90 bg-accent text-cream">{{ $item['cta'] ?? 'More Info' }}</a>
                            </div>
                        </div>
                    @elseif (($item['type'] ?? null) === 'promo')
                        <div class="flex-none w-80 rounded-2xl overflow-hidden relative group cursor-pointer shadow-sm hover:shadow-md transition ar-4x3 {{ $item['theme'] ?? 'bg-accent-pink' }}">
                            <img src="{{ $item['image'] }}" alt="Promo" class="w-full h-full object-cover {{ $item['imageClass'] ?? 'opacity-30' }} transition-transform duration-500 group-hover:scale-105">
                            <div class="absolute inset-0 flex flex-col items-center justify-center text-center px-6 gap-3">
                                <p class="text-2xl font-bold leading-tight text-dark">{!! $item['title_html'] ?? '' !!}</p>
                                @if(!empty($item['caption_html']))
                                    <p class="text-xs text-dark">{!! $item['caption_html'] !!}</p>
                                @endif
                                @if(!empty($item['actions']))
                                    <div class="flex gap-2 mt-2">
                                        @foreach ($item['actions'] as $action)
                                            <a href="{{ $action['url'] ?? '#' }}" class="text-[10px] font-bold uppercase tracking-wider px-3 py-1.5 rounded-full transition hover:opacity-90 {{ $action['class'] }}">{{ $action['label'] }}</a>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        </div>
                    @elseif (($item['type'] ?? null) === 'feature')
                        <a href="{{ $item['url'] ?? '#' }}" class="{{ $item['cardClass'] ?? 'flex-none w-80 rounded-2xl overflow-hidden relative group shadow-sm hover:shadow-md transition ar-16x9' }}">
                            <img src="{{ $item['image'] }}" alt="{{ $item['alt'] ?? $item['title'] ?? 'Highlight' }}" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
                            <div class="absolute inset-0 {{ $item['overlayClass'] ?? 'bg-linear-to-t from-black/70 via-black/10 to-transparent' }}"></div>
                            <div class="absolute bottom-0 left-0 right-0 {{ $item['contentClass'] ?? 'p-4' }}">
                                <p class="text-white text-sm font-bold leading-snug">{{ $item['title'] }}</p>
                                @if(!empty($item['subtitle']))
                                    <p class="text-white/80 text-xs mt-1">{{ $item['subtitle'] }}</p>
                                @endif
                            </div>
                        </a>
                    @elseif (($item['type'] ?? null) === 'blog')
                        <div class="flex-none w-80 rounded-2xl overflow-hidden relative group cursor-pointer shadow-sm hover:shadow-md transition ar-4x3">
                            <img src="{{ $item['image'] }}" alt="{{ $item['category'] }}" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
                            <div class="absolute inset-0 bg-linear-to-t from-black/70 via-black/20 to-transparent"></div>
                            <div class="absolute bottom-0 left-0 right-0 p-4">
                                <p class="text-[10px] font-bold uppercase tracking-widest mb-1 text-accent-pink">{{ $item['category'] }}</p>
                                <p class="text-white text-sm font-bold leading-snug">{{ $item['title'] }}</p>
                            </div>
                        </div>
                    @endif
                @endforeach
            @else
                @foreach($hlRealWeddings->take(3) as $hlIdx => $rw)
                    @php $rwCover = $rw->cover_image_url ?: 'https://picsum.photos/seed/rw-' . $rw->id . '/640/480'; @endphp
                    <a href="{{ route('real-wedding.show', $rw) }}"
                       class="flex-none w-72 rounded-2xl overflow-hidden relative group cursor-pointer shadow-sm hover:shadow-md transition ar-4x3">
                        <img src="{{ $rwCover }}" alt="{{ $rw->couple_names }}" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
                        <div class="absolute inset-0 bg-linear-to-t from-black/70 via-black/10 to-transparent"></div>
                        <div class="absolute bottom-0 left-0 right-0 p-4">
                            <p class="text-white text-xs mb-2 opacity-80">Wedding Story of <span class="font-bold">{{ $rw->couple_names }}</span></p>
                            <span class="inline-block text-xs font-bold uppercase tracking-widest px-4 py-1.5 rounded-full bg-accent text-cream">More Info</span>
                        </div>
                    </a>

                    @if($hlIdx === 0 && $homeAd && !$adInserted)
                        @php $adInserted = true; @endphp
                        <a href="{{ $homeAd->link_url ?: '#' }}" class="flex-none w-52 rounded-2xl overflow-hidden relative group cursor-pointer shadow-sm hover:shadow-md transition bg-accent-pink ar-4x3">
                            @if($homeAd->image_url)
                                <img src="{{ $homeAd->image_url }}" alt="{{ $homeAd->title }}" class="w-full h-full object-cover opacity-30 transition-transform duration-500 group-hover:scale-105">
                            @endif
                            <div class="absolute inset-0 flex flex-col items-center justify-center text-center px-6 gap-3">
                                <p class="text-2xl font-bold leading-tight text-dark">{{ $homeAd->title }}</p>
                                @if($homeAd->caption)
                                    <p class="text-xs text-dark">{{ $homeAd->caption }}</p>
                                @endif
                                <span class="inline-block text-[10px] font-bold uppercase tracking-wider px-3 py-1.5 rounded-full bg-dark text-cream mt-2">
                                    {{ $homeAd->link_label ?: 'More Info' }}
                                </span>
                            </div>
                        </a>
                    @endif
                @endforeach

                @if(!$adInserted && $homeAd)
                    <a href="{{ $homeAd->link_url ?: '#' }}" class="flex-none w-52 rounded-2xl overflow-hidden relative group cursor-pointer shadow-sm hover:shadow-md transition bg-accent-pink ar-4x3">
                        @if($homeAd->image_url)
                            <img src="{{ $homeAd->image_url }}" alt="{{ $homeAd->title }}" class="w-full h-full object-cover opacity-30 transition-transform duration-500 group-hover:scale-105">
                        @endif
                        <div class="absolute inset-0 flex flex-col items-center justify-center text-center px-6 gap-3">
                            <p class="text-2xl font-bold leading-tight text-dark">{{ $homeAd->title }}</p>
                            @if($homeAd->caption)
                                <p class="text-xs text-dark">{{ $homeAd->caption }}</p>
                            @endif
                            <span class="inline-block text-[10px] font-bold uppercase tracking-wider px-3 py-1.5 rounded-full bg-dark text-cream mt-2">
                                {{ $homeAd->link_label ?: 'More Info' }}
                            </span>
                        </div>
                    </a>
                @endif

                @foreach($hlBlogs->take(4) as $blog)
                    @php $blogCover = $blog->cover_image_url ?: 'https://picsum.photos/seed/blog-' . $blog->id . '/640/480'; @endphp
                    <a href="{{ route('blog.show', $blog) }}"
                       class="flex-none w-72 rounded-2xl overflow-hidden relative group cursor-pointer shadow-sm hover:shadow-md transition ar-4x3">
                        <img src="{{ $blogCover }}" alt="{{ $blog->title }}" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
                        <div class="absolute inset-0 bg-linear-to-t from-black/70 via-black/20 to-transparent"></div>
                        <div class="absolute bottom-0 left-0 right-0 p-4">
                            @if($blog->category)
                                <p class="text-[10px] font-bold uppercase tracking-widest mb-1 text-accent-pink">{{ $blog->category }}</p>
                            @endif
                            <p class="text-white text-sm font-bold leading-snug line-clamp-3">{{ $blog->title }}</p>
                        </div>
                    </a>
                @endforeach

                @if($hlRealWeddings->isEmpty() && !$homeAd && $hlBlogs->isEmpty())
                    <p class="text-sm text-gray-400 py-10">Belum ada konten highlights.</p>
                @endif
            @endif
        </div>

        <button type="button" data-scroll-target="{{ $scrollId }}" data-scroll-by="400"
                class="{{ $rightButtonClass }}">
            <svg class="w-5 h-5 text-dark" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        </button>
    </div>
</section>