@extends('master')

@section('title', '书籍列表')

@section('content')
    <div class="weui_cells weui_cells_access">
        @if(count($products)>0)
            @foreach($products as $product)
                <a class="weui_cell" href="{{url('/product')}}/{{$product->id}}" onclick="_toJump(this.href)">
                    <div class="weui_cell_hd"><img class="bk_preview" src="{{asset($product->preview)}}"></div>
                    <div class="weui_cell_bd weui_cell_primary">
                        <div style="margin-bottom: 10px;">
                            <span class="bk_title">{{$product->name}}</span>
                            <span class="bk_price">￥ {{$product->price}}</span>
                        </div>

                        <p class="bk_summary">{{$product->summary}}</p>
                    </div>
                    <div class="weui_cell_ft"></div>
                </a>
            @endforeach
        @else
            暂无数据
        @endif
    </div>
@endsection
<script type="text/javascript">
    //跳转
    function _toJump(href){
        layer.msg('正在加载...', {icon: 16,shade: [0.5, '#f5f5f5'],scrollbar: false,offset: '250px', time:100000}) ;
        location.href = href;
    }
</script>
