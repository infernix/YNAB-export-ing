<?php

namespace App\Providers;

use Dotenv\Dotenv;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $dotenv = new Dotenv(base_path());
        $dotenv->load();
    }

    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }
}
