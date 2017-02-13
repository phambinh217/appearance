<?php 
/**
 * ModuleAlias: appearance
 * ModuleName: appearance
 * Description: Route of module appearance.This bellow have 3 type route: normal rotue, admin route, api route
 * to use, you have to uncommnet it
 * @author: noname
 * @version: 1.0
 * @package: PhambinhCMS
 */

Route::group(['module' => 'appearance', 'namespace' => 'Phambinh\Appearance\Http\Controllers\Admin', 'middleware' => ['web'], 'prefix' => 'admin/appearance'], function () {
    Route::get('menu', 'MenuController@menu')->name('admin.appearance.menu');
    Route::post('menu', 'MenuController@menuStore')->name('admin.appearance.menu.store');
    Route::post('menu/{id}', 'MenuController@menuAdd')->name('admin.appearance.menu.add');
    Route::post('menu/{id}/default', 'MenuController@menuAddByDefault')->name('admin.appearance.menu.add-default');
    Route::put('menu/{id}/struct', 'MenuController@menuUpdateStruct')->name('admin.appearance.menu.struct.update');
    Route::put('menu-item/{id}', 'MenuController@menuItemUpdate')->name('admin.appearance.menu-item.update');
    Route::delete('menu-item/{id}', 'MenuController@menuItemDestroy')->name('admin.appearance.menu-item.destroy');

    Route::get('style-guide', 'StyleGuideController@index')->name('admin.appearance.style-guide.index');
});
