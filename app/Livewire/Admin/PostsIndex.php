<?php

namespace App\Livewire\Admin;

use App\Services\BlogService;
use Livewire\Component;

/**
 * Full-page Livewire component backing /admin/posts.
 *
 * The wire:poll.30s directive on the view's root element triggers a re-render
 * every 30 seconds, which is what makes "Scheduled · in 2 minutes" tick down
 * and flip to "Published" without the admin manually refreshing the page.
 *
 * No state is held on the component itself — each render fetches fresh posts
 * from BlogService, so the cache (cleared via blog:refresh on every admin
 * write) is the single source of truth.
 */
class PostsIndex extends Component
{
    public function render(BlogService $blogService)
    {
        return view('livewire.admin.posts-index', [
            'posts' => $blogService->getAllPosts(),
        ])->layout('components.admin.layout', ['title' => 'Posts']);
    }
}
