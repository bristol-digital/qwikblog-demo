@extends('app')

@section('title', $post->title)

@section('content')
    <article class="max-w-7xl mx-auto px-4 py-12">
        {{-- Hero Image --}}
        @if($post->heroImage)
            <img src="{{ $post->heroImage }}"
                 alt="{{ $post->title }}"
                 class="w-full h-96 object-cover rounded-lg shadow-lg mb-8">
        @endif

        <div class="lg:grid lg:grid-cols-3 lg:gap-12">
            {{-- Main Content (2/3) --}}
            <div class="lg:col-span-2">
                {{-- Post Header --}}
                <div class="mb-8">
                    <a href="{{ route('blog.index') }}"
                       class="inline-flex items-center text-blue-600 hover:text-blue-800 mb-4 font-medium">
                        <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                        </svg>
                        Back to Blog
                    </a>

                    <span class="inline-block text-sm font-semibold text-blue-600 uppercase tracking-wide mb-2">
                    {{ $post->category }}
                </span>

                    <h1 class="text-4xl lg:text-5xl font-bold text-gray-900 mb-4">
                        {{ $post->title }}
                    </h1>

                    @if($post->subtitle)
                        <p class="text-xl text-gray-600 mb-4">
                            {{ $post->subtitle }}
                        </p>
                    @endif

                    <time class="text-gray-500" datetime="{{ $post->date->format('Y-m-d') }}">
                        {{ $post->date->format('F j, Y') }}
                    </time>
                </div>

                {{-- Blog Content with Prose --}}
                <div class="prose prose-lg prose-blue max-w-none
                        prose-headings:font-bold prose-headings:text-gray-900
                        prose-p:text-gray-700 prose-p:leading-relaxed
                        prose-a:text-blue-600 prose-a:no-underline hover:prose-a:underline
                        prose-strong:text-gray-900 prose-strong:font-semibold
                        prose-code:text-pink-600 prose-code:bg-gray-100 prose-code:px-1 prose-code:py-0.5 prose-code:rounded
                        prose-pre:bg-gray-900 prose-pre:text-gray-100
                        prose-blockquote:border-blue-500 prose-blockquote:bg-blue-50 prose-blockquote:py-1
                        prose-img:rounded-lg prose-img:shadow-md">
                    {!! $post->content !!}
                </div>

                {{-- Previous/Next Navigation --}}
                <nav class="mt-12 pt-8 border-t border-gray-200">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        @if($previous)
                            <a href="{{ route('blog.show', $previous->slug) }}"
                               class="group p-6 bg-white rounded-lg border border-gray-200 hover:border-blue-500 hover:shadow-md transition">
                                <span class="text-sm text-gray-500 block mb-2">← Previous</span>
                                <span class="text-lg font-semibold text-gray-900 group-hover:text-blue-600">
                                {{ $previous->title }}
                            </span>
                            </a>
                        @else
                            <div></div>
                        @endif

                        @if($next)
                            <a href="{{ route('blog.show', $next->slug) }}"
                               class="group p-6 bg-white rounded-lg border border-gray-200 hover:border-blue-500 hover:shadow-md transition text-right">
                                <span class="text-sm text-gray-500 block mb-2">Next →</span>
                                <span class="text-lg font-semibold text-gray-900 group-hover:text-blue-600">
                                {{ $next->title }}
                            </span>
                            </a>
                        @endif
                    </div>
                </nav>
            </div>

            {{-- Sidebar (1/3) --}}
            <aside class="mt-12 lg:mt-0">
                <div class="lg:sticky lg:top-8 bg-white p-6 rounded-lg shadow-md">
                    <h3 class="text-xl font-bold text-gray-900 mb-4 border-b border-gray-200 pb-3">
                        All Posts
                    </h3>
                    <nav class="space-y-3">
                        @foreach($allPosts as $sidebarPost)
                            <a href="{{ route('blog.show', $sidebarPost->slug) }}"
                               class="block text-sm transition
                                  {{ $sidebarPost->slug === $post->slug
                                     ? 'text-blue-600 font-semibold bg-blue-50 px-3 py-2 rounded'
                                     : 'text-gray-600 hover:text-blue-600 hover:bg-gray-50 px-3 py-2 rounded' }}">
                                {{ $sidebarPost->title }}
                            </a>
                        @endforeach
                    </nav>
                </div>
            </aside>
        </div>
    </article>
@endsection
