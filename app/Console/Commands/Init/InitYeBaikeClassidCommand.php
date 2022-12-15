<?php

namespace App\Console\Commands\Init;

use App\Models\Category;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class InitYeBaikeClassidCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'init:yebaike-classid';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '根据栏目名映射叶百科分类ID';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle(): void
    {
        $bar = $this->output->createProgressBar(35);
        for ($i = 1; $i <= 35; $i++) {
            $bar->advance();
            $url = 'https://www.yebaike.com/e/action/ListInfo.php?classid='.$i;
            $response = Http::withoutVerifying()->get($url);
            $pattern = "/<title>(.*?) - 业百科<\/title>/u";
            if (preg_match($pattern, $response->body(), $matches)) {
                $name = $matches[1];
                Category::whereName($name)->update(['baike_classid' => $i]);
            }
        }
    }
}
