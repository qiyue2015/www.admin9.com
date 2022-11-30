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
    <div class="p-10 box">
@foreach($data as $row)
        <ul>
            <span title="{{ $row->title }}">{{ $row->id }}：</span>
@foreach($categoryIds as $categoryId)
            <a href="https://www.yebaike.com/e/action/ShowInfo.php?classid={{$categoryId}}&id={{ $row->id }}">{{$categoryId}}</a>
@endforeach
        </ul>
@endforeach
    </div>
</body>
</html>
