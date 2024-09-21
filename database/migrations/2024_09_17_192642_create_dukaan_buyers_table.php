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
        Schema::create('dukaan_buyers', function (Blueprint $table) {
            $table->id();
            $table->string('order_id', 100)->nullable();
            $table->longText('order_uuid', 100)->nullable();
            $table->string('pin', 100)->nullable();
            $table->string('area', 100)->nullable();
            $table->string('city', 100)->nullable();
            $table->string('line', 100)->nullable();
            $table->string('name', 100)->nullable();
            $table->string('email', 100)->nullable();
            $table->string('mobile', 100)->nullable();
            $table->string('state', 100)->nullable();
            $table->string('county', 100)->nullable();
            $table->string('line_1', 100)->nullable();
            $table->string('region', 100)->nullable();
            $table->string('emirate', 100)->nullable();
            $table->string('landmark', 100)->nullable();
            $table->string('province', 100)->nullable();
            $table->string('prefecture', 100)->nullable();
            $table->string('governorate', 100)->nullable();
            $table->string('unique_id', 50)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dukaan_buyers');
    }
};
