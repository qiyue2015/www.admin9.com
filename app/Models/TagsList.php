<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\TagsList
 *
 * @property int $id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|TagsList newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TagsList newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TagsList query()
 * @method static \Illuminate\Database\Eloquent\Builder|TagsList whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TagsList whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TagsList whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class TagsList extends Model
{
    use HasFactory;
}
