<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Dataset
 *
 * @property int $id
 * @property mixed $type 类别
 * @property string $category 分类
 * @property string $tags 分类
 * @property string $keyword 关键词
 * @property string $category1 分类1
 * @property string $category2 分类2
 * @property string $category3 分类3
 * @property string $title 标题
 * @property string $desc 标题描述
 * @property string $answer 答案
 * @property int $status 状态
 * @method static \Illuminate\Database\Eloquent\Builder|Dataset newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Dataset newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Dataset query()
 * @method static \Illuminate\Database\Eloquent\Builder|Dataset whereAnswer($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Dataset whereCategory($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Dataset whereCategory1($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Dataset whereCategory2($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Dataset whereCategory3($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Dataset whereDesc($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Dataset whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Dataset whereKeyword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Dataset whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Dataset whereTags($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Dataset whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Dataset whereType($value)
 * @mixin \Eloquent
 * @property string $body 长文本
 * @property string $content
 * @property string $link 链接
 * @property string|null $created_at
 * @property string|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Dataset whereBody($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Dataset whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Dataset whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Dataset whereLink($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Dataset whereUpdatedAt($value)
 */
class Dataset extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $guarded = [];
}
