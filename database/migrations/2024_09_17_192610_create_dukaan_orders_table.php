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
        Schema::create('dukaan_orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_id', 100)->nullable();
            $table->longText('order_uuid', 100)->nullable();
            $table->string('order_created_at', 100)->nullable();
            $table->string('created_at_utc', 100)->nullable();
            $table->string('modified_at', 100)->nullable();
            $table->string('modified_at_utc', 100)->nullable();
            $table->string('uuid', 100)->nullable();
            $table->string('status', 100)->nullable();
            $table->string('type', 100)->nullable();
            $table->string('return_status', 100)->nullable();
            $table->string('return_type', 100)->nullable();
            $table->string('payment_mode', 100)->nullable();
            $table->string('table_uuid', 100)->nullable();
            $table->string('reseller_commission', 100)->nullable();
            $table->string('is_new', 100)->nullable();
            $table->string('total_cost', 100)->nullable();
            $table->string('channel', 100)->nullable();
            $table->string('source', 100)->nullable();
            $table->longText('image')->nullable();
            $table->string('store_table_data', 100)->nullable();
            $table->string('product_count', 100)->nullable();
            $table->string('table_id', 100)->nullable();
            $table->string('seller_marked_prepaid', 100)->nullable();
            $table->string('sort_order_delivered', 100)->nullable();
            $table->string('sort_order_pending', 100)->nullable();
            $table->string('utm_source', 100)->nullable();
            $table->string('utm_medium', 100)->nullable();
            $table->string('utm_campaign', 100)->nullable();
            $table->string('utm_term', 100)->nullable();
            $table->string('utm_query', 100)->nullable();
            $table->string('utm_content', 100)->nullable();
            $table->string('unique_id', 50)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dukaan_orders');
    }
};
