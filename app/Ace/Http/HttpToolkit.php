<?php

namespace App\Ace\Http;

use App\Models\Proxy;
use Illuminate\Http\Client\Response as ClientResponse;
use Psr\SimpleCache\InvalidArgumentException;
use GuzzleHttp\Psr7\Response as GuzzleResponse;
use App\Ace\Http\ProxyRepository as ProxyCache;
use Cache;
use Closure;
use Http;
use Log;
use RuntimeException;
use Throwable;

/**
 * Class Proxy.
 *
 * @mixin Http
 */
class HttpToolkit
{
    public static function getWithOptionCallable(): Closure
    {
        return static function ($url, $timeout = 30) {
            return Http::timeout($timeout)
                ->withoutVerifying()
                ->withHeaders(['User-Agent' => FakeUserAgent::random()])
                ->get($url);
        };
    }

    public static function getWithProxyCallable(): Closure
    {
        return static function ($url, $query = null, $timeout = 20) {
            $proxy = ProxyRepository::getRandomProxy();
            $tries = 0;
            try {
                retry:
                $tries++;

                // 生产环境代理为空不让进行采集
                if (!$proxy) {
                    throw new RuntimeException('代理为空.');
                }

                if (config('app.debug')) {
                    $timeline = now()->format('Y-m-d H:i:s');
                    echo "第 {$tries} GET 次请求 {$timeline} {$proxy} {$url}\n";
                }

                // 记录使用量
                cache_increment(Proxy::generateQueueDailyApplyKey($proxy), 1, now()->endOfDay());

                return Http::timeout($timeout)
                    ->withoutVerifying()
                    ->withOptions(['proxy' => $proxy])
                    ->withHeaders(['User-Agent' => FakeUserAgent::random()])
                    ->get($url, $query);
            } catch (Throwable $e) {
                $tries = self::recordConnectionException($e, $proxy, $url, $tries);
                if ($tries <= 1) {
                    // 换一个 IP
                    $proxy = ProxyRepository::getRandomProxy();
                    goto retry;
                }
                return new ClientResponse(new GuzzleResponse(500, [], $e->getMessage()));
            }
        };
    }

    /**
     * 记录请求异常.
     *
     * @param $e
     * @param $proxy
     * @param $url
     * @param $tries
     * @return mixed
     * @throws InvalidArgumentException
     */
    protected static function recordConnectionException($e, $proxy, $url, $tries): mixed
    {
        // 记录异常
        if (!($tries <= 1) && config('app.debug')) {
            Log::channel('proxy')->warning("代理连接失败  [ url:{$url} ] [ proxy:{$proxy} ] [ msg:{$e->getMessage()} ]");
        }

        // 切换代理重试
        cache_increment(Proxy::generateQueueDailyFailKey($proxy), 1, now()->endOfDay());
        $failTimes = Cache::get(Proxy::generateQueueDailyFailKey($proxy));

        // 计算失败率
        $failRate = Proxy::generateQueueDailyFailRate($proxy);

        $theProxy = Proxy::where('address', $proxy)->first();

        // 失败数过 10 次的，失败率 >=30% 的都禁用掉
        if ($theProxy && $failTimes >= 10 && $failRate >= 30) {
            $theProxy->update(['status' => false]);
            ProxyCache::refresh();
        }

        return $tries;
    }
}
