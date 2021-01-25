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
            'bigmom-auth.packages' => array_merge([[
                'name' => 'Excel',
                'description' => 'Import/Export excel files from database.',
                'routes' => [
                    [
                        'title' => 'Import',
                        'name' => 'bigmom-excel.import.getIndex',
                        'permission' => 'excel-admin||excel-import',
                    ],
                    [
                        'title' => 'Export',
                        'name' => 'bigmom-excel.export.getIndex',
                        'permission' => 'excel-admin||excel-export',
                    ],
                    [
                        'title' => 'Admin Export',
                        'name' => 'bigmom-excel.export.getAdminIndex',
                        'permission' => 'excel-admin',
                    ],
                ],
                'permissions' => [
                    'excel-admin',
                    'excel-import',
                    'excel-export',
                ]
            ]], config('bigmom-auth.packages', []))
        ]);

        $this->app->singleton('excel.export.xlsx', function ($app) {
            return new XLSXWriter;
        });

        $this->app->singleton('excel.import.xlsx', function ($app) {
            return new XLSXReader;
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/excel.php' => config_path('excel.php'),
            ]);

            $this->publishes([
                __DIR__.'/../public' => public_path('vendor/excel'),
            ], 'public');
        }

        $this->loadRoutesFrom(__DIR__.'/../routes.php');

        $this->loadViewsFrom(__DIR__.'/../resources/views', 'bigmom-excel');
    }
}
