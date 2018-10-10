<?php

namespace App\Http\Controllers\Service;

use App\Entity\CartItem;
use App\Models\M3Result;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CartController extends Controller
{
    //添加购物车
    public function addCart(Request $request,$product_id)
    {
        $m3_result = new M3Result();
        $m3_result->status = 0;
        $m3_result->message = '添加成功';

        //已经登录
        $member = $request->session()->get('member');
        if(!empty($member)){
            $cart_items = CartItem::query()->where('member_id',$member->id)->get();
            $is_exist = false;
            foreach ($cart_items as $cart_item){
                if($cart_item->product_id == $product_id){
                    $cart_item->count++;
                    $cart_item->save();
                    $is_exist = true;
                    break;
                }
            }
            if(!$is_exist){
                $cart_item = new CartItem();
                $cart_item->product_id = $product_id;
                $cart_item->count = 1;
                $cart_item->member_id = $member->id;
                $cart_item->save();
            }
            return $m3_result->toJson();
        }
        //未登录
        $bk_cart = $request->cookie('bk_cart');
        $bk_cart_arr = $bk_cart != null ? explode(',',$bk_cart) : [];
        $count = 1;
        foreach($bk_cart_arr as &$v){
            $index = strpos($v, ':');
            if(substr($v,0,$index) == $product_id){
                $count = ((int)substr($v,$index+1))+1;
                $v = $product_id.':'.$count;
                break;
            }
        }
        if($count == 1){
            array_push($bk_cart_arr,$product_id.':'.$count);
        }
        return response($m3_result->toJson())->withCookie('bk_cart',implode(',',$bk_cart_arr));
    }
    //删除购物车
    public function deleteCart(Request $request)
    {
        $m3_result = new M3Result();
        $m3_result->status = 0;
        $m3_result->message = '删除成功';
        $product_ids = $request->input('product_ids','');
        if($product_ids == '') {
            $m3_result->status = 1;
            $m3_result->message = '书籍ID为空';
            return $m3_result->toJson();
        }
        $product_ids_arr = explode(',',$product_ids);
        //已经登录
        $member = $request->session()->get('member');
        if(!empty($member)){
            CartItem::query()->whereIn('product_id',$product_ids_arr)->delete();
            return response($m3_result->toJson())->withCookie('bk_cart',null);
        }
        //未登录
        $bk_cart = $request->cookie('bk_cart');
        $bk_cart_arr = $bk_cart != null ? explode(',',$bk_cart) : [];
        foreach($bk_cart_arr as $k=>$v){
            $index = strpos($v, ':');
            $product_id = substr($v,0,$index);
            if(in_array($product_id,$product_ids_arr)){
                unset($bk_cart_arr[$k]);
            }
        }
        return response($m3_result->toJson())->withCookie('bk_cart',implode(',',$bk_cart_arr));
    }
}
