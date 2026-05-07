<?php

namespace App\Services;

use App\ValueObjects\BlogPost;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class BlogService
{
    private string $postsPath;

    public function __construct()
    {
        $this->postsPath = config('qwikblog.posts_path') ?: resource_path('posts');
    }

    public function getAllPosts(): Collection
    {
        return Cache::remember(
            'blog.all_posts',
            config('qwikblog.cache_duration', 3600),
            function () {
                File::ensureDirectoryExists($this->postsPath);

                $files = File::glob($this->postsPath . '/*.md');

                return collect($files)
                    ->map(fn($file) => BlogPost::fromFile($file))
                    ->sortByDesc('date')
                    ->values();
            }
        );
    }

    public function getPublishedPosts(): Collection
    {
        return $this->getAllPosts()
            ->filter(fn($p) => $p->isPublished())
            ->values();
    }

    public function getPostBySlug(string $slug): ?BlogPost
    {
        return $this->getAllPosts()->firstWhere('slug', $slug);
    }

    public function getPublishedPostBySlug(string $slug): ?BlogPost
    {
        return $this->getPublishedPosts()->firstWhere('slug', $slug);
    }

    public function getAdjacentPublishedPosts(BlogPost $currentPost): array
    {
        $posts = $this->getPublishedPosts();
        $currentIndex = $posts->search(fn($post) => $post->slug === $currentPost->slug);

        if ($currentIndex === false) {
            return ['previous' => null, 'next' => null];
        }

        return [
            'previous' => $posts->get($currentIndex - 1),
            'next' => $posts->get($currentIndex + 1),
        ];
    }

    /* ---------------------------------------------------------------------
     | Taxonomy — used by both admin (suggestion lists) and front-end (filters)
     | -------------------------------------------------------------------*/

    /**
     * Distinct categories from every post on disk (admin scope —
     * includes scheduled posts so the form suggests them).
     *
     * @return array<int,string>
     */
    public function getAllCategories(): array
    {
        return $this->collectFrom($this->getAllPosts(), 'categories');
    }

    /**
     * @return array<int,string>
     */
    public function getAllTags(): array
    {
        return $this->collectFrom($this->getAllPosts(), 'tags');
    }

    /**
     * Distinct categories from published posts only — these feed the public
     * blog index's filter chips so visitors don't click ghost filters that
     * lead to empty result sets.
     *
     * @return array<int,string>
     */
    public function getPublishedCategories(): array
    {
        return $this->collectFrom($this->getPublishedPosts(), 'categories');
    }

    /**
     * @return array<int,string>
     */
    public function getPublishedTags(): array
    {
        return $this->collectFrom($this->getPublishedPosts(), 'tags');
    }

    /**
     * @return array<int,string>
     */
    private function collectFrom(Collection $posts, string $field): array
    {
        return $posts
            ->pluck($field)
            ->flatten()
            ->filter(fn($v) => is_string($v) && trim($v) !== '')
            ->unique()
            ->sort(SORT_FLAG_CASE | SORT_NATURAL)
            ->values()
            ->all();
    }

    public function clearCache(): void
    {
        Cache::forget('blog.all_posts');
    }

    /* ---------------------------------------------------------------------
     | CRUD — used by the admin
     | -------------------------------------------------------------------*/

    public function create(array $data): ?string
    {
        File::ensureDirectoryExists($this->postsPath);

        $date = $this->parseDate($data['date'] ?? null);
        $slug = $this->ensureUniqueSlug(Str::slug($data['title'] ?? 'untitled'));

        $filename = BlogPost::buildFilename($date, $slug);
        $path = $this->postsPath . '/' . $filename;

        $payload = $this->buildPayload($data, $date);

        if (File::put($path, BlogPost::toMarkdown($payload, $data['body'] ?? '')) === false) {
            return null;
        }

        $this->refreshBlog();
        return $slug;
    }

    public function update(string $currentSlug, array $data): ?string
    {
        $existing = $this->getPostBySlug($currentSlug);
        if (!$existing) {
            return null;
        }

        $date = $this->parseDate($data['date'] ?? null) ?? $existing->date;

        $newSlug = Str::slug($data['title'] ?? $existing->title);
        if ($newSlug === '') {
            $newSlug = $existing->slug;
        }

        $newFilename = BlogPost::buildFilename($date, $newSlug);
        $newPath = $this->postsPath . '/' . $newFilename;

        if ($newPath !== $existing->filepath && File::exists($newPath)) {
            $newSlug = $this->ensureUniqueSlug($newSlug, $existing->slug);
            $newFilename = BlogPost::buildFilename($date, $newSlug);
            $newPath = $this->postsPath . '/' . $newFilename;
        }

        $payload = $this->buildPayload($data, $date);
        $body = $data['body'] ?? $existing->rawBody();

        if (File::put($newPath, BlogPost::toMarkdown($payload, $body)) === false) {
            return null;
        }

        if ($newPath !== $existing->filepath && File::exists($existing->filepath)) {
            File::delete($existing->filepath);
        }

        if ($newSlug !== $existing->slug) {
            $this->moveImageDirectory($existing->slug, $newSlug);
        }

        $this->refreshBlog();
        return $newSlug;
    }

    public function delete(string $slug): bool
    {
        $post = $this->getPostBySlug($slug);
        if (!$post) {
            return false;
        }

        $deleted = File::delete($post->filepath);
        if ($deleted) {
            $imageDir = public_path('images/blog/' . $slug);
            if (File::isDirectory($imageDir)) {
                File::deleteDirectory($imageDir);
            }
            $this->refreshBlog();
        }
        return $deleted;
    }

    /* ---------------------------------------------------------------------
     | Internal helpers
     | -------------------------------------------------------------------*/

    private function refreshBlog(): void
    {
        Artisan::call('blog:refresh');
    }

    private function parseDate(mixed $date): ?Carbon
    {
        if (!$date) {
            return Carbon::now();
        }
        try {
            return Carbon::parse($date);
        } catch (\Throwable) {
            return Carbon::now();
        }
    }

    private function ensureUniqueSlug(string $slug, ?string $ignoreSlug = null): string
    {
        $original = $slug ?: 'untitled';
        $candidate = $original;
        $i = 2;

        $existingSlugs = $this->getAllPosts()
            ->pluck('slug')
            ->reject(fn($s) => $ignoreSlug !== null && $s === $ignoreSlug)
            ->values();

        while ($existingSlugs->contains($candidate)) {
            $candidate = $original . '-' . $i;
            $i++;
        }

        return $candidate;
    }

    private function buildPayload(array $data, Carbon $date): array
    {
        // Categories and tags arrive as arrays from AdminController (which
        // splits the comma-separated form input). Other scalars trim/cast as before.
        return [
            'title' => trim($data['title'] ?? ''),
            'subtitle' => trim($data['subtitle'] ?? ''),
            'summary' => trim($data['summary'] ?? ''),
            'categories' => is_array($data['categories'] ?? null) ? $data['categories'] : [],
            'tags' => is_array($data['tags'] ?? null) ? $data['tags'] : [],
            'hero_image' => trim($data['hero_image'] ?? ''),
            'author' => trim($data['author'] ?? ''),
            'date' => $date->format('Y-m-d H:i:s'),
        ];
    }

    private function moveImageDirectory(string $oldSlug, string $newSlug): void
    {
        $oldDir = public_path('images/blog/' . $oldSlug);
        $newDir = public_path('images/blog/' . $newSlug);

        if (File::isDirectory($oldDir) && !File::isDirectory($newDir)) {
            File::moveDirectory($oldDir, $newDir);
        }
    }
}
