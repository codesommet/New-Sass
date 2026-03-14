<?php

use App\Http\Controllers\SuperAdmin\AccountRequestController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Super Admin — Account Request Routes
|--------------------------------------------------------------------------
*/

Route::prefix('account-requests')->as('account-requests.')->group(function () {
    Route::get('/', [AccountRequestController::class, 'index'])->name('index');
    Route::get('/{accountRequest}', [AccountRequestController::class, 'show'])->name('show');
    Route::post('/{accountRequest}/approve', [AccountRequestController::class, 'approve'])->name('approve');
    Route::post('/{accountRequest}/reject', [AccountRequestController::class, 'reject'])->name('reject');
    Route::delete('/{accountRequest}', [AccountRequestController::class, 'destroy'])->name('destroy');
});
