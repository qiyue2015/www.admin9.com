<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Category
 *
 * @property int $id
 * @property int $parent_id 父ID
 * @property string $name 名称
 * @property string $alias alias
 * @property string|null $slug
 * @property int $sort 排序
 * @property int $is_last 0非终极栏目 1终极栏目
 * @property int $is_list 0封面模式 1列表模式
 * @method static \Illuminate\Database\Eloquent\Builder|Category newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Category newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Category query()
 * @method static \Illuminate\Database\Eloquent\Builder|Category whereAlias($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Category whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Category whereIsLast($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Category whereIsList($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Category whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Category whereParentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Category whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Category whereSort($value)
 * @property int $num 分类信息数量
 * @property int $is_show 导航展示 1展示
 * @property array|null $children 子栏目ID集合
 * @property array|null $parents 父栏目ID集合
 * @method static \Illuminate\Database\Eloquent\Builder|Category whereChildren($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Category whereIsShow($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Category whereNum($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Category whereParents($value)
 * @mixin \Eloquent
 */
class Category extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $hidden = ['sort'];

    protected $guarded = [];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'children' => 'array',
        'parents' => 'array',
    ];

    public function link(): string
    {
        return route('archive.index', ['slug' => $this->slug], false);
    }
}
