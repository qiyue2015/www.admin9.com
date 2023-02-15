<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Laravel\Scout\Attributes\SearchUsingFullText;
use Laravel\Scout\Attributes\SearchUsingPrefix;
use Laravel\Scout\Searchable;

/**
 * App\Models\Photo
 *
 * @property int $id
 * @property string|null $tags 标签
 * @property int $status 状态 0待处理 1已处理
 * @property array $result 结果
 * @method static \Illuminate\Database\Eloquent\Builder|Photo newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Photo newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Photo query()
 * @method static \Illuminate\Database\Eloquent\Builder|Photo whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Photo whereResult($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Photo whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Photo whereTags($value)
 * @mixin \Eloquent
 */
class Photo extends Model
{
    use HasFactory, Searchable;

    public $timestamps = false;

    protected $guarded = [];

    protected $casts = [
        'result' => 'array',
    ];

    /**
     * 获取模型的可索引的数据
     *
     * @return array
     */
    public function toSearchableArray()
    {
        $item = isset($this->result['hits']) ? $this->result['hits'][0] : '';
        return [
            'tags' => $this->tags,
            'image' => $item ? $item['previewURL'] : '',
        ];
    }

    /**
     * 确定模型信息是否可搜索
     * @return bool
     */
    public function shouldBeSearchable(): bool
    {
        return $this->status === 2;
    }
}
