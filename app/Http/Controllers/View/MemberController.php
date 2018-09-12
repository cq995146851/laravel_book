<?php

namespace App\Http\Controllers\View;

use App\Http\Controllers\Controller;

class MemberController extends Controller
{
    //注册
    public function toRegister()
    {
        return view('register');
    }
    //登录
    public function toLogin()
    {
        return view('login');
    }
}
