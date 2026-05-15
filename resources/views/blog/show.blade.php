@extends('layout.app')

@section('title', $blog->title . ' - Blog - Makna Wedding')

@section('body-class', 'bg-cream text-dark')

@section('content')
    @include('layout.header')

    @php
        $breadcrumbItems = [
            ['label' => 'Home', 'url' => route('home')],
            ['label' => 'Blog', 'url' => route('blog.index')],
            ['label' => $blog->title, 'url' => null],
        ];
        $coverImage = $blog->cover_image_url ?: 'https://picsum.photos/seed/blog-' . $blog->id . '/1200/630';
    @endphp

    <section class="pt-3 lg:py-8 bg-cream">
        <x-ui.container>
            <div class="pt-1 pb-4 lg:pt-4">
                <x-breadcrumb :items="$breadcrumbItems" />
            </div>
            <div class="mt-3">
                <x-banner-ad mt="0" />
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

                <!-- Main Content -->
                <div class="lg:col-span-8">

                    <!-- Header Card -->
                    <div class="bg-white rounded-2xl border border-gray-100 p-6">
                        <div class="flex items-start justify-between gap-4 mb-4">
                            <div class="flex-1">
                                @if($blog->category)
                                    <span class="inline-block text-[10px] font-bold uppercase tracking-widest border border-accent/40 rounded-full px-3 py-0.5 mb-3 text-accent">
                                        {{ $blog->category }}
                                    </span>
                                @endif
                                <h1 class="text-xl sm:text-2xl font-extrabold leading-tight text-dark">{{ $blog->title }}</h1>
                                <div class="flex flex-wrap gap-3 mt-2 text-xs text-gray-500">
                                    @if($blog->author)
                                        <span>Oleh <span class="font-semibold text-dark">{{ $blog->author->name }}</span></span>
                                    @endif
                                    @if($blog->published_at)
                                        <span>· {{ $blog->published_at->translatedFormat('d F Y') }}</span>
                                    @endif
                                    <span>· {{ number_format($blog->views_count) }} views</span>
                                </div>
                                @if($blog->excerpt)
                                    <p class="mt-3 text-sm text-gray-500 leading-relaxed">{{ $blog->excerpt }}</p>
                                @endif
                            </div>
                            <a href="{{ route('blog.index') }}"
                               class="flex-shrink-0 text-xs font-bold px-3 py-2 rounded-xl border border-gray-200 bg-white hover:border-gray-300 transition text-dark">
                                Kembali
                            </a>
                        </div>

                        <!-- Cover Image -->
                        <div class="rounded-xl overflow-hidden">
                            <img src="{{ $coverImage }}" alt="{{ $blog->title }}" class="w-full object-cover max-h-[420px]">
                        </div>
                    </div>

                    <!-- Content -->
                    @if($blog->content)
                        <div class="mt-5 bg-white rounded-2xl border border-gray-100 overflow-hidden">

                            <!-- Content Header Bar -->
                            <div class="flex items-center gap-3 px-6 py-4 border-b border-gray-100 bg-light-sage/20">
                                <div class="w-1 h-5 rounded-full bg-accent flex-shrink-0"></div>
                                <span class="text-xs font-bold uppercase tracking-widest text-gray-400">Isi Artikel</span>
                                @php
                                    $wordCount = str_word_count(strip_tags($blog->content));
                                    $readMinutes = max(1, (int) ceil($wordCount / 200));
                                @endphp
                                <span class="ml-auto text-[11px] text-gray-400 flex items-center gap-1">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    {{ $readMinutes }} menit baca
                                </span>
                            </div>

                            <div class="px-6 py-6">
                                <div class="blog-content">
                                    {!! $blog->content !!}
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Tags -->
                    @if(is_array($blog->tags) && count($blog->tags) > 0)
                        <div class="mt-5 bg-white rounded-2xl border border-gray-100 p-5">
                            <p class="text-xs font-bold uppercase tracking-widest text-gray-400 mb-3">Tags</p>
                            <div class="flex flex-wrap gap-2">
                                @foreach($blog->tags as $tag)
                                    <span class="text-xs px-3 py-1 rounded-full bg-light-sage/50 text-dark font-medium"># {{ $tag }}</span>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- Related Articles -->
                    @if($related->isNotEmpty())
                        <div class="mt-8">
                            <h2 class="text-base font-bold text-dark mb-4">Artikel Terkait</h2>
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                                @foreach($related as $rel)
                                    @php $relImage = $rel->cover_image_url ?: 'https://picsum.photos/seed/blog-' . $rel->id . '/400/250'; @endphp
                                    <a href="{{ route('blog.show', $rel->slug) }}"
                                       class="bg-white rounded-2xl overflow-hidden hover:shadow-md transition group block border border-gray-100">
                                        <div class="overflow-hidden">
                                            <img src="{{ $relImage }}" alt="{{ $rel->title }}" class="w-full h-36 object-cover transition-transform duration-500 group-hover:scale-105">
                                        </div>
                                        <div class="p-3">
                                            @if($rel->category)
                                                <span class="text-[10px] font-bold text-accent">{{ $rel->category }}</span>
                                            @endif
                                            <p class="text-xs font-bold text-gray-900 leading-snug mt-1 group-hover:underline">{{ $rel->title }}</p>
                                        </div>
                                    </a>
                                @endforeach
                            </div>
                        </div>
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
                                        <img src="{{ $popImage }}" alt="{{ $pop->title }}" class="w-16 h-12 rounded-xl object-cover flex-shrink-0">
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

                    <!-- Back to Blog -->
                    <a href="{{ route('blog.index') }}"
                       class="flex items-center justify-center gap-2 w-full py-3 rounded-2xl border border-gray-200 bg-white hover:border-accent hover:text-accent transition text-sm font-semibold text-dark">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                        </svg>
                        Lihat Semua Blog
                    </a>

                </div>

            </div>
        </x-ui.container>
    </section>

    @include('layout.footer')
@endsection
