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
use Overtrue\Pinyin\Pinyin;

class Test extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test';

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
        $lastId = Archive::where('has_cover', 0)->max('id');
        $count = Archive::where('has_cover', 0)->count();
        $bar = $this->output->createProgressBar($count);
        $star = 0;
        while ($star < $lastId) {
            $list = Archive::where('has_cover', 0)->where('id', '>', $star)->limit(200)->get(['id', 'title', 'tags']);
            if ($list->isEmpty()) {
                break;
            }

            $bar->advance($list->count());
            $star = $list->last()->id;

            foreach ($list as $row) {
                dispatch(static function () use ($row) {
                    $cover = makeTitleCover($row->title, true);
                    $row->update([
                        'cover' => $cover ?: null,
                        'has_cover' => (bool) $cover,
                    ]);
                })->onQueue(CustomQueue::LARGE_PROCESSES_QUEUE);
            }
        }
    }

    private function getCategory($string): Model|Category
    {
        $arr = explode(',', $string);
        return Category::firstOrCreate([
            'alias' => $arr[0],
            'parent_id' => 0,
        ], [
            'name' => $arr[0],
            'slug' => Pinyin::permalink($arr[0], ''),
            'children' => [],
            'parents' => [],
        ]);
    }
}
