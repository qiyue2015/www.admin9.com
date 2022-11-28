<?php

namespace App\Console\Commands;

use App\Models\Article;
use Illuminate\Console\Command;
use Illuminate\Database\Connection;
use Illuminate\Database\ConnectionInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class Test extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    public static function query(): \Illuminate\Database\Query\Builder
    {
        return DB::connection('locoy')->table('Content');
    }

    public function handle(): void
    {
        $url = 'https://www.yebaike.com/32/202210/3492335.html';
        $response = Http::withoutVerifying()
            ->withUserAgent('Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/107.0.0.0 Safari/537.36')
            ->timeout(30)
            ->get($url);
        dd($response->body());

        $star = 0;
        $lastId = 0;
        $count = self::query()->count();
        $bar = $this->output->createProgressBar($count);
        $sql = [];
        while ($star < $count) {
            $query = self::query()->where('ID', '>', $lastId)->orderBy('ID', 'ASC');
            $list = $query->take(100)->get();

            if (is_null($list)) {
                dd('all ok');
            }

            foreach ($list as $row) {
                $star++;
                $lastId = $row->ID;
                $parse = parse_url($row->PageUrl, PHP_URL_QUERY);
                parse_str($parse, $result);
                if ($row->标题) {
                    $subtable = $result['id'] % 10;
                    $sql[] = sprintf(
                        "UPDATE articles SET category_id=1, title='%s', checked=1, created_at='%s', updated_at='%s' WHERE id=%d;\n",
                        $row->标题,
                        now()->parse($row->时间)->toDateTimeString(),
                        now()->toDateTimeString(),
                        $result['id']
                    );
                    $sql[] = sprintf(
                        "INSERT INTO articles_%d(`id`, `content`) VALUES ('%s', '%s');\n",
                        $subtable,
                        $result['id'],
                        $row->内容
                    );
                }
            }

            $step = $list->count();
            $bar->advance($step);
        }
        Storage::put('update.sql', implode(PHP_EOL, $sql));
    }
}
