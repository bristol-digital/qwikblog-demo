@extends(config('qwikblog.layout', 'layouts.app'))

@section('title', 'Blog')

@section('content')
    <div class="container mx-auto px-4 py-12">
        <h1 class="text-4xl font-bold mb-12">Blog</h1>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($posts as $post)
                <article class="group">
                    <a href="{{ route(config('qwikblog.route.name_prefix') . 'show', $post->slug) }}" class="block">
                        @if($post->heroImage)
                            <img src="{{ $post->heroImage }}"
                                 alt="{{ $post->title }}"
                                 class="w-full h-48 object-cover rounded-lg mb-4 group-hover:opacity-90 transition">
                        @endif

                        <div class="space-y-2">
                            <span class="text-sm text-gray-500">{{ $post->category }}</span>
                            <h2 class="text-2xl font-bold group-hover:text-blue-600 transition">
                                {{ $post->title }}
                            </h2>
                            <p class="text-gray-600">{{ $post->subtitle }}</p>
                            <time class="text-sm text-gray-400">
                                {{ $post->date->format('F j, Y') }}
                            </time>
                        </div>
                    </a>
                </article>
            @endforeach
        </div>

        <div class="mt-12">
            {{ $posts->links() }}
        </div>
    </div>
@endsection
