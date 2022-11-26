<?php

namespace App\Console\Commands\Article;

use App\Models\Article;
use Illuminate\Console\Command;

class YeBaiKeCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'article:yebaike
                            {--init= : 类型 1 初始化id}
                            {--num=10 : 数量}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    protected array $categoryIds = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 24, 26, 27, 28, 29, 30, 31, 32];

    /**
     * @return void
     */
    public function handle()
    {
        $init = (int) $this->option('init');
        $num = (int) $this->option('num');
        if ($init === 1) {
            $this->init($num);
        }
    }

    protected function init($num)
    {
        $star = 1;
        $maxId = (3502440 - 80); // 3502440
        $bar = $this->output->createProgressBar($maxId);
        while ($star <= $maxId) {
            $data = [];
            for ($i = 0; $i < $num; $i++) {
                $data[] = [
                    'title' => '待采集',
                    'channel_id' => 1,
                    'category_id' => 1,
                    'checked' => false,
                ];
            }

            Article::insert($data);

            $star += $num;
            $bar->advance($num);
        }
    }
}
