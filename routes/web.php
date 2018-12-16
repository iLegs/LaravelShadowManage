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

//后台路由组
Route::group(["prefix" => "shadow", "middleware" => "shadow"], function() {
    //退出登录
    Route::get('/logout', 'LogoutController@onGet');
    //后台登录
    Route::get('/login', 'LoginController@onGet');
    Route::post('/login', 'LoginController@onPost');
    //后台桌面
    Route::get('/main', 'Shadow\MainController@onGet');
});
