<?php

namespace App\Console\Commands\Dongde;

use App\Jobs\Dongde\DongdeIndexJob;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class DongdeIndex extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dongde:index';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '遍历推荐例表';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle(): void
    {
        ini_set('memory_limit', -1);
        $maxId = 4115188; // 4115188
        $star = 2;
        $bar = $this->output->createProgressBar($maxId);
        $i = 0;
        while ($star < $maxId) {
            // 数据分别放在 100个 文件夹中
            $dir = $i % 100;
            $path = 'dongde/index/'.$dir.'/'.$star.'.json';
            // 如果文件不存在，就从 API 中获取数据并写入文件
            if (!Storage::exists($path)) {
                DongdeIndexJob::dispatch($star, $path)->onQueue('just_for_max_processes');
            }
            // 继续获取下一批数据
            $i++;
            $star += 20;
            $bar->advance(20);
        }
    }
}
