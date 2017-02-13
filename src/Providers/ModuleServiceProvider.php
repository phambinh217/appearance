<?php
/**
 * ModuleAlias: appearance
 * ModuleName: appearance
 * Description: This is the first file run of module. You can assign bootstrap or register module services
 * @author: noname
 * @version: 1.0
 * @package: PhambinhCMS
 */
namespace Phambinh\Appearance\Providers;

use Illuminate\Support\ServiceProvider;

class ModuleServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        // Load views
        $this->loadViewsFrom(__DIR__ . '/../../resources/views', 'Appearance');

        // Load translations
        $this->loadTranslationsFrom(__DIR__ . '/../../resources/lang', 'Appearance');

        // Load migrations
        $this->loadMigrationsFrom(__DIR__ . '/../../database/migrations');

        // Merge configs
        if (\File::exists(__DIR__ . '/../../config/config.php')) {
            $this->mergeConfigFrom(__DIR__ . '/../../config/config.php', 'appearance');
        }

        // Load helper
        if (\File::exists(__DIR__ . '/../../helper/helper.php')) {
            include __DIR__ . '/../../helper/helper.php';
        }

        // Load route
        if (!$this->app->routesAreCached()) {
            if (\File::exists(__DIR__ . '/../../routes.php')) {
                include __DIR__ . '/../../routes.php';
            }
        }

        $this->publishes([
            __DIR__.'/../../assets' => public_path('assets'),
        ], 'public');
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
		\Module::registerFromJsonFile('appearance', __DIR__ .'/../../module.json');
        add_action('admin.init', function () {
            \AdminMenu::register('setting.appearance.menu', [
                'label'     => 'Menu',
                'parent'    =>  'setting.appearance',
                'url'       =>  route('admin.appearance.menu'),
                'icon'      =>  'icon-list',
            ]);

            \AdminMenu::register('setting.appearance', [
                'label'     => 'Cài đặt giao diện',
                'parent'    =>  'setting',
                'url'       =>  route('admin.appearance.menu'),
                'icon'      =>  'icon-grid',
            ]);
            
            \AdminMenu::register('setting.check-version', [
                'label'     => 'Style guide',
                'parent'    =>  'setting',
                'url'       =>  route('admin.appearance.style-guide.index'),
                'icon'      =>  'icon-drop',
            ]);
        });
    }
}
