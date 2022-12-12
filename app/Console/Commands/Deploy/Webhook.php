<?php

namespace App\Console\Commands\Deploy;

use Illuminate\Console\Command;

class Webhook extends Command
{
    /**
     * The name and signature of the console command.
     *
     * option argument :
     *                  all : 部署前后端 默认参数
     *                  frontend : 只部署前端
     *                  backend : 只部署后端
     *
     * @var string
     */
    protected $signature = 'deploy:webhook
                           {option=all : 部署的选项}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'webhook部署';

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
     *
     * @return int
     */
    public function handle()
    {
        $option = $this->argument('option');

        if (! in_array($option, ['all', 'frontend', 'backend'])) {
            $option = 'all';
        }

        $date = now()->toDateString();

        $cmd = base_path()."/deploy.sh {$option} ".'>> '.storage_path("deploy/deploy-{$date}.log").' 2>&1';

        system($cmd);
    }
}
