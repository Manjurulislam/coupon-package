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
        Schema::create('coupons', function (Blueprint $table) {
            $table->id();
            $table->string('label');
            $table->string('code')->unique();
            $table->unsignedTinyInteger('coupon_applied_on')->default(1);
            $table->unsignedBigInteger('course_category_id')->nullable();
            $table->unsignedTinyInteger('discount_type')->default(1);
            $table->double('discount_amount');
            $table->dateTime('expire_date');
            $table->boolean('status')->default(1);
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
        Schema::dropIfExists('coupons');
    }
};
