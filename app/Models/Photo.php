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
