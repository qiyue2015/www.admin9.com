<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('accounts', function (Blueprint $table) {
            $table->id();
            $table->string('name', 30)->comment('公众号名称');
            $table->char('account', 30)->comment('微信号');
            $table->char('original', 50)->comment('原始ID');
            $table->char('signature', 255)->comment('简介');
            $table->char('biz', 16)->unique()->comment('biz');
            $table->char('avatar', 255)->comment('头像');
            $table->dateTime('run_time')->index()->useCurrent()->useCurrentOnUpdate()->comment('采集时间');
            $table->tinyInteger('status')->index()->default(1)->comment('状态 0|异常 1|正常');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('accounts');
    }
};
