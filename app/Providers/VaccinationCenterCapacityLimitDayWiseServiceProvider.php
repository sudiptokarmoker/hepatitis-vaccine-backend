<?php

namespace App\Providers;

use Axilweb\Vaccine\Interfaces\VaccinationCenterCapacityLimitDayWiseRepositoryInterface;
use Axilweb\Vaccine\Repositories\VaccinationCenterCapacityLimitDayWiseRepository;
use Illuminate\Support\ServiceProvider;

class VaccinationCenterCapacityLimitDayWiseServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(VaccinationCenterCapacityLimitDayWiseRepositoryInterface::class, VaccinationCenterCapacityLimitDayWiseRepository::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
