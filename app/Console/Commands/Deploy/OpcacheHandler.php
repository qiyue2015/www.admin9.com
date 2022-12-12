<?php

namespace App\Console\Commands\Deploy;

use Illuminate\Console\Command;

class OpcacheHandler extends Command
{
    /**
     * The name and signature of the console command.
     * run arguments options : status config compile clear.
     *
     * @var string
     */
    protected $signature = 'deploy:opcache
                           {run : 执行的命令}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '调用opcache命令';

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
     * @return mixed
     */
    public function handle()
    {
        $command = $this->argument('run');

        if (! in_array($command, ['status', 'config', 'compile', 'clear'])) {
            $this->warn('Undefined command');

            return;
        }

        $fullCommand = 'opcache:'.$command;

        if ('compile' == $command) {
            // 编译命令会使用较多内存
            ini_set('memory_limit', '-1');
            // 休息5秒钟 因为 opcache clear 后会 restart pending 有个短暂的重启过程
            // 这个时候立刻执行 compile 会失败
            sleep(6);
            $this->call('opcache:status');
            $this->call($fullCommand, ['--force' => true]);

            return;
        }

        $this->call($fullCommand);
    }
}
