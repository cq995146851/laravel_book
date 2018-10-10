@extends('admin.master')
@section('title', '我的桌面')
@section('content')
<div class="page-container">
    <p class="f-20 text-success" style="margin-left: 10px;">欢迎登录<span style="color:blue;">基于Laravel的微商城后台管理系统</span></p>
    <p style="margin-left: 10px;">上次登录IP：127.0.0.1  上次登录时间：2018-10-8 11:19:55</p>
    <table class="table table-border table-bordered table-bg mt-20">
        <thead>
        <tr>
            <th colspan="2" scope="col">服务器信息</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td>服务器IP地址</td>
            <td>{{$data['HTTP_HOST']}}</td>
        </tr>
        <tr>
            <td>站点根目录</td>
            <td>{{$data['DOCUMENT_ROOT']}}</td>
        </tr>
        <tr>
            <td>服务器端口 </td>
            <td>{{$data['SERVER_PORT']}}</td>
        </tr>
        <tr>
            <td>PHP版本 </td>
            <td>{{$data['PHP_VERSION']}}</td>
        </tr>
        <tr>
            <td>服务器操作系统 </td>
            <td>{{$data['SERVER_SOFTWARE']}}</td>
        </tr>
        </tbody>
    </table>
</div>
<footer class="footer mt-20">
    <div class="container">

    </div>
</footer>
@endsection