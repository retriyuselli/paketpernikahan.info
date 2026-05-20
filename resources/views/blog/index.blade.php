@extends('layout.app')

@section('title', 'Blog Pernikahan - Tips & Inspirasi - Paket Pernikahan')

@section('body-class', 'bg-cream text-dark')

@section('content')
    @include('layout.header')
    @include('layout.inspirations-tabs')

    @php
        $breadcrumbItems = [
            ['label' => 'Home', 'url' => route('home')],
            ['label' => 'Blog', 'url' => null],
        ];
    @endphp

    <section class="pt-3 lg:pt-3 lg:pb-8 bg-cream">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="pt-1 pb-4 lg:pt-1">
                <x-breadcrumb :items="$breadcrumbItems" />
            </div>
            
            <x-banner-ad mt="0" mb="1rem" />

            <!-- Page Header -->
            <div class="mb-8">
                <h1 class="text-base font-bold text-dark">Blog Pernikahan</h1>
                <p class="text-xs text-gray-500 mt-1">Tips, inspirasi, dan panduan untuk pernikahan impian kamu</p>
            </div>

            <!-- Filter & Search Bar -->
            <form method="GET" action="{{ route('blog.index') }}" class="mb-6">
                <div class="flex flex-wrap gap-3 items-center">
                    <!-- Search -->
                    <div class="relative flex-1 min-w-50">
                        <input type="text" name="q" value="{{ $q }}"
                               placeholder="Cari artikel..."
                               class="w-full h-10 pl-9 pr-4 text-sm rounded-xl border border-gray-200 bg-white focus:outline-none focus:border-accent transition">
                        <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>

                    <!-- Category Filter -->
                    @if($categories->isNotEmpty())
                        <select name="category"
                                class="h-10 px-4 text-sm rounded-xl border border-gray-200 bg-white focus:outline-none focus:border-accent transition text-dark"
                                onchange="this.form.submit()">
                            <option value="">Semua Kategori</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat }}" @selected($category === $cat)>{{ $cat }}</option>
                            @endforeach
                        </select>
                    @endif

                    <!-- Sort -->
                    <select name="sort"
                            class="h-10 px-4 text-sm rounded-xl border border-gray-200 bg-white focus:outline-none focus:border-accent transition text-dark"
                            onchange="this.form.submit()">
                        <option value="terbaru" @selected($sort === 'terbaru')>Terbaru</option>
                        <option value="populer" @selected($sort === 'populer')>Terpopuler</option>
                    </select>

                    <button type="submit"
                            class="h-10 px-5 text-sm font-bold rounded-xl bg-accent text-white hover:bg-accent/90 transition">
                        Cari
                    </button>
                </div>
            </form>

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

                <!-- Articles Grid -->
                <div class="lg:col-span-8">
                    @if($blogs->isEmpty())
                        <div class="bg-white rounded-2xl border border-gray-100 p-10 text-center">
                            <p class="text-sm text-gray-400">Tidak ada artikel yang ditemukan.</p>
                            <a href="{{ route('blog.index') }}" class="mt-4 inline-block text-xs font-bold text-accent hover:underline">Lihat semua artikel</a>
                        </div>
                    @else
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                            @foreach($blogs as $post)
                                @php $image = $post->cover_image_url ?: 'https://picsum.photos/seed/blog-' . $post->id . '/600/400'; @endphp
                                <a href="{{ route('blog.show', $post->slug) }}"
                                   class="bg-white rounded-2xl overflow-hidden hover:shadow-md transition group border border-gray-100 flex flex-col">
                                    <div class="overflow-hidden">
                                        <img src="{{ $image }}" alt="{{ $post->title }}"
                                             loading="lazy"
                                             class="w-full h-48 object-cover transition-transform duration-500 group-hover:scale-105">
                                    </div>
                                    <div class="p-4 flex-1 flex flex-col">
                                        @if($post->category)
                                            <span class="text-[10px] font-bold text-accent uppercase tracking-widest">{{ $post->category }}</span>
                                        @endif
                                        <h2 class="mt-1 text-sm font-bold text-gray-900 leading-snug group-hover:underline flex-1">{{ $post->title }}</h2>
                                        @if($post->excerpt)
                                            <p class="text-xs text-gray-500 mt-2 line-clamp-2">{{ $post->excerpt }}</p>
                                        @endif
                                        <div class="flex items-center gap-2 mt-3 text-[10px] text-gray-400">
                                            @if($post->published_at)
                                                <span>{{ $post->published_at->translatedFormat('d M Y') }}</span>
                                            @endif
                                            <span>· {{ number_format($post->views_count) }} views</span>
                                        </div>
                                    </div>
                                </a>
                            @endforeach
                        </div>

                        <!-- Pagination -->
                        @if($blogs->hasPages())
                            <div class="mt-8">
                                {{ $blogs->links() }}
                            </div>
                        @endif
                    @endif
                </div>

                <!-- Sidebar -->
                <div class="lg:col-span-4 space-y-5">

                    <!-- Popular Articles -->
                    @if($popularBlogs->isNotEmpty())
                        <div class="bg-white rounded-2xl border border-gray-100 p-5">
                            <h3 class="text-xs font-extrabold uppercase tracking-widest text-gray-400 border-b border-gray-100 pb-3 mb-4">Artikel Terpopuler</h3>
                            <div class="flex flex-col gap-4">
                                @foreach($popularBlogs as $idx => $pop)
                                    @php $popImage = $pop->cover_image_url ?: 'https://picsum.photos/seed/popular-' . $pop->id . '/160/120'; @endphp
                                    @if($idx > 0)
                                        <div class="border-t border-gray-100"></div>
                                    @endif
                                    <a href="{{ route('blog.show', $pop->slug) }}" class="flex gap-3 group">
                                        <img src="{{ $popImage }}" alt="{{ $pop->title }}" loading="lazy" class="w-16 h-12 rounded-xl object-cover shrink-0">
                                        <div>
                                            @if($pop->category)
                                                <p class="text-[10px] font-bold text-accent">{{ $pop->category }} <span class="text-gray-400 font-normal">· {{ number_format($pop->views_count) }} views</span></p>
                                            @endif
                                            <p class="text-xs font-semibold text-gray-800 leading-snug group-hover:underline">{{ $pop->title }}</p>
                                        </div>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- Categories -->
                    @if($categories->isNotEmpty())
                        <div class="bg-white rounded-2xl border border-gray-100 p-5">
                            <h3 class="text-xs font-extrabold uppercase tracking-widest text-gray-400 border-b border-gray-100 pb-3 mb-4">Kategori</h3>
                            <div class="flex flex-col gap-1">
                                <a href="{{ route('blog.index') }}"
                                   class="text-sm px-3 py-2 rounded-xl hover:bg-light-sage/30 transition {{ !$category ? 'font-bold text-accent' : 'text-dark' }}">
                                    Semua Artikel
                                </a>
                                @foreach($categories as $cat)
                                    <a href="{{ route('blog.index', ['category' => $cat]) }}"
                                       class="text-sm px-3 py-2 rounded-xl hover:bg-light-sage/30 transition {{ $category === $cat ? 'font-bold text-accent' : 'text-dark' }}">
                                        {{ $cat }}
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @endif

                </div>

            </div>
        </div>
    </section>

    @include('layout.footer')
@endsection
