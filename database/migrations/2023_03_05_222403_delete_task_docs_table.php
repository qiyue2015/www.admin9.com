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
        // 将数据合并过去
        DB::beginTransaction();
        try {
            DB::insert("INSERT INTO task_entries SELECT * FROM task_docs;");
            Schema::dropIfExists('task_docs');
            DB::commit();
        } catch (Exception $exception) {
            DB::rollBack();
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::create('task_docs', function (Blueprint $table) {
            $table->id();
            $table->char('hash', 32)->unique()->comment('唯一HASH');
            $table->char('title', 100);
            $table->char('tags', 30);
            $table->longText('contents')->nullable();
            $table->unsignedTinyInteger('run_num')->default(0)->comment('运行次数');
            $table->unsignedInteger('run_time')->index()->default(0)->comment('运行时间');
            $table->timestamps();
        });
    }
};
