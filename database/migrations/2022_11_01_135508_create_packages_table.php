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
        Schema::create('packages', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->enum('package_type', [PACKAGE_TYPE_SUBSCRIPTION, PACKAGE_TYPE_SAAS_INSTRUCTOR, PACKAGE_TYPE_SAAS_ORGANIZATION])->comment('1=subscription, 2=instructor saas, 3=organization saas');
            $table->string('title');
            $table->string('slug')->unique();
            $table->longText('description')->nullable();
            $table->decimal('discounted_monthly_price', 12,2);
            $table->decimal('monthly_price', 12,2);
            $table->decimal('discounted_yearly_price', 12,2);
            $table->decimal('yearly_price', 12,2);
            $table->string('icon');
            $table->integer('student')->default(0);
            $table->integer('instructor')->default(0);
            $table->integer('course')->default(0);
            $table->integer('consultancy')->default(0);
            $table->integer('subscription_course')->default(0);
            $table->integer('bundle_course')->default(0);
            $table->integer('product')->default(0);
            $table->integer('device')->default(0);
            $table->integer('admin_commission')->default(0);
            $table->tinyInteger('in_home')->default(PACKAGE_STATUS_ACTIVE);
            $table->tinyInteger('recommended')->default(PACKAGE_STATUS_DISABLED);
            $table->tinyInteger('status')->default(PACKAGE_STATUS_ACTIVE);
            $table->unsignedBigInteger('user_id');
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
        Schema::dropIfExists('packages');
    }
};
