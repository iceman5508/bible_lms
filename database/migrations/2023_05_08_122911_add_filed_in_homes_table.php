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
        Schema::table('homes', function (Blueprint $table) {
            $table->tinyInteger('category_courses_area')->default(STATUS_PENDING)->after('instructor_support_area');
            $table->tinyInteger('upcoming_courses_area')->default(STATUS_PENDING)->after('category_courses_area');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('homes', function (Blueprint $table) {
            $table->dropColumn('category_courses_area');
            $table->dropColumn('upcoming_courses_area');
        });
    }
};
