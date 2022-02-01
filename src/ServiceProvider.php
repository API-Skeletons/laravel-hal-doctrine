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
//        $this->app->singleton(ApiKeyService::class, static function ($app) {
//            return new ApiKeyService();
//        });
    }

    /**
     * Bootstrap any application services.
     */
    // phpcs:disable SlevomatCodingStandard.ControlStructures.EarlyExit.EarlyExitNotUsed
    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->commands([]);
        }
    }
}
