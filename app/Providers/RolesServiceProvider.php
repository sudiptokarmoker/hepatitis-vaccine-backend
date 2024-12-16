<?php

namespace App\Providers;

use Axilweb\Vaccine\Interfaces\RolesRepositoryInterface;
use Axilweb\Vaccine\Repositories\RolesRepository;
use Illuminate\Support\ServiceProvider;

class RolesServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(RolesRepositoryInterface::class, RolesRepository::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
