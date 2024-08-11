<?php

namespace RealRashid\Cliq\Tests;

use RealRashid\Cliq\CliqServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

abstract class TestCase extends  Orchestra
{
    protected function setUp(): void
    {
        parent::setUp();
    }

    protected function getPackageProviders($app)
    {
        return [
            CliqServiceProvider::class,
        ];
    }

    protected function defineEnvironment($app): void
    {
        $app['config']->set('database.default', 'sqlite');

        $app['config']->set('database.connections.sqlite', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]);
    }
}
