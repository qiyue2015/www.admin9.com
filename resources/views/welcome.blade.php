<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no"/>
    <title>{{ config('app.name') }}</title>
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

<div class="container px-4 mt-10">
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

</body>
</html>
