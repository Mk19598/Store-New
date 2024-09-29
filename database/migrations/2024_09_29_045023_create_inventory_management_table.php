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
        Schema::create('inventory_management', function (Blueprint $table) {
            $table->id();
            $table->string('warehouse_id')->nullable();
            $table->string('product_name')->nullable();
            $table->string('weight')->nullable();
            $table->string('sku')->nullable();
            $table->string('barcode')->nullable();
            $table->string('barcode_image')->nullable();
            $table->string('product_type')->nullable();
            $table->integer('status')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventory_management');
    }
};
