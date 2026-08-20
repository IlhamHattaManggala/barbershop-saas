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
        Schema::table('tenants', function (Blueprint $table) {
            if (! Schema::hasColumn('tenants', 'cashier_commission_percentage')) {
                $table->decimal('cashier_commission_percentage', 5, 2)->default(0.00)->after('barber_commission_percentage');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tenants', function (Blueprint $table) {
            if (Schema::hasColumn('tenants', 'cashier_commission_percentage')) {
                $table->dropColumn('cashier_commission_percentage');
            }
        });
    }
};
