<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Dongde
 *
 * @method static \Illuminate\Database\Eloquent\Builder|Dongde newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Dongde newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Dongde query()
 * @property int $id
 * @property string $alias
 * @property string $type
 * @property int $status
 * @property int $category_id
 * @property string $category_name
 * @property int $channel_id
 * @property string $title
 * @property string $subtitle
 * @property string $search_title
 * @property string $toutiao_title
 * @property string $sogou_title
 * @property string $keywords
 * @property string $tags
 * @property string $description
 * @property string $cover
 * @property string $publish_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string $url
 * @property int $user_id
 * @property int $dongde_id
 * @method static \Illuminate\Database\Eloquent\Builder|Dongde whereAlias($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Dongde whereCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Dongde whereCategoryName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Dongde whereChannelId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Dongde whereCover($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Dongde whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Dongde whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Dongde whereDongdeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Dongde whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Dongde whereKeywords($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Dongde wherePublishAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Dongde whereSearchTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Dongde whereSogouTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Dongde whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Dongde whereSubtitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Dongde whereTags($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Dongde whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Dongde whereToutiaoTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Dongde whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Dongde whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Dongde whereUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Dongde whereUserId($value)
 * @mixin \Eloquent
 */
class Dongde extends Model
{
    use HasFactory;
}
