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

//视图

Route::group(['namespace' => 'View'], function () {
    //注册
    Route::get('/register', 'MemberController@toRegister');
    //登录
    Route::get('/login', 'MemberController@toLogin');
    //书籍分类
    Route::get('/category', 'BookController@toCategory');
    //商品
    Route::get('/product/category_id/{category_id}', 'BookController@toProduct');
    //商品详情
    Route::get('/product/{product_id}', 'BookController@pdt_content');
});


//接口
Route::group(['prefix' => 'service', 'namespace' => 'Service'], function () {
    /**
     * 注册登录
     */
    //获取验证码
    Route::get('validate_code/create', 'ValidateController@create');
    //获取手机验证码
    Route::post('validate_sms/send_sms', 'ValidateController@sendSms');
    //验证邮箱
    Route::get('validate_email', 'ValidateController@validateEmail');
    //注册表单
    Route::post('register', 'MemberController@register');
    //登录表单
    Route::post('login', 'MemberController@login');
    /**
     * 书籍分类
     */
    //根据分类id获取所有分类
    Route::get('getCategoryByParentId/parent_id/{parent_id}', 'BookController@getCategoryByParentId');
});
