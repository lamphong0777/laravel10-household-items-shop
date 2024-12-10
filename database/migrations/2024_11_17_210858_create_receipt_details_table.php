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
        Schema::create('receipt_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_id');
            $table->unsignedBigInteger('good_receipt_id');
            $table->integer('import_qty');
            $table->integer('remaining_qty');
            $table->double('import_price');
            $table->timestamps();

            $table->foreign('product_id')->references('id')->on('products');
            $table->foreign('good_receipt_id')->references('id')->on('goods_receipts');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('receipt_details');
    }
};
