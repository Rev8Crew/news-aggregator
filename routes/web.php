<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/grab','GrabController@start');

Route::prefix('api')->group(function(){

    Route::prefix('feed')->group(function() {
        Route::get('list', 'Api\FeedController@index');
        Route::post('add', 'Api\FeedController@add');
    });

    Route::prefix('news')->group(function() {
        Route::get('list', 'Api\NewsController@index');

        Route::get('search', 'Api\NewsController@search');
    });

    Route::prefix('user')->group(function() {
        Route::post('add', 'Api\UserController@add');
        Route::post('add/news', 'Api\UserController@addNews');
        Route::post('add/category', 'Api\UserController@addCategory');

        Route::get('delayed', 'Api\UserController@listDelayedNews');
        Route::get('favorite', 'Api\UserController@listFavoriteCategories');
    });

});
