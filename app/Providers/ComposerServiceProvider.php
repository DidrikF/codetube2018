<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class ComposerServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    //This pease of code is executed at statup as this is in a service provider. We must remember to register the service provider in config/app.php
    public function boot()
    {
        view()->composer('layouts.partials._navigation',
            \App\Http\ViewComposers\NavigationComposer::class
        );
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
