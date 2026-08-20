<?php

use App\Http\Controllers\PakasirWebhookController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes & Webhooks
|--------------------------------------------------------------------------
*/

Route::post('webhooks/pakasir', [PakasirWebhookController::class, 'handle'])->name('api.webhooks.pakasir');
