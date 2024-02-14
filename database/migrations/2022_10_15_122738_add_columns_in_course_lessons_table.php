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
        Schema::table('course_lectures', function (Blueprint $table) {
            $table->integer('after_day')->after('audio')->nullable();
            $table->date('unlock_date')->after('after_day')->nullable();
            $table->text('pre_ids')->after('unlock_date')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('course_lectures', function (Blueprint $table) {
            $table->dropColumn('after_day');
            $table->dropColumn('unlock_date');
            $table->dropColumn('pre_ids');
        });
    }
};
