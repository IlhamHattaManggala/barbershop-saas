<?php

use App\Models\Product;
use App\Models\StockMovement;
use App\Models\Tenant;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;

uses(RefreshDatabase::class);

test('stock movement is logged when stock is updated', function () {
    $tenant = Tenant::create([
        'name' => 'Barbershop Test',
        'slug' => 'barbershop-test-'.Str::random(6),
    ]);

    $product = Product::create([
        'tenant_id' => $tenant->id,
        'name' => 'Shampoo Premium',
        'category' => 'Shampoo',
        'price' => 45000,
        'stock' => 10,
    ]);

    StockMovement::create([
        'tenant_id' => $tenant->id,
        'product_id' => $product->id,
        'type' => 'purchase',
        'quantity' => 10,
        'notes' => 'Initial purchase stock',
    ]);

    $movements = StockMovement::where('product_id', $product->id)->get();

    expect($movements)->toHaveCount(1);
    expect($movements->first()->type)->toBe('purchase');
    expect($movements->first()->quantity)->toBe(10);
});
