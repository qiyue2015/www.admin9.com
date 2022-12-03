<?php

namespace App\Console\Commands;

use App\Models\Article;
use App\Models\Category;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class TrainCategory extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'train:category';

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
     */
    public function handle()
    {
        Category::groupBy(['id', 'name'])->each(function ($category) {
            $data = Article::whereCategoryId($category->id)
                ->checked()
                ->take(100)
                ->get();
            $list = collect($data)->map(function ($row) {
                $subtable = 'articles_'.($row->id % 10);
                $content = DB::table($subtable)->where('id', $row->id)->value('content');
                $content = str_replace(['。 ', '<p>'], ['。'.PHP_EOL, PHP_EOL], $content);
                $content = strip_tags($content);
                return [
                    'id' => $row->id,
                    'title' => $row->title,
                    'content' => trim($content),
                ];
            });

            \App\Models\Train::insert($list->toArray());
        });
    }
}
