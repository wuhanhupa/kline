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


use Illuminate\Support\Facades\Route;

Route::get('/', "IndexController@index");

Route::get('/ted', "IndexController@ted");
Route::get('/admin/ted', "IndexController@ted");

Route::get('/ted_info', "IndexController@tedInfo");
Route::get('/admin/ted_info', "IndexController@tedInfo");

Route::get("/handleRedisData", "IndexController@handleRedisData");
Route::get("/admin/handleRedisData", "IndexController@handleRedisData");

Route::get("/repair/index", "RepairController@index");
Route::get("/admin/repair/index", "RepairController@index");

Route::any("/writeRedis", "IndexController@writeRedis");
Route::any("/admin/writeRedis", "IndexController@writeRedis");

Route::any("/redis_list", "IndexController@redis_list");
Route::any("/admin/redis_list", "IndexController@redis_list");

Route::any("/repair/search", "RepairController@search");


Route::get("/test", "TestController@index");
