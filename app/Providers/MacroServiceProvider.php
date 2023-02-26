<?php

namespace App\Providers;

use Http;
use App\Ace\Http\HttpToolkit;
use Illuminate\Support\ServiceProvider;

class MacroServiceProvider extends ServiceProvider
{
    public function boot()
    {
        Http::macro('getWithProxy', function ($url, $timeout = 20) {
            return HttpToolkit::getWithProxyCallable()(...func_get_args());
        });

        Http::macro('getWithOptions', function ($url, $timeout = 20) {
            return HttpToolkit::getWithOptionCallable()(...func_get_args());
        });
    }
}
