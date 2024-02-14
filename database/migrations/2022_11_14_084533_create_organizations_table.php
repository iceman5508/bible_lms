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
        Schema::create('organizations', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('country_id')->nullable();
            $table->unsignedBigInteger('province_id')->nullable();
            $table->unsignedBigInteger('state_id')->nullable();
            $table->unsignedBigInteger('city_id')->nullable();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('professional_title')->nullable();
            $table->string('phone_number')->nullable();
            $table->string('postal_code', 100)->nullable();
            $table->string('address')->nullable();
            $table->tinyInteger('consultancy_area')->default(3);
            $table->mediumText('about_me')->nullable();
            $table->string('gender', 50)->nullable();
            $table->mediumText('social_link')->nullable();
            $table->string('slug')->nullable();
            $table->string('is_private', 10)->default('no')->comment('yes, no');
            $table->string('remove_from_web_search', 10)->default('no')->comment('yes, no');
            $table->tinyInteger('status')->default(0)->comment('0=pending, 1=approved, 2=blocked');
            $table->tinyInteger('is_offline')->default(0)->comment('offline status');
            $table->string('offline_message')->default(0)->comment('offline message');
            $table->tinyInteger('consultation_available')->default(0)->comment('1=yes, 0=no');
            $table->decimal('hourly_rate')->nullable();
            $table->decimal('hourly_old_rate')->nullable();
            $table->tinyInteger('available_type')->default(3)->comment('1=In-person, 0=Online, 3=Both');
            $table->string('cv_file')->nullable();
            $table->string('cv_filename')->nullable();
            $table->unsignedBigInteger('level_id')->nullable();
            $table->softDeletes();
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
        Schema::dropIfExists('organizations');
    }
};
