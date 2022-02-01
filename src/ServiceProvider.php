<?php

declare(strict_types=1);

namespace ApiSkeletons\Laravel\HAL\Doctrine;

use Illuminate\Support\ServiceProvider as LaravelServiceProvider;

class ServiceProvider extends LaravelServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
    }

    /**
     * Bootstrap any package services.
     */
    public function boot(): void
    {
        $this->publishes([
            __DIR__ . '/../config/hal-doctrine.php'
                => config_path('hal-doctrine.php'),
        ]);
    }
}
