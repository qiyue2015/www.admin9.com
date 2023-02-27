<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="winter">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <title>Locoy</title>
    @vite(['resources/sass/app.scss'])
</head>
<body>
<div class="container pt-4">
    <dl>
@foreach($list as $row)
        <dt>（{{$row->id}}）<a href="{{$row->link()}}" target="_blank">{{$row->title}}</a></dt>
        <dd class="mb-8">{{$row->description}}</dd>
@endforeach
    </dl>
</div>
</body>
</html>
