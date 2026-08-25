<?php

use App\Models\Tenant;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;

uses(RefreshDatabase::class);

test('tenant commission settings default to 40 percent barber and 5 percent cashier', function () {
    $tenant = Tenant::create([
        'name' => 'Commission Test Tenant',
        'slug' => 'commission-test-'.Str::random(6),
        'barber_commission_percentage' => 40,
        'cashier_commission_percentage' => 5,
    ]);

    expect((float) $tenant->barber_commission_percentage)->toBe(40.0);
    expect((float) $tenant->cashier_commission_percentage)->toBe(5.0);
});
