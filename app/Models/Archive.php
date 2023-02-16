<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Conner\Tagging\Taggable;

/**
 * App\Models\Archive
 *
 * @property int $id
 * @property string $title
 * @property string|null $subtitle
 * @property string|null $search_title
 * @property string|null $keywords
 * @property string|null $description
 * @property string|null $cover
 * @property string|null $images
 * @property int $view_num 点击量
 * @property int $is_publish
 * @property string $publish_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property array $tag_names
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Tagged[] $tags
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Conner\Tagging\Model\Tagged> $tagged
 * @property-read int|null $tagged_count
 * @method static \Illuminate\Database\Eloquent\Builder|Archive newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Archive newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Archive query()
 * @method static \Illuminate\Database\Eloquent\Builder|Archive whereCover($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Archive whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Archive whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Archive whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Archive whereImages($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Archive whereIsPublish($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Archive whereKeywords($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Archive wherePublishAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Archive whereSearchTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Archive whereSubtitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Archive whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Archive whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Archive whereViewNum($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Archive withAllTags($tagNames)
 * @method static \Illuminate\Database\Eloquent\Builder|Archive withAnyTag($tagNames)
 * @method static \Illuminate\Database\Eloquent\Builder|Archive withoutTags($tagNames)
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Conner\Tagging\Model\Tagged> $tagged
 * @mixin \Eloquent
 */
class Archive extends Model
{
    use HasFactory, Taggable;

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'images' => 'array',
    ];
}
