<?php

namespace App\Http\Controllers\Admin\View;

use App\Entity\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CategoryController extends Controller
{
    /**
     * @return 类别视图
     */
    public function toCategory () {
        $categories = Category::all();
        foreach ($categories as $category) {
            if (!empty($category->parent_id)) {
                $category->parent = Category::query()->find($category->parent_id);
            }
            if (!empty($category->preview)) {
                $temp = explode('@', $category->preview);
                array_pop($temp);
                $category->preview = $temp;
            }
        }
        return view('admin.category', compact('categories'));
    }

    /**
     * @return 添加类别视图
     */
    public function toCategoryAdd () {
        $categories = Category::query()->whereNull('parent_id')->get();
        return view('admin.category_add',compact('categories'));
    }

    /**
     * @return 修改类别视图
     */
    public function toCategoryEdit (Request $request) {
        $id = $request->input('id');
        $category = Category::query()->find($id);
        $categories = Category::query()->whereNull('parent_id')->get();
        return view('admin.category_edit', compact('category','categories'));
    }
}
