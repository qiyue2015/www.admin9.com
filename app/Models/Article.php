<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Jenssegers\Optimus\Optimus;

/**
 * App\Models\Article
 *
 * @property int $id
 * @property int $channel_id 频道ID
 * @property int $category_id 分类ID
 * @property string $title 标题
 * @property string|null $author_name 作者
 * @property string|null $digest 摘要
 * @property int $view_num 点击量
 * @property int $checked 已审核
 * @property string|null $cover_url 封面图片
 * @property string|null $source_name 来源
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Article newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Article newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Article query()
 * @method static \Illuminate\Database\Eloquent\Builder|Article whereAuthorName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Article whereCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Article whereChannelId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Article whereChecked($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Article whereCoverUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Article whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Article whereDigest($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Article whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Article whereSourceName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Article whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Article whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Article whereViewNum($value)
 * @mixin \Eloquent
 */
class Article extends Model
{
    use HasFactory;

    protected $guarded = [];

    /**
     * @param  array  $params
     * @return string
     */
    public function link(array $params = []): string
    {
        $encode_id = app(Optimus::class)->encode($this->id);
        $params = array_merge(['id' => $encode_id], $params);
        return route('article.show', $params, false);
    }
}
