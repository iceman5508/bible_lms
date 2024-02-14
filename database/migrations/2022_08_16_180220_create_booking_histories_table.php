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
        Schema::create('booking_histories', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->unsignedBigInteger('order_id');
            $table->unsignedBigInteger('order_item_id');
            $table->unsignedBigInteger('instructor_user_id');
            $table->unsignedBigInteger('student_user_id');
            $table->unsignedBigInteger('consultation_slot_id');
            $table->string('date');
            $table->tinyInteger('day')->comment('0=sunday,1=monday,2=tuesday,3=wednesday,4=thursday,5=friday,6=saturday');
            $table->string('time');
            $table->string('duration');
            $table->tinyInteger('status')->comment('0=Pending,1=Approve,2=Cancel,3=Completed');
            $table->tinyInteger('type')->default(1)->comment('1=In-person,2=Online');
            $table->mediumText('join_url')->nullable();
            $table->string('meeting_id')->nullable();
            $table->string('meeting_password')->nullable();
            $table->string('meeting_host_name')->comment('zoom,bbb,jitsi')->nullable();
            $table->string('moderator_pw')->comment('use only for bbb')->nullable();
            $table->string('attendee_pw')->comment('use only for bbb')->nullable();
            $table->mediumText('cancel_reason')->nullable();
            $table->tinyInteger('send_back_money_status')->default(0)->comment('1=Yes, 0=No')->nullable();
            $table->string('back_admin_commission')->comment('Admin Commission')->nullable();
            $table->string('back_owner_balance')->comment('Instructor Commission')->nullable();
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
        Schema::dropIfExists('booking_histories');
    }
};
