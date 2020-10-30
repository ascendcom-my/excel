<?php

namespace Bigmom\Excel\Providers;

use Bigmom\Excel\Services\Readers\XLSXReader;
use Bigmom\Excel\Services\Writers\XLSXWriter;
use Bigmom\Excel\Facades\Hook;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class ExcelServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        config([
            'auth.guards.excel' => array_merge([
                'driver' => config('excel.guard.driver', 'session'),
                'provider' => config('excel.guard.provider', 'users'),
            ], config('auth.guards.excel', [])),
        ]);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../config/excel.php' => config_path('excel.php'),
        ]);

        $this->loadRoutesFrom(__DIR__.'/../routes.php');

        $this->loadViewsFrom(__DIR__.'/../resources/views', 'excel');

        $this->publishes([
            __DIR__.'/../public' => public_path('vendor/excel'),
        ], 'public');

        $this->app->singleton('excel.export.xlsx', function ($app) {
            return new XLSXWriter;
        });

        $this->app->singleton('excel.import.xlsx', function ($app) {
            return new XLSXReader;
        });

        $this->gate();
    }

    /**
     * Register the Hook UI gate.
     *
     * This gate determines who can access VE Editor in non-local environments.
     *
     * @return void
     */
    protected function gate()
    {
        Gate::define('excel-admin', function ($user = null) {
            return in_array(optional($user)->email, config('excel.allowed-users.admin'));
        });
        Gate::define('excel-export', function ($user = null) {
            return in_array(optional($user)->email, config('excel.allowed-users.export'));
        });
        Gate::define('excel-import', function ($user = null) {
            return in_array(optional($user)->email, config('excel.allowed-users.import'));
        });
    }
}
