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
        Schema::table('courses', function (Blueprint $table) {
            $table->tinyInteger('course_type')->after('user_id')->default(1)->comment('1=general, 2=scorm');
        });

        Schema::table('scorm', function (Blueprint $table) {
            $table->unsignedBigInteger('course_id')->after('id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->dropColumn('course_type');
        });

        Schema::table('scorm', function (Blueprint $table) {
            $table->dropColumn('course_id');
        });
    }
};