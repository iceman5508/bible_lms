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
        Schema::create('certificate_by_instructors', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->bigInteger('course_id')->nullable();
            $table->bigInteger('certificate_id')->nullable();
            $table->string('title')->nullable();
            $table->integer('title_x_position')->default(0);
            $table->integer('title_y_position')->default(0);
            $table->integer('title_font_size')->default(20);
            $table->string('title_font_color', 25)->nullable();
            $table->mediumText('body')->nullable();
            $table->integer('body_max_length')->default(80);
            $table->integer('body_x_position')->default(0);
            $table->integer('body_y_position')->default(16);
            $table->integer('body_font_size')->default(20);
            $table->string('body_font_color', 25)->nullable();
            $table->string('signature')->nullable();
            $table->integer('role_2_y_position')->default(10);
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
        Schema::dropIfExists('certificate_by_instructors');
    }
};
