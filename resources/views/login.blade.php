@extends('master')
@section('title', '登录')

@section('content')
<div class="weui_cells_title"></div>
<div class="weui_cells weui_cells_form">
  <div class="weui_cell">
      <div class="weui_cell_hd"><label class="weui_label">帐号</label></div>
      <div class="weui_cell_bd weui_cell_primary">
          <input class="weui_input" type="text" placeholder="邮箱或手机号" name="username"/>
      </div>
  </div>
  <div class="weui_cell">
      <div class="weui_cell_hd"><label class="weui_label">密码</label></div>
      <div class="weui_cell_bd weui_cell_primary">
          <input class="weui_input" type="password" placeholder="不少于6位" name="password"/>
      </div>
  </div>
  <div class="weui_cell weui_vcode">
      <div class="weui_cell_hd"><label class="weui_label">验证码</label></div>
      <div class="weui_cell_bd weui_cell_primary">
          <input class="weui_input" type="text" placeholder="请输入验证码" name="validate_code"/>
      </div>
      <div class="weui_cell_ft">
          <img src="{{url('/service/validate_code/create')}}" class="bk_validate_code"/>
      </div>
  </div>
</div>
<div class="weui_cells_tips"></div>
<div class="weui_btn_area">
  <a class="weui_btn weui_btn_primary" href="javascript:;" onclick="onLoginClick();">登录</a>
</div>
<a href="{{url('/register')}}" class="bk_bottom_tips bk_important">没有帐号? 去注册</a>
@endsection

@section('my-js')
<script type="text/javascript">
    $('.bk_validate_code').click(function () {
        this.src = "{{url('/service/validate_code/create')}}?random="+Math.random();
    });

    function onLoginClick() {
        // 帐号
        var username = $('input[name=username]').val();
        if(username.length == 0) {
            layer.msg('手机号或邮箱不能为空',{time:1000});
            return;
        }
        if(username.indexOf('@') == -1) { //手机号
            if(username.length != 11 || username[0] != 1) {
                layer.msg('账号格式不正确',{time:1000});
                return;
            }
        } else {
            if(username.indexOf('.') == -1) {
                layer.msg('账号格式不正确',{time:1000});
                return;
            }
        }
        // 密码
        var password = $('input[name=password]').val();
        if(password.length == 0) {
            layer.msg('密码不能为空',{time:1000});
            return;
        }
        if(password.length < 6) {
            layer.msg('密码不能少于6位',{time:1000});
            return;
        }
        // 验证码
        var validate_code = $('input[name=validate_code]').val();
        if(validate_code.length == 0) {
            layer.msg('验证码不能为空',{time:1000});
            return;
        }
        if(validate_code.length < 4) {
            layer.msg('验证码不能少于4位',{time:1000});
            return;
        }

        $.ajax({
            type: "POST",
            url: '{{url('/service/login')}}',
            dataType: 'json',
            cache: false,
            data: {username: username, password: password, validate_code: validate_code, _token: "{{csrf_token()}}"},
            beforeSend: function () {
                i=layer.msg('正在请求...', {icon: 16,shade: [0.5, '#f5f5f5'],scrollbar: false,offset: '250px', time:100000}) ;
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
                    location.href = '{{$returnUrl}}';
                },1000);

            },
            error: function(xhr, status, error) {
                layer.close(i);
                console.log(xhr);
                console.log(status);
                console.log(error);
            }
        });
    }
</script>
@endsection
