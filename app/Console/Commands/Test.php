<?php

namespace App\Console\Commands;

use App\Ace\Horizon\CustomQueue;
use App\Jobs\Task\TaskTouTiaoPublishJob;
use App\Models\Archive;
use App\Models\Category;
use App\Models\Task;
use App\Repositories\TaskRepository;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Jenssegers\Optimus\Optimus;
use Overtrue\Pinyin\Pinyin;

class Test extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test {key}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     * @throws \Exception
     */
    public function handle()
    {
        $id = $this->argument('key');
        $encode_id = app(Optimus::class)->decode($id);
        $this->info($encode_id);
    }
}
