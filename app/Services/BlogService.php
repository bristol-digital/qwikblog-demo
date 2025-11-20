<?php

namespace App\Services;

use App\ValueObjects\BlogPost;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;

class BlogService
{
    private string $postsPath;

    public function __construct()
    {
        $this->postsPath = resource_path('posts');
    }

    public function getAllPosts(): Collection
    {
        return Cache::remember('blog.all_posts', 3600, function () {
            $files = File::glob($this->postsPath . '/*.md');

            return collect($files)
                ->map(fn($file) => BlogPost::fromFile($file))
                ->sortByDesc('date')
                ->values();
        });
    }

    public function getPostBySlug(string $slug): ?BlogPost
    {
        return $this->getAllPosts()->firstWhere('slug', $slug);
    }

    public function getAdjacentPosts(BlogPost $currentPost): array
    {
        $posts = $this->getAllPosts();
        $currentIndex = $posts->search(fn($post) => $post->slug === $currentPost->slug);

        return [
            'previous' => $posts->get($currentIndex - 1),
            'next' => $posts->get($currentIndex + 1),
        ];
    }

    public function clearCache(): void
    {
        Cache::forget('blog.all_posts');
    }
}
