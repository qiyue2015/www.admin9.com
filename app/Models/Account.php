<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Account
 *
 * @property int $id
 * @property string $name 公众号名称
 * @property string $account 微信号
 * @property string $original 原始ID
 * @property string $signature 简介
 * @property string $biz biz
 * @property string $avatar 头像
 * @property string $run_time 采集时间
 * @property int $status 状态 0|异常 1|正常
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Account newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Account newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Account query()
 * @method static \Illuminate\Database\Eloquent\Builder|Account whereAccount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Account whereAvatar($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Account whereBiz($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Account whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Account whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Account whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Account whereOriginal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Account whereRunTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Account whereSignature($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Account whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Account whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Account extends Model
{
    use HasFactory;

    /**
     * 可批量赋值的属性。
     * @var array
     */
    protected $fillable = [
        'name',
        'account',
        'original',
        'signature',
        'biz',
        'avatar',
    ];
}
