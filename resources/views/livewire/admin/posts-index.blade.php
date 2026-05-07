{{--
    Livewire root element — wire:poll triggers an automatic re-render every
    30 seconds. Status badge ("Scheduled · in N min" → "Published") updates
    without a page refresh.
--}}
<div wire:poll.30s>
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Blog Posts</h1>
        <a
            href="{{ route('admin.posts.create') }}"
            class="bg-slate-800 text-white px-4 py-2 rounded hover:bg-slate-900 transition-colors"
        >
            + New Post
        </a>
    </div>

    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="w-full">
            <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Title</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Author</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Categories</th>
                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
            </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
            @forelse($posts as $post)
                @php($postCategories = $post->categories ?? [])
                @php($postTags = $post->tags ?? [])
                <tr wire:key="post-{{ $post->slug }}" class="hover:bg-gray-50 {{ $post->isScheduled() ? 'bg-amber-50/30' : '' }}">
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        <div>{{ $post->date->format('Y-m-d') }}</div>
                        <div class="text-xs text-gray-400">{{ $post->date->format('H:i') }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @if($post->isScheduled())
                            <span class="text-xs bg-amber-100 text-amber-800 px-2 py-1 rounded font-medium">
                                Scheduled
                            </span>
                            <div class="text-xs text-gray-500 mt-1">{{ $post->date->diffForHumans() }}</div>
                        @else
                            <span class="text-xs bg-green-100 text-green-800 px-2 py-1 rounded font-medium">
                                Published
                            </span>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        <div class="font-medium text-gray-900">{{ $post->title }}</div>
                        <div class="text-sm text-gray-500">{{ \Illuminate\Support\Str::limit($post->summary, 80) }}</div>
                        @if(count($postTags) > 0)
                            <div class="mt-1 flex flex-wrap gap-1">
                                @foreach($postTags as $tag)
                                    <span class="text-xs bg-emerald-50 text-emerald-700 px-1.5 py-0.5 rounded">#{{ $tag }}</span>
                                @endforeach
                            </div>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                        {{ $post->author ?: '—' }}
                    </td>
                    <td class="px-6 py-4">
                        @if(count($postCategories) > 0)
                            <div class="flex flex-wrap gap-1">
                                @foreach($postCategories as $cat)
                                    <span class="text-xs bg-slate-100 text-slate-800 px-2 py-1 rounded">{{ $cat }}</span>
                                @endforeach
                            </div>
                        @else
                            <span class="text-gray-400 text-xs">—</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm">
                        <a
                            href="{{ route('blog.show', $post->slug) }}"
                            target="_blank"
                            class="text-gray-500 hover:text-gray-700 mr-3"
                            title="{{ $post->isScheduled() ? 'Preview (scheduled — public visitors see 404)' : 'View' }}"
                        >
                            {{ $post->isScheduled() ? 'Preview' : 'View' }}
                        </a>
                        <a
                            href="{{ route('admin.posts.images', $post->slug) }}"
                            class="text-blue-600 hover:text-blue-800 mr-3"
                            title="Manage images"
                        >
                            Images ({{ count(\App\Services\BlogImageService::getImagesForPost($post->slug)) }})
                        </a>
                        <a
                            href="{{ route('admin.posts.edit', $post->slug) }}"
                            class="text-slate-700 hover:text-slate-900 mr-3"
                        >
                            Edit
                        </a>
                        <form
                            method="POST"
                            action="{{ route('admin.posts.destroy', $post->slug) }}"
                            class="inline"
                            onsubmit="return confirm('Delete this post and all its images? This cannot be undone.')"
                        >
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-800">
                                Delete
                            </button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                        No posts yet.
                        <a href="{{ route('admin.posts.create') }}" class="text-slate-700 hover:underline">
                            Write your first post
                        </a>
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>

    <p class="text-sm text-gray-500 mt-4">
        Posts with a future date are scheduled — they go live automatically the moment the date passes.
        This page refreshes itself every 30 seconds, so countdowns and statuses stay current.
    </p>
</div>
