<?php

use Illuminate\Support\Facades\Route;


Route::group([

    'prefix'     => config('backpack.base.route_prefix', 'admin'),
    'middleware' => array_merge(
        (array) config('backpack.base.web_middleware', 'web'),
        (array) config('backpack.base.middleware_key', 'admin')
    ),
    'namespace'  => 'App\Http\Controllers\Admin',

], function () {

    //Route::crud('dashboard', 'AdminController');
    Route::crud('settings', 'SettingCrudController');
    Route::crud('cities', 'CityCrudController');
    Route::crud('countries', 'CountryCrudController');
    Route::crud('users', 'UserCrudController');
    Route::crud('article-comment', 'ArticleCommentCrudController');
    Route::crud('article', 'ArticleCrudController');
    Route::crud('article-group', 'ArticleGroupCrudController');
    Route::crud('article-field', 'ArticleFieldCrudController');
    Route::crud('article-field-group', 'ArticleFieldGroupCrudController');
    Route::crud('user-group', 'UserGroupCrudController');
    Route::crud('user-field', 'UserFieldCrudController');
    Route::crud('user-field-group', 'UserFieldGroupCrudController');
    Route::crud('complain', 'ComplainCrudController');
    Route::crud('setting-group', 'SettingGroupCrudController');


    Route::get('charts/weekly-users', 'Charts\WeeklyUsersChartController@response')->name('charts.weekly-users.index');
    Route::get('charts/weekly-article', 'Charts\WeeklyArticleChartController@response')->name('charts.weekly-article.index');
    
});