@extends('app')
@section('title', 'Blog')
@section('content')

    <section class="text-gray-600 body-font">
        <div class="container max-w-7xl px-5 pb-24 mx-auto">

            <div class="flex flex-wrap items-baseline gap-4 my-5">
                <h1 class="text-4xl font-thin">Your {{ config('app.name') }} Blog</h1>
                @if($activeCategory || $activeTag)
                    <a href="{{ route('blog.index') }}" class="text-sm text-blue-900 hover:underline">Clear filters</a>
                @endif
            </div>

            @if(count($allCategories) > 0)
                <div class="flex flex-wrap items-center gap-2 mb-3">
                    <span class="text-sm font-thin text-gray-500 mr-1">Categories:</span>
                    @foreach($allCategories as $category)
                        @php($isActive = $activeCategory === $category)
                        @php($queryParams = $isActive
                            ? array_filter(['tag' => $activeTag])
                            : array_filter(['category' => $category, 'tag' => $activeTag]))
                        <a href="{{ route('blog.index', $queryParams) }}"
                           class="text-xs px-3 py-1 rounded-full transition-colors {{ $isActive ? 'bg-blue-900 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                            {{ $category }}
                            @if($isActive) <span class="ml-1">&times;</span> @endif
                        </a>
                    @endforeach
                </div>
            @endif

            @if(count($allTags) > 0)
                <div class="flex flex-wrap items-center gap-2 mb-6">
                    <span class="text-sm font-thin text-gray-500 mr-1">Tags:</span>
                    @foreach($allTags as $tag)
                        @php($isActive = $activeTag === $tag)
                        @php($queryParams = $isActive
                            ? array_filter(['category' => $activeCategory])
                            : array_filter(['category' => $activeCategory, 'tag' => $tag]))
                        <a href="{{ route('blog.index', $queryParams) }}"
                           class="text-xs px-3 py-1 rounded-full transition-colors {{ $isActive ? 'bg-blue-900 text-white' : 'bg-blue-50 text-blue-800 hover:bg-blue-100' }}">
                            #{{ $tag }}
                            @if($isActive) <span class="ml-1">&times;</span> @endif
                        </a>
                    @endforeach
                </div>
            @endif

            <div class="flex flex-wrap -m-4">

                @forelse($posts as $post)
                    @php($heroImage = $post->effectiveHeroImage())
                    @php($postCategories = $post->categories ?? [])
                    @php($postTags = $post->tags ?? [])

                    <div class="p-4 sm:w-1/2 lg:w-1/3 bg">
                        <div class="
                        h-full border-2 bg-white border-gray-200 border-opacity-60 rounded-lg overflow-hidden
                        hover:ease-in-out hover:opacity-95 duration-300 hover:transition hover:scale-105
                        hover:cursor-pointer
                    "
                             x-data
                             @click="window.location.href = '/blog/{{ $post->slug }}'"
                        >

                            @if($heroImage)
                                <div class="relative rounded-lg h-64 overflow-hidden">

                                    <div class="absolute inset-0">
                                        <img
                                            src="{{ $heroImage }}"
                                            class="w-full h-full object-cover blur-sm opacity-60"
                                        >
                                    </div>

                                    <div class="absolute inset-0 flex items-center justify-center">
                                        <img
                                            src="{{ $heroImage }}"
                                            class="h-full object-contain"
                                        >
                                    </div>

                                    <div class="absolute inset-0 flex items-end">
                                        <div class="p-4">
                                            <h1 class="text-white text-2xl font-thin
                                               inline-block px-4 py-2 rounded-lg
                                               bg-black/50 backdrop-blur-sm"
                                            >
                                                {{ $post->title }}
                                            </h1>
                                        </div>
                                    </div>

                                </div>
                            @endif

                            <div class="p-6">
                                <div class="flex items-center justify-between">
                                    <h2 class="tracking-tight text-xs title-font font-thin text-gray-800 mb-1 truncate">
                                        {{ implode(' • ', $postCategories) }}
                                    </h2>

                                    <div class="flex items-center space-x-4 tracking-tight text-xs title-font font-thin text-gray-800 mb-1">
                                        <time datetime="{{ $post->date->format('Y-m-d') }}">
                                            {{ $post->date->format('jS M Y') }}
                                        </time>
                                    </div>
                                </div>

                                <h1 class="font-thin text-2xl text-gray-900 mb-3">{{ $post->subtitle ?: $post->title }}</h1>

                                <p class="font-light text-gray-500">{{ $post->summary }}</p>

                                @if(count($postTags) > 0)
                                    <div class="flex flex-wrap gap-1 mt-3">
                                        @foreach($postTags as $tag)
                                            <span class="text-xs bg-blue-50 text-blue-800 px-2 py-0.5 rounded">#{{ $tag }}</span>
                                        @endforeach
                                    </div>
                                @endif

                                @if($post->author)
                                    <p class="text-xs text-gray-500 mt-3">By {{ $post->author }}</p>
                                @endif

                                <div class="flex justify-end bottom-0 flex-wrap mt-5">
                                    <a href="/blog/{{ $post->slug }}" class="text-blue-900 font-thin text-lg hover:underline italic inline-flex items-center md:mb-2 lg:mb-0 hover:ease-in-out hover:opacity-95 duration-300 hover:transition hover:scale-105">
                                        Read more &raquo;
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="p-4 w-full text-center text-gray-500 font-thin py-12">
                        @if($activeCategory || $activeTag)
                            No posts match these filters.
                            <a href="{{ route('blog.index') }}" class="text-blue-900 hover:underline">Clear filters</a>
                        @else
                            No posts yet.
                        @endif
                    </div>
                @endforelse

            </div>

        </div>
    </section>

@endsection
