<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="winter">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <title>@yield('title')</title>
    <meta name="keywords" content="@yield('keywords', config('site.name'))"/>
    <meta name="description" content="@yield('description')"/>
    <meta name="renderer" content="webkit">
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no, viewport-fit=cover">
    <link rel="dns-prefetch" href="{{ config('app.url') }}"/>
@if (config('app.asset_url') && config('app.asset_url') != config('app.url'))
    <link rel="dns-prefetch" href="{{ config('app.asset_url') }}"/>
@endif
@if (config('app.pic_url'))
    <link rel="dns-prefetch" href="{{ config('app.pic_url') }}"/>
@endif
    <link rel="dns-prefetch" href="https://lf26-cdn-tos.bytecdntp.com"/>
    <meta name="applicable-device" content="pc,mobile"/>
    <meta name="force-rendering" content="webkit">
    <meta name="format-detection" content="telephone=no"/>
    @vite(['resources/sass/app.scss'])

@yield('css')
</head>
<body>
@include('layouts._header')

<div class="container page-content pt-4">
    <div class="left-side">
@if($category_id)
        <div class="text-sm breadcrumbs mb-4">
            <ul>
                <li><a href="/" title="{{ config('app.name') }}">首页</a></li>
                <li><a href="{{ $category->link() }}" title="{{ $category->name }}">{{ $category->name }}</a></li>
            </ul>
        </div>
@endif
        @yield('content')
    </div>
    <div class="right-side">
        <div class="block">
            <div class="side-photo mb-8">
                <img src="/img/banner.jpg?m=1" alt="">
            </div>
            <div class="panel">
                <div class="panel-hd">
                    <span class="title">排行榜</span>
                </div>
                <div class="panel-bd">
                    <ul class="rank-list">
@loop($category_id, 8, 'h', 1)
                        <li class="item">
                            <span class="rank-index">{{ $loop->iteration }}</span>
                            <a href="{{ $row->link() }}" target="_blank" title="{{ $row->title }}">{{ $row->title }}</a>
                        </li>
@endloop
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

@include('layouts._footer')

<script type="text/javascript" src="//lf26-cdn-tos.bytecdntp.com/cdn/expire-1-M/jquery/2.2.4/jquery.min.js"></script>
@yield('scripts')

@vite('resources/js/app.js')
</body>
</html>
