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

Route::get('/login', 'LoginController@index');
Route::any('/signIn', 'LoginController@signIn');
Route::any('/login/signOut', 'LoginController@signOut');

Route::group(['middleware' => 'checkLogin'], function () {
    Route::get('/', "IndexController@index");

    Route::get('/ted', "TedController@index");

    Route::any('/ted/info', "TedController@info");

    Route::get('/ted/preview', "TedController@preview");

    Route::get("/repair/index", "RepairController@index");

    Route::any("/writeRedis", "IndexController@writeRedis");

    Route::any("/redis/index", "RedisController@index");

    Route::any("/repair/search", "RepairController@search");

    Route::any('/contract/index', "ContractController@index");

    Route::any('/contract/preview', "ContractController@preview");

    Route::get("/test", "TestController@index");
});


