@extends('layouts.default')
@if($category_id ===0)
@section('title', config('site.name'))
@section('keywords', config('site.keywords'))
@section('description', config('site.description'))
@endif

@if($category_id)
@section('title', $category->name.'-'.config('site.name'))
@endif
@section('content')
    <div class="article-list">
@loop($category_id, 30)
        <div class="article-item">
            <a href="{{ $row->link() }}" class="article-img">
                <img src="{{ $row->cover }}" alt="{{ $row->title }}" class="object-cover rounded"/>
            </a>
            <div class="article-text">
                <h2><a href="{{ $row->link() }}" title="{{ $row->title }}">{{ $row->title }}</a></h2>
                <div class="explain">{{ $row->description }}</div>
@if($row->tags)
                <div class="tags">
                    @foreach($row->tags as $tag)<a href="#">{{ $tag }}</a>@endforeach

                </div>
@endif
            </div>
        </div>
@endloop
    </div>
@endsection
