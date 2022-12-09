<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Tags
 *
 * @property int $id
 * @property int $category_id 频道ID
 * @property int $parent_id 父ID
 * @property string $name TAG名称
 * @property int $num 信息数量
 * @property int $is_good 是否推荐
 * @property mixed $extend
 * @method static \Illuminate\Database\Eloquent\Builder|Tags newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Tags newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Tags query()
 * @method static \Illuminate\Database\Eloquent\Builder|Tags whereCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tags whereExtend($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tags whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tags whereIsGood($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tags whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tags whereNum($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tags whereParentId($value)
 * @mixin \Eloquent
 */
class Tags extends Model
{
    use HasFactory;
}
