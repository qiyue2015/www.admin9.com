<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Channel
 *
 * @property int $id
 * @property string $name 名称
 * @property int $sort 排序
 * @method static \Illuminate\Database\Eloquent\Builder|Channel newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Channel newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Channel query()
 * @method static \Illuminate\Database\Eloquent\Builder|Channel whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Channel whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Channel whereSort($value)
 * @mixin \Eloquent
 * @property string|null $mapping 映射名称
 * @method static \Illuminate\Database\Eloquent\Builder|Channel whereMapping($value)
 */
class Channel extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $guarded = [];
}
