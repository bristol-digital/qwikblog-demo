<?php

namespace App\Http\Controllers;

use App\Services\BlogService;
use Illuminate\View\View;

class BlogController extends Controller
{
    public function __construct(
        private BlogService $blogService
    ) {}

    public function index(): View
    {
        $posts = $this->blogService->getAllPosts();

        return view('blog.index', [
            'posts' => $posts->paginate(12),
        ]);
    }

    public function show(string $slug): View
    {
        $post = $this->blogService->getPostBySlug($slug);

        abort_if(!$post, 404);

        $adjacent = $this->blogService->getAdjacentPosts($post);
        $allPosts = $this->blogService->getAllPosts();

        return view('blog.show', [
            'post' => $post,
            'previous' => $adjacent['previous'],
            'next' => $adjacent['next'],
            'allPosts' => $allPosts,
        ]);
    }
}
