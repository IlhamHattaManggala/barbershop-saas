<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Reservation;
use App\Models\Service;
use App\Models\SiteSetting;
use App\Models\Tenant;
use App\Models\Transaction;
use App\Models\TransactionItem;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class BarbershopSeeder extends Seeder
{
    public function run(): void
    {
        // 1. SuperAdmin User
        $superadmin = User::create([
            'name' => 'Super Admin BarberSaaS',
            'email' => 'superadmin@babershop.my.id',
            'password' => Hash::make('password'),
            'role' => 'superadmin',
            'phone' => '081200000000',
        ]);

        // Default Site Settings
        SiteSetting::set('app_name', 'BarberSaaS');
        SiteSetting::set('app_tagline', 'Platform Barbershop Multi-Tenant #1 di Indonesia');
        SiteSetting::set('app_logo', 'images/logos/Logo-BaberSaaS.webp');
        SiteSetting::set('app_favicon', 'images/logos/Logo-BaberSaaS.webp');
        SiteSetting::set('footer_text', '© 2026 BarberSaaS. All rights reserved.');

        // Pakasir Payment Gateway Settings (Encrypted API Key)
        SiteSetting::setEncrypted('pakasir_api_key', 'Vbt9gVU18YnB2fq316y9XoKnhbFep4vr');
        SiteSetting::set('pakasir_slug', 'babershopsaas');
        SiteSetting::set('pakasir_is_active', '1');

        // 2. Tenant 1: Gentlemen Barber Studio
        $tenant1 = Tenant::create([
            'name' => 'Gentlemen Barber Studio',
            'slug' => 'gentlemen-barber',
            'phone' => '081234567890',
            'address' => 'Jl. Sudirman No. 45, Jakarta Selatan',
            'theme' => 'modern',
            'status' => 'active',
        ]);

        // Owner Tenant 1
        $owner1 = User::create([
            'tenant_id' => $tenant1->id,
            'name' => 'Budi Santoso',
            'email' => 'budi@babershop.my.id',
            'password' => Hash::make('password'),
            'role' => 'owner',
            'phone' => '081234567890',
        ]);
        $tenant1->update(['owner_id' => $owner1->id]);

        // Cashier Tenant 1
        $cashier1 = User::create([
            'tenant_id' => $tenant1->id,
            'name' => 'Rina Kasir',
            'email' => 'kasir@babershop.my.id',
            'password' => Hash::make('password'),
            'role' => 'cashier',
            'phone' => '081211112222',
        ]);

        // Barbers Tenant 1
        $barber1 = User::create([
            'tenant_id' => $tenant1->id,
            'name' => 'Agus Barber',
            'email' => 'agus@babershop.my.id',
            'password' => Hash::make('password'),
            'role' => 'barber',
            'phone' => '081233334444',
        ]);

        $barber2 = User::create([
            'tenant_id' => $tenant1->id,
            'name' => 'Joko Mastercut',
            'email' => 'joko@babershop.my.id',
            'password' => Hash::make('password'),
            'role' => 'barber',
            'phone' => '081255556666',
        ]);

        // Customer
        $customer1 = User::create([
            'name' => 'Andi Pelanggan',
            'email' => 'andi@gmail.com',
            'password' => Hash::make('password'),
            'role' => 'customer',
            'phone' => '081277778888',
        ]);

        // Services for Tenant 1
        $s1 = Service::create([
            'tenant_id' => $tenant1->id,
            'name' => 'Gentleman Haircut & Wash',
            'description' => 'Potong rambut pria presisi, shampoo, pijat kepala, dan styling pomade.',
            'price' => 50000,
            'duration_minutes' => 45,
            'is_active' => true,
        ]);

        $s2 = Service::create([
            'tenant_id' => $tenant1->id,
            'name' => 'Royal Shave & Hot Towel',
            'description' => 'Cukur kumis & jenggot halus dengan handuk hangat relaksasi.',
            'price' => 35000,
            'duration_minutes' => 30,
            'is_active' => true,
        ]);

        $s3 = Service::create([
            'tenant_id' => $tenant1->id,
            'name' => 'Hair Coloring & Hair Spa',
            'description' => 'Pewarnaan rambut hitam/fashion & perawatan vitamin rambut.',
            'price' => 120000,
            'duration_minutes' => 60,
            'is_active' => true,
        ]);

        // Products for Tenant 1
        $p1 = Product::create([
            'tenant_id' => $tenant1->id,
            'name' => 'Water-Based Pomade Matte Hold',
            'category' => 'Styling',
            'price' => 85000,
            'cost_price' => 45000,
            'stock' => 24,
            'min_stock' => 5,
            'is_active' => true,
        ]);

        $p2 = Product::create([
            'tenant_id' => $tenant1->id,
            'name' => 'Hair Tonic Ginseng 100ml',
            'category' => 'Hair Care',
            'price' => 65000,
            'cost_price' => 30000,
            'stock' => 3, // LOW STOCK ALERT
            'min_stock' => 5,
            'is_active' => true,
        ]);

        $p3 = Product::create([
            'tenant_id' => $tenant1->id,
            'name' => 'Hair Clay Texture Strong',
            'category' => 'Styling',
            'price' => 95000,
            'cost_price' => 50000,
            'stock' => 18,
            'min_stock' => 5,
            'is_active' => true,
        ]);

        // Transactions Today
        $tx1 = Transaction::create([
            'tenant_id' => $tenant1->id,
            'transaction_number' => 'TRX-'.date('Ymd').'-001',
            'customer_name' => 'Andi Pelanggan',
            'customer_phone' => '081277778888',
            'customer_user_id' => $customer1->id,
            'cashier_user_id' => $cashier1->id,
            'subtotal' => 135000,
            'tax' => 0,
            'discount' => 0,
            'total_amount' => 135000,
            'cash_paid' => 150000,
            'change_due' => 15000,
            'payment_method' => 'cash',
            'status' => 'paid',
        ]);

        TransactionItem::create([
            'transaction_id' => $tx1->id,
            'item_type' => 'service',
            'service_id' => $s1->id,
            'barber_user_id' => $barber1->id,
            'item_name' => $s1->name,
            'price' => 50000,
            'quantity' => 1,
            'subtotal' => 50000,
        ]);

        TransactionItem::create([
            'transaction_id' => $tx1->id,
            'item_type' => 'product',
            'product_id' => $p1->id,
            'item_name' => $p1->name,
            'price' => 85000,
            'quantity' => 1,
            'subtotal' => 85000,
        ]);

        // Reservations Today
        Reservation::create([
            'tenant_id' => $tenant1->id,
            'reservation_code' => 'RSV-'.strtoupper(Str::random(6)),
            'customer_name' => 'Doni Setiawan',
            'customer_phone' => '081899990000',
            'service_id' => $s1->id,
            'barber_user_id' => $barber1->id,
            'reservation_date' => date('Y-m-d'),
            'start_time' => '14:00:00',
            'end_time' => '14:45:00',
            'status' => 'confirmed',
            'notes' => 'Potong model Undercut Fade',
        ]);

        Reservation::create([
            'tenant_id' => $tenant1->id,
            'reservation_code' => 'RSV-'.strtoupper(Str::random(6)),
            'customer_name' => 'Rizal Fahmi',
            'customer_phone' => '085711112222',
            'service_id' => $s3->id,
            'barber_user_id' => $barber2->id,
            'reservation_date' => date('Y-m-d'),
            'start_time' => '15:30:00',
            'end_time' => '16:30:00',
            'status' => 'pending',
            'notes' => 'Cat warna Dark Brown',
        ]);
    }
}
