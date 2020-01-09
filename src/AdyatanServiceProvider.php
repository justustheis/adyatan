<?php

namespace JustusTheis\Adyatan;

use Illuminate\Support\ServiceProvider;

class AdyatanServiceProvider extends ServiceProvider
{
    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {
        // Publishing is only necessary when using the CLI.
        if ($this->app->runningInConsole()) {
            $this->bootForConsole();
        }
    }

    /**
     * Register any package services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/adyatan.php', 'adyatan');
    }

    /**
     * Console-specific booting.
     *
     * @return void
     */
    protected function bootForConsole()
    {
        // Publishing the configuration file.
        $this->publishes([
            __DIR__ . '/../config/adyatan.php' => config_path('adyatan.php'),
        ], 'adyatan.config');

        // Registering package commands.
        $this->commands([
            Adyatan::class
        ]);
    }
}
