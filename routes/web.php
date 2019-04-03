<?php

use Illuminate\Support\Facades\Route;

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

/**
 * 需要加入 rbac 控制的路由置于此处
 */
Route::group([
    'middleware' => ['install.check', 'session.check', 'rbac'],
    'as'         => 'rbac',
], function ($route) {
    //控制台
    $route->get('console', 'Admin\IndexController@console');
    $route->group(['prefix' => 'admin'], function ($route) {
        //菜单管理
        $route->get('menu/list', 'Admin\AdministratorController@menuList');
        $route->get('menu/add', 'Admin\AdministratorController@menuAddView');
        $route->post('menu/add', 'Admin\AdministratorController@menuAdd');
        $route->get('menu/update/{id}', 'Admin\AdministratorController@menuUpdateView');
        $route->post('menu/update/{id}', 'Admin\AdministratorController@menuUpdate');
        $route->post('menu/del/{id}', 'Admin\AdministratorController@menuDel');
        //角色管理
        $route->get('role/list', 'Admin\AdministratorController@roleList');
        $route->get('role/add', 'Admin\AdministratorController@roleAddView');
        $route->post('role/add', 'Admin\AdministratorController@roleAdd');
        $route->get('role/update/{id}', 'Admin\AdministratorController@roleUpdateView');
        $route->post('role/update/{id}', 'Admin\AdministratorController@roleUpdate');
        $route->post('role/del/{id}', 'Admin\AdministratorController@roleDel');
        //权限管理
        $route->get('permission/list', 'Admin\AdministratorController@permissionList');
        $route->get('permission/add', 'Admin\AdministratorController@permissionAddView');
        $route->post('permission/add', 'Admin\AdministratorController@permissionAdd');
        $route->get('permission/update/{id}', 'Admin\AdministratorController@permissionUpdateView');
        $route->post('permission/update/{id}', 'Admin\AdministratorController@permissionUpdate');
        $route->post('permission/del/{id}', 'Admin\AdministratorController@permissionDel');
        //管理员管理
        $route->get('administrator/list', 'Admin\AdministratorController@administratorList');
        $route->get('administrator/add', 'Admin\AdministratorController@administratorAddView');
        $route->post('administrator/add', 'Admin\AdministratorController@administratorAdd');
        $route->get('administrator/update/{id}', 'Admin\AdministratorController@administratorUpdateView');
        $route->post('administrator/update/{id}', 'Admin\AdministratorController@administratorUpdate');
        $route->post('administrator/del/{id}', 'Admin\AdministratorController@administratorDel');
        //配置管理
        $route->get('config/list', 'Admin\ConfigController@configList');
        $route->get('config/add', 'Admin\ConfigController@configAddView');
        $route->post('config/add', 'Admin\ConfigController@configAdd');
        $route->get('config/update/{id}', 'Admin\ConfigController@configUpdateView');
        $route->post('config/update/{id}', 'Admin\ConfigController@configUpdate');
        $route->post('config/del/{id}', 'Admin\ConfigController@configDel');
    });

});
Route::group([
    'middleware' => ['install.check', 'session.check'],
    'as'         => 'base',
], function ($route) {
    //框架
    $route->get('/', 'Admin\IndexController@index');
    //403无访问权限
    $route->get('403', 'Admin\IndexController@noPermission');
    //修改个人信息
    $route->get('edit/info/{id}', 'Admin\AdministratorController@editInfoView');
    $route->post('edit/info/{id}', 'Admin\AdministratorController@editInfo');
    //图片上传
    $route->post('admin/upload', 'Admin\IndexController@upload');
    $route->post('admin/wangeditor/upload', 'Admin\IndexController@wangeditorUpload');
    //退出登录
    $route->get('logout', 'Admin\AdministratorController@logout');
});
Route::group([
    'middleware' => ['install.check'],
    'as'         => 'base',
], function ($route) {
    // 登录
    $route->get('login', 'Admin\AdministratorController@login');
    $route->post('login', 'Admin\AdministratorController@checkLogin');
});

// 图标库（开发者用）
Route::get('icon', function () {
    return view('admin.icon');
});
// 安装向导
Route::get('install', 'InstallController@index')->name('installView');
Route::post('install/1', 'InstallController@setEnviroment')->name('setEnviroment');
Route::post('install/2', 'InstallController@startInstall')->name('startInstall');
