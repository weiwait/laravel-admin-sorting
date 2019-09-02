<?php

namespace Weiwait\Sorting;

use Encore\Admin\Admin;
use Encore\Admin\Grid\Column;
use Illuminate\Support\ServiceProvider;

class SortingServiceProvider extends ServiceProvider
{
    /**
     * {@inheritdoc}
     */
    public function boot(Sorting $extension)
    {
        if (! Sorting::boot()) {
            return ;
        }

        if ($views = $extension->views()) {
            $this->loadViewsFrom($views, 'sorting');
        }

        if ($this->app->runningInConsole() && $assets = $extension->assets()) {
            $this->publishes(
                [$assets => public_path('vendor/weiwait-laravel-admin-ext/sorting')],
                'sorting'
            );
        }

        $this->app->booted(function () {
//            Sorting::routes(__DIR__.'/../routes/web.php');
            $this->loadRoutesFrom(__DIR__ . '/../routes/web.php');
        });

        Admin::booting(function () {
            Column::extend('sorting', SortableDisplay::class);
        });
    }
}
