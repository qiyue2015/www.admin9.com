<?php

namespace App\Console\Commands\Dataset;

use App\Jobs\TrainTagsJob;
use App\Models\Dataset;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class DatasetInitTrainCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dataset:init-train';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '彩集训练集';

    public function handle(): void
    {
        ini_set('memory_limit', -1);
        $status = 0;
        $count = Dataset::whereStatus($status)->count();
        $bar = $this->output->createProgressBar($count);
        $lastId = Dataset::whereStatus($status)->max('id');
        $star = 0;
        while ($star < $lastId) {
            $list = Dataset::whereStatus($status)
                ->where('id', '>', $star)
                ->orderBy('id', 'ASC')
                ->take(1000)
                ->get(['id', 'category1', 'category2', 'title', 'desc', 'body', 'link', 'status']);

            $ids = [];
            foreach ($list as $row) {
                $star = $row->id;
                $ids[] = $row->id;
                if (empty($row->category2)) {
                    continue;
                }
                $category1 = str_replace('/', '', $row->category1);
                $category2 = str_replace('/', '', $row->category2);
                $path = 'dataset-train-data/'.$row->id;

                $body = $row->title;
                if (!empty($row->desc)) {
                    $body .= PHP_EOL.$row->desc;
                }
                $body .= str_replace('\n', PHP_EOL, $row->body);

                $categories = [
                    'labels' => [
                        ['name' => $category1.'-'.$category2],
                    ],
                ];
                $categories = json_encode($categories, JSON_UNESCAPED_UNICODE);

                dispatch(static function () use ($path, $categories, $body) {
                    Storage::put($path.'.txt', $body);
                    Storage::put($path.'.json', $categories);
                })->onQueue('just_for_train');

                //TrainTagsJob::dispatch($row)->onQueue('just_for_train');
            }

            Dataset::whereIn('id', $ids)->update(['status' => 1]);

            $bar->advance($list->count());
        }
    }
}
