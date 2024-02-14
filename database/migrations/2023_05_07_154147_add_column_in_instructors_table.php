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
        Schema::table('instructors', function (Blueprint $table) {
            $table->tinyInteger('auto_content_approval')->default(STATUS_PENDING)->after('remove_from_web_search');
        });
       
        Schema::table('organizations', function (Blueprint $table) {
            $table->tinyInteger('auto_content_approval')->default(STATUS_PENDING)->after('remove_from_web_search');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('instructors', function (Blueprint $table) {
            $table->dropColumn('auto_content_approval');
        });
       
        Schema::table('organizations', function (Blueprint $table) {
            $table->dropColumn('auto_content_approval');
        });
    }
};
