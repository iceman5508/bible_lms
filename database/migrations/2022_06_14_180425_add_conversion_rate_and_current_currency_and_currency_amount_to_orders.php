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
        Schema::table('orders', function (Blueprint $table) {
            $table->string('current_currency')->after('platform_charge')->nullable();
            $table->string('payment_currency')->after('grand_total')->nullable();
            $table->decimal('conversion_rate',28,8)->default(0)->after('payment_currency')->nullable();
            $table->decimal('grand_total_with_conversation_rate',28,8)->default(0)->after('conversion_rate')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('current_currency');
            $table->dropColumn('payment_currency');
            $table->dropColumn('conversion_rate');
            $table->dropColumn('grand_total_with_conversation_rate');
        });
    }
};
