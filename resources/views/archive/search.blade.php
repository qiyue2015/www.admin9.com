@extends('layouts.default')
@section('title', $title.'-'.config('site.name'))

@section('content')
    <div class="article-list min-h-96"></div>
@endsection

@section('scripts')
    @vite('resources/js/view.js')
@endsection
