@extends('master')

@section('title', $product->name)

@section('content')

    <div class="page bk_content" style="top: 0;">
        <div class="addWrap">
            <div class="swipe" id="mySwipe">
                <div class="swipe-wrap">
                    @if(count($pdt_images)>0)
                        @foreach($pdt_images as $pdt_image)
                            <div>
                                <a href="javascript:;"><img class="img-responsive" src="{{asset($pdt_image->image_path)}}" /></a>
                            </div>
                        @endforeach
                    @else
                        暂无轮播图
                    @endif
                </div>
            </div>
            <ul id="position">
                @foreach($pdt_images as $index => $pdt_image)
                    <li class={{$index == 0 ? 'cur' : ''}}></li>
                @endforeach
            </ul>
        </div>

        <div class="weui_cells_title">
            <span class="bk_title">{{$product->name}}</span>
            <span class="bk_price">￥ {{$product->price}}</span>
        </div>
        <div class="weui_cells">
            <div class="weui_cell">
                <p class="bk_summary">{{$product->summary}}</p>
            </div>
        </div>

        <div class="weui_cells_title">详细介绍</div>
        <div class="weui_cells">
            <div class="weui_cell">
                @if($pdt_content != null)
                    {!! $pdt_content->content !!}
                @else
                暂无详细介绍
                @endif
            </div>
        </div>
    </div>

    <div class="bk_fix_bottom">
        <div class="bk_half_area">
            <button class="weui_btn weui_btn_primary" onclick="_addCart();">加入购物车</button>
        </div>
        <div class="bk_half_area">
            <button class="weui_btn weui_btn_default" onclick="_toCart()">查看购物车(<span id="cart_num" class="m3_price">{{$count}}</span>)</button>
        </div>
    </div>

@endsection

@section('my-js')
    <script type="text/javascript">
        //轮播图
        var bullets = document.getElementById('position').getElementsByTagName('li');
        Swipe(document.getElementById('mySwipe'), {
            auto: 2000,
            continuous: true,
            disableScroll: false,
            callback: function(pos) {
                var i = bullets.length;
                while (i--) {
                    bullets[i].className = '';
                }
                bullets[pos].className = 'cur';
            }
        });
        function _addCart(){
            var product_id = '{{$product->id}}';
            $.ajax({
                type: 'get',
                dataType: 'json',
                url: '{{url("/service/add_cart")}}/product_id/'+product_id,
                beforeSend: function () {
                     i = layer.msg('正在添加...', {icon: 16,shade: [0.5, '#f5f5f5'],scrollbar: false,offset: '250px', time:100000}) ;
                },
                success: function (data) {
                    layer.close(i);
                    if(data == null){
                        layer.msg('服务器端错误',{time: 1000});
                        return false;
                    }
                    var count = $('#cart_num').html();
                    var newCount = count == '' ? 0 : Number(count)+1;
                    $('#cart_num').html(newCount);
                    layer.msg(data.message,{time: 1000});
                },
                error: function(xhr, status, error) {
                    layer.close(i);
                    console.log(xhr);
                    console.log(status);
                    console.log(error);
                }
            })
        }
        function _toCart() {
            layer.msg('正在加载...', {icon: 16,shade: [0.5, '#f5f5f5'],scrollbar: false,offset: '250px', time:100000}) ;
            location.href = '{{url("/cart")}}';
        }
    </script>


@endsection
