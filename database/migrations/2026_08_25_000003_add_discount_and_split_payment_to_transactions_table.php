<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->decimal('discount_amount', 12, 2)->default(0)->after('discount');
            $table->string('second_payment_method')->nullable()->after('payment_method');
            $table->decimal('second_payment_amount', 12, 2)->default(0)->after('second_payment_method');
        });
    }

    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropColumn(['discount_amount', 'second_payment_method', 'second_payment_amount']);
        });
    }
};
