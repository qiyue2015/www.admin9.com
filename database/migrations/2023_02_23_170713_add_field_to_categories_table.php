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
        Schema::table('categories', function (Blueprint $table) {
            $table->char('name', 10)->change();
            $table->char('alias', 10)->index()->nullable()->after('name')->comment('alias');
            $table->char('slug', 10)->nullable()->index()->after('alias');
            $table->unsignedInteger('num')->default(0)->change();
            $table->unsignedSmallInteger('is_show')->default(0)->change();
            $table->dropColumn('baike_classid');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->dropColumn('alias');
            $table->dropColumn('slug');
            $table->unsignedSmallInteger('baike_classid')->index();
        });
    }
};
