<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\Storage;
use Jenssegers\Optimus\Optimus;

/**
 * App\Models\Archive
 *
 * @property int $id
 * @property string $title
 * @property int $category_id 分类ID
 * @property mixed|null $flag
 * @property string|null $subtitle
 * @property string|null $search_title
 * @property string|null $keywords
 * @property string|null $description
 * @property string $cover
 * @property array $tags
 * @property string|null $writer 作者
 * @property string|null $source_name 来源名称
 * @property string|null $source_url 来源地址
 * @property int $has_cover 是否有封面
 * @property string|null $images
 * @property int $view_num 点击量
 * @property int $user_id 后台发布ID
 * @property int $checked
 * @property int $published
 * @property string $publish_at
 * @property int $is_html 生成 PC html
 * @property int $is_wap_html 生成 WAP html
 * @property int $is_sitemap 生成
 * @property string|null $task_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Category|null $category
 * @property-read \App\Models\ArchiveExtend|null $extend
 * @property-read \App\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder|Archive checked()
 * @method static \Illuminate\Database\Eloquent\Builder|Archive newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Archive newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Archive published()
 * @method static \Illuminate\Database\Eloquent\Builder|Archive query()
 * @method static \Illuminate\Database\Eloquent\Builder|Archive whereCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Archive whereChecked($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Archive whereCover($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Archive whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Archive whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Archive whereFlag($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Archive whereHasCover($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Archive whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Archive whereImages($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Archive whereIsHtml($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Archive whereIsPublish($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Archive whereIsSitemap($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Archive whereIsWapHtml($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Archive whereKeywords($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Archive wherePublishAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Archive whereSearchTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Archive whereSourceName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Archive whereSourceUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Archive whereSubtitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Archive whereTags($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Archive whereTaskId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Archive whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Archive whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Archive whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Archive whereViewNum($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Archive whereWriter($value)
 * @mixin \Eloquent
 */
class Archive extends Model
{

    protected $guarded = [];

    /**
     * 是否有封面
     * @return void
     */
    public function setHasCoverAttribute(): void
    {
        $this->attributes['has_cover'] = !is_null($this->getAttribute('cover'));
    }

    /*
     * 是否发布
     */
    public function setIsPublishAttribute(): void
    {
        $this->attributes['published'] = true;
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getPublishAtAttribute($value): string
    {
        return now()->parse($value)->format('Y-m-d');
    }

    public function getCoverAttribute($value): string
    {
        if (str()->contains($value, 'https') || str()->contains($value, 'http')) {
            return $value;
        }

        if (str()->contains($value, '/storage')) {
            return $value;
        }

        return Storage::url($value);
    }

    public function getTagsAttribute($value): array
    {
        return array_filter(explode(',', $value));
    }

    public function scopeChecked($query): void
    {
        $query->where('checked', true);
    }

    public function scopePublished($query): void
    {
        $query->where('published', true);
    }

    /**
     * @param  array  $params
     * @return string
     */
    public function link(array $params = []): string
    {
        $encode_id = app(Optimus::class)->encode($this->id);
        $params = array_merge(['id' => $encode_id], $params);
        return route('archive.show', $params, false);
    }

    public function extend(): HasOne
    {
        $key = $this->id % 10;
        $tableName = 'archives_extend_'.$key;
        $instance = $this->newRelatedInstance(ArchiveExtend::class);
        $instance->setTable($tableName);

        $foreignKey = 'id';
        $localKey = 'id';

        return $this->newHasOne($instance->newQuery(), $this, $instance->getTable().'.'.$foreignKey, $localKey);
    }
}
