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
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->bigInteger('sender_id')->nullable();
            $table->bigInteger('user_id')->nullable();
            $table->string('text');
            $table->string('target_url')->nullable();
            $table->string('is_seen')->default('no')->comment('yes, no');
            $table->tinyInteger('user_type')->default(2)->comment('1=admin, 2=instructor, 3=student');
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
        Schema::dropIfExists('notifications');
    }
};
