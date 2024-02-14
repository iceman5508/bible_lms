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
        Schema::create('exams', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->bigInteger('user_id');
            $table->bigInteger('course_id');
            $table->string('name');
            $table->mediumText('short_description')->nullable();
            $table->integer('marks_per_question')->default(0);
            $table->integer('duration')->default(0);
            $table->string('type', 50)->comment('multiple_choice, true_false');
            $table->tinyInteger('status')->default(0)->comment('0=unpublish, 1=published');
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
        Schema::dropIfExists('exams');
    }
};
