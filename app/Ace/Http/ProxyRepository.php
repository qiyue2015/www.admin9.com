<?php

namespace App\Ace\Http;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use App\Models\Proxy;
use RuntimeException;

class ProxyRepository
{
    /**
     * 缓存的 key.
     */
    public static function key(): string
    {
        return 'proxies';
    }

    /**
     * 从数据库输出代理地址
     *
     * @return Collection
     */
    protected static function getProxyAddress(): Collection
    {
        // 只有 30 秒到期的排除，避免碰到刚用上就失效
        return Proxy::whereStatus(true)
            ->where('expire_at', '>', now()->addSeconds(30))
            ->pluck('address');
    }

    /**
     * 刷新缓存内的代理.
     *
     * @return void
     */
    public static function refresh(): void
    {
        $connection = redis_cache();

        $connection->del(self::key());

        $proxies = self::getProxyAddress();
        if ($proxies->isEmpty()) {
            throw new RuntimeException('代理为空.', 404);
        }

        $connection->sAdd(self::key(), ...$proxies);
    }


    /**
     * 随机返回可用的代理.
     * @return string
     */
    public static function getRandomProxy(): string
    {
        $connection = redis_cache();
        $proxy = $connection->sRandMember(self::key());
        if ($proxy) {
            $expireAt = Cache::remember(Proxy::generateTimeoutKey($proxy), now()->endOfDay(),
                static function () use ($proxy) {
                    return Proxy::whereAddress($proxy)->pluck('expire_at')->first();
                });
            if (now()->diffInSeconds($expireAt, false) > 10) {
                return $proxy;
            }
        }

        self::refresh();

        return $connection->sRandMember(self::key());
    }
}
