@extends('app')

@section('title', 'Blog')

@section('content')
    <div class="container mx-auto px-4 py-12">
        <h1 class="text-4xl font-bold text-gray-900 mb-12">Blog</h1>

        {{-- Posts Grid --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @forelse($posts as $post)
                <article class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-xl transition duration-300">
                    <a href="{{ route('blog.show', $post->slug) }}" class="block">
                        @if($post->heroImage)
                            <img src="{{ $post->heroImage }}"
                                 alt="{{ $post->title }}"
                                 class="w-full h-48 object-cover">
                        @else
                            <div class="w-full h-48 bg-gradient-to-br from-blue-500 to-purple-600"></div>
                        @endif

                        <div class="p-6">
                        <span class="text-xs font-semibold text-blue-600 uppercase tracking-wide">
                            {{ $post->category }}
                        </span>

                            <h2 class="text-2xl font-bold text-gray-900 mt-2 mb-3 hover:text-blue-600 transition">
                                {{ $post->title }}
                            </h2>

                            <p class="text-gray-600 mb-4">
                                {{ $post->subtitle }}
                            </p>

                            <div class="flex items-center justify-between text-sm text-gray-500">
                                <time datetime="{{ $post->date->format('Y-m-d') }}">
                                    {{ $post->date->format('M j, Y') }}
                                </time>
                                <span class="text-blue-600 font-medium">Read more →</span>
                            </div>
                        </div>
                    </a>
                </article>
            @empty
                <div class="col-span-full text-center py-12">
                    <p class="text-gray-500 text-lg">No blog posts yet. Create your first post!</p>
                </div>
            @endforelse
        </div>

        {{-- Pagination --}}
        @if($posts->hasPages())
            <div class="mt-12">
                {{ $posts->links() }}
            </div>
        @endif
    </div>
@endsection
