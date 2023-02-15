<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Dongde
 *
 * @property int $id
 * @property int $_id
 * @property string $type
 * @property int $category_id
 * @property int $channel_id
 * @property int $user_id
 * @property string $title
 * @property string $subtitle
 * @property string $search_title
 * @property string $toutiao_title
 * @property string $sogou_title
 * @property string $tags
 * @property string $description
 * @property string $publish_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Dongde newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Dongde newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Dongde query()
 * @method static \Illuminate\Database\Eloquent\Builder|Dongde whereCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Dongde whereChannelId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Dongde whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Dongde whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Dongde whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Dongde wherePublishAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Dongde whereSearchTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Dongde whereSogouTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Dongde whereSubtitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Dongde whereTags($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Dongde whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Dongde whereToutiaoTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Dongde whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Dongde whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Dongde whereUserId($value)
 * @mixin \Eloquent
 * @property string $alias
 * @property int $status
 * @property string $keywords
 * @property string $cover
 * @property string $url
 * @property int $dongde_id
 * @method static \Illuminate\Database\Eloquent\Builder|Dongde whereAlias($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Dongde whereCover($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Dongde whereDongdeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Dongde whereKeywords($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Dongde whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Dongde whereUrl($value)
 * @property string $category_name
 * @method static \Illuminate\Database\Eloquent\Builder|Dongde whereCategoryName($value)
 */
class Dongde extends Model
{
    use HasFactory;
}
