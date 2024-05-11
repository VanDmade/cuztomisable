<?php

namespace VanDmade\Cuztomisable;

use Illuminate\Support\ServiceProvider;

class CuztomisableServiceProvider extends ServiceProvider
{

    public function boot(): void
    {
        $this->loadRoutesFrom(__DIR__.'/../routes.php');
    }

    public function register(): void
    {
        $this->publishes([
            __DIR__.'/../config/account.php' => config_path('cuztomisable/account.php'),
            __DIR__.'/../config/login.php' => config_path('cuztomisable/login.php'),
            __DIR__.'/../resources/views' => resource_path('views/cuztomisable'),
            __DIR__.'/../resources/languages/en' => $this->app->langPath('en/cuztomisable'),
            __DIR__.'/../database/migrations' => database_path('migrations/cuztomisable'),
        ]);
    }

}