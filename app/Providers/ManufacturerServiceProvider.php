<?php

namespace App\Providers;

use Axilweb\Vaccine\Interfaces\ManufacturerRepositoryInterface;
use Axilweb\Vaccine\Repositories\ManufacturerRepository;
use Illuminate\Support\ServiceProvider;

class ManufacturerServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(ManufacturerRepositoryInterface::class, ManufacturerRepository::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
