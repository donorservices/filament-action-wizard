<?php

namespace Donorservices\FilamentActionWizard;

use Donorservices\FilamentActionWizard\Commands\FilamentActionWizard;
use Illuminate\Support\ServiceProvider;

class FilamentActionWizardServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        /*
         * Optional methods to load your package assets
         */
        // $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'filament-action-wizard');
        // $this->loadViewsFrom(__DIR__.'/../resources/views', 'filament-action-wizard');
        // $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        // $this->loadRoutesFrom(__DIR__.'/routes.php');

        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/config.php' => config_path('filament-action-wizard.php'),
            ], 'config');

            $this->commands([
                \Donorservices\FilamentActionWizard\Commands\BuildAction::class,
            ]);

            // Publishing the views.
            /*$this->publishes([
                __DIR__.'/../resources/views' => resource_path('views/vendor/filament-action-wizard'),
            ], 'views');*/

            // Publishing assets.
            /*$this->publishes([
                __DIR__.'/../resources/assets' => public_path('vendor/filament-action-wizard'),
            ], 'assets');*/

            // Publishing the translation files.
            /*$this->publishes([
                __DIR__.'/../resources/lang' => resource_path('lang/vendor/filament-action-wizard'),
            ], 'lang');*/

            // Registering package commands.
            // $this->commands([]);
        }
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        // Automatically apply the package configuration
        $this->mergeConfigFrom(__DIR__.'/../config/config.php', 'filament-action-wizard');

        // Register the main class to use with the facade

    }
}
