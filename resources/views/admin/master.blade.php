<!DOCTYPE HTML>
<html>
<head>
    <meta charset="utf-8">
    <meta name="renderer" content="webkit|ie-comp|ie-stand">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no" />
    <meta http-equiv="Cache-Control" content="no-siteapp" />
    <LINK rel="Bookmark" href="{{asset('/favicon.ico')}}" >
    <LINK rel="Shortcut Icon" href="{{asset('/favicon.ico')}}" />
    <link href="{{asset('/admin/css/H-ui.min.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('/admin/css/H-ui.admin.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('/admin/lib/Hui-iconfont/1.0.6/iconfont.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('/admin/skin/default/skin.css')}}" rel="stylesheet" type="text/css" id="skin" />
    <link href="{{asset('/admin/css/style.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('/admin/css/uploadfile.css')}}" rel="stylesheet" type="text/css" />
    <!--[if IE 6]>
    <script type="text/javascript" src="http://lib.h-ui.net/DD_belatedPNG_0.0.8a-min.js" ></script>
    <script>DD_belatedPNG.fix('*');</script>
    <![endif]-->
    <title>@yield('title')</title>
    <meta name="keywords" content="">
    <meta name="description" content="">
</head>
<body>
@yield('content')
</body>
</html>
<script type="text/javascript" src="{{asset('/admin/lib/jquery/1.9.1/jquery.min.js')}}"></script>
<script type="text/javascript" src="{{asset('/admin/js/jquery.form.js')}}"></script>
<script type="text/javascript" src="{{asset('/admin/js/uploadfile.js')}}"></script>
<script type="text/javascript" src="{{asset('/admin/lib/layer/2.1/layer.js')}}"></script>
<script type="text/javascript" src="{{asset('/admin/js/H-ui.js')}}"></script>
<script type="text/javascript" src="{{asset('/admin/js/H-ui.admin.js')}}"></script>
<script type="text/javascript" src="{{asset('/admin/lib/Validform/5.3.2/Validform.min.js')}}"></script>
<script type="text/javascript" src="{{asset('/admin/lib/ueditor/1.4.3/ueditor.config.js')}}"></script>
<script type="text/javascript" src="{{asset('/admin/lib/ueditor/1.4.3/ueditor.all.min.js')}}"> </script>
<script type="text/javascript" src="{{asset('/admin/lib/ueditor/1.4.3/lang/zh-cn/zh-cn.js')}}"></script>
<script type="text/javascript" src="{{asset('/admin/lib/datatables/1.10.0/jquery.dataTables.min.js')}}"></script>
@yield('my-js')
