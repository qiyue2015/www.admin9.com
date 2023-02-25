<?php

namespace App\Jobs\ClueAi;

use App\Models\Archive;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;

class ClueAiGenerateAnswerJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected string $key = 'ICMvbSvcgK3jSB1HfZpzK1000111011';
    protected Archive $archive;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Archive $archive)
    {
        $this->archive = $archive;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $url = 'https://www.modelfun.cn/modelfun/api/serving_api';
        $response = Http::timeout(30)
            ->withHeaders([
                'Model-name' => 'clueai-large',
                'Api-key' => 'BEARER '.$this->key,
            ])
            ->asJson()
            ->post($url, [
                'task_type' => 'generate',
                'model_name' => 'ChatYuan-large',
                'input_data' => ["问答：\n问题：{$this->archive->title}：\n答案："],
            ]);

        $results = $response->json('result');
        $content = '';
        foreach ($results as $result) {
            $content = $result['generate_text'];
        }
        $this->archive->update([
            'checked' => true,
            'description' => str($content)->limit(),
        ]);
        $this->archive->extend()->create([
            'id' => $this->archive->id,
            'content' => $content,
        ]);
    }
}
