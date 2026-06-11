<?php

namespace Rajtika\Mongovity;

use Illuminate\Support\ServiceProvider;
use Rajtika\Mongovity\Constants\Mongovity;

class MongovityServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/Config/config.php', Mongovity::NAMESPACE);

        $this->loadRoutesFrom(__DIR__ . '/Routes/web.php');

        $this->loadViewsFrom(__DIR__ . '/Resources/views', Mongovity::NAMESPACE);

        $this->loadMigrationsFrom(__DIR__ . '/Migrations');
    }

    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/Config/config.php' => config_path(Mongovity::NAMESPACE . '.php'),
            ], ['mongovity-config', 'config']);

            $this->publishes([
                __DIR__ . '/Migrations/' => database_path('migrations'),
            ], ['mongovity-migrations', 'migrations']);

            $this->publishes([
                __DIR__ . '/Resources/views' => resource_path('views/vendor/' . Mongovity::NAMESPACE),
            ], 'mongovity-views');
        }
    }
}
