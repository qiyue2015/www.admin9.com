<?php

namespace App\Console\Commands\ClueAi;

use App\Ace\Horizon\CustomQueue;
use App\Jobs\ClueAi\ClueAiGenerateAnswerJob;
use App\Models\Archive;
use App\Models\Category;
use Illuminate\Console\Command;

class GenerateAnswersCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'clue-ai:generate-answers';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '通过元语AI生成答安';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        Category::all()->each(function ($category) {
            $this->error($category->name);
            $list = Archive::whereCategoryId($category->id)->take(50)->get();
            foreach ($list as $archive) {
                ClueAiGenerateAnswerJob::dispatch($archive)->onQueue(CustomQueue::CLUEAI_API_QUEUE);
            }
        });
        //$count = Archive::where('checked', 0)->count();
        //$lastId = Archive::where('checked', 0)->max('id');
        //$star = 0;
        //while ($star < $lastId) {
        //    $list = Archive::where('id', '>', $star)->take(200)->get();
        //    if ($list->isEmpty()) {
        //        break;
        //    }
        //
        //    foreach ($list as $archive) {
        //        ClueAiGenerateAnswerJob::dispatch($archive)->onQueue(CustomQueue::LARGE_PROCESSES_QUEUE);
        //    }
        //}
    }
}
