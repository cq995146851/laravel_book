@extends('master')

@section('title', '购物车')

@section('content')
    <div class="page bk_content" style="top: 0;">
            <div class="weui-cells weui-cells_checkbox">
                @foreach($cart_items as $cart_item)
                    <label class="weui-cell weui-check__label" for="{{$cart_item->product->id}}">
                        <div class="weui-cell__hd">
                            <input type="checkbox" class="weui-check" name="cart_item" id="{{$cart_item->product->id}}" checked="checked">
                            <i class="weui-icon-checked"></i>
                        </div>
                        <div class="weui-cell__bd">
                            <div style="position: relative;">
                                <img class="bk_preview" src="{{asset($cart_item->product->preview)}}" class="m3_preview" onclick="_toProduct({{$cart_item->product->id}});"/>
                                <div style="position: absolute; left: 100px; right: 0; top: 0">
                                    <p>{{$cart_item->product->name}}</p>
                                    <p class="bk_time" style="margin-top: 15px;">数量: <span class="bk_summary">x{{$cart_item->count}}</span></p>
                                    <p class="bk_time">总计: <span class="bk_price_right">￥{{$cart_item->product->price * $cart_item->count}}</span></p>
                                </div>
                            </div>
                        </div>
                    </label>
                @endforeach
            </div>
    </div>
    <form action="/order_commit" id="order_commit" method="post">
        {{ csrf_field() }}
        <input type="hidden" name="product_ids" value="" />
        {{--<input type="hidden" name="is_wx" value="" />--}}
    </form>
    <div class="bk_fix_bottom">
        <div class="bk_half_area">
            <button class="weui_btn weui_btn_primary" onclick="_toCharge();">结算</button>
        </div>
        <div class="bk_half_area">
            <button class="weui_btn weui_btn_default" onclick="_onDelete();">删除</button>
        </div>
    </div>

@endsection

@section('my-js')
    <script type="text/javascript">
        function _onDelete(){
            var product_ids_arr = [];
            $('input[name=cart_item]:checked').each(function(){
                product_ids_arr.push($(this).attr('id'));
            });
            if(product_ids_arr.length == 0){
                layer.msg('请选择删除项',{time: 1000});
                return;
            }
            layer.confirm('确定要删除?',{title: '删除提示'},function(){
                var product_ids = product_ids_arr.toString();
                $.ajax({
                    type: 'get',
                    dataType: 'json',
                    url: '{{url("/service/delete_cart")}}',
                    data: {product_ids: product_ids},
                    beforeSend: function () {
                        i = layer.msg('正在删除...', {icon: 16,shade: [0.5, '#f5f5f5'],scrollbar: false,offset: '250px', time:100000}) ;
                    },
                    success: function (data) {
                        layer.close(i);
                        if(data == null){
                            layer.msg('服务器端错误',{time: 1000});
                            return false;
                        }
                        if(data.status != 0){
                            layer.msg(data.message,{time: 1000});
                            return false;
                        }
                        layer.msg(data.message,{time: 1000});
                        setTimeout(function () {
                            window.location.reload();
                        },1000);
                    },
                    error: function(xhr, status, error) {
                        layer.close(i);
                        console.log(xhr);
                        console.log(status);
                        console.log(error);
                    }
                });
            });
        }

        function _toCharge(){
            var product_ids_arr = [];
            $('input[name=cart_item]:checked').each(function(){
                product_ids_arr.push($(this).attr('id'));
            });
            if(product_ids_arr.length == 0){
                layer.msg('购物车空空如也',{time: 1000});
                return;
            }
            var product_ids = product_ids_arr.toString();
            //判断是否微信浏览器
            // var is_wx = 0;
            // var ua = navigator.userAgent.toLowerCase();
            // if (ua.match(/MicroMessenger/i) == "micromessenger") {
            //     is_wx = 1;
            // }
           layer.msg('正在提交...', {icon: 16,shade: [0.5, '#f5f5f5'],scrollbar: false,offset: '250px', time:100000}) ;
            $('input[name=product_ids]').val(product_ids_arr+'');
            // $('input[name=is_wx]').val(is_wx+'');
            $('#order_commit').submit();
        }
    </script>
@endsection
