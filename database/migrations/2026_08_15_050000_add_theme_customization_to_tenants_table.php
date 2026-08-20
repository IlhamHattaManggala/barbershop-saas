<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tenants', function (Blueprint $table) {
            $table->string('primary_color')->default('amber')->nullable();
            $table->string('hero_tagline')->default('Toko Buka • Siap Menerima Reservasi Waktu Pangkas')->nullable();
            $table->string('button_style')->default('rounded-xl')->nullable();
            $table->string('hero_banner')->nullable();
            $table->json('purchased_themes')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('tenants', function (Blueprint $table) {
            $table->dropColumn(['primary_color', 'hero_tagline', 'button_style', 'hero_banner', 'purchased_themes']);
        });
    }
};
