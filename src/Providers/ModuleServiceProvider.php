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

        $this->registerPolicies();
    }

    public function registerPolicies()
    {
        \AccessControl::define('Giao diện - Xem danh sách menu', 'admin.appearance.menu.index');
        \AccessControl::define('Giao diện - Thêm menu mới', 'admin.appearance.menu.create');
        \AccessControl::define('Giao diện - Chỉnh sửa menu', 'admin.appearance.menu.edit');
        \AccessControl::define('Giao diện - Xóa menu', 'admin.appearance.menu.destroy');
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        \Module::registerFromJsonFile('appearance', __DIR__ .'/../../module.json');
        $this->registerAdminMenu();
        \Menu::registerLocation([
            'id' => 'master-menu',
            'name' => 'Master menu',
        ]);
    }

    private function registerAdminMenu()
    {
        add_action('admin.init', function () {
            if (\Auth::user()->can('admin.appearance.menu.index')) {
                \AdminMenu::register('setting.appearance', [
                    'label'     => 'Cài đặt giao diện',
                    'parent'    =>  'setting',
                    'url'       =>  route('admin.appearance.menu.index'),
                    'icon'      =>  'icon-grid',
                ]);
            }
            if (\Auth::user()->can('admin.appearance.menu.index')) {
                \AdminMenu::register('setting.appearance.menu', [
                    'label'     => 'Menu',
                    'parent'    =>  'setting.appearance',
                    'url'       =>  route('admin.appearance.menu.index'),
                    'icon'      =>  'icon-list',
                ]);
            }
            if (\Auth::user()->can('admin')) {
                \AdminMenu::register('setting.check-version', [
                    'label'     => 'Style guide',
                    'parent'    =>  'setting',
                    'url'       =>  route('admin.appearance.style-guide.index'),
                    'icon'      =>  'icon-drop',
                ]);
            }
        });
    }
}
