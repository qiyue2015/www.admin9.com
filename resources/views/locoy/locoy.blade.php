@extends('layouts.default')

@section('title', 'Locoy')

@section('content')
    <div class="article-list">
@foreach($list as $row)
        <div class="article-item">
            <a href="{{ $row->link() }}" class="article-img">
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
@endforeach
    </div>
    <div class="m-10">{{ $list->links() }}</div>
@endsection
