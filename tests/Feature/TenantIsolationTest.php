<?php

use App\Models\Product;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;

uses(RefreshDatabase::class);

test('multi-tenant products are isolated between tenants', function () {
    $tenantA = Tenant::create([
        'name' => 'Tenant A Barbershop',
        'slug' => 'tenant-a-'.Str::random(6),
        'status' => 'active',
    ]);

    $tenantB = Tenant::create([
        'name' => 'Tenant B Barbershop',
        'slug' => 'tenant-b-'.Str::random(6),
        'status' => 'active',
    ]);

    $productA = Product::create([
        'tenant_id' => $tenantA->id,
        'name' => 'Pomade Deluxe Tenant A',
        'category' => 'Pomade',
        'price' => 50000,
        'stock' => 10,
    ]);

    $productB = Product::create([
        'tenant_id' => $tenantB->id,
        'name' => 'Hair Tonic Tenant B',
        'category' => 'Hair Care',
        'price' => 75000,
        'stock' => 5,
    ]);

    $userA = User::factory()->create([
        'tenant_id' => $tenantA->id,
        'role' => 'owner',
    ]);

    $this->actingAs($userA);

    $tenantAProducts = Product::where('tenant_id', $userA->tenant_id)->get();

    expect($tenantAProducts)->toHaveCount(1);
    expect($tenantAProducts->first()->name)->toBe('Pomade Deluxe Tenant A');
    expect($tenantAProducts->pluck('id'))->not->toContain($productB->id);
});
