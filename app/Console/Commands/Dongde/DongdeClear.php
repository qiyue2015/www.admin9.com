<?php

namespace App\Console\Commands\Dongde;

use App\Models\Dongde;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use function Clue\StreamFilter\fun;

class DongdeClear extends Command
{
    /**
     * The name and signature of the console command.
     *
     * option argument :
     *                  categories : 分类 默认参数
     *                  index : 推荐的
     *                  tags : 按标签
     *
     * @var string
     */
    protected $signature = 'dongde:clear
                            {option=categories : 清洗的选项}';

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
        ini_set('memory_limit', -1);
        $option = $this->argument('option');
        $this->{$option}();
    }

    private function formatParams($params)
    {
        $data = [
            'type' => $params->type,
            'status' => 2,
            'category_id' => $params->category_id,
            'category_name' => $params->category_name,
            'channel_id' => $params->channel_id,
            'title' => $params->title,
            'subtitle' => $params->subtitle,
            'search_title' => $params->search_title,
            'toutiao_title' => $params->toutiao_title,
            'sogou_title' => $params->sogou_title,
            'description' => $params->description,
            'cover' => $params->cover,
            'publish_at' => now()->parse($params->publish_time_name),
            'created_at' => now()->parse($params->create_time_name),
            'updated_at' => now()->parse($params->update_time_name),
            'user_id' => 0,
            'dongde_id' => $params->id,
        ];

        if ($params->keywords) {
            $keywords = collect($params->keywords)->map(function ($word) {
                return $word->name;
            });
            $data['keywords'] = $keywords->toArray();
        }

        if ($params->tags) {
            $tags = collect($params->tags)->map(function ($tag) {
                return $tag->name;
            });
            $data['tags'] = implode(',', $tags->toArray());
        }

        return $data;
    }

    private function categories()
    {
        $files = Storage::files('dongde/categories');
        $this->info(PHP_EOL.'categories');
        $bar = $this->output->createProgressBar(count($files));
        collect($files)->each(function ($file) use ($bar) {
            $bar->advance();
            $content = Storage::get($file);
            $content = json_decode($content);
            collect($content->data->data)->each(function ($item) {
                $data = $this->formatParams($item);
                dispatch(static function () use ($data, $item) {
                    Dongde::where('alias', $item->alias)->update($data);
                })->onQueue('just_for_max_processes');
            });
        });
    }

    private function index()
    {
        $files = Storage::allFiles('dongde/index');
        $this->info(PHP_EOL.'categories');
        $bar = $this->output->createProgressBar(count($files));
        collect($files)->each(function ($file) use ($bar) {
            $bar->advance();
            $content = Storage::get($file);
            $content = json_decode($content);
            collect($content->data->data)->each(function ($row) {
                foreach ($row->contents as $item) {
                    $data = $this->formatParams($item);
                    dispatch(static function () use ($data, $item) {
                        Dongde::where('alias', $item->alias)->update($data);
                    })->onQueue('just_for_max_processes');
                }
            });
        });
    }

    private function tags()
    {
        $directories = Storage::directories('dongde/tags');
        $this->info(PHP_EOL.'tags');
        collect($directories)->each(function ($directory) {
            $this->info(PHP_EOL.$directory);

            $files = Storage::files($directory);
            $bar = $this->output->createProgressBar(count($files));
            collect($files)->each(function ($file) use ($bar) {
                $bar->advance();
                $content = Storage::get($file);
                $content = json_decode($content);
                collect($content->data->data)->each(function ($item) {
                    $data = $this->formatParams($item);
                    dispatch(static function () use ($data, $item) {
                        Dongde::where('alias', $item->alias)->update($data);
                    })->onQueue('just_for_max_processes');
                });
            });
        });
    }
}
