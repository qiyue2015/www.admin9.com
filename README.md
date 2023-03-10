## 参考
- [Ity, 基于 laravel9 + vue 的基础后台](https://gitee.com/pleaseyang/Ity)
- [hdcms，Laravel9 + Vue 多站点、多模块 SASS 平台系统](https://gitee.com/houdunren/v2022/tree/master/admin-php)
- [L03 Laravel 教程 - 实战构架 API 服务器 ( Laravel 9.x ) 【课程】](https://learnku.com/courses/laravel-advance-training/9.x)
- [L03 Laravel 教程 - 实战构架 API 服务器 ( Laravel 9.x ) 【源码】](https://github.com/summerblue/larabb)
- [Laravel Application skeleton for 安正超](https://github.com/overtrue/laravel-skeleton)
- https://github.com/moell-peng/mojito
- https://github.com/thecodeholic/laravel-vue-survey
- https://github.com/yusuftaufiq/laravel-books-api

## 文档
- [tailwindcss](https://tailwindcss.com/docs/font-size)
- [laravel 9 中文文档](https://learnku.com/docs/laravel/9.x)
- [Laravel 英文文档](https://laravel.com/docs/9.x)
- [Laravel 速查表](https://learnku.com/docs/laravel-cheatsheet/9.x)

## 模板标签
### 循环栏目导航标签
```
@listshowclass(0, 8)
    <a href="{{ $row->link() }}">{{ $row->name }}</a>
@endlistshowclass
```

### 灵动标签
```
@loop($category_id, 30, 'c', 1)
    <a href="{{ $row->link() }}">{{ $row->title }}</a>
@endloop
```

