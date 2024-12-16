<?php

namespace App\Providers;

use Axilweb\Vaccine\Interfaces\UserVaccinationDetailsRepositoryInterface;
use Axilweb\Vaccine\Repositories\UserVaccinationDetailsRepository;
use Illuminate\Support\ServiceProvider;

class UserVaccinationDetailsServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(UserVaccinationDetailsRepositoryInterface::class, UserVaccinationDetailsRepository::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
