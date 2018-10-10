<?php

namespace App\Http\Controllers\Admin\View;

use App\Entity\Admin;
use App\Models\M3Result;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class IndexController extends Controller
{
    //首页
    public function toIndex (Request $request) {
        $admin = $request->session()->get('admin');
        return view('admin.index', compact('admin'));
    }
    //欢迎界面
    public function toWelcome(Request $request) {
        $data = [
            'HTTP_HOST' => $request->server()['HTTP_HOST'],
            'DOCUMENT_ROOT' => $request->server()['DOCUMENT_ROOT'],
            'SERVER_SOFTWARE' => $request->server()['SERVER_SOFTWARE'],
            'SERVER_PORT' => $request->server()['SERVER_PORT'],
            'PHP_VERSION' =>  PHP_VERSION,
        ];
        return view('admin.welcome',compact('data'));
    }
}
