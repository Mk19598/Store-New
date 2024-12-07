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
        Schema::create('content_template', function (Blueprint $table) {
            $table->id();
            $table->string('template_type')->nullable();
            $table->string('template_subject')->nullable();
            $table->longText('template_content')->nullable();
            $table->string('role_type')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('content_template');
    }
};
