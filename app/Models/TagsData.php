<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\TagsData
 *
 * @property int $id
 * @property string $tags_id Tags ID
 * @property int $channel_id 频道ID
 * @property int $article_id 信息ID
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|TagsData newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TagsData newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TagsData query()
 * @method static \Illuminate\Database\Eloquent\Builder|TagsData whereArticleId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TagsData whereChannelId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TagsData whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TagsData whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TagsData whereTagsId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TagsData whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class TagsData extends Model
{
    use HasFactory;
}
