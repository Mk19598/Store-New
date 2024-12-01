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
        Schema::create('env_settings', function (Blueprint $table) {
            $table->id();
            $table->longText('MAIL_HOST')->nullable();
            $table->longText('MAIL_PORT')->nullable();
            $table->longText('MAIL_USERNAME')->nullable();
            $table->longText('MAIL_PASSWORD')->nullable();
            $table->longText('MAIL_ENCRYPTION')->nullable();
            $table->longText('MAIL_FROM_ADDRESS')->nullable();
            $table->longText('MAIL_FROM_NAME')->nullable();
            $table->longText('POETS_API_ACCESS_TOKEN')->nullable();
            $table->longText('POETS_API_INSTANCE_ID')->nullable();
            $table->longText('Shipping_Username')->nullable();
            $table->longText('Shipping_Password')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('env_settings');
    }
};
