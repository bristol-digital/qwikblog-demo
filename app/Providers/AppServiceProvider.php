<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Add paginate method to collections
        Collection::macro('paginate', function ($perPage = 15, $page = null, $options = []) {
            $page = $page ?: (LengthAwarePaginator::resolveCurrentPage() ?: 1);

            return new LengthAwarePaginator(
                $this->forPage($page, $perPage)->values(),
                $this->count(),
                $perPage,
                $page,
                $options
            );
        });
    }
}
