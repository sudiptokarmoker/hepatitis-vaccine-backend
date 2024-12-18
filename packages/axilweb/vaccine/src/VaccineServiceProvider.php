<?php

namespace Axilweb\Vaccine;

use Axilweb\Vaccine\Events\VaccineEmailNotificationToEvent;
use Axilweb\Vaccine\Listeners\EmailNotificationListener;
use Illuminate\Support\ServiceProvider;

class VaccineServiceProvider extends ServiceProvider
{
    protected $listen = [
        VaccineEmailNotificationToEvent::class => [
            EmailNotificationListener::class
        ]
    ];
    public function boot()
    {
        $this->loadRoutesFrom(__DIR__.'/routes/api.php');
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        $this->publishes([
            __DIR__.'/../config/vaccine.php' => config_path('vaccine.php'),
        ], 'vaccine-config');
    }
    /**
     * Register any application services.
     */
    public function register()
    {
        // Merge config
        $this->mergeConfigFrom(__DIR__.'/../config/vaccine.php', 'vaccine');
    }
}
