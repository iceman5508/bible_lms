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
        Schema::table('certificates', function (Blueprint $table) {
            $table->tinyInteger('status')->default(CERTIFICATE_VALID)->after('path');
            $table->string('certificate_number', 50)->nullable()->change();
            $table->string('image')->nullable()->change();
            $table->string('show_number', 10)->nullable()->comment('yes, no')->change();
            $table->integer('number_x_position')->nullable()->change();
            $table->integer('number_y_position')->nullable()->change();
            $table->integer('number_font_size')->nullable()->change();
            $table->string('number_font_color', 25)->nullable()->change();
            $table->string('title')->nullable()->change();
            $table->integer('title_x_position')->nullable()->change();
            $table->integer('title_y_position')->nullable()->change();
            $table->integer('title_font_size')->nullable()->change();
            $table->string('title_font_color', 25)->nullable()->change();
            $table->string('show_date', 10)->default('yes')->comment('yes, no')->change();
            $table->integer('date_x_position')->nullable()->change();
            $table->integer('date_y_position')->nullable()->change();
            $table->integer('date_font_size')->nullable()->change();
            $table->string('date_font_color', 25)->nullable()->change();
            $table->string('show_student_name', 10)->default('yes')->comment('yes, no')->change();
            $table->integer('student_name_x_position')->nullable()->change();
            $table->integer('student_name_y_position')->nullable()->change();
            $table->integer('student_name_font_size')->nullable()->change();
            $table->string('student_name_font_color', 25)->nullable()->change();
            $table->mediumText('body')->nullable()->change();
            $table->integer('body_max_length')->nullable()->change();
            $table->integer('body_x_position')->nullable()->change();
            $table->integer('body_y_position')->nullable()->change();
            $table->integer('body_font_size')->nullable()->change();
            $table->string('body_font_color', 25)->nullable()->change();
            $table->string('role_1_show', 10)->nullable()->after('body_font_color')->comment('yes, no');
            $table->string('role_1_title')->nullable()->change();
            $table->string('role_1_signature')->nullable()->change();
            $table->integer('role_1_x_position')->nullable()->change();
            $table->integer('role_1_y_position')->nullable()->change();
            $table->integer('role_1_font_size')->nullable()->change();
            $table->string('role_1_font_color', 25)->nullable()->change();
            $table->string('role_2_show', 10)->nullable()->after('role_1_font_color')->comment('yes, no');
            $table->string('role_2_title')->nullable()->change();
            $table->integer('role_2_x_position')->nullable()->change();
            $table->integer('role_2_y_position')->nullable()->change();
            $table->integer('role_2_font_size')->nullable()->change();
            $table->string('role_2_font_color', 25)->nullable()->change();
            $table->string('path')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('certificates', function (Blueprint $table) {
            $table->dropColumn('status');
            $table->dropColumn('role_1_show');
            $table->dropColumn('role_2_show');
        });
    }
};
