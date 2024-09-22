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
        Schema::create('dukaan_products', function (Blueprint $table) {
            $table->id();
            $table->string('order_id', 100)->nullable();
            $table->longText('order_uuid', 100)->nullable();
            $table->string('product_id')->nullable();
            $table->integer('quantity')->nullable();
            $table->string('is_sku_edited')->nullable();
            $table->integer('quantity_freed')->nullable();
            $table->string('product_slug')->nullable();
            $table->string('line_item_id')->nullable();
            $table->string('line_item_state')->nullable();
            $table->string('status')->nullable();
            $table->string('new_line_time')->nullable();
            $table->string('line_item_uuid')->nullable();
            $table->integer('old_quantity')->nullable();
            $table->integer('quantity_returned')->nullable();
            $table->string('product_sku_id')->nullable();
            $table->float('shipping_weight_kgs')->nullable();
            $table->string('default_staff_id')->nullable();
            $table->string('default_staff_name')->nullable();
            $table->string('shipment_id')->nullable();
            $table->decimal('line_item_tax', 8, 2)->nullable();
            $table->decimal('line_item_discount', 8, 2)->nullable();
            $table->decimal('line_item_service_charges', 8, 2)->nullable();
            $table->decimal('line_item_delivery_cost', 8, 2)->nullable();
            $table->decimal('selling_price', 8, 2)->nullable();
            $table->decimal('original_price', 8, 2)->nullable();
            $table->decimal('line_item_total_cost', 8, 2)->nullable();
            $table->string('line_item_group')->nullable();
            $table->string('is_membership_line_item')->nullable();
            $table->text('gift_wrap_message')->nullable();
            $table->string('name')->nullable();
            $table->string('image')->nullable();
            $table->string('unit')->nullable();
            $table->float('base_qty', 8, 2)->nullable();
            $table->string('product_uuid')->nullable();
            $table->json('sku')->nullable();
            $table->json('sku_weight_unit')->nullable();
            $table->json('variant_size')->nullable();
            $table->float('gst_rate', 5, 2)->nullable();
            $table->decimal('item_gst_charge', 8, 2)->nullable();
            $table->decimal('discount_per_unit', 8, 2)->nullable();
            $table->string('return_enabled')->nullable();
            $table->string('replacement_enabled')->nullable();
            $table->integer('return_duration_days')->nullable();
            $table->string('unique_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dukaan_products');
    }
};
