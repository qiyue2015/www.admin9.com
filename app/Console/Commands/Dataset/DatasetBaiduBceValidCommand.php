<?php

namespace App\Console\Commands\Dataset;

use App\Jobs\Dataset\DatasetBaiduBceValidJob;
use App\Models\Dataset;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class DatasetBaiduBceValidCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dataset:baidu-bce-valid';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '通过百度获取分类写入验证集';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $count = Dataset::whereStatus(0)->count();
        $lastId = Dataset::whereStatus(0)->orderByDesc('id')->value('id');

        $this->comment("\n待处理：{$count} 最大ID：{$lastId}");

        $bar = $this->output->createProgressBar($count);

        $star = 0;
        while ($star < $lastId) {
            $list = Dataset::whereStatus(0)->where('id', '>', $star)->take(10)->get();
            $bar->advance($list->count());
            collect($list)->each(function ($row) use (&$star) {
                $star = $row->id;
                DatasetBaiduBceValidJob::dispatch($row)->onQueue('just_for_dataset');
            });
        }
    }
}
