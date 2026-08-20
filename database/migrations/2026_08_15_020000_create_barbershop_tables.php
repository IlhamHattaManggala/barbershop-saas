<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Tenants Table
        Schema::create('tenants', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->unsignedBigInteger('owner_id')->nullable();
            $table->string('phone')->nullable();
            $table->text('address')->nullable();
            $table->string('logo')->nullable();
            $table->text('description')->nullable();
            $table->string('theme')->default('modern');
            $table->string('status')->default('active');
            $table->timestamps();
        });

        // 2. Add tenant_id & role to users table
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('tenant_id')->nullable()->constrained('tenants')->onDelete('cascade');
            $table->enum('role', ['superadmin', 'owner', 'cashier', 'barber', 'customer'])->default('customer');
            $table->string('phone')->nullable();
        });

        // 3. Services Table (Potong rambut, styling, dll)
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained('tenants')->onDelete('cascade');
            $table->string('name');
            $table->text('description')->nullable();
            $table->decimal('price', 12, 2);
            $table->integer('duration_minutes')->default(30);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // 4. Products Table (Pomade, Sampo, dll)
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained('tenants')->onDelete('cascade');
            $table->string('name');
            $table->string('category')->default('Hair Care');
            $table->decimal('price', 12, 2);
            $table->decimal('cost_price', 12, 2)->default(0); // HPP
            $table->integer('stock')->default(0);
            $table->integer('min_stock')->default(5);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // 5. Transactions Table (Penjualan POS Kasir)
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained('tenants')->onDelete('cascade');
            $table->string('transaction_number')->unique();
            $table->string('customer_name')->nullable();
            $table->string('customer_phone')->nullable();
            $table->foreignId('customer_user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('cashier_user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->decimal('subtotal', 12, 2);
            $table->decimal('tax', 12, 2)->default(0);
            $table->decimal('discount', 12, 2)->default(0);
            $table->decimal('total_amount', 12, 2);
            $table->decimal('cash_paid', 12, 2)->default(0);
            $table->decimal('change_due', 12, 2)->default(0);
            $table->enum('payment_method', ['cash', 'qris', 'transfer', 'debit'])->default('cash');
            $table->enum('status', ['paid', 'cancelled', 'refunded'])->default('paid');
            $table->timestamps();
        });

        // 6. Transaction Items Table
        Schema::create('transaction_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('transaction_id')->constrained('transactions')->onDelete('cascade');
            $table->enum('item_type', ['service', 'product']);
            $table->foreignId('service_id')->nullable()->constrained('services')->onDelete('set null');
            $table->foreignId('product_id')->nullable()->constrained('products')->onDelete('set null');
            $table->foreignId('barber_user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->string('item_name');
            $table->decimal('price', 12, 2);
            $table->integer('quantity')->default(1);
            $table->decimal('subtotal', 12, 2);
            $table->timestamps();
        });

        // 7. Reservations Table (Papan Booking Slot Workstation)
        Schema::create('reservations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained('tenants')->onDelete('cascade');
            $table->string('reservation_code')->unique();
            $table->string('customer_name');
            $table->string('customer_phone');
            $table->foreignId('customer_user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('service_id')->constrained('services')->onDelete('cascade');
            $table->foreignId('barber_user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->date('reservation_date');
            $table->time('start_time');
            $table->time('end_time');
            $table->enum('status', ['pending', 'confirmed', 'completed', 'cancelled'])->default('confirmed');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reservations');
        Schema::dropIfExists('transaction_items');
        Schema::dropIfExists('transactions');
        Schema::dropIfExists('products');
        Schema::dropIfExists('services');

        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['tenant_id']);
            $table->dropColumn(['tenant_id', 'role', 'phone']);
        });

        Schema::dropIfExists('tenants');
    }
};
