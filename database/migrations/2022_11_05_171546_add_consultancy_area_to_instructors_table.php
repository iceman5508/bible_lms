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
        Schema::table('instructors', function (Blueprint $table) {
            $table->tinyInteger('consultancy_area')->default(3)->after('address');
            $table->tinyInteger('is_offline')->default(0)->comment('offline status')->after('status');
            $table->string('offline_message')->nullable()->comment('offline message')->after('is_offline');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('instructors', function (Blueprint $table) {
            $table->dropColumn('consultancy_area');
            $table->dropColumn('is_offline');
            $table->dropColumn('offline_message');
        });
    }
};
