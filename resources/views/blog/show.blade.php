@extends(config('qwikblog.layout', 'layouts.app'))

@section('title', $post->title)

@section('content')
    <article class="max-w-7xl mx-auto px-4 py-12">
        @if($post->heroImage)
            <img src="{{ $post->heroImage }}"
                 alt="{{ $post->title }}"
                 class="w-full h-96 object-cover rounded-lg mb-8">
        @endif

        <div class="lg:grid lg:grid-cols-3 lg:gap-12">
            {{-- Main Content (2/3) --}}
            <div class="lg:col-span-2">
                <div class="mb-8">
                    <a href="{{ route(config('qwikblog.route.name_prefix') . 'index') }}"
                       class="text-blue-600 hover:text-blue-800 mb-4 inline-block">
                        ← Back to Blog
                    </a>
                    <span class="text-sm text-blue-600 font-semibold block">{{ $post->category }}</span>
                    <h1 class="text-5xl font-bold mt-2 mb-4">{{ $post->title }}</h1>
                    <p class="text-xl text-gray-600 mb-4">{{ $post->subtitle }}</p>
                    <time class="text-gray-400">{{ $post->date->format('F j, Y') }}</time>
                </div>

                {{-- Blog Content --}}
                <div class="prose prose-lg max-w-none prose-headings:font-bold prose-a:text-blue-600 prose-a:no-underline hover:prose-a:underline">
                    {!! $post->content !!}
                </div>

                {{-- Previous/Next Navigation --}}
                <div class="flex justify-between items-center mt-12 pt-8 border-t">
                    @if($previous)
                        <a href="{{ route(config('qwikblog.route.name_prefix') . 'show', $previous->slug) }}"
                           class="text-blue-600 hover:text-blue-800 flex-1">
                            <span class="text-sm text-gray-500 block">Previous</span>
                            <span class="font-semibold">{{ $previous->title }}</span>
                        </a>
                    @else
                        <span></span>
                    @endif

                    @if($next)
                        <a href="{{ route(config('qwikblog.route.name_prefix') . 'show', $next->slug) }}"
                           class="text-blue-600 hover:text-blue-800 text-right flex-1">
                            <span class="text-sm text-gray-500 block">Next</span>
                            <span class="font-semibold">{{ $next->title }}</span>
                        </a>
                    @endif
                </div>
            </div>

            {{-- Sidebar (1/3) --}}
            <aside class="mt-12 lg:mt-0">
                <div class="lg:sticky lg:top-8">
                    <h3 class="text-xl font-bold mb-4">All Posts</h3>
                    <nav class="space-y-3">
                        @foreach($allPosts as $sidebarPost)
                            <a href="{{ route(config('qwikblog.route.name_prefix') . 'show', $sidebarPost->slug) }}"
                               class="block text-sm hover:text-blue-600 transition
                                      {{ $sidebarPost->slug === $post->slug ? 'text-blue-600 font-semibold' : 'text-gray-600' }}">
                                {{ $sidebarPost->title }}
                            </a>
                        @endforeach
                    </nav>
                </div>
            </aside>
        </div>
    </article>
@endsection
