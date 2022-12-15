<?php

namespace App\Console\Commands\Init;

use App\Models\Article;
use App\Models\Category;
use Illuminate\Console\Command;

class InitCategoryNumCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'init:category-num';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '统计栏目数据';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $count = Category::count();
        $bar = $this->output->createProgressBar($count);
        Category::all()->each(function ($category) use ($bar) {
            $bar->advance();
            $count = Article::whereCategoryId($category->id)->count();
            $category->num = $count;
            $category->save();
        });
    }
}
