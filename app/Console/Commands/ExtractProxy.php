<?php

namespace App\Console\Commands;

use Illuminate\Support\Facades\Http;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Queue;
use Laravel\Horizon\Contracts\MasterSupervisorRepository;
use App\Ace\Horizon\CustomQueue;
use App\Ace\Http\ProxyRepository;
use App\Models\Proxy;
use RuntimeException;
use Throwable;
use Log;

class ExtractProxy extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'extract:proxy {--force}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '提取代理IP';

    public function handle(): void
    {
        $proxyNum = 5;

        $this->comment('Check available proxy...');

        // 当前时间提前 30 秒，给代理留点空间
        $count = Proxy::whereStatus(true)->where('expire_at', '>', now()->addSeconds(30))->count();
        if ($count >= $proxyNum) {
            $this->error('Sufficient available agents. IP >= '.$proxyNum);
            return;
        }

        $this->comment('Extract proxy...');

        if ($count > 0) {
            $proxyNum -= $count; // 保证代理沲最小可用 IP 数量
        }

        try {
            // 25分钟至03小时
            //$url = "http://webapi.http.zhimacangku.com/getip?num={$proxyNum}&type=2&pro=0&city=0&yys=0&port=1&time=2&ts=1&ys=1&cs=1&lb=1&sb=0&pb=45&mr=3&regions=";
            //$url = "http://http.tiqu.letecs.com/getip3?num={$proxyNum}&type=2&pro=0&city=0&yys=0&port=1&time=3&ts=1&ys=1&cs=1&lb=1&sb=0&pb=45&mr=3&regions=&gm=4";
            //$url = "http://http.tiqu.letecs.com/getip3?num={$proxyNum}&type=2&pro=0&city=0&yys=0&port=1&time=3&ts=1&ys=1&cs=1&lb=1&sb=0&pb=4&mr=1&regions=&gm=4";
            $url = 'http://http.tiqu.letecs.com/getip3?num={$proxyNum}&type=2&pro=0&city=0&yys=0&port=1&pack=296610&ts=1&ys=1&cs=1&lb=1&sb=0&pb=4&mr=1&regions=&gm=4';
            $response = Http::get($url)->object();
            if ($response->code === 0) {
                collect($response->data)->each(function ($row) {
                    $address = $row->ip.':'.$row->port;
                    Proxy::updateOrCreate(['address' => $address], ['expire_at' => $row->expire_time]);
                });

                // 停用到期代理
                Proxy::where('expire_at', '<', now()->toDateTimeString())->update(['status' => false]);

                // 刷新代理沲
                ProxyRepository::refresh();
            } else {
                throw new RuntimeException($response->msg);
            }
        } catch (Throwable $throwable) {
            Log::warning('代理获取失败', ['content' => $throwable->getMessage()]);
        }
    }
}
