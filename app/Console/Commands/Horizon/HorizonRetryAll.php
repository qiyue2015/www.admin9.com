<?php

namespace App\Console\Commands\Horizon;

use Illuminate\Console\Command;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Laravel\Horizon\Contracts\JobRepository;
use Laravel\Horizon\Jobs\RetryFailedJob;

class HorizonRetryAll extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'horizon:retry-all {type?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '队列重试';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    public function handle(): void
    {
        ini_set('memory_limit', '-1');

        $type = $this->argument('type');

        if ($type === 'table') {
            $this->tableFailedJobs();
        } else {
            $this->horizonFailedJobs();
        }
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function horizonFailedJobs(): void
    {
        $afterIndex = -1;

        $jobs = app(JobRepository::class);
        // key 太多的时候可能会造成阻塞
        //$this->info("共计 {$jobs->countFailed()} 个失败队列");

        retry:
        $this->info($afterIndex);
        $failJobs = $jobs->getFailed($afterIndex);
        foreach ($failJobs as $failJob) {
            $lastRetriedBy = Arr::last(json_decode($failJob->retried_by, true));
            if ($lastRetriedBy && 'completed' === $lastRetriedBy['status']) {
                continue;
            }

            $id = $failJob->id;
            dispatch(new RetryFailedJob($id));
        }

        if ($failJobs->count()) {
            $afterIndex += 50;
            usleep(500000);
            goto retry;
        }
    }

    public function tableFailedJobs(): void
    {
        DB::table('failed_jobs')->orderBy('id', 'ASC')
            ->chunk(100, function ($items) {
                collect($items)->each(function ($row) {
                    echo '.';
                    try {
                        Artisan::call('queue:retry '.$row->uuid);
                    } catch (\Exception $exception) {
                        DB::table('failed_jobs')->where('id', $row->id)->delete();
                        $this->error($exception->getMessage());
                    }
                });
            });
    }
}
