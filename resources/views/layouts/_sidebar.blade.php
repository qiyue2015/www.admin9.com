            <div class="side-photo mb-8">
                <img src="/img/banner.jpg?m=1" alt="">
            </div>
            <div class="panel rank">
                <div class="panel-hd">
                    <span class="title">排行榜</span>
                </div>
                <div class="panel-bd">
                    <ul class="rank-list">
@loop($category_id, 8, 'h', 1)
                        <li class="item">
                            <span class="rank-index">{{ $loop->iteration }}</span>
                            <a href="{{ $row->link() }}" target="_blank" title="{{ $row->title }}">{{ $row->title }}</a>
                        </li>
@endloop
                    </ul>
                </div>
            </div>
