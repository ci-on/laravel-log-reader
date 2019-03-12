<?php

namespace Cion\LaravelLogReader;

use Illuminate\Support\ServiceProvider as BaseServiceProvider;

class ServiceProvider extends BaseServiceProvider
{
    public function boot()
    {
        $this->loadViewsFrom(__DIR__.'/views', 'logreader');

        $this->loadRoutesFrom(__DIR__.'/routes.php');

        $this->publishes([
            __DIR__.'/config.php' => config_path('logreader.php')
        ], 'config');
    }

    public function register()
    {
        //
    }
}
