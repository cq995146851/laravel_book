<?php

namespace App\Http\Controllers\View;

use App\Entity\Category;
use App\Entity\PdtContent;
use App\Entity\PdtImages;
use App\Entity\Product;
use App\Http\Controllers\Controller;

class BookController extends Controller
{
    //书籍分类
    public function toCategory()
    {
        $categorys = Category::query()->whereNull('parent_id')->get();
        return view('category',compact('categorys'));
    }
    //根据类型id获取商品
    public function toProduct($category_id)
    {
        $products = Product::query()->where('category_id',$category_id)->get();
        return view('product',compact('products'));
    }
    //根据类商品id获取商品详情
    public function pdt_content($product_id)
    {
        $product = Product::query()->find($product_id);
        $pdt_content = PdtContent::query()->where('product_id',$product_id)->first();
        $pdt_images =  PdtImages::query()->where('product_id',$product_id)->get();
        return view('pdt_content',compact('product','pdt_content','pdt_images'));
    }
}
