<?php

namespace App\Services\Tap;

use Illuminate\Support\Facades\Blade;


class TapService
{
    // boot中使用  (new TagService())->make()
    public function make(): void
    {
        $this->listshowclass();
        $this->loop();
    }

    /**
     * 循环栏目导航标签
     * @return void
     */
    public function listshowclass(): void
    {
        Blade::directive('listshowclass', static function ($expression) {
            return <<<php
<?php
    \$__currentLoopData = for_show_sub_class($expression);
    \$__env->addLoop(\$__currentLoopData);
    foreach(\$__currentLoopData as \$row):
    \$__env->incrementLoopIndices();
    \$loop = \$__env->getLastLoop();
?>
php;
        });
        // 定义循环结束标签
        Blade::directive('endlistshowclass', static function () {
            return '<?php endforeach;?>';
        });
    }

    /**
     * 仿帝国 ECMS 的灵动标签
     * @return void
     */
    public function loop(): void
    {
        Blade::directive('loop', static function ($expression) {
            return <<<php
<?php
    \$__currentLoopData = for_show_loop($expression);
    \$__env->addLoop(\$__currentLoopData);
    foreach(\$__currentLoopData as \$row):
    \$__env->incrementLoopIndices();
    \$loop = \$__env->getLastLoop();
?>
php;
        });
        // 定义循环结束标签
        Blade::directive('endloop', static function () {
            return '<?php endforeach;?>';
        });
    }
}
