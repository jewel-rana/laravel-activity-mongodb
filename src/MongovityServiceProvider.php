<?php
namespace Rajtika\Mongovity;

use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Support\ServiceProvider;
use Rajtika\Mongovity\Constants\Mongovity;
use Rajtika\Mongovity\Http\Controllers\MongoActivityController;

class MongovityServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     * @throws BindingResolutionException
     */
    public function register(): void
    {
        //Register Controllers
        $this->app->make(MongoActivityController::class);

        //Register Routes
        $this->loadRoutesFrom(__DIR__ . '/Routes/web.php');

        //Load Configs
        $this->mergeConfigFrom(__DIR__ . '/Config/config.php', Constants\Mongovity::NAMESPACE);

        //Load Views
        $this->loadViewsFrom(__DIR__.'/Resources/views', Constants\Mongovity::NAMESPACE);

        //Load Migrations
        $this->loadMigrationsFrom(__DIR__ . '/Migrations');
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot(): void
    {
        $this->publishes([
            __DIR__ . '/Config/config.php' => config_path(Mongovity::NAMESPACE . '.php'),
        ], 'config');
    }
}
