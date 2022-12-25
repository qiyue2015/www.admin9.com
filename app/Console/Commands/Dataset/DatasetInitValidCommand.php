<?php

namespace App\Console\Commands\Dataset;

use App\Exceptions\FakeUserAgent;
use App\Models\Dataset;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class DatasetInitValidCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dataset:init-valid';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '采集验证集';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        ini_set('memory_limit', -1);
        $path = Storage::path('百科类问答json版');
        $files = File::files($path);
        foreach ($files as $file) {
            $rows = explode(PHP_EOL, $file->getcontents());

            $this->info($file->getBasename());

            $bar = $this->output->createProgressBar(count($rows));
            collect($rows)->chunk(100)->each(function ($rows) use ($bar) {
                foreach ($rows as $row) {

                    $bar->advance();
                    $result = json_decode($row);
                    if (empty($result->category)) {
                        return '';
                    }
                    $result->category = str_replace('-', '/', $result->category);
                    $cateArr = explode('/', $result->category);
                    $category = $cateArr[0];
                    $filename = 'baike-json-data/'.$category.'/'.$result->qid;

                    $body = $result->title;
                    if ($result->desc) {
                        $body .= PHP_EOL.$result->desc;
                    }
                    $answer = trim(strip_tags($result->answer));
                    if ($answer) {
                        $body .= PHP_EOL.$answer;
                    }

                    $tags = [];
                    foreach ($cateArr as $cate) {
                        $tags['labels'][] = ['name' => trim($cate)];
                    }
                    $tags = json_encode($tags, JSON_UNESCAPED_UNICODE);

                    //dd($filename, $body, $tags);
                    dispatch(static function () use ($filename, $body, $tags) {
                        Storage::put($filename.'.txt', $body);
                        Storage::put($filename.'.json', $tags);
                    })->onQueue('just_for_train');
                }
            });
        }
    }

    public function checkTitleExists($category, $title)
    {
        $key = md5($category.$title);
        return cache()->rememberForever($key, function () use ($category, $title) {
            return Dataset::where('title', $title)->where('category1', $category)->exists();
        });
    }

    public function formatContent($string): ?string
    {
        if (!$string) {
            return '';
        }

        $string = strip_tags($string);
        $string = preg_replace('/\s+/', '', trim($string));
        $string = preg_replace("/([。!\?])/u", "$1###", $string);
        $words = explode('###', $string);
        $content = '';
        foreach ($words as $word) {
            if (strlen($content) > 500) {
                break;
            }
            $content .= $word;
        }

        return $content;
    }
}
