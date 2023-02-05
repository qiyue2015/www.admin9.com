<?php

namespace App\Jobs\Init;

use App\Models\Article;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class InitMeiXiaoSanKeywordsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected Article $article;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Article $article)
    {
        $this->article = $article;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // 取副表内容
        $item = $this->query()->first();
        if ($item) {
            // 没有摘要
            $description = $this->article->description;
            if (empty($description)) {
                $content = strip_tags($item->content);
                $description = Str::limit($content, 200);
                if (preg_match_all('/(.*?)([。！……])(?![”"」】\]\)）》>])/u', $description, $matches)) {
                    $description = implode('', $matches[0]);
                }
            }

            $response = Http::withoutVerifying()
                ->withHeaders([
                    'x-requested-with' => 'XMLHttpRequest',
                ])
                ->withCookies([
                    'uid' => 44776,
                    'token' => '85bdc7aa-5edc-4a54-814a-8fc6a7b84c54',
                ], 'www.meixiaosan.com')
                ->post('https://www.meixiaosan.com/getkeywords.html', [
                    'title' => Str::limit($this->article->title, 80, ''),
                    'content' => $description,
                ]);
            $content = $response->object()->data;
            $this->article->update([
                'status' => 1,
                'keywords' => $content->newtext === '未提取到关键词!' ? '' : $content->newtext,
                'description' => $description,
            ]);
        }
    }

    private function query(): \Illuminate\Database\Query\Builder
    {
        $subtable = 'articles_'.$this->article->id % 10;
        return DB::table($subtable)->where('id', $this->article->id);
    }

    private function deleteHtml($str): string
    {
        //$str = trim($str); //清除字符串两边的空格
        //$str = preg_replace("/\t/", "", $str); //使用正则表达式替换内容，如：空格，换行，并将替换为空。
        $str = preg_replace("/\r\n{1,}/", "", $str);
        $str = preg_replace("/\r{1,}/", "", $str);
        $str = preg_replace("/\n{1,}/", "", $str);
        return trim($str); // 返回字符串
    }
}
