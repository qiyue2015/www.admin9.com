<?php

namespace App\Console\Commands\GenerateHtml;

use App\Models\Article;
use Illuminate\Console\Command;

class GenerateHtml extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate-html';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $news = Article::checked()->orderByDesc('id')->take(90)->get();
        $string = view('welcome', compact('news'));
        $file = public_path('index.html');
        file_put_contents($file, $string);
    }
}
