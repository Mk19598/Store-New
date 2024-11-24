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
        Schema::table('orders', function (Blueprint $table) {
            $table->string('buyer_shipping_first_name')->nullable()->after('buyer_pin');
            $table->string('buyer_shipping_last_name')->nullable()->after('buyer_shipping_first_name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('buyer_shipping_first_name');
            $table->dropColumn('buyer_shipping_last_name');
        });
    }
};
