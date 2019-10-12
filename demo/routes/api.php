<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

/*Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});*/

$api = app('Dingo\Api\Routing\Router');

$api->version('v1' ,[
    'namespace'=>'App\Http\Controllers\Api' ,
    'prefix' => 'api',
    'middleware' => ['serializer:array', 'bindings']
] , function($api){
    //用户注册
    $api->post('sign-up' , 'UserController@signUp');
    //用户登录
    $api->post('login' , 'UserController@login');
    //找回密码
    $api->post('find-password' , 'UserController@findPwd');

    $api->group(['middleware' => ['api.auth' , 'refresh']],function($api){
        //用户信息
        $api->get('user-info' , 'UserController@userInfo');
        //用户登出
        $api->get('logout' , 'UserController@logout');
        //重置密码
        $api->post('reset-password' , 'UserController@resetPassword');
    });

    //发送验证码
    $api->get('send-captcha' , 'CommonController@sendCode');
    //验证码审核
    $api->post('check-captcha' , 'CommonController@checkCaptcha');




});
