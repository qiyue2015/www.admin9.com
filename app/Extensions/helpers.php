<?php

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

