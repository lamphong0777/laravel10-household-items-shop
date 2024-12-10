<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('order_status')->default('pending')->after('grand_total');
            $table->dateTime('shipped_date')->nullable()->after('order_status');
            $table->dateTime('delivered_date')->nullable()->after('shipped_date');
            $table->string('payment_method')->nullable()->after('delivered_date');
            $table->dateTime('paid_date')->nullable()->after('payment_method')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('order_status');
            $table->dropColumn('shipped_date');
            //delivered_date
            $table->dropColumn('delivered_date');
            $table->dropColumn('payment_method');
            $table->dropColumn('paid_date');
        });
    }
};