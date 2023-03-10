<?php

namespace App\Console\Commands\Dongde;

use App\Ace\Horizon\CustomQueue;
use App\Jobs\Dongde\DongdeTagsJob;
use Illuminate\Console\Command;

class DongdeTags extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dongde:tags';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '拿到 TAGS 例表信息';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        ini_set('memory_limit', -1);
        $bar = $this->output->createProgressBar(500000);
        for ($i = 0; $i < 1000000; $i++) {
            $bar->advance();
            $j = $i + 1;
            DongdeTagsJob::dispatch($j)->onQueue(CustomQueue::LARGE_PROCESSES_QUEUE);
        }
    }
}
