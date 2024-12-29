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
        Schema::table('env_settings', function (Blueprint $table) {
            $table->string('storeId')->nullable()->after('Shipping_Password');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('env_settings', function (Blueprint $table) {
            $table->dropColumn('storeId');
        });
    }
};
