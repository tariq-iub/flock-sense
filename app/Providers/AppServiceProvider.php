<?php

namespace App\Providers;

use Illuminate\Support\Facades\Blade;
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
        // Admin check
        Blade::if('admin', function () {
            return auth()->check() && auth()->user()->hasRole('admin');
        });

        // Owner check
        Blade::if('owner', function () {
            return auth()->check() && auth()->user()->hasRole('owner');
        });

        // Manager check
        Blade::if('manager', function () {
            return auth()->check() && auth()->user()->hasRole('manager');
        });
    }
}
