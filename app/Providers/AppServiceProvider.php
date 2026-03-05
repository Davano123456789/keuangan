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
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        \Illuminate\Pagination\Paginator::useBootstrapFour();

        if (str_contains(request()->header('X-Forwarded-Proto'), 'https') || 
            str_contains(request()->header('Host'), 'ngrok-free.app')) {
            \Illuminate\Support\Facades\URL::forceScheme('https');
        }
    }
}
