<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

/**
 * App\Models\Proxy
 *
 * @property int $id
 * @property string $address 代理地址
 * @property int $status 可用状态
 * @property string $expire_at 过期时间
 * @method static \Illuminate\Database\Eloquent\Builder|Proxy newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Proxy newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Proxy query()
 * @method static \Illuminate\Database\Eloquent\Builder|Proxy whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Proxy whereExpireAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Proxy whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Proxy whereStatus($value)
 * @mixin \Eloquent
 */
class Proxy extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $guarded = [];

    public static function generateQueueDailyFailKey($address): string
    {
        return 'proxy:'.$address.':queue:fail:daily';
    }

    public static function generateQueueDailyApplyKey($address): string
    {
        return 'proxy:'.$address.':queue:apply:daily';
    }

    public static function generateTimeoutKey($address): string
    {
        return 'proxy:'.$address.':timeout';
    }

    public static function generateQueueDailyFailRate($address): int
    {
        $failTimes = Cache::get(self::generateQueueDailyFailKey($address));
        $applyTimes = Cache::get(self::generateQueueDailyApplyKey($address));
        return (int) ($failTimes / $applyTimes) * 100;
    }
}
