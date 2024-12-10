<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('discount_coupons', function (Blueprint $table) {
            $table->id();
            $table->string('code');
            $table->string('name')->nullable();
            $table->text('description')->nullable();
            // The max uses this discount coupon has
            $table->integer('max_uses')->nullable();
            // How many times user can use this coupon
            $table->integer('max_uses_user')->nullable();
            // Whether the coupon is percentage or a fixed price
            $table->enum('type', ['percent', 'fixed'])->default('fixed');
            $table->double('discount_value'); // the amount to discount base on type
            $table->double('min_discount_value')->nullable(); // the amount to discount base on type
            $table->timestamp('starts_at')->nullable(); // when the coupon starts
            $table->timestamp('expires_at')->nullable(); // when the coupon ends
            $table->integer('status')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('discount_coupons');
    }
};
