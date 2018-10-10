@extends('admin.master')
@section('content')
    <form class="form form-horizontal" id="form-category-add" >
        <div class="row cl">
            <label class="form-label col-3"><span class="c-red">*</span>名称：</label>
            <div class="formControls col-5">
                <input type="text" class="input-text" value="" placeholder="" name="name" datatype="*" nullmsg="名称不能为空">
            </div>
            <div class="col-4"></div>
        </div>
        <div class="row cl">
            <label class="form-label col-3"><span class="c-red">*</span>序号：</label>
            <div class="formControls col-5">
                <input type="number" min="1" class="input-text" value="1" placeholder="" name="category_no" datatype="*"
                       nullmsg="序号不能为空">
            </div>
            <div class="col-4"></div>
        </div>
        <div class="row cl">
            <label class="form-label col-3">父类别：</label>
            <div class="formControls col-5"> <span class="select-box" style="width:150px;">
              <select class="select" name="parent_id" size="1">
                <option value="">无</option>
                  @foreach($categories as $category)
                      <option value="{{$category->id}}">{{$category->name}}</option>
                  @endforeach
              </select>
              </span>
            </div>
        </div>
        <div class="row cl">
            <label class="form-label col-3">预览图：</label>
            <div class="formControls col-5">
                <div id="fileuploader">Upload</div>
            </div>
        </div>
        <div class="row cl">
            <div class="col-9 col-offset-3">
                <input id="btn_add" class="btn btn-success radius" type="submit" value="&nbsp;&nbsp;提交&nbsp;&nbsp;">
                <input id="btn_reset" class="btn btn-primary radius" type="button" value="&nbsp;&nbsp;重置&nbsp;&nbsp;">
            </div>
        </div>
    </form>
@endsection

@section('my-js')
    <script>
        $("#fileuploader").uploadFile({
            url: "{{url('service/upload')}}",
            method: 'post',
            fileName: "file",
            formData: {_token: '{{csrf_token()}}'},
            maxFileSize: 2*1024*1024,
            maxFileCount: 5,
            allowedTypes: 'jpg,jpeg,png,gif',
            uploadStr: '上传图片',
            extErrorStr: '图片格式错误',
            sizeErrorStr: '图片大小错误',
            maxFileCountErrorStr: '只能上传一个文件',
            // dragDropStr: '拖放',
            onSuccess:function(files,data,xhr,pd) {
                let img = '<img src="'+data+'" class="uploadimg">';
                $('.ajax-file-upload-container').append(img);
                $('.uploadimg').css({'width': '100px', 'height':  '100px'});
            },
            onError: function(files,status,errMsg,pd)
            {
                console.log(files);
                console.log(status);
                console.log(errMsg);
                console.log(pd);
                layer.msg('上传失败', {icon: 2, time: 2000})
            }

        });
        $("#form-category-add").Validform({
            btnSubmit: '#btn_add',
            btnReset: "#btn_reset",
            tiptype: 2,
            callback: function (form) {
                let imgs = '';
                $('.uploadimg').each(function() {
                    imgs += $(this).attr('src').split('/').pop() + '@';
                });
                $.ajax({
                    method: 'post',
                    url: '{{url("admin/category/add")}}',
                    data: {
                        name: $('input[name=name]').val(),
                        category_no: $('input[name=category_no]').val(),
                        parent_id: $('select[name=parent_id] option:selected').val(),
                        preview: imgs,
                        _token: "{{csrf_token()}}"
                    },
                    beforeSend: function () {
                        i = layer.load();
                    },
                    success: function (data) {
                        layer.close(i);
                        data = JSON.parse(data)
                        if (data == null) {;
                            layer.msg('服务端错误', {icon: 2, time: 2000});
                            return;
                        }
                        if (data.status != 0) {

                            layer.msg(data.message, {icon: 2, time: 2000});
                            return;
                        }
                        layer.msg(data.message, {icon: 1, time: 2000});
                        parent.location.reload();
                    },
                    error: function (xhr, status, error) {
                        layer.close(i);
                        console.log(xhr);
                        console.log(status);
                        console.log(error);
                        layer.msg('ajax error', {icon: 2, time: 2000});
                    }
                });
                return false;  //这里必须添加
            }
        })
    </script>
@endsection