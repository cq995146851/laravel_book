<?php

namespace App\Http\Controllers\View;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MemberController extends Controller
{
    //注册
    public function toRegister()
    {
        return view('register');
    }
    //登录
    public function toLogin(Request $request)
    {
        $returnUrl = $request->session()->get('returnUrl','/category');
        return view('login',compact('returnUrl'));
    }
}
