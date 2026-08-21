<?php

use App\Livewire\Admin\TenantsManager;
use App\Livewire\Admin\ThemeGuidePage;
use App\Livewire\Admin\ThemesManager;
use App\Livewire\Barber\BarberDashboard;
use App\Livewire\Cashier\CashierDashboard;
use App\Livewire\Feedback\UserFeedbackForm;
use App\Livewire\Inventory\ProductsManager;
use App\Livewire\Pos\KasirPos;
use App\Livewire\Public\PaymentFailedPage;
use App\Livewire\Public\PaymentSuccessPage;
use App\Livewire\Public\ShopBookingPage;
use App\Livewire\Public\ThemePreviewPage;
use App\Livewire\Reports\ReportsManager;
use App\Livewire\Reservations\PapanReservasi;
use App\Livewire\Services\ServicesManager;
use App\Livewire\Staff\StaffManager;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome')->name('home');

// AUTHENTICATED USER CENTRAL REDIRECT & STAFF WORKSPACE
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard', function () {
        $user = auth()->user();
        if ($user->isSuperAdmin()) {
            return redirect()->route('superadmin.dashboard');
        }
        if ($user->role === 'cashier') {
            return redirect()->route('cashier.dashboard');
        }
        if ($user->role === 'barber') {
            return redirect()->route('barber.dashboard');
        }

        return redirect()->route('owner.dashboard');
    })->name('dashboard');

    // CASHIER DEDICATED DASHBOARD & POS
    Route::get('cashier/dashboard', CashierDashboard::class)->name('cashier.dashboard');
    Route::get('cashier/pos', KasirPos::class)->name('pos');
    Route::redirect('pos', 'cashier/pos');

    // BARBER DEDICATED DASHBOARD & WORKSTATION
    Route::get('barber/dashboard', BarberDashboard::class)->name('barber.dashboard');
    Route::get('barber/reservations', PapanReservasi::class)->name('barber.reservations');

    Route::get('reservations', PapanReservasi::class)->name('reservations');
    Route::get('feedback', UserFeedbackForm::class)->name('user.feedback');
});

// STRICT TENANT OWNER MANAGEMENT GROUP (/owner/)
Route::middleware(['auth', 'verified', 'owner'])->prefix('owner')->group(function () {
    Route::view('dashboard', 'dashboard')->name('owner.dashboard');
    Route::get('reports', ReportsManager::class)->name('reports');
    Route::get('products', ProductsManager::class)->name('products');
    Route::get('services', ServicesManager::class)->name('services');
    Route::get('staff', StaffManager::class)->name('staff');
});

// SUPERADMIN PROTECTED GROUP (/superadmin/)
Route::middleware(['auth', 'verified', 'superadmin'])->prefix('superadmin')->group(function () {
    Route::view('dashboard', 'dashboard')->name('superadmin.dashboard');
    Route::get('tenants', TenantsManager::class)->name('tenants');
    Route::get('themes', ThemesManager::class)->name('superadmin.themes');
    Route::get('themes/guide', ThemeGuidePage::class)->name('superadmin.themes.guide');
});

require __DIR__.'/settings.php';

// PUBLIC STANDALONE THEME PREVIEW PORTAL (/themes/preview/{themeSlug})
Route::get('themes/preview/{themeSlug}', ThemePreviewPage::class)->name('theme.preview');

// PAKASIR PAYMENT GATEWAY THEME PURCHASE CALLBACK LANDING PAGES
Route::get('payment/success', PaymentSuccessPage::class)->name('payment.success');
Route::get('payment/failed', PaymentFailedPage::class)->name('payment.failed');

// PUBLIC TENANT RESERVATION PORTAL (/{slug} e.g. /gentlemen-barber)
Route::get('{slug}', ShopBookingPage::class)->name('tenant.public');
