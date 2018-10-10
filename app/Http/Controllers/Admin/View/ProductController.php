<?php

namespace App\Http\Controllers\Admin\View;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ProductController extends Controller
{
    public function toProduct () {
        return view('admin.product');
    }
}
