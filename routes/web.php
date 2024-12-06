<?php

use Tec\Base\Facades\AdminHelper;
use Illuminate\Support\Facades\Route;

Route::group(['namespace' => 'Tec\Menu\Http\Controllers'], function () {
    AdminHelper::registerRoutes(function () {
        Route::group(['prefix' => 'menus', 'as' => 'menus.'], function () {
            Route::resource('', 'MenuController')->parameters(['' => 'menu']);

            Route::get('ajax/get-node', [
                'as' => 'get-node',
                'uses' => 'MenuController@getNode',
                'permission' => 'menus.index',
            ]);
        });
    });
});
