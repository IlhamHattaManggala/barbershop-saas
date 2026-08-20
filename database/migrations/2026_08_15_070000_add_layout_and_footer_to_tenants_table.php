<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tenants', function (Blueprint $table) {
            $table->string('layout_pos')->default('left')->nullable();
            $table->boolean('show_services')->default(true);
            $table->boolean('show_products')->default(true);
            $table->text('footer_text')->nullable();
            $table->string('footer_copyright')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('tenants', function (Blueprint $table) {
            $table->dropColumn(['layout_pos', 'show_services', 'show_products', 'footer_text', 'footer_copyright']);
        });
    }
};
