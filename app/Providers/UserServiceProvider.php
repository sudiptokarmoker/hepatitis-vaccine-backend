<?php

namespace App\Providers;

use Axilweb\Vaccine\Interfaces\UsersRepositoryInterface;
use Axilweb\Vaccine\Repositories\UsersRepository;
use Illuminate\Support\ServiceProvider;

class UserServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(UsersRepositoryInterface::class, UsersRepository::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
