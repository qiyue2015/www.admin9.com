<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no"/>
    <title>{{ $article->title }}-{{ config('app.name') }}</title>
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
            <div class="sm:ml-4 sm:pl-4 sm:border-l sm:border-slate-200 dark:sm:border-slate-200/5">前端资源导航站</div>
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
                <span class="category"><a href="">{{ $article->category_id }}</a></span>
            </div>
        </div>
        <div class="article-content mt-4">
            {!! $content !!}
        </div>
        <nav class="article-nav text-sm">
    @if($article->prev())
            <span class="article-nav-prev">上一篇<br> <a href="{{ $article->prev()->link() }}">{{ $article->prev()->title }}</a></span>
    @endif
    @if($article->next())
            <span class="article-nav-next">下一篇<br> <a href="{{ $article->next()->link() }}">{{ $article->next()->title }}</a></span>
    @endif
        </nav>
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
</body>
</html>
