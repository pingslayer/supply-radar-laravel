<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(\App\Services\DisruptionManager::class, function ($app) {
            $manager = new \App\Services\DisruptionManager();
            
            // Register real-world sources
            $manager->addSource(new \App\Services\Sources\GdacsSource());
            $manager->addSource(new \App\Services\Sources\ReliefWebSource());
            
            // Optionally keep the mock source for testing if needed
            // $manager->addSource(new \App\Services\Sources\MockTestingSource());
            
            return $manager;
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
