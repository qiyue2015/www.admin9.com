<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Train
 *
 * @property int $id
 * @property string $title 标题
 * @property string $content 纯文内容
 * @property string|null $lv1_categories 一级分类
 * @property string|null $lv2_categories 二级分类
 * @property string|null $tags 二级分类
 * @property int $status 状态
 * @method static \Illuminate\Database\Eloquent\Builder|Train newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Train newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Train query()
 * @method static \Illuminate\Database\Eloquent\Builder|Train whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Train whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Train whereLv1Categories($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Train whereLv2Categories($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Train whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Train whereTags($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Train whereTitle($value)
 * @mixin \Eloquent
 */
class Train extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $guarded = [];
}
