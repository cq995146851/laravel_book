<?php

namespace App\Http\Controllers\View;

use App\Entity\CartItem;
use App\Entity\Order;
use App\Entity\OrderItem;
use App\Entity\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class OrderController extends Controller
{
    //去支付页面
    public function toOrderCommit(Request $request)
    {
        $product_ids = $request->input('product_ids');
        $product_ids_arr = !empty($product_ids) ? explode(',', $product_ids) : [];
        $member = $request->session()->get('member', '');
        $cart_items = CartItem::query()
            ->where('member_id', $member->id)
            ->whereIn('product_id', $product_ids_arr)
            ->get();
        $order = new Order();
        $order->member_id = $member->id;
        $order->save();

        $cart_items_arr = [];
        $cart_items_ids_arr = [];
        $total_price = 0;
        $name = '';
        foreach ($cart_items as $cart_item) {
            $cart_item->product = Product::query()->find($cart_item->product_id);
            if($cart_item->product != null) {
                $total_price += $cart_item->product->price * $cart_item->count;
                $name .= ('《'.$cart_item->product->name.'》');
                array_push($cart_items_arr, $cart_item);
                array_push($cart_items_ids_arr, $cart_item->id);

                $order_item = new OrderItem();
                $order_item->order_id = $order->id;
                $order_item->product_id = $cart_item->product_id;
                $order_item->count = $cart_item->count;
                $order_item->pdt_snapshot = json_encode($cart_item->product, JSON_UNESCAPED_UNICODE);
                $order_item->save();
            }
        }
        $order_no = 'E'.time().''.$order->id;
        $order->name = $name;
        $order->total_price = $total_price;
        $order->order_no = $order_no;
        $order->save();
        //删除购物车
        CartItem::query()->whereIn('id',$cart_items_ids_arr)->delete();
        return view('order_commit',
            compact('cart_items_arr','total_price','name','order_no'));
    }
    //订单页面
    public function toOrderList(Request $request)
    {
        $member = $request->session()->get('member');
        $orders = Order::query()->where('member_id', $member->id)->get();
        foreach ($orders as $order) {
            $order_items = OrderItem::query()->where('order_id', $order->id)->get();
            $order->order_items = $order_items;
            foreach ($order_items as $order_item) {
                $order_item->product = json_decode($order_item->pdt_snapshot);
            }
        }
        return view('order_list',compact('orders'));
    }
}
