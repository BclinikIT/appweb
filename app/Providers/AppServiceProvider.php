<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;

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
       if(env(key: 'APP_ENV') === 'production') {
            URL::forceScheme(scheme: 'https');
        }
        // \URL::forceScheme(env(key: 'APP_URL_SCHEME', 'https
    }
}
