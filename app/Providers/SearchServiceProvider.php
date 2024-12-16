<?php

namespace App\Providers;

use Axilweb\Vaccine\Interfaces\SearchRepositoryInterface;
use Axilweb\Vaccine\Repositories\SearchRepository;
use Illuminate\Support\ServiceProvider;

class SearchServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(SearchRepositoryInterface::class, SearchRepository::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
