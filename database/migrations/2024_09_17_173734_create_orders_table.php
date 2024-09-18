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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_vai', 20)->nullable();
            $table->string('order_id', 100)->nullable();
            $table->string('order_created_at', 100)->nullable();
            $table->string('modified_at', 100)->nullable();
            $table->string('uuid', 100)->nullable();
            $table->string('status', 30)->nullable();
            $table->string('payment_mode', 30)->nullable();
            $table->string('total_cost', 30)->nullable();
            $table->string('currency', 30)->nullable();

            $table->string('buyer_first_name', 50)->nullable();
            $table->string('buyer_last_name', 50)->nullable();
            $table->string('buyer_email', 30)->nullable();
            $table->string('buyer_mobile_number', 20)->nullable();

            $table->longtext('buyer_line', 100)->nullable();
            $table->string('buyer_area', 100)->nullable();
            $table->string('buyer_city', 50)->nullable();
            $table->string('buyer_state', 50)->nullable();
            $table->string('buyer_county', 50)->nullable();
            $table->string('buyer_pin', 20)->nullable();

            
            $table->longtext('buyer_shipping_address_1', 100)->nullable();
            $table->string('buyer_shipping_address_2', 100)->nullable();
            $table->string('buyer_shipping_city', 50)->nullable();
            $table->string('buyer_shipping_state', 50)->nullable();
            $table->string('buyer_shipping_county', 50)->nullable();
            $table->string('buyer_shipping_pin', 20)->nullable();
            $table->string('buyer_shipping_mobile_number', 20)->nullable();
            $table->string('unique_id', 50)->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
