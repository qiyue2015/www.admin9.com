<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="winter">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <title>@yield('title')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no, viewport-fit=cover">
    <meta name="keywords" content="@yield('keywords')"/>
    <meta name="description" content="@yield('description')"/>
    <meta name="renderer" content="webkit">
    <link rel="dns-prefetch" href="{{ config('app.url') }}"/>
@if (config('app.asset_url') && config('app.asset_url') !== config('app.url'))
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

@stack('style')
</head>
<body>
{{-- Header --}}
    @include('layouts._header')

@if (Route::is(['app.home', '*.index']))
    <div class="banner-wrapper">
        <div class="banner-title">
            <a href="/view/806828457.html" target="_blank">兔哥百科 招募知识达人</a>
        </div>
        <div class="search-group">
            <div class="middle-align search-btn">
                <span class="iconfont icon-search"></span>
            </div>
            <input class="search-input" type="text" placeholder="搜索一下，用好奇心驱动世界" autocomplete="off">
            <div class="clear-btn middle-align">
                <span class="iconfont icon-close"></span>
            </div>
        </div>
    </div>
@endif

<div class="container page-content pt-4">
    {{-- Main --}}
    <div class="left-side">
        @yield('content')
    </div>

    {{-- Sidebar --}}
    <div class="right-side">
        <section>
            @include('layouts._sidebar')
        </section>
    </div>
</div>

@include('layouts._footer')

<script type="text/javascript" src="//lf26-cdn-tos.bytecdntp.com/cdn/expire-1-M/jquery/2.2.4/jquery.min.js"></script>
@vite('resources/js/app.js')
@stack('script')
</body>
</html>
