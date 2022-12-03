<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Tags
 *
 * @property int $id
 * @property string $name TAG名称
 * @property int $num 信息数量
 * @property int $is_good 是否推荐
 * @method static \Illuminate\Database\Eloquent\Builder|Tags newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Tags newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Tags query()
 * @method static \Illuminate\Database\Eloquent\Builder|Tags whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tags whereIsGood($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tags whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tags whereNum($value)
 * @mixin \Eloquent
 */
class Tags extends Model
{
    use HasFactory;
}
