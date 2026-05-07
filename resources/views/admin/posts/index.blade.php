<x-admin.layout title="Posts">
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
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
            </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
            @forelse($posts as $post)
                <tr class="hover:bg-gray-50 {{ $post->isScheduled() ? 'bg-amber-50/30' : '' }}">
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
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                        {{ $post->author ?: '—' }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="text-xs bg-slate-100 text-slate-800 px-2 py-1 rounded">
                            {{ $post->category }}
                        </span>
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
        Posts with a future date are scheduled — they go live automatically the moment the date passes. No cron required.
    </p>
</x-admin.layout>
