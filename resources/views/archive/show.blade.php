@extends('layouts.default')
@section('title', $archive->title.'-'.config('site.sitename'))
@section('keywords', $archive->keywords)
@section('description', $archive->description)
@section('content')

    <div class="archive">
        <h1>{{ $archive->title }}</h1>
        <div class="bar-wrap">
            <span class="time">{{ $archive->publish_at }}</span>
        </div>
        <div class="content">{!! $archive->extend->content !!}</div>
        <div class="tips">
            <div class="tags">
                @foreach($archive->tags as $tag)<a href="#">{{ $tag }}</a>@endforeach
            </div>
            <div class="report"></div>
        </div>

    </div>

    <div class="article-list mt-8">
        @loop($category_id, 36, 'c', 1)
        <div class="article-item">
            <a href="{{ $row->link() }}" class="article-img bg-indigo-300 rounded">
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

@section('scripts')
    @vite('resources/js/view.js')
@endsection
