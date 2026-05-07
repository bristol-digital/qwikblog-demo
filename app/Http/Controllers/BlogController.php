<?php

namespace App\Http\Controllers;

use App\Services\BlogService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BlogController extends Controller
{
    public function __construct(
        private BlogService $blogService
    ) {}

    public function index(Request $request): View
    {
        $posts = $this->blogService->getPublishedPosts();

        // Optional ?category= and ?tag= filters. They stack: passing both
        // narrows to posts that match BOTH (intersection, not union).
        $activeCategory = $request->query('category');
        $activeTag = $request->query('tag');

        if ($activeCategory) {
            $posts = $posts->filter(fn($p) => in_array($activeCategory, $p->categories, true));
        }
        if ($activeTag) {
            $posts = $posts->filter(fn($p) => in_array($activeTag, $p->tags, true));
        }

        return view('blog.index', [
            'posts' => $posts->values()->paginate(12),
            'allCategories' => $this->blogService->getPublishedCategories(),
            'allTags' => $this->blogService->getPublishedTags(),
            'activeCategory' => $activeCategory,
            'activeTag' => $activeTag,
        ]);
    }

    public function show(string $slug): View
    {
        // Logged-in admins can preview scheduled posts via the public URL.
        $isAdmin = session('admin_authenticated');

        $post = $isAdmin
            ? $this->blogService->getPostBySlug($slug)
            : $this->blogService->getPublishedPostBySlug($slug);

        abort_if(!$post, 404);

        $adjacent = $this->blogService->getAdjacentPublishedPosts($post);
        $allPosts = $this->blogService->getPublishedPosts();

        return view('blog.show', [
            'post' => $post,
            'previous' => $adjacent['previous'],
            'next' => $adjacent['next'],
            'allPosts' => $allPosts,
        ]);
    }
}
