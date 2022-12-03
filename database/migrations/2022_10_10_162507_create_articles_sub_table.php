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
        collect(range(0, 9))->each(function ($i) {
            Schema::create('articles_'.$i, function (Blueprint $table) {
                $table->unsignedBigInteger('id')->primary();
                $table->mediumText('content')->nullable()->comment('内容');
                // 关联
                $table->foreign('id')->references('id')->on('articles')->onDelete('cascade');
            });
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        collect(range(0, 9))->each(function ($i) {
            Schema::dropIfExists('articles_'.$i);
        });
    }
};
