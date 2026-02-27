<?php

use App\Http\Controllers\SuperAdmin\PlanController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Super Admin — Plan Management Routes
|--------------------------------------------------------------------------
*/

Route::prefix('plans')->as('plans.')->group(function () {
    Route::get('/', [PlanController::class, 'index'])->name('index');
    Route::get('/create', [PlanController::class, 'create'])->name('create');
    Route::post('/', [PlanController::class, 'store'])->name('store');
    Route::get('/{plan}', [PlanController::class, 'show'])->name('show');
    Route::get('/{plan}/edit', [PlanController::class, 'edit'])->name('edit');
    Route::put('/{plan}', [PlanController::class, 'update'])->name('update');
    Route::delete('/{plan}', [PlanController::class, 'destroy'])->name('destroy');
});
