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
        Schema::dropIfExists('products');
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->tinyInteger('type')->default(PHYSICAL_PRODUCT);
            $table->string('title');
            $table->string('slug');
            $table->unsignedBigInteger('product_category_id');
            $table->decimal('old_price', 12,2)->default(0);
            $table->decimal('discount_percentage', 12,2)->default(0);
            $table->decimal('current_price', 12,2)->default(0);
            $table->integer('quantity')->default(0);
            $table->decimal('shipping_charge',12,2)->default(0);
            $table->decimal('average_review')->default(0);
            $table->string('thumbnail');
            $table->string('main_file')->nullable();
            $table->string('image_1');
            $table->string('image_2');
            $table->string('image_3');
            $table->string('image_4');
            $table->text('description');
            $table->text('shipping_return')->nullable();
            $table->text('additional_information')->nullable();
            $table->unsignedBigInteger('user_id');
            $table->tinyInteger('status')->default(STATUS_PENDING);
            $table->tinyInteger('is_feature')->default(STATUS_PENDING);
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->text('meta_keywords')->nullable();
            $table->string('og_image')->nullable();
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
        Schema::dropIfExists('products');
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->bigInteger('user_id');
            $table->string('name');
            $table->mediumText('details');
            $table->mediumText('book_summery')->nullable();
            $table->decimal('price')->default(0.00);
            $table->string('image')->nullable();
            $table->string('summery_file')->nullable();
            $table->string('main_file')->nullable();
            $table->string('type')->comment('ebook, hard_copy');
            $table->string('slug');
            $table->tinyInteger('status')->default(0)->comment('1=approved, 0=unapproved');
            $table->timestamps();
        });
    }
};
