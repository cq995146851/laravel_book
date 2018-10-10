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

//前台
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
    //购物车
    Route::get('/cart', 'CartController@toCart');
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
    //添加购物车
    Route::get('add_cart/product_id/{product_id}','CartController@addCart');
    //删除购物车
    Route::get('delete_cart','CartController@deleteCart');
    //支付宝支付
    Route::post('alipay', 'PayController@aliPay');
    //文件上传
    Route::post('upload', 'UploadController@uploadFile');
});

//判断是否登录
Route::group(['middleware'=>'check.login'],function(){
    Route::post('/order_commit', 'View\OrderController@toOrderCommit');
    Route::get('/order_list', 'View\OrderController@toOrderList');
});


/*********************************后台相关*****************************************************/
Route::get('admin/login','Admin\view\LoginController@toLogin');

Route::group(['prefix' => 'admin', 'namespace' => 'Admin\Service'], function () {
    Route::post('login','LoginController@login');
    Route::get('logout','LoginController@logout');
});

Route::group(['prefix' => 'admin', 'namespace' => 'Admin\view', 'middleware'=>'check.admin.login'], function () {
    Route::get('index','IndexController@toIndex');
    Route::get('welcome','IndexController@toWelcome');
    Route::get('category','CategoryController@toCategory');
    Route::get('category_add','CategoryController@toCategoryAdd');
    Route::get('category_edit','CategoryController@toCategoryEdit');
    Route::get('product','ProductController@toProduct');
});

Route::group(['prefix' => 'admin', 'namespace' => 'Admin\Service', 'middleware'=>'check.admin.login'], function () {
    Route::post('category/add','CategoryController@add');
    Route::post('category/delete','CategoryController@delete');
    Route::post('category/edit','CategoryController@edit');
});




