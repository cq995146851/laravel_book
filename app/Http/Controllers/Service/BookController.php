<?php

namespace App\Http\Controllers\Service;

use App\Entity\Category;
use App\Models\M3Result;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class BookController extends Controller
{
    //根据父类id获取所有分类
    public function getCategoryByParentId($parentId)
    {
        $categorys = Category::query()->where('parent_id', $parentId)->get();
        $m3_result = new M3Result();
        $m3_result->status = 0;
        $m3_result->message = '返回成功';
        $m3_result->categorys = $categorys;
        return $m3_result->toJson();
    }

}
