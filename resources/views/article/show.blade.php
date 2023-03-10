<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no"/>
    <title>{{ $article->title }}-{{ config('site.name') }}</title>
    <meta name="keywords" content="{{ $article->keyboard??'' }}" />
    <meta name="description" content="{{ $article->description ?? '' }}" />
    <!-- Fonts -->
    @vite(['resources/sass/app.scss'])

</head>
<body class="antialiased text-slate-500 dark:text-slate-400 bg-white dark:bg-slate-900">
<header>
    <div class="max-w-8xl mx-auto">
        <div class="py-4 border-b border-slate-900/10 lg:px-8 lg:border-0 dark:border-slate-300/10 mx-4 lg:mx-0 flex leading-8">
            <h1><a href="/" rel="home">{{ config('site.name') }}</a></h1>
            <div class="sm:ml-4 sm:pl-4 sm:border-l sm:border-slate-200 dark:sm:border-slate-200/5">{{ $article->category ? $article->category->name : '' }}</div>
        </div>
    </div>
</header>
<div class="px-4 detail-container">
    <div class="main">
        <h1>{{ $article->title }}</h1>
        <div class="top-bar-inner">
            <div class="py-4">
                <span>{{ $article->created_at }}</span>
                <span class="author">{{ $article->source_name }}</span>
                <span class="category">{{ $article->category ? $article->category->name : '' }}</span>
            </div>
        </div>
        <div class="article-content mt-4">
            {!! $content !!}
        </div>
@if(count($recommendeds))
        <div class="pb-8">
            <div class="text-sky-500 dark:text-sky-400 mb-2">推荐阅读</div>
            <ul class="list-disc list-inside md:grid md:gap-4 md:grid-cols-2">
@foreach($recommendeds as $row)
                <li><a href="{{ $row->link() }}" target="_blank">{{ $row->title }}</a></li>
@endforeach
            </ul>
        </div>
@endif
        <div class="entry-page">
@if($prev)
            <div class="entry-page-prev">
                <a href="{{ $prev->link() }}" title="{{ $prev->title }}" rel="prev"><span>{{ $prev->title }}</span></a>
                <div class="entry-page-info"><span class="float-left"><< 上一篇</span></div>
            </div>
@endif
@if($next)
                <div class="entry-page-next">
                    <a href="{{ $next->link() }}" title="{{ $next->title }}" rel="next"><span>{{ $next->title }}</span></a>
                    <div class="entry-page-info"><span class="float-right">下一篇 >></span></div>
                </div>
@endif
        </div>
    </div>
    <div class="sidebar">
        <div class="box hot">
            <div class="hd">
                <div class="title">热榜</div>
            </div>
            <div class="bd">
                <ol class="text-base">
@foreach($hotList as $hot)
                    <li class="leading-10"><a href="{{ $hot->link() }}">{{ $hot->title }}</a></li>
@endforeach
                </ol>
            </div>
        </div>
    </div>
</div>
<script>
    !function(p){"use strict";!function(t){var s=window,e=document,i=p,c="".concat("https:"===e.location.protocol?"https://":"http://","sdk.51.la/js-sdk-pro.min.js"),n=e.createElement("script"),r=e.getElementsByTagName("script")[0];n.type="text/javascript",n.setAttribute("charset","UTF-8"),n.async=!0,n.src=c,n.id="LA_COLLECT",i.d=n;var o=function(){s.LA.ids.push(i)};s.LA?s.LA.ids&&o():(s.LA=p,s.LA.ids=[],o()),r.parentNode.insertBefore(n,r)}()}({id:"JtmgxCrsJJVl7F2Z",ck:"JtmgxCrsJJVl7F2Z"});
</script>
</body>
</html>
