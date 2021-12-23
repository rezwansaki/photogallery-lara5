<?php

namespace App\Providers;

use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema; //Bug Fix for Laravel5.4 and Laravel5.5 

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //Bug Fix for Laravel5.4 and Laravel5.5 
        Schema::defaultStringLength(191);
        //when this project will be 'production' mode then it convert http into https
        if ($this->app->environment('production')) {
            URL::forceScheme('https');
        }
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
