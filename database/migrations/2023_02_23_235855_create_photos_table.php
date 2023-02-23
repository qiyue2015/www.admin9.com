<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('photos', function (Blueprint $table) {
            $table->comment('');
            $table->bigIncrements('id');
            $table->string('tags', 100)->nullable()->comment('标签');
            $table->boolean('status')->index()->comment('状态 0待处理 1已处理');
            $table->text('result')->comment('结果');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('photos');
    }
};
