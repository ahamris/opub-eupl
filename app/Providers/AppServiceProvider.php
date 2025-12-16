<?php

namespace App\Providers;

use App\Services\Typesense\TypesenseSearchService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Register TypesenseSearchService as singleton for connection reuse
        $this->app->singleton(TypesenseSearchService::class, function ($app) {
            return new TypesenseSearchService();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
