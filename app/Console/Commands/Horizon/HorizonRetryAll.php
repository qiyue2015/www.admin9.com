<?php

namespace App\Console\Commands\Horizon;

use Arr;
use Illuminate\Console\Command;
use Laravel\Horizon\Contracts\JobRepository;
use Laravel\Horizon\Jobs\RetryFailedJob;

class HorizonRetryAll extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'horizon:retry-all';

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

    /**
     * Execute the console command.
     */
    public function handle()
    {
        ini_set('memory_limit', '-1');

        $afterIndex = -1;
        /** @var JobRepository $jobs */
        $jobs = app(JobRepository::class);
        // key 太多的时候可能会造成阻塞
        //$this->info("共计 {$jobs->countFailed()} 个失败队列");

        retry:
        $this->info($afterIndex);
        $failJobs = $jobs->getFailed($afterIndex);
        foreach ($failJobs as $failJob) {
            $lastRetriedBy = Arr::last(json_decode($failJob->retried_by, true));
            if ($lastRetriedBy && 'completed' == $lastRetriedBy['status']) {
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
}
