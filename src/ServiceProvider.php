<?php

declare(strict_types=1);

namespace ApiSkeletons\Laravel\HAL\Doctrine;

use Illuminate\Support\ServiceProvider as LaravelServiceProvider;

use function config_path;

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
            $this->getConfigPath() => config_path('hal-doctrine.php'),
        ], 'config');
    }

    protected function getConfigPath(): string
    {
        return __DIR__ . '/../config/hal-doctrine.php';
    }
}
