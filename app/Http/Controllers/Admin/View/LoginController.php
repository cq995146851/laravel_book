<?php

namespace App\Http\Controllers\Admin\View;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class LoginController extends Controller
{
    //登录
    public function toLogin()
    {
        return view('admin.login');
    }
}
