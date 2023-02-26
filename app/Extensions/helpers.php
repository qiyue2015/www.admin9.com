<?php

if (!function_exists('cache_increment')) {
    /**
     * @param $key
     * @param  int  $increment
     * @param $ttl
     *
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    function cache_increment($key, int $increment = 1, $ttl = null)
    {
        if (Cache::has($key)) {
            Cache::increment($key, $increment);
        } else {
            Cache::set($key, 1, $ttl);
        }
    }
}

if (!function_exists('redis_cache')) {
    /**
     * Return redis object with cache driver.
     *
     * @return \Illuminate\Redis\Connections\Connection|\Redis
     */
    function redis_cache()
    {
        return \Illuminate\Support\Facades\Redis::connection('cache');
    }
}

if (!function_exists('getParentCategories')) {
    /**
     * 获取某一分类的所有子分类.
     *
     * @param $categoryId
     * @param $categories
     * @return array
     */
    function getChildCategories($categoryId, $categories): array
    {
        $childCategories = [];

        // 遍历所有的分类，找出所有的子分类
        foreach ($categories as $c) {
            if ($c['parent_id'] === $categoryId) {
                $childCategories[] = $c['id'];
                // 递归调用函数来获取所有的子分类
                $childCategories = array_merge($childCategories, getChildCategories($c['id'], $categories));
            }
        }

        return $childCategories;
    }
}

if (!function_exists('getParentCategories')) {
    /**
     * 获取某一个子类的所有父级.
     *
     * @param $categoryId
     * @param  array  $categories
     * @return array
     */
    function getParentCategories($categoryId, array $categories = []): array
    {
        $parentCategories = [];

        // 从分类数组中查询该分类的信息
        $category = null;
        foreach ($categories as $c) {
            if ($c['id'] === $categoryId) {
                $category = $c;
                break;
            }
        }

        // 如果这个分类有父级分类，则将其加入数组
        if ($category && $category['parent_id']) {
            $parentCategories[] = $category['parent_id'];
            // 递归调用函数来获取所有的父级分类
            $parentCategories = array_merge($parentCategories, getParentCategories($category['parent_id'], $categories));
        }

        return $parentCategories;
    }
}

if (!function_exists('for_show_sub_class')) {
    /**
     * 循环栏目导航标签.
     * @param  int  $categoryId
     * @param  int  $line
     * @return mixed
     */
    function for_show_sub_class(int $categoryId = 0, int $line = 10): mixed
    {
        $key = Str::snake(__FUNCTION__.'_'.implode('_', func_get_args()));
        return cache()->rememberForever($key, function () use ($line, $categoryId) {
            return \App\Models\Category::query()
                ->where('parent_id', $categoryId)
                ->where('is_show', 1)
                ->orderBy('sort')
                ->take($line)
                ->get(['id', 'parent_id', 'name', 'alias', 'slug']);
        });
    }
}

if (!function_exists('for_show_loop')) {
    /**
     * 灵动标签
     * @param $categoryId
     * @param  int  $line
     * @param $type
     * @param  int  $has_cover
     * @return mixed
     */
    function for_show_loop($categoryId, int $line = 10, $type = null, int $has_cover = 0): mixed
    {
        $key = Str::snake(__FUNCTION__.'_'.implode('_', func_get_args()));
        return cache()->remember($key, now()->addMinutes(30), function () use ($line, $has_cover, $categoryId, $type) {
            return \App\Models\Archive::published()
                ->when($categoryId !== 0, function ($query) use ($categoryId) {
                    return $query->where('category_id', $categoryId);
                })
                ->when($has_cover, function ($query) {
                    return $query->where('has_cover', true);
                })
                ->when($type, function ($query) use ($type) { // 栏目最新
                    return $query->where('flag', $type);
                })
                ->orderBy('publish_at', 'DESC')
                ->take($line)
                ->get();
        });
    }
}

if (!function_exists('getFontSize')) {
    function getFontSize($string): int
    {
        // 先用正则表达式把所有中文替换为空格，就可以用strlen()统计总字数了，总字数就是中文字数与英文字数的总和。
        $string = preg_replace('/[\x80-\xff]{1,3}/', ' ', $string, -1);

        // 统计字符串长度
        $length = mb_strlen($string);

        // 最小字体大小
        $minFontSize = 32;

        // 最大字体大小
        $maxFontSize = 50;

        // 计算每个字符应该减少的字体大小
        $delta = ($maxFontSize - $minFontSize) / (10 - 1);

        // 计算字体大小
        if ($length <= 10) {
            $fontSize = $maxFontSize;
        } else {
            $fontSize = $maxFontSize - ($length - 10) * $delta;
            if ($fontSize < $minFontSize) {
                $fontSize = $minFontSize;
            }
        }

        return round($fontSize);
    }
}
