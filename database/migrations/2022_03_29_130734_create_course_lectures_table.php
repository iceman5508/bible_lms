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
        Schema::create('course_lectures', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->bigInteger('course_id');
            $table->bigInteger('lesson_id');
            $table->string('title');
            $table->tinyInteger('lecture_type')->default(2)->comment('1=free/show, 2=paid/lock');
            $table->string('file_path')->nullable();
            $table->string('url_path')->nullable();
            $table->string('file_size')->nullable();
            $table->string('file_duration')->nullable();
            $table->double('file_duration_second')->nullable();
            $table->string('type', 100)->default('uploaded_video')->comment('video, youtube, vimeo, text, image, pdf, slide_document, audio');
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
        Schema::dropIfExists('course_lectures');
    }
};
