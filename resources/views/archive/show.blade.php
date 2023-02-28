@extends('layouts.default')

@section('title', $archive->title)
@section('keywords', $archive->keywords)
@section('description', $archive->description)

@section('content')
    <div class="text-sm breadcrumbs">
        <ul>
            <li><a>{{ config('site.name') }}</a></li>
@if($archive->category)
            <li><a href="{{ $archive->category->link() }}">{{ $archive->category->name }}</a></li>
@endif
        </ul>
    </div>
    <div class="archive">
        <h1>{{ $archive->title }}</h1>
        <div class="bar-wrap">
            <span class="time">{{ $archive->publish_at }}</span>
@if($archive->user)
            <span class="user">{{ $archive->user->name }}</span>
@endif
        </div>
        <div class="content">
@if($archive->extend->content)
            {!! $archive->extend->content !!}
@endif
        </div>
        <div class="tips">
            <div class="tags">@foreach($archive->tags as $tag){{ $tag }}@endforeach</div>
            <div class="report"></div>
        </div>
    </div>

    <div class="article-list mt-8">
@loop($category_id, 10, 'c', 1)
        <div class="article-item">
            <a href="{{ $row->link() }}" class="article-img bg-indigo-300 rounded">
                <img src="{{ $row->cover }}" alt="{{ $row->title }}" class="object-cover rounded"/>
            </a>
            <div class="article-text">
                <h2><a href="{{ $row->link() }}" title="{{ $row->title }}">{{ $row->title }}</a></h2>
                <div class="explain">{{ $row->description }}</div>
@if($row->tags)
                    <div class="tags">@foreach($row->tags as $tag)<a href="#">{{ $tag }}</a>@endforeach</div>
@endif
            </div>
        </div>
@endloop
    </div>
@endsection

@push('script')
    @vite('resources/js/view.js')
@endpush
