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
        Schema::create('tags', function (Blueprint $table) {
            $table->id();
            $table->unsignedSmallInteger('category_id')->index()->default(0)->comment('频道ID');
            $table->unsignedSmallInteger('parent_id')->index()->default(0)->comment('父ID');
            $table->string('name')->comment('TAG名称');
            $table->unsignedInteger('num')->default(0)->comment('信息数量');
            $table->boolean('is_good')->default(0)->comment('是否推荐');
            $table->json('extend');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tags');
    }
};
