<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Photo
 *
 * @property int $id
 * @property string $category 分类
 * @property string $title 相册名称
 * @property string|null $tags 标签
 * @property string $artist 艺人名称
 * @property string $platform 秀人平台
 * @property array|null $images 图片
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Photo newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Photo newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Photo query()
 * @method static \Illuminate\Database\Eloquent\Builder|Photo whereArtist($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Photo whereCategory($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Photo whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Photo whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Photo whereImages($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Photo wherePlatform($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Photo whereTags($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Photo whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Photo whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property int $view_num 点击量
 * @method static \Illuminate\Database\Eloquent\Builder|Photo whereViewNum($value)
 */
class Photo extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'images' => 'array',
    ];
}
