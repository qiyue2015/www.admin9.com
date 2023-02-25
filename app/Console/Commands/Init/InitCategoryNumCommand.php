<?php

namespace App\Console\Commands\Init;

use App\Models\Archive;
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
        Category::orderByDesc('num')->get()
            ->each(function ($category, $index) use ($bar) {
                $sort = $index;
                if ($category->alias === '其他') {
                    $sort = 99;
                } elseif (mb_strlen($category->alias) > 2) {
                    $sort += 20;
                }

                $bar->advance();
                $count = Archive::whereCategoryId($category->id)->count();
                $category->num = $count;
                $category->sort = $sort;
                $category->save();
            });
    }
}
