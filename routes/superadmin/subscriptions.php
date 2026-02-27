<?php

use App\Http\Controllers\SuperAdmin\SubscriptionController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Super Admin — Subscription Management Routes
|--------------------------------------------------------------------------
*/

Route::prefix('subscriptions')->as('subscriptions.')->group(function () {
    Route::get('/', [SubscriptionController::class, 'index'])->name('index');
    Route::get('/{subscription}', [SubscriptionController::class, 'show'])->name('show');
});
