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
        Schema::create('certificates', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->string('certificate_number', 50)->nullable();
            $table->string('image')->nullable();
            $table->string('show_number', 10)->default('yes')->comment('yes, no');
            $table->integer('number_x_position')->default(0);
            $table->integer('number_y_position')->default(0);
            $table->integer('number_font_size')->default(18);
            $table->string('number_font_color', 25)->nullable();
            $table->string('title')->nullable();
            $table->integer('title_x_position')->default(0);
            $table->integer('title_y_position')->default(0);
            $table->integer('title_font_size')->default(20);
            $table->string('title_font_color', 25)->nullable();
            $table->string('show_date', 10)->default('yes')->comment('yes, no');
            $table->integer('date_x_position')->default(0);
            $table->integer('date_y_position')->default(16);
            $table->integer('date_font_size')->default(30);
            $table->string('date_font_color', 25)->nullable();
            $table->string('show_student_name', 10)->default('yes')->comment('yes, no');
            $table->integer('student_name_x_position')->default(0);
            $table->integer('student_name_y_position')->default(16);
            $table->integer('student_name_font_size')->default(32);
            $table->string('student_name_font_color', 25)->nullable();
            $table->mediumText('body')->nullable();
            $table->integer('body_max_length')->default(80);
            $table->integer('body_x_position')->default(0);
            $table->integer('body_y_position')->default(16);
            $table->integer('body_font_size')->default(20);
            $table->string('body_font_color', 25)->nullable();
            $table->string('role_1_title')->nullable();
            $table->string('role_1_signature')->nullable();
            $table->integer('role_1_x_position')->default(16);
            $table->integer('role_1_y_position')->default(16);
            $table->integer('role_1_font_size')->default(18);
            $table->string('role_1_font_color', 25)->nullable();
            $table->string('role_2_title')->nullable();
            $table->integer('role_2_x_position')->default(0);
            $table->integer('role_2_y_position')->default(0);
            $table->integer('role_2_font_size')->default(18);
            $table->string('role_2_font_color', 25)->nullable();
            $table->string('path')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('certificates');
    }
};
