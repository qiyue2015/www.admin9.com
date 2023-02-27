<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="winter">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <title>Locoy</title>
    @vite(['resources/sass/app.scss'])
</head>
<body>
<div class="max-w-6xl mx-auto">
    <table class="table-fixed">
        <thead>
        <tr>
            <th class="w-12">ID</th>
            <th>TITLE</th>
        </tr>
        </thead>
        <tbody>
        @foreach($list as $row)
            <tr>
                <td class="text-center">{{$row->id}}</td>
                <td class="py-4"><a href="{{$row->link()}}" class="link link-secondary" target="_blank">{{$row->title}}</a></td>
            </tr>
            <tr>
                <td></td>
                <td>{{$row->description}}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
</body>
</html>
