@extends('master')
@section('title','邮箱激活')

@section('content')
    @if($success)
        <div class="weui-msg">
            <div class="weui-msg__icon-area"><i class="weui-icon-success weui-icon_msg"></i></div>
            <div class="weui-msg__text-area">
                <h2 class="weui-msg__title">{{$content}}</h2>
                <p class="weui-msg__desc"><a href="{{url('/login')}}">点击立即跳转</a></p>
            </div>
        </div>
    @else
        <div class="weui-msg">
            <div class="weui-msg__icon-area"><i class="weui-icon-warn weui-icon_msg"></i></div>
            <div class="weui-msg__text-area">
                <h2 class="weui-msg__title">{{$content}}</h2>
                <p class="weui-msg__desc"><a href="{{url('/login')}}">点击立即跳转</a></p>
            </div>
        </div>
    @endif
@endsection


@section('my-js')
<script type="text/javascript">
    var content = '{{$content}}',
        url = '{{$url}}';
    setTimeout(function () {
        location.href =url;
    }, 3000);
</script>
@endsection
