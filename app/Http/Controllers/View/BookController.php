<?php

namespace App\Http\Controllers\View;

use App\Entity\CartItem;
use App\Entity\Category;
use App\Entity\PdtContent;
use App\Entity\PdtImages;
use App\Entity\Product;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

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
    public function pdt_content(Request $request,$product_id)
    {
        //获取产品信息
        $product = Product::query()->find($product_id);
        //获取产品详情
        $pdt_content = PdtContent::query()->where('product_id',$product_id)->first();
        //获取轮播图
        $pdt_images =  PdtImages::query()->where('product_id',$product_id)->get();
        //获取购物车数量
        //已经登录
        $member = $request->session()->get('member', '');
        if(!empty($member)) {
            $count = 0;
            $cart_items = CartItem::query()->where('member_id', $member->id)->get();
            foreach ($cart_items as $cart_item) {
                if($cart_item->product_id == $product_id) {
                    $count = (int)$cart_item->count;
                    break;
                }
            }
        }else{
            //未登录
            $bk_cart = $request->Cookie('bk_cart');
            $bk_cart_arr = $bk_cart != null ? explode(',',$bk_cart) : [];
            $count = 0;
            foreach($bk_cart_arr as $v){
                $index = strpos($v,':');
                if(substr($v,0,$index) == $product_id){
                    $count = (int)substr($v,$index+1);
                    break;
                }
            }
        }
        return view('pdt_content',compact('product','pdt_content','pdt_images','count'));
    }
}
