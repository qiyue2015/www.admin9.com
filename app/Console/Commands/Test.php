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
        $lastId = Archive::where('category_id', 0)->max('id');
        $count = Archive::where('category_id', 0)->count();
        $bar = $this->output->createProgressBar($count);
        $star = 0;
        while ($star < $lastId) {
            $list = Archive::where('id', '>', $star)->limit(200)->get(['id', 'title', 'tags']);
            if ($list->isEmpty()) {
                break;
            }

            $bar->advance($list->count());
            $star = $list->last()->id;

            foreach ($list as $row) {
                $categoryId = $this->getCategory($row->tags[0])->id;
                $row->update(['category_id' => $categoryId]);
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
