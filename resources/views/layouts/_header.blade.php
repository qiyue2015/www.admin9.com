<div class="navbar-wrap fixed-header">
    <div class="navbar">
        <div class="navbar-start">
            <a href="/" class="site-name" rel="home" title="{{ config('site.name') }}">HaoQi<span>.net</span></a>
        </div>
        <div class="navbar-center">
            <ul class="menu menu-horizontal px-1">
                <li><a href="/" target="_self" data-tab-id="0" @if($category_id===0) class="active"@endif>推荐</a></li>
@listshowclass(0, 12)
                <li><a href="{{ $row->link() }}/" target="_self" data-tab-id="{{$row->id}}" @if($category_id===$row->id) class="active"@endif>{{ $row->name }}</a></li>
@endlistshowclass
            </ul>
        </div>
        <div class="navbar-end">
            <label for="my-modal-3" class="btn btn-square btn-ghost mr-2 lg:hidden">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" class="inline-block w-5 h-5 stroke-current"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
            </label>
            <span data-toggle-theme="night" data-act-class="pl-4" class="border rounded-full border-primary flex items-center cursor-pointer w-10 transition-all duration-300 ease-in-out pl-0">
                <span class="rounded-full w-3 h-3 m-1 bg-primary"></span>
            </span>
        </div>
    </div>
</div>

<div class="banner-wrapper">
    <div class="banner-title">知识达人招募</div>
    <div class="search-group">
            <div class="middle-align search-btn">
                <span class="iconfont icon-search"></span>
            </div>
            <input class="search-input" type="text" placeholder="搜索一下，用好奇心驱动世界" autocomplete="off">
            <div class="clear-btn middle-align">
                <span class="iconfont icon-close"></span>
            </div>
    </div>
    <div class="hot-words">
        <span class="hot-word-search">热门搜索</span>
        <a href="/view/1234802473.html">金盏菊怎样繁殖</a>
        <a href="/view/44898633.html">桃花运是什么运</a>
        <a href="/view/1093150395.html">根外追肥有什么特点</a>
    </div>
</div>

