<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Task
 *
 * @property int $id
 * @property string $title
 * @property string $tags
 * @property string|null $contents
 * @property int $run_num 运行次数
 * @property int $run_time 运行时间
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Task newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Task newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Task query()
 * @method static \Illuminate\Database\Eloquent\Builder|Task whereContents($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Task whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Task whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Task whereRunNum($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Task whereRunTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Task whereTags($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Task whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Task whereUpdatedAt($value)
 * @property string $hash 唯一HASH
 * @method static \Illuminate\Database\Eloquent\Builder|Task whereHash($value)
 * @mixin \Eloquent
 */
class Task extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $table = 'task_entries';

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'contents' => 'json',
    ];
}
