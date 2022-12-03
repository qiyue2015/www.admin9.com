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
        Schema::create('trains', function (Blueprint $table) {
            $table->id();
            $table->string('title', 100)->comment('标题');
            $table->longText('content')->comment('纯文内容');
            $table->string('lv1_categories')->nullable()->default('')->comment('一级分类');
            $table->string('lv2_categories')->nullable()->default('')->comment('二级分类');
            $table->string('tags')->nullable()->default('')->comment('二级分类');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('trains');
    }
};
