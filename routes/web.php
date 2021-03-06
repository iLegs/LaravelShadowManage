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
Route::get('/', 'IndexController@onGet');

Route::get('/models.html', 'Web\ModelsController@onGet');
Route::get('/tags.html', 'Web\TagsController@onGet');

Route::get('/login.html', 'Web\LoginController@onGet');

//列表页
Route::get('/lib/{key}/list.html', 'ListController@onGet');
Route::post('/lib/{key}/list.html', 'ListController@onPost');

//模特专辑列表
Route::get('/model/{key}.html', 'Web\ModelAlbumListController@onGet');
Route::post('/model/{key}.html', 'Web\ModelAlbumListController@onPost');

//标签专辑列表
Route::get('/tag/{key}.html', 'Web\TagAlbumListController@onGet');
Route::post('/tag/{key}.html', 'Web\TagAlbumListController@onPost');

//浏览专辑下的所有图片
Route::get('/album/detail/{albumid}.html', 'AlbumDetailController@onGet');

Route::post('/photo/del', 'PhotoDelController@onPost');

Route::get('/cron/photo_delete', 'Cron\PhotoDelController@onGet');
Route::get('/cron/flush/photo', 'Cron\FlushQiNiuController@onGet');

Route::get('/sitemap.xml', 'SitemapController@onGet');

//后台路由组
Route::group(["prefix" => "shadow", "middleware" => "shadow"], function() {
    //退出登录
    Route::get('/logout', 'LogoutController@onGet');
    //后台登录
    Route::get('/login', 'LoginController@onGet');
    Route::post('/login', 'LoginController@onPost');
    //后台桌面
    Route::get('/main', 'Shadow\MainController@onGet');

    //上传图片
    Route::post('/resource/upload', 'Shadow\ResourceUploadController@onPost');

    //标签管理
    Route::any('/tags/manage', 'Shadow\Tags\ManageController@any');
    Route::get('/tags/add', 'Shadow\Tags\AddController@onGet');
    Route::post('/tags/add', 'Shadow\Tags\AddController@onPost');
    Route::get('/tags/edit', 'Shadow\Tags\EditController@onGet');
    Route::post('/tags/edit', 'Shadow\Tags\EditController@onPost');
    Route::post('/tags/delete', 'Shadow\Tags\DeleteController@onPost');

    //模特管理
    Route::any('/legmodels/manage', 'Shadow\LegModels\ManageController@any');
    Route::get('/legmodels/add', 'Shadow\LegModels\AddController@onGet');
    Route::post('/legmodels/add', 'Shadow\LegModels\AddController@onPost');
    Route::get('/legmodels/edit', 'Shadow\LegModels\EditController@onGet');
    Route::post('/legmodels/edit', 'Shadow\LegModels\EditController@onPost');
    Route::post('/legmodels/delete', 'Shadow\LegModels\DeleteController@onPost');

    //图库管理
    Route::any('/leglibs/manage', 'Shadow\LegLibs\ManageController@any');
    Route::get('/leglibs/add', 'Shadow\LegLibs\AddController@onGet');
    Route::post('/leglibs/add', 'Shadow\LegLibs\AddController@onPost');
    Route::get('/leglibs/edit', 'Shadow\LegLibs\EditController@onGet');
    Route::post('/leglibs/edit', 'Shadow\LegLibs\EditController@onPost');
    Route::post('/leglibs/delete', 'Shadow\LegLibs\DeleteController@onPost');

    //专辑管理
    Route::any('/albums/manage', 'Shadow\Albums\ManageController@any');
    Route::get('/albums/add', 'Shadow\Albums\AddController@onGet');
    Route::post('/albums/add', 'Shadow\Albums\AddController@onPost');
    Route::get('/albums/edit', 'Shadow\Albums\EditController@onGet');
    Route::post('/albums/edit', 'Shadow\Albums\EditController@onPost');
    Route::post('/albums/delete', 'Shadow\Albums\DeleteController@onPost');
    Route::get('/albums/upload', 'Shadow\Albums\UploadController@onGet');
    Route::post('/albums/upload', 'Shadow\Albums\UploadController@onPost');

    //图片管理
    Route::any('/photoes/manage', 'Shadow\Photoes\ManageController@any');
    Route::post('/photoes/delete', 'Shadow\Photoes\DeleteController@onPost');
    Route::post('/photoes/change/horizontal', 'Shadow\Photoes\ChangeController@horizontal');
    Route::post('/photoes/change/vertical', 'Shadow\Photoes\ChangeController@vertical');

    //七牛云文件搜索
    Route::any('/qiniufile/manage', 'Shadow\QiniuFile\ManageController@any');
});

Route::post('/lib/query', 'Api\LibQueryController@onPost');
