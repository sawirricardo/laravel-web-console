<?php

namespace Sawirricardo\LaravelWebConsole\Tests;

use Orchestra\Testbench\TestCase as Orchestra;
use Sawirricardo\LaravelWebConsole\LaravelWebConsoleServiceProvider;

class TestCase extends Orchestra
{
    protected function getPackageProviders($app)
    {
        return [
            LaravelWebConsoleServiceProvider::class,
        ];
    }

    protected function getEnvironmentSetUp($app)
    {
        config()->set('app.debug', true);
        config()->set('app.env', 'testing');
    }
}
