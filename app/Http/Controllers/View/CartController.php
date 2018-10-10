<?php

namespace App\Http\Controllers\View;

use App\Entity\CartItem;
use App\Entity\Product;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;

class CartController extends Controller
{
    //购物车
    public function toCart(Request $request)
    {
        $member = $request->session()->get('member');
        $bk_cart = $request->cookie('bk_cart');
        $bk_cart_arr = $bk_cart!=null ? explode(',',$bk_cart) : [];
        //已经登录
        if(!empty($member)){

            //同步购物车
            $cart_items = $this->syncCart($member->id,$bk_cart_arr);
//            $cart_items = CartItem::query()
//                ->join('product', 'cart_item.product_id', '=', 'product.id')
//                ->where('cart_item.member_id', $member->id)
//                ->get();
//            dd($cookie);
            return view('cart',compact('cart_items'))
                    ->withCookie('bk_cart', null);
        }
        //未登录
        $cart_items = [];
        foreach($bk_cart_arr as $k=>$v){
            $index = strpos($v,':');
            $cart_item = new CartItem();
            $cart_item->id = $k;
            $cart_item->product_id = substr($v,0,$index);
            $cart_item->count = substr($v,$index+1);
            $cart_item->product = Product::query()->find($cart_item->product_id);
            if($cart_item->product != null){
                array_push($cart_items,$cart_item);
            }
        }
        return view('cart',compact('cart_items'));
    }
    //同步购物车
    private function syncCart($member_id,$bk_cart_arr)
    {
        $cart_items = CartItem::query()->where('member_id',$member_id)->get();
        $cart_items_arr = [];
        foreach($bk_cart_arr as $v){
            $index = strpos($v,':');
            $product_id = substr($v,0,$index);
            $count = (int)substr($v,$index+1);
            $is_exist = false;
            foreach ($cart_items as $cart_item) {
                if($cart_item->product_id == $product_id) {
                    if($cart_item->count < $count) {
                        $cart_item->count = $count;
                        $cart_item->save();
                    }
                    $is_exist = true;
                    break;
                }
            }
            if(!$is_exist){
                $cart_item = new CartItem();
                $cart_item->member_id = $member_id;
                $cart_item->product_id = $product_id;
                $cart_item->count = $count;
                $cart_item->save();
                $cart_item->product = Product::query()->find($cart_item->product_id);
                array_push($cart_items_arr, $cart_item);
            }
        }
        foreach ($cart_items as $cart_item) {
            $cart_item->product = Product::query()->find($cart_item->product_id);
            array_push($cart_items_arr, $cart_item);
        }
        return $cart_items_arr;
    }
}
