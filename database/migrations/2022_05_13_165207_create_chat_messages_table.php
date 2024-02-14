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
        Schema::create('chat_messages', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('incoming_user_id')->nullable();
            $table->unsignedBigInteger('outgoing_user_id')->nullable();
            $table->longText('message')->nullable();
            $table->tinyInteger('view')->default(2)->comment('1=seen,2=not seen')->nullable();
            $table->tinyInteger('created_user_type')->comment('1=student,2=instructor')->nullable();
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
        Schema::dropIfExists('chat_messages');
    }
};
