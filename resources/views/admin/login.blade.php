@extends('admin.master')
@section('title','晓琳书店-登录')
@section('content')
    <link href="{{asset('admin/css/H-ui.login.css')}}" rel="stylesheet" type="text/css" />
    <div class="loginWraper">
        <div id="loginform" class="loginBox">
            <form class="form form-horizontal">
                <div class="row cl">
                    <label class="form-label col-3"><i class="Hui-iconfont">&#xe60d;</i></label>
                    <div class="formControls col-8">
                        <input name="username" type="text" placeholder="账户" class="input-text size-L">
                    </div>
                </div>
                <div class="row cl">
                    <label class="form-label col-3"><i class="Hui-iconfont">&#xe60e;</i></label>
                    <div class="formControls col-8">
                        <input name="password" type="password" placeholder="密码" class="input-text size-L">
                    </div>
                </div>
                <div class="row cl">
                    <div class="formControls col-8 col-offset-3">
                        <input name="validate_code" class="input-text size-L" type="text"  style="width:150px;">
                        <img src="{{url('/service/validate_code/create')}}"> <a id="kanbuq" href="javascript:;">看不清，换一张</a>
                    </div>
                </div>
                <div class="row">
                    <div class="formControls col-8 col-offset-3">
                        <input name="" type="button" class="btn btn-success radius size-L" value="&nbsp;登&nbsp;&nbsp;&nbsp;&nbsp;录&nbsp;" id="tologin">
                        <input name="" type="reset" class="btn btn-default radius size-L" value="&nbsp;取&nbsp;&nbsp;&nbsp;&nbsp;消&nbsp;">
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="footer">Copyright 晓琳书店</div>
@endsection
@section('my-js')
    <script type="text/javascript">
        $("#kanbuq").click(function(){
            $(this).prev().attr('src',"{{url('/service/validate_code/create')}}?random="+Math.random());
        })
        $("#tologin").click(function () {
            var flag = true;
            var username = $('input[name=username]').val(),
                password = $('input[name=password]').val(),
                validate_code = $('input[name=validate_code]').val();
            if(username.length == 0 || password.length == 0) {
                layer.msg('用户名或密码不能为空',{time:1000});
                flag = false;
                return;
            }
            if (flag) {
                console.log(validate_code.length)
                if(validate_code.length == 0) {
                    layer.msg('验证码不能为空',{time:1000});
                    flag = false;
                    return;
                }
            }
            if (flag) {
                $.ajax({
                    type: 'post',
                    dataType: 'json',
                    url: '{{url("/admin/login")}}',
                    data: {username: username, password: password, validate_code: validate_code, '_token': '{{csrf_token()}}'},
                    beforeSend: function () {
                        i=layer.msg('正在登录...', {icon: 16,shade: [0.5, '#f5f5f5'],scrollbar: false,offset: '250px', time:100000}) ;
                    },
                    success: function(data) {
                        layer.close(i);
                        if(data == null) {
                            layer.msg('服务端错误',{time:1000});
                            return;
                        }
                        if(data.status != 0) {
                            layer.msg(data.message,{time:1000});
                            return;
                        }
                        layer.msg(data.message,{time:1000});
                        setTimeout(function(){
                            location.href = '{{url("/admin/index")}}';
                        },1000);
                    },
                    error: function(xhr, status, error) {
                        layer.close(i);
                        console.log(xhr);
                        console.log(status);
                        console.log(error);
                    }
                })
            }
        })
    </script>
@endsection