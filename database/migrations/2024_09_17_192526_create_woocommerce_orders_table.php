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
        Schema::create('woocommerce_orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_id', 100)->nullable();
            $table->longText('order_uuid', 100)->nullable();
            $table->string('status', 20)->nullable();
            $table->string('currency', 20)->nullable();
            $table->string('version', 100)->nullable();
            $table->string('prices_include_tax', 100)->nullable();
            $table->string('date_created', 100)->nullable();
            $table->string('date_modified', 100)->nullable();
            $table->string('discount_total', 100)->nullable();
            $table->string('discount_tax', 100)->nullable();
            $table->string('shipping_total', 100)->nullable();
            $table->string('shipping_tax', 100)->nullable();
            $table->string('cart_tax', 100)->nullable();
            $table->string('total', 20)->nullable();
            $table->string('total_tax', 100)->nullable();
            $table->string('customer_id', 100)->nullable();
            $table->string('payment_method', 100)->nullable();
            $table->string('payment_method_title', 100)->nullable();
            $table->string('transaction_id', 100)->nullable();
            $table->string('customer_ip_address', 100)->nullable();
            $table->longText('customer_user_agent')->nullable();
            $table->string('created_via', 100)->nullable();
            $table->string('customer_note', 100)->nullable();
            $table->string('date_completed', 100)->nullable();
            $table->string('date_paid', 100)->nullable();
            $table->string('cart_hash', 100)->nullable();
            $table->string('number', 100)->nullable();
            $table->longText('payment_url')->nullable();
            $table->string('is_editable', 100)->nullable();
            $table->string('needs_payment', 100)->nullable();
            $table->string('needs_processing', 100)->nullable();
            $table->string('date_created_gmt', 100)->nullable();
            $table->string('date_modified_gmt', 100)->nullable();
            $table->string('date_completed_gmt', 100)->nullable();
            $table->string('date_paid_gmt', 100)->nullable();
            $table->string('currency_symbol', 20)->nullable();
            $table->string('unique_id', 50)->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('woocommerce_orders');
    }
};
