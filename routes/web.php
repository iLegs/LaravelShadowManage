<?php
/**
 * 网站路由入口文件。
 *
 * @author    fairyin <fairyin@126.com>
 * @copyright © 2018 www.imcn.vip
 * @version   v1.0
 */

//生成验证码
Route::get('/gencaptcha', 'CaptchaController@onGet');

//首页
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

    Route::any('/tags/manage', 'Shadow\Tags\ManageController@any');
    Route::get('/tags/add', 'Shadow\Tags\AddController@onGet');
    Route::post('/tags/add', 'Shadow\Tags\AddController@onPost');
    Route::get('/tags/edit', 'Shadow\Tags\EditController@onGet');
    Route::post('/tags/edit', 'Shadow\Tags\EditController@onPost');

    Route::post('/tags/delete', 'Shadow\Tags\DeleteController@onPost');
});
