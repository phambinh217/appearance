<?php 

namespace Phambinh\Appearance\Providers;

use Illuminate\Support\Facades\Route;
use App\Providers\RouteServiceProvider as ServiceProvider;

class RoutingServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     * Include helper file in helpers folder
     * @return void
     */
    public function boot()
    {
        parent::boot();

        Route::pattern('menu', '[0-9]+');
        Route::pattern('menu_item', '[0-9]+');

        if (!$this->app->routesAreCached()) {
            if (\File::exists(__DIR__ . '/../../routes.php')) {
                include __DIR__ . '/../../routes.php';
            }
        }
    }

    /**
     * Register application services
     * @return void
     */
    public function register()
    {
        //
    }
}
