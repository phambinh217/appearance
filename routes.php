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
    Route::get('menu', 'MenuController@index')->name('admin.appearance.menu.index');
    Route::get('menu/{id}', 'MenuController@menuEdit')->name('admin.appearance.menu.edit');
    Route::put('menu/{id}', 'MenuController@menuUpdate')->name('admin.appearance.menu.update');
    Route::put('menu/{id}/struct', 'MenuController@menuUpdateStruct')->name('admin.appearance.menu.update.struct');

    Route::post('menu', 'MenuController@menuStore')->name('admin.appearance.menu.store');
    Route::post('menu/{id}', 'MenuController@menuAdd')->name('admin.appearance.menu.add');
    Route::post('menu/{id}/default', 'MenuController@menuAddByDefault')->name('admin.appearance.menu.add-default');
    Route::put('menu-item/{id}', 'MenuController@menuItemUpdate')->name('admin.appearance.menu-item.update');
    Route::delete('menu-item/{id}', 'MenuController@menuItemDestroy')->name('admin.appearance.menu-item.destroy');
    Route::delete('menu-item/{id}', 'MenuController@menuDestroy')->name('admin.appearance.menu.destroy');

    Route::get('style-guide', 'StyleGuideController@index')->name('admin.appearance.style-guide.index');
});
