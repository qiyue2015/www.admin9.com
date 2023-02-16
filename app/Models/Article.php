<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Jenssegers\Optimus\Optimus;
use Laravel\Scout\Searchable;

/**
 * App\Models\Article
 *
 * @property int $id
 * @property int $category_id 分类ID
 * @property string $title 标题
 * @property string $keywords 关键词
 * @property string $description 描述
 * @property int $view_num 点击量
 * @property int $checked 已审核
 * @property int $status
 * @property string $cover_url 封面图片
 * @property string $source_name 来源
 * @property string $author_name 作者
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Category|null $category
 * @method static \Illuminate\Database\Eloquent\Builder|Article checked()
 * @method static \Illuminate\Database\Eloquent\Builder|Article newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Article newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Article query()
 * @method static \Illuminate\Database\Eloquent\Builder|Article whereAuthorName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Article whereCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Article whereChecked($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Article whereCoverUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Article whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Article whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Article whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Article whereKeywords($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Article whereSourceName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Article whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Article whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Article whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Article whereViewNum($value)
 * @property array $tag_names
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Tagged[] $tags
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Conner\Tagging\Model\Tagged> $tagged
 * @property-read int|null $tagged_count
 * @method static \Illuminate\Database\Eloquent\Builder|Article withAllTags($tagNames)
 * @method static \Illuminate\Database\Eloquent\Builder|Article withAnyTag($tagNames)
 * @method static \Illuminate\Database\Eloquent\Builder|Article withoutTags($tagNames)
 * @mixin \Eloquent
 */
class Article extends Model
{
    use HasFactory, Searchable;
    use \Conner\Tagging\Taggable;

    protected $guarded = [];

    /**
     * 获取模型的可索引的数据
     * @return array
     */
    public function toSearchableArray(): array
    {
        return [
            'title' => $this->title,
            'description' => $this->description,
            'keywords' => $this->keywords,
        ];
    }

    /**
     * 确定模型信息是否可搜索
     * @return bool
     */
    public function shouldBeSearchable(): bool
    {
        return $this->checked === 1 && $this->category_id > 0;
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function scopeChecked($query): void
    {
        $query->where('checked', true);
    }

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
