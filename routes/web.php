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

Route::any('/login/login','login\LoginController@login');
Route::any('/login/do_login','login\LoginController@do_login');
Route::any('/login/wechat','login\LoginController@wechat');

Route::group(['middleware'=>['Login']],function(){
    Route::any('/admin/index','admin\AdminController@index');
});

//----------------------------第三方微信登录

Route::any('/login/wechatlogin','login\LoginController@wechatlogin');

