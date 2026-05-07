<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\BlogController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public blog (front end)
|--------------------------------------------------------------------------
*/
Route::prefix('blog')->name('blog.')->group(function () {
    Route::get('/', [BlogController::class, 'index'])->name('index');
    Route::get('/{slug}', [BlogController::class, 'show'])->name('show');
});

/*
|--------------------------------------------------------------------------
| Admin (file-based, .env credentials)
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('login', [AdminController::class, 'loginForm'])->name('login');
    Route::post('login', [AdminController::class, 'login']);
    Route::post('logout', [AdminController::class, 'logout'])->name('logout');

    Route::middleware('admin')->group(function () {
        Route::get('/', fn() => redirect()->route('admin.posts.index'));

        Route::prefix('posts')->name('posts.')->group(function () {
            // Index is a full-page Livewire component so the status badges
            // and countdowns update via wire:poll without a manual refresh.
            Route::get('/', \App\Livewire\Admin\PostsIndex::class)->name('index');

            Route::get('create', [AdminController::class, 'create'])->name('create');
            Route::post('/', [AdminController::class, 'store'])->name('store');
            Route::get('{slug}/edit', [AdminController::class, 'edit'])->name('edit');
            Route::put('{slug}', [AdminController::class, 'update'])->name('update');
            Route::delete('{slug}', [AdminController::class, 'destroy'])->name('destroy');
            Route::get('{slug}/images', \App\Livewire\Admin\BlogImages::class)->name('images');
        });
    });
});
