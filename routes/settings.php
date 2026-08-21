<?php

use App\Livewire\Settings\BrandingSettings;
use App\Livewire\Settings\GatewaySettings;
use App\Livewire\Settings\ReceiptSettings;
use App\Livewire\Settings\ShopSettings;
use App\Livewire\Settings\ThemeCustomizer;
use App\Livewire\Settings\ThemeSettings;
use Illuminate\Support\Facades\Route;

// BARBER SETTINGS ROUTES (/barber/settings/*)
Route::middleware(['auth', 'verified'])->prefix('barber')->group(function () {
    Route::redirect('settings', 'barber/settings/profile');
    Route::livewire('settings/profile', 'pages::settings.profile')->name('barber.profile.edit');
    Route::livewire('settings/appearance', 'pages::settings.appearance')->name('barber.appearance.edit');
    Route::livewire('settings/security', 'pages::settings.security')
        ->middleware([
            'password.confirm',
        ])
        ->name('barber.security.edit');
});

// CASHIER SETTINGS ROUTES (/cashier/settings/*)
Route::middleware(['auth', 'verified'])->prefix('cashier')->group(function () {
    Route::redirect('settings', 'cashier/settings/profile');
    Route::livewire('settings/profile', 'pages::settings.profile')->name('cashier.profile.edit');
    Route::livewire('settings/appearance', 'pages::settings.appearance')->name('cashier.appearance.edit');
    Route::livewire('settings/security', 'pages::settings.security')
        ->middleware([
            'password.confirm',
        ])
        ->name('cashier.security.edit');
});

// OWNER SETTINGS ROUTES (/owner/settings/*)
Route::middleware(['auth', 'verified', 'owner'])->prefix('owner')->group(function () {
    Route::redirect('settings', 'owner/settings/profile');
    Route::livewire('settings/profile', 'pages::settings.profile')->name('owner.profile.edit');
    Route::livewire('settings/appearance', 'pages::settings.appearance')->name('owner.appearance.edit');
    Route::get('settings/shop', ShopSettings::class)->name('owner.shop.edit');
    Route::get('settings/receipt', ReceiptSettings::class)->name('owner.receipt.edit');
    Route::get('settings/theme', ThemeSettings::class)->name('owner.theme.edit');
    Route::get('settings/theme/customize', ThemeCustomizer::class)->name('owner.theme.customize');
    Route::livewire('settings/security', 'pages::settings.security')
        ->middleware([
            'password.confirm',
        ])
        ->name('owner.security.edit');
});

// SUPERADMIN PROTECTED SETTINGS ROUTES (/superadmin/settings/*)
Route::middleware(['auth', 'verified', 'superadmin'])->prefix('superadmin')->group(function () {
    Route::redirect('settings', 'superadmin/settings/branding');
    Route::get('settings/branding', BrandingSettings::class)->name('branding.edit');
    Route::get('settings/gateway', GatewaySettings::class)->name('superadmin.gateway.edit');
    Route::livewire('settings/profile', 'pages::settings.profile')->name('superadmin.profile.edit');
    Route::livewire('settings/appearance', 'pages::settings.appearance')->name('superadmin.appearance.edit');
    Route::livewire('settings/security', 'pages::settings.security')
        ->middleware([
            'password.confirm',
        ])
        ->name('superadmin.security.edit');
});

// LEGACY SETTINGS REDIRECTS
Route::middleware(['auth'])->group(function () {
    Route::get('settings/profile', function () {
        $user = auth()->user();
        if ($user->isSuperAdmin()) {
            return redirect()->route('superadmin.profile.edit');
        }
        if ($user->isCashier()) {
            return redirect()->route('cashier.profile.edit');
        }
        if ($user->isBarber()) {
            return redirect()->route('barber.profile.edit');
        }

        return redirect()->route('owner.profile.edit');
    })->name('profile.edit');

    Route::get('settings/appearance', function () {
        $user = auth()->user();
        if ($user->isSuperAdmin()) {
            return redirect()->route('superadmin.appearance.edit');
        }
        if ($user->isCashier()) {
            return redirect()->route('cashier.appearance.edit');
        }
        if ($user->isBarber()) {
            return redirect()->route('barber.appearance.edit');
        }

        return redirect()->route('owner.appearance.edit');
    })->name('appearance.edit');

    Route::get('settings/security', function () {
        $user = auth()->user();
        if ($user->isSuperAdmin()) {
            return redirect()->route('superadmin.security.edit');
        }
        if ($user->isCashier()) {
            return redirect()->route('cashier.security.edit');
        }
        if ($user->isBarber()) {
            return redirect()->route('barber.security.edit');
        }

        return redirect()->route('owner.security.edit');
    })->name('security.edit');
});

Route::get('.well-known/passkey-endpoints', function () {
    return response()->json([
        'enroll' => route('owner.security.edit'),
        'manage' => route('owner.security.edit'),
    ]);
})->name('well-known.passkeys');
