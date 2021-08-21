<?php

use Botble\Series\Models\Series as SeriesModel;

Route::group(['namespace' => 'Botble\Series\Http\Controllers', 'middleware' => ['web', 'core']], function () {

    Route::group(['prefix' => BaseHelper::getAdminPrefix(), 'middleware' => 'auth'], function () {

        Route::group(['prefix' => 'series', 'as' => 'series.'], function () {
            Route::resource('', 'SeriesController')->parameters(['' => 'series']);
            Route::delete('items/destroy', [
                'as' => 'deletes',
                'uses' => 'SeriesController@deletes',
                'permission' => 'series.destroy',
            ]);

            Route::get('widgets/list-series', [
                'as' => 'widget.list-series',
                'uses' => 'SeriesController@getWidgetSeries',
                'permission' => 'series.index',
            ]);
        });
    });

    if (defined('THEME_MODULE_SCREEN_NAME')) {
        Route::group(apply_filters(BASE_FILTER_GROUP_PUBLIC_ROUTE, []), function () {

            if (SlugHelper::getPrefix(SeriesModel::class)) {
                Route::get(SlugHelper::getPrefix(SeriesModel::class) . '/{slug}', [
                    'as' => 'public.series',
                    'uses' => 'PublicController@getSerries',
                ]);
            }
        });
    }
});
