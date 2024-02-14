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
        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->bigInteger('user_id');
            $table->bigInteger('instructor_id')->nullable();
            $table->bigInteger('category_id')->nullable();
            $table->bigInteger('subcategory_id')->nullable();
            $table->bigInteger('course_language_id')->nullable();
            $table->bigInteger('difficulty_level_id')->nullable();
            $table->string('title');
            $table->mediumText('description');
            $table->mediumText('feature_details')->nullable();
            $table->decimal('price')->default(0.00);
            $table->string('learner_accessibility', 50)->comment('paid,free')->nullable();
            $table->string('image')->nullable();
            $table->string('slug');
            $table->tinyInteger('status')->default(0)->comment('0=pending, 1=published, 2=waiting_for_review, 3=hold, 4=draft');
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
        Schema::dropIfExists('courses');
    }
};
