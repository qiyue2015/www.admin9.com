<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Jenssegers\Optimus\Optimus;

class OptimusServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(Optimus::class, function ($app) {
            return new Optimus(
                config('optimus.prime'),
                config('optimus.inverse'),
                config('optimus.random')
            );
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
    }
}
