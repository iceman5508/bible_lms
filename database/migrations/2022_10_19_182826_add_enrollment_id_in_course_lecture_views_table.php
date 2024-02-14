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
        Schema::table('course_lecture_views', function (Blueprint $table) {
            $table->unsignedBigInteger('enrollment_id')->nullable()->after('course_lecture_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('course_lecture_views', function (Blueprint $table) {
            $table->dropColumn('enrollment_id');
        });
    }
};
