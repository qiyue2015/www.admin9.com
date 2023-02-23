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
            Schema::create('articles_'.$i, function (Blueprint $table) use ($i) {
                $table->comment('文章扩展表'.$i);
                $table->unsignedBigInteger('id')->primary();
                $table->string('tags')->comment('标签信息');
                $table->mediumText('content')->nullable()->comment('内容');

                $table->foreign(['id'])->references(['id'])->on('articles')->onUpdate('NO ACTION')->onDelete('CASCADE');
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
