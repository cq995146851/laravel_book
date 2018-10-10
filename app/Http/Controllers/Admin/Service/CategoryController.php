<?php

namespace App\Http\Controllers\Admin\Service;

use App\Entity\Category;
use App\Models\M3Result;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CategoryController extends Controller
{
    /**
     * @param Request $request
     * @return json信息
     */
    public function add(Request $request) {
        $m3_result = new M3Result();
        $m3_result->status = 0;
        $m3_result->message = '添加成功';
        $name = $request->input('name', '');
        $category_no = $request->input('category_no', '');
        $parent_id = $request->input('parent_id', '');
        $preview = $request->input('preview', '');
        if (empty($name) || empty($category_no)) {
            $m3_result->status = 1;
            $m3_result->message = '内容不能为空';
            return $m3_result->toJson();
        }
        $category = new Category();
        $category->name = $name;
        $category->category_no = $category_no;
        $category->parent_id = $parent_id;
        $category->preview = $preview;
        $category->save();
        return $m3_result->toJson();
    }

    /**
     * @param Request $request
     * @return false|string
     */
    public function edit(Request $request){
        $m3_result = new M3Result();
        $m3_result->status = 0;
        $m3_result->message = '修改成功';
        $id = $request->input('id', '');
        $category = Category::query()->find($id);
        $name = $request->input('name', '');
        $category_no = $request->input('category_no', '');
        $parent_id = $request->input('parent_id', '');
        if (empty($name) || empty($category_no)) {
            $m3_result->status = 1;
            $m3_result->message = '内容不能为空';
            return $m3_result->toJson();
        }
        $category->name = $name;
        $category->category_no = $category_no;
        $category->parent_id = $parent_id;
        $category->save();
        return $m3_result->toJson();
    }

    /**
     * @param Request $request
     * @return json信息
     */
    public function delete(Request $request) {
        $m3_result = new M3Result();
        $m3_result->status = 0;
        $m3_result->message = '删除成功';
        $id = $request->input('id', '');
        Category::query()->find($id)->delete();
        return $m3_result->toJson();
    }
}
