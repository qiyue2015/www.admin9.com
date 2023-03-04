<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryAddRequest;
use App\Http\Resources\CategoryCollection;
use App\Models\Category;
use Illuminate\Http\JsonResponse;

class CategoryController extends Controller
{
    /**
     * 分类例表
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $list = Category::orderBy('sort')->get();
        return $this->success(new CategoryCollection($list));
    }

    /**
     * 添加栏目
     *
     * @param  CategoryAddRequest  $request
     * @return JsonResponse
     */
    public function store(CategoryAddRequest $request): JsonResponse
    {
        $params = $request->all();
        $params['children'] = [];
        $params['parents'] = [];
        if ($params['parent_id']) {
            $parent = Category::findOrFail($params['parent_id']);
            if ($parent->is_last) {
                return $this->fail('父栏目不能是终极栏目');
            }
            if ($parent->parents) {
                $params['parents'] = [...$parent->parents, ...[$parent->id]];
            }
        }

        $category = Category::create($params);

        if ($category->parent_id) {
            // 修改父栏目的子栏目
            $parent = Category::findOrFail($category->parent_id);
            if ($parent->children) {
                $parent->children = [...$parent->children, ...[$category->id]];
                $parent->save();
                if ($parent->parents) {
                    // 更改父类别的父栏目的子栏目
                    Category::whereIn('id', $parent->parents)->each(function ($super) use ($category) {
                        $super->children = [...$super->children, ...[$category->id]];
                        $super->save();
                    });
                }
            }
        }

        return $this->success();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function show(Category $category)
    {
        //
    }

    /**
     * 修改分类
     *
     * @param  CategoryAddRequest  $request
     * @param  Category  $category
     * @return JsonResponse
     */
    public function update(CategoryAddRequest $request, Category $category): JsonResponse
    {
        $params = $request->all();
        if ($params['parent_id'] && $params['parent_id'] !== $category->parent_id) {
            // 大栏目跟原栏目相同
            if ($params['parent_id'] === $category->id) {
                return $this->fail('您选择的大栏目跟本栏目是同一对象，请重新选择大栏目');
            }

            // 取得现在大栏目
            $parent = Category::findOrFail($params['parent_id']);
            if ($parent->is_last) {
                return $this->fail('父栏目不能是终极栏目');
            }

            // 是否非法父栏目
            if (!$params['is_last'] && $parent->children && in_array($category->id, $parent->children, true)) {
                return $this->fail('您选择隶属的大栏目是栏目本身的子栏目。请重新选择大栏目');
            }
        }

        $category->update($params);
        if ($category->parent_id) {
            $categories = Category::all(['id', 'parent_id'])->toArray();

            // 修改父栏目的子栏目
            $category->children = getChildCategories($category->id, $categories);

            // 更改父类别的父栏目的子栏目
            $parents = getParentCategories($category->id, $categories);
            Category::whereIn('id', $parents)->each(function ($super) use ($categories) {
                $super->children = getChildCategories($super->id, $categories);
                $super->save();
            });
        }

        return $this->success();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Category  $category
     * @return JsonResponse
     */
    public function destroy(Category $category)
    {
        if (!$category->is_last) {
            // 删除子栏目
            Category::whereIn('id', $category->children)->delete();
        }

        // 删除栏目本身
        $category->delete();

        return $this->success();
    }
}
