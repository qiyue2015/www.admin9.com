<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no"/>
    <title>{{ config('app.name') }}</title>
    <meta name="keywords" content="Admin9" />
    <meta name="description" content="Admin9 是集科技、生活、教育、美食、体育、旅游、健康等为一体的综合资讯门户网站，以满足广大网民日益增长的信息资讯生活需求为目的，旨在为用户提供最新、最热、最全的资讯新闻以及百科类专业问答。" />
    <!-- Fonts -->
    <link href="https://fonts.bunny.net/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">
    @vite(['resources/sass/app.scss'])

</head>
<body class="antialiased text-slate-500 dark:text-slate-400 bg-white dark:bg-slate-900">
<div class="absolute z-20 top-0 inset-x-0 flex justify-center overflow-hidden pointer-events-none">
    <div class="w-[108rem] flex-none flex justify-end">
        <picture>
            <source srcset="https://tailwindcss.com/_next/static/media/docs@30.8b9a76a2.avif" type="image/avif">
            <img src="https://tailwindcss.com/_next/static/media/docs@tinypng.d9e4dcdc.png" alt="" class="w-[71.75rem] flex-none max-w-none dark:hidden" decoding="async">
        </picture>
        <picture>
            <source srcset="https://tailwindcss.com/_next/static/media/docs-dark@30.1a9f8cbf.avif" type="image/avif">
            <img src="https://tailwindcss.com/_next/static/media/docs-dark@tinypng.1bbe175e.png" alt="" class="w-[90rem] flex-none max-w-none hidden dark:block" decoding="async">
        </picture>
    </div>
</div>
<header>
    <div class="max-w-8xl mx-auto">
        <div class="py-4 border-b border-slate-900/10 lg:px-8 lg:border-0 dark:border-slate-300/10 mx-4 lg:mx-0 flex leading-8">
            <h1><a href="/" rel="home">{{ config('app.name') }}</a></h1>
        </div>
    </div>
</header>

<div class="container px-4">
    <div class="news mb-8">
        <div class="box">
            <div class="hd">科技动态</div>
            <div class="box grid gap-4 md:grid-cols-2">
                <ul class="list">
@foreach($news as $row)
@if($loop->index && $loop->index % 10 === 0)
                </ul>
                <ul class="list">
@endif
                    <li><a href="{{ $row->link() }}">{{ $row->title }}</a></li>
@endforeach
                </ul>
            </div>
        </div>
    </div>
    <div id="main"></div>
</div>

<footer class="text-sm">
    <div class="container px-4">
        <div class="py-10 sm:flex justify-between text-slate-500 dark:border-slate-200/5">
            <div class="sm:flex">
                <p>Copyright © 2022 西昌齐跃网络科技有限责任公司.</p>
                <p class="sm:ml-4 sm:pl-4 sm:border-l sm:border-slate-200 dark:sm:border-slate-200/5">
                    <a href="https://beian.miit.gov.cn/" rel="external nofollow" target="_blank">蜀ICP备2022018286号-1</a>
                </p>
            </div>
            <span class="hover:text-slate-900 dark:hover:text-slate-400">Laravel v{{ Illuminate\Foundation\Application::VERSION }}</span>
        </div>
    </div>
</footer>

@vite('resources/js/app.js')

<script>
    !function(p){"use strict";!function(t){var s=window,e=document,i=p,c="".concat("https:"===e.location.protocol?"https://":"http://","sdk.51.la/js-sdk-pro.min.js"),n=e.createElement("script"),r=e.getElementsByTagName("script")[0];n.type="text/javascript",n.setAttribute("charset","UTF-8"),n.async=!0,n.src=c,n.id="LA_COLLECT",i.d=n;var o=function(){s.LA.ids.push(i)};s.LA?s.LA.ids&&o():(s.LA=p,s.LA.ids=[],o()),r.parentNode.insertBefore(n,r)}()}({id:"JtmgxCrsJJVl7F2Z",ck:"JtmgxCrsJJVl7F2Z"});
</script>
</body>
</html>
