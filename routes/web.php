<?php

declare(strict_types=1);

use App\Http\Controllers\BudgetController;
use App\Http\Controllers\ChecklistController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TimelineController;
use App\Http\Controllers\VendorController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Module #5: Executive Dashboard & Couple Profile (Lead: irsyad)
|--------------------------------------------------------------------------
*/
Route::get('/', [DashboardController::class, 'index'])->name('dashboard.index');
Route::patch('/profile', [DashboardController::class, 'updateProfile'])->name('dashboard.updateProfile');

/*
|--------------------------------------------------------------------------
| Module #1: Budget Planner (Owner: marsha)
|--------------------------------------------------------------------------
*/
Route::prefix('budget')->name('budget.')->group(function (): void {
    Route::get('/', [BudgetController::class, 'index'])->name('index');
    Route::post('/', [BudgetController::class, 'store'])->name('store');
    Route::patch('/cap', [BudgetController::class, 'updateCap'])->name('updateCap');
    Route::put('/{budgetItem}', [BudgetController::class, 'update'])->name('update');
    Route::delete('/{budgetItem}', [BudgetController::class, 'destroy'])->name('destroy');
});

/*
|--------------------------------------------------------------------------
| Module #2: Wedding Checklist (Owner: dinda)
|--------------------------------------------------------------------------
*/
Route::prefix('checklist')->name('checklist.')->group(function (): void {
    Route::get('/', [ChecklistController::class, 'index'])->name('index');
    Route::post('/', [ChecklistController::class, 'store'])->name('store');
    Route::put('/{weddingTask}', [ChecklistController::class, 'update'])->name('update');
    Route::patch('/{weddingTask}/toggle', [ChecklistController::class, 'toggle'])->name('toggle');
    Route::delete('/{weddingTask}', [ChecklistController::class, 'destroy'])->name('destroy');
});

/*
|--------------------------------------------------------------------------
| Module #3: Wedding Timeline (Owner: adam)
|--------------------------------------------------------------------------
*/
Route::prefix('timeline')->name('timeline.')->group(function (): void {
    Route::get('/', [TimelineController::class, 'index'])->name('index');
    Route::post('/', [TimelineController::class, 'store'])->name('store');
    Route::patch('/{weddingTask}/status', [TimelineController::class, 'updateStatus'])->name('updateStatus');
});

/*
|--------------------------------------------------------------------------
| Module #4: Vendor Management (Owner: adam)
|--------------------------------------------------------------------------
*/
Route::prefix('vendors')->name('vendors.')->group(function (): void {
    Route::get('/', [VendorController::class, 'index'])->name('index');
    Route::post('/', [VendorController::class, 'store'])->name('store');
    Route::put('/{vendor}', [VendorController::class, 'update'])->name('update');
    Route::patch('/{vendor}/payment', [VendorController::class, 'updatePayment'])->name('updatePayment');
    Route::delete('/{vendor}', [VendorController::class, 'destroy'])->name('destroy');
});
