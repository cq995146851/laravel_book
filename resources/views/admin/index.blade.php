@extends('admin.master')
@section('title', '基于Laravel的微商城后台管理系统')
@section('content')
    <header class="Hui-header cl" style="background-color: #00B83F;"><a class="Hui-logo" title="" href="{{url('/admin/index')}}">晓琳书店后台管理</a>
        <ul class="Hui-userbar">
            <li>{{$admin->username}}</li>
            <li><a href="{{url('/admin/logout')}}">退出</a></li>
        </ul>
        <a href="javascript:;" class="Hui-nav-toggle Hui-iconfont" aria-hidden="false">&#xe667;</a>
    </header>
    <aside class="Hui-aside">
        <input runat="server" id="divScrollValue" type="hidden" value="" />
        <div class="menu_dropdown bk_2">
            <dl id="menu-product">
                <dt><i class="Hui-iconfont">&#xe620;</i> 产品管理<i class="Hui-iconfont menu_dropdown-arrow">&#xe6d5;</i></dt>
                <dd>
                    <ul>
                        <li><a _href="{{url('/admin/category')}}" data-title="分类管理" href="javascript:void(0)">分类管理</a></li>
                        <li><a _href="{{url('/admin/product')}}" data-title="产品管理" href="javascript:void(0)">产品管理</a></li>
                    </ul>
                </dd>
            </dl>
            <dl id="menu-order">
                <dt><i class="Hui-iconfont">&#xe687;</i> 订单管理<i class="Hui-iconfont menu_dropdown-arrow">&#xe6d5;</i></dt>
                <dd>
                    <ul>
                        <li><a _href="{{url('/admin/order')}}" data-title="订单列表" href="javascript:void(0)">订单列表</a></li>
                    </ul>
                </dd>
            </dl>
            <dl id="menu-member">
                <dt><i class="Hui-iconfont">&#xe60d;</i> 会员管理<i class="Hui-iconfont menu_dropdown-arrow">&#xe6d5;</i></dt>
                <dd>
                    <ul>
                        <li><a _href="{{url('/admin/member')}}" data-title="会员列表" href="javascript:;">会员列表</a></li>
                    </ul>
                </dd>
            </dl>
        </div>
    </aside>
    <div class="dislpayArrow"><a class="pngfix" href="javascript:void(0);" onClick="displaynavbar(this)"></a></div>
    <section class="Hui-article-box">
        <div id="Hui-tabNav" class="Hui-tabNav">
            <div class="Hui-tabNav-wp">
                <ul id="min_title_list" class="acrossTab cl">
                </ul>
            </div>
            <div class="Hui-tabNav-more btn-group"><a id="js-tabNav-prev" class="btn radius btn-default size-S" href="javascript:;"><i class="Hui-iconfont">&#xe6d4;</i></a><a id="js-tabNav-next" class="btn radius btn-default size-S" href="javascript:;"><i class="Hui-iconfont">&#xe6d7;</i></a></div>
        </div>
        <div id="iframe_box" class="Hui-article">
            <div class="show_iframe">
                <div style="display:none" class="loading"></div>
            </div>
        </div>
    </section>
@endsection

@section('my-js')
    <script>
        $(function(){
            let title = localStorage.title ? localStorage.title : '我的桌面',
                href = localStorage.href ? localStorage.href : ['{{url("admin/welcome")}}'];
            let li = '<li class="active"><span title="'+title+'">'+title+'</span><em></em></li>';
            let iframe = '<iframe scrolling="yes" frameborder="0" src="'+href+'"></iframe>'
            $('.show_iframe').append(iframe)
            $('#min_title_list').html(li);
        })
        $('.menu_dropdown li').click(function () {
            title = $(this).find('a').attr('data-title');
            href = $(this).find('a').attr('_href');
            localStorage.title = title;
            localStorage.href = href;
        })
    </script>
@endsection