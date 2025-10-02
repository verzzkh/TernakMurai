<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\ClaudeVisionService;

class ClaudeVisionServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton(ClaudeVisionService::class, function ($app) {
            return new ClaudeVisionService();
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}