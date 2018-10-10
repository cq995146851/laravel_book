<?php

namespace App\Http\Controllers\Admin\Service;

use App\Entity\Admin;
use App\Models\M3Result;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class LoginController extends Controller
{
    //登录验证
    public function login(Request $request) {
        $m3_result = new M3Result();
        $m3_result->status = 0;
        $m3_result->message = '登录成功';
        $username = $request->input('username');
        $password = $request->input('password');
        $validate_code = $request->input('validate_code');
        if (empty($username) || empty($password)) {
            $m3_result->status = 1;
            $m3_result->message = '用户名或密码不能为空';
            return $m3_result->toJson();
        }
        $admin = Admin::query()->where('username', $username)
            ->where('password', md5('book'.$password))
            ->first();
        if (empty($admin)) {
            $m3_result->status = 2;
            $m3_result->message = '用户名或密码错误';
            return $m3_result->toJson();
        }
        $validate_code_session = $request->session()->get('validate_code');
        if($validate_code_session != $validate_code){
            $m3_result->status = 3;
            $m3_result->message = '验证码不正确';
            return $m3_result->toJson();
        }
        $request->session()->put('admin', $admin);
        return $m3_result->toJson();
    }
    //退出
    public function logout(Request $request) {
        $request->session()->forget('admin');
        return redirect('/admin/login');
    }
}
