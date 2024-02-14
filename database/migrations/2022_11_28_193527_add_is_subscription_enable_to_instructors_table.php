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
            $table->tinyInteger('is_subscription_enable')->nullable()->default(STATUS_PENDING)->after('status');
        });
        Schema::table('organizations', function (Blueprint $table) {
            $table->tinyInteger('is_subscription_enable')->nullable()->default(STATUS_PENDING)->after('status');
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
            $table->dropColumn('is_subscription_enable');
        });
        Schema::table('organizations', function (Blueprint $table) {
            $table->dropColumn('is_subscription_enable');
        });
    }
};
