<?php

namespace RealRashid\Cliq;

use Illuminate\Support\ServiceProvider;
use RealRashid\Cliq\Command\InstallCommand;

class CliqServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->bootForConsole();
        }
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        // Merge configuration file
        $this->mergeConfigFrom(__DIR__.'/../config/cliq.php', 'cliq');

        // Register the main class to use with the facade
        $this->app->singleton('cliq', function () {
            return new Cliq;
        });
    }

    /**
     * Console-specific booting.
     */
    protected function bootForConsole(): void
    {
        // Publish configuration file
        $this->publishes([
            __DIR__.'/../config/cliq.php' => config_path('cliq.php'),
        ], 'cliq-config');


        // Register the install command
        $this->commands([
            InstallCommand::class,
        ]);
    }
}
