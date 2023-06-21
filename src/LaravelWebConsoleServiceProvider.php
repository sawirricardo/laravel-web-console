<?php

namespace Sawirricardo\LaravelWebConsole;

use Illuminate\Support\Facades\Route;
use Sawirricardo\LaravelWebConsole\Http\Controllers\WebConsoleController;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class LaravelWebConsoleServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('laravel-web-console')
            ->hasConfigFile()
            ->hasViews();
    }

    public function packageBooted()
    {
        Route::macro('webconsole', function ($url = 'web-console') {
            return Route::name(str($url)->slug()->append('.'))
                ->group(function () use ($url) {
                    Route::get($url, [WebConsoleController::class, 'index'])
                        ->name('index');
                    Route::post($url, [WebConsoleController::class, 'interact'])
                        ->name('interact');
                });
        });
    }
}
