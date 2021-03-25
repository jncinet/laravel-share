<?php

namespace Jncinet\LaravelShare;

use Illuminate\Support\ServiceProvider;

class ShareServiceProvider extends ServiceProvider
{
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->loadMigrationsFrom(__DIR__ . '/../migrations/');
        }

        $this->mergeConfigFrom(
            __DIR__ . '/../config/share.php',
            'share'
        );

        $this->publishes([
            __DIR__ . '/../config/share.php' => config_path('share.php'),
            __DIR__ . '/../migrations/' => database_path('migrations'),
        ]);
    }
}