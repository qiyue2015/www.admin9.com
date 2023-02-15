<?php

namespace App\Console\Commands\Dongde;

use App\Jobs\Dongde\DongdeTagsJob;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

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
        $bar = $this->output->createProgressBar(199044);
        for ($i = 1; $i <= 199044; $i++) {
            $bar->advance();
            DongdeTagsJob::dispatch($i)->onQueue('just_for_max_processes');
        }
    }
}
