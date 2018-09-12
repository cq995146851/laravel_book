@extends('master')

@section('title', '书籍类别')

@section('content')
    <div class="weui_cells_title">选择书籍类别</div>
    <div class="weui_cells weui_cells_split">
        <div class="weui_cell weui_cell_select">
            <div class="weui_cell_bd weui_cell_primary">
                <select class="weui_select" name="category">
                   @foreach($categorys as $category)
                        <option value="{{$category->id}}">{{$category->name}}</option>
                    @endforeach
                </select>
            </div>

        </div>
    </div>

    <div class="weui_cells weui_cells_access">

    </div>



@endsection

@section('my-js')
    <script type="text/javascript">

        _getCategory();

        $('.weui_select').change(function() {
            _getCategory()
        });

        function _getCategory() {
            var parent_id = $('.weui_select option:selected').val();
            $.ajax({
                type: "get",
                url: '{{url("/service/getCategoryByParentId")}}/parent_id/'+parent_id,
                dataType: 'json',
                cache: false,
                beforeSend: function () {
                    i=layer.msg('正在加载...', {icon: 16,shade: [0.5, '#f5f5f5'],scrollbar: false,offset: '250px', time:100000}) ;
                },
                success: function(data) {
                    layer.close(i);
                    if(data == null) {
                        layer.msg('服务端错误',{time:1000})
                        return;
                    }
                    if(data.status != 0) {
                        layer.msg(data.message,{time:1000})
                        return;
                    }
                    $('.weui_cells_access').html('');
                    var html = '';
                    $(data.categorys).each(function(k,v){
                        var productUrl = '{{url("/product")}}/category_id/'+v.id;
                        html += '<a class="weui_cell" href="' + productUrl + '" onclick="_toJump(this.href)">';
                        html +=     '<div class="weui_cell_bd weui_cell_primary">';
                        html +=            '<p>'+ v.name +'</p>';
                        html +=     '</div>';
                        html +=     '<div class="weui_cell_ft"></div>';
                        html += '</a>';
                    });
                    $('.weui_cells_access').append(html);

                },
                error: function(xhr, status, error) {
                    layer.close(i);
                    console.log(xhr);
                    console.log(status);
                    console.log(error);
                }
            });
        }
        //跳转
        function _toJump(href){
            layer.msg('正在加载...', {icon: 16,shade: [0.5, '#f5f5f5'],scrollbar: false,offset: '250px', time:100000}) ;
            location.href = href;
        }
    </script>

@endsection
