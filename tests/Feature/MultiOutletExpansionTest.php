<?php

use App\Models\Tenant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;

uses(RefreshDatabase::class);

test('owner can switch active outlet context and share purchased themes across outlets', function () {
    $owner = User::factory()->create([
        'role' => 'owner',
        'allowed_outlets' => 2,
        'purchased_themes' => ['dark-luxury'],
    ]);

    $outlet1 = Tenant::create([
        'name' => 'Barbershop Utama',
        'slug' => 'barber-utama-'.Str::random(6),
        'owner_id' => $owner->id,
        'theme' => 'classic-light',
    ]);

    $outlet2 = Tenant::create([
        'name' => 'Barbershop Kemang',
        'slug' => 'barber-kemang-'.Str::random(6),
        'owner_id' => $owner->id,
        'theme' => 'modern',
    ]);

    $owner->update(['tenant_id' => $outlet1->id, 'current_tenant_id' => $outlet1->id]);

    $this->actingAs($owner);

    expect($owner->active_tenant->id)->toBe($outlet1->id);

    // Switch context to Outlet 2
    $owner->update(['current_tenant_id' => $outlet2->id]);
    expect($owner->fresh()->active_tenant->id)->toBe($outlet2->id);

    // Option A Verification: Premium theme purchased by owner is unlocked for both outlets
    expect($outlet1->hasPurchasedTheme('dark-luxury'))->toBeTrue();
    expect($outlet2->hasPurchasedTheme('dark-luxury'))->toBeTrue();
});
