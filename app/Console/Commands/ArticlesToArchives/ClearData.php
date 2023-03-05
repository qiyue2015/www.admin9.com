<?php

namespace App\Console\Commands\ArticlesToArchives;

use App\Models\Article;
use Illuminate\Console\Command;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ClearData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'clear:data';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '清理文章表数据';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->comment('1.删除之前的新闻内容');
        if (!cache()->has('clear-data:1')) {
            Article::where('id', '<', 321)->delete();
            cache()->put('clear-data:1', true);
        }

        $this->comment('2.TAGS 替换');
        if (!cache()->has('clear-data:2')) {
            DB::insert("UPDATE articles SET tags = REPLACE(tags, '|', ',')");
            cache()->put('clear-data:2', true);
        }

        $this->error('3.archives 更改字段类型.');
        if (!cache()->has('clear-data:3')) {
            Schema::table('archives', function (Blueprint $table) {
                $table->char('keywords', 50)->nullable()->change();
                $table->string('description')->nullable()->change();
                $table->string('tags')->nullable()->change();
            });
            cache()->put('clear-data:3', true);
        }

        $this->error('4.articles info to archives.');
        if (!cache()->has('clear-data:4')) {
            DB::insert("INSERT INTO archives (id, title, keywords, tags, description, published, publish_at, created_at, updated_at) SELECT id, title, keywords, tags, description, checked AS published, updated_at AS publish_at, created_at, updated_at FROM articles;");
            cache()->put('clear-data:4', true);
        }

        $this->comment('5.副表迁移');
        collect(range(0, 9))->each(function ($i) {
            $this->info('archives_extend_'.$i);
            if (!cache()->has('clear-data:5-'.$i)) {
                DB::insert("INSERT INTO archives_extend_{$i} (id, content) SELECT id, content FROM articles_{$i};");
                cache()->put('clear-data:5-'.$i, true);
            }
        });

        $this->comment('6.副表图片地址处理');
        collect(range(0, 9))->each(function ($i) {
            if (!cache()->has('clear-data:6-'.$i)) {
                DB::insert("UPDATE archives_extend_{$i} SET content = REPLACE(content, 'http://www.yebaike.com/d/file/', '/files/');");
                cache()->put('clear-data:6-'.$i, true);
            }
        });
    }
}
