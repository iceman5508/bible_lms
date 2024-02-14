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
            $table->longText('text')->after('type')->nullable();
            $table->string('image')->after('text')->nullable();
            $table->string('pdf')->after('image')->nullable();
            $table->string('slide_document')->after('pdf')->nullable();
            $table->string('audio')->after('slide_document')->nullable();
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
            $table->dropColumn('text');
            $table->dropColumn('image');
            $table->dropColumn('pdf');
            $table->dropColumn('slide_document');
            $table->dropColumn('audio');
        });
    }
};
