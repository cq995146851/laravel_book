@extends('admin.master')
@section('content')
    <form class="form form-horizontal"  id="form-category-edit">
        <div class="row cl">
            <label class="form-label col-3"><span class="c-red">*</span>名称：</label>
            <div class="formControls col-5">
                <input type="text" class="input-text"value="{{$category->name}}"  placeholder="" name="name" datatype="*" nullmsg="名称不能为空">
            </div>
            <div class="col-4"> </div>
        </div>
        <div class="row cl">
            <label class="form-label col-3"><span class="c-red">*</span>序号：</label>
            <div class="formControls col-5">
                <input type="number" min="1" class="input-text" value="{{$category->category_no}}" placeholder="" name="category_no"  datatype="*" nullmsg="序号不能为空">
            </div>
            <div class="col-4"> </div>
        </div>
        <div class="row cl">
            <label class="form-label col-3">父类别：</label>
            <div class="formControls col-5"> <span class="select-box" style="width:150px;">
              <select class="select" name="parent_id" size="1">
                 <option value="">无</option>
                  @foreach($categories as $temp)
                      @if($category->parent_id == $temp->id)
                          <option selected value="{{$temp->id}}">{{$temp->name}}</option>
                      @elseif($category->id != $temp->id)
                      <option value="{{$temp->id}}">{{$temp->name}}</option>
                      @endif
                  @endforeach
              </select>
              </span>
            </div>
        </div>
        <div class="row cl">
            <div class="col-9 col-offset-3">
                <input class="btn btn-primary radius" type="submit" value="&nbsp;&nbsp;提交&nbsp;&nbsp;">
            </div>
        </div>
    </form>
@endsection

@section('my-js')
    <script>
        $("#form-category-edit").Validform({
            tiptype:3,
            callback: function () {
                $("#form-category-edit").ajaxSubmit({
                    type: 'post',
                    dataType: 'json',
                    url: '{{url("admin/category/edit")}}',
                    data: {
                        id: '{{$category->id}}',
                        name: $('input[name=name]').val(),
                        category_no: $('input[name=category_no]').val(),
                        parent_id: $('select[name=parent_id] option:selected').val(),
                        _token: "{{csrf_token()}}"
                    },
                    beforeSend: function () {
                        layer.load()
                    },
                    success: function (data) {
                        if(data == null) {
                            layer.msg('服务端错误', {icon:2, time:2000});
                            return;
                        }
                        if(data.status != 0) {
                            layer.msg(data.message, {icon:2, time:2000});
                            return;
                        }
                        layer.msg(data.message, {icon:1, time:2000});
                        parent.location.reload();
                    },
                    error: function(xhr, status, error) {
                        console.log(xhr);
                        console.log(status);
                        console.log(error);
                        layer.msg('ajax error', {icon:2, time:2000});
                    },
                })
            }
        })
    </script>
@endsection