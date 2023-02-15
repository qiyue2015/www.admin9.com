<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Dtime
 *
 * @property int $id
 * @property string $title
 * @property string $url
 * @property string $url_hash
 * @method static \Illuminate\Database\Eloquent\Builder|Dtime newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Dtime newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Dtime query()
 * @method static \Illuminate\Database\Eloquent\Builder|Dtime whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Dtime whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Dtime whereUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Dtime whereUrlHash($value)
 * @mixin \Eloquent
 * @property int $status
 * @method static \Illuminate\Database\Eloquent\Builder|Dtime whereStatus($value)
 */
class Dtime extends Model
{
    use HasFactory;

    public $timestamps = false;
}
