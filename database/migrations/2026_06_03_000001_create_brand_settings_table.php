<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('brand_settings', function (Blueprint $table) {
            $table->id();
            $table->nullableMorphs('tenant');
            $table->string('brand_name')->nullable();
            $table->string('logo_path')->nullable();
            $table->string('favicon_path')->nullable();
            $table->string('font_family')->nullable()->default('Inter');
            $table->json('colors')->nullable();
            $table->longText('custom_css')->nullable();
            $table->string('email_from_address')->nullable();
            $table->string('email_from_name')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();

            $table->unique(['tenant_type', 'tenant_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('brand_settings');
    }
};