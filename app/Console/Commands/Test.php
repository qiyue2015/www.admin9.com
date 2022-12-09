<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

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

    /**
     * Execute the console command.
     * @return void
     */
    public function handle(): void
    {
        //
    }


    /**
     * 对整数id进行可逆混淆
     *
     * @param $id
     * @return int
     */
    public static function encodeId($id): int
    {
        $sid = ($id & 0xff000000);
        $sid += ($id & 0x0000ff00) << 8;
        $sid += ($id & 0x00ff0000) >> 8;
        $sid += ($id & 0x0000000f) << 4;
        $sid += ($id & 0x000000f0) >> 4;
        $sid ^= 10;
        return $sid;
    }

    /**
     * 对通过encodeId混淆的id进行还原
     *
     * @param $sid
     * @return false|int
     */
    public static function decodeId($sid): bool|int
    {
        if (!is_numeric($sid)) {
            return false;
        }
        $sid ^= 10;
        $id = ($sid & 0xff000000);
        $id += ($sid & 0x00ff0000) >> 8;
        $id += ($sid & 0x0000ff00) << 8;
        $id += ($sid & 0x000000f0) >> 4;
        $id += ($sid & 0x0000000f) << 4;
        return $id;
    }
}
