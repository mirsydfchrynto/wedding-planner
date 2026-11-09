<?php

declare(strict_types=1);

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BudgetController;
use App\Http\Controllers\ChecklistController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\GuestController;
use App\Http\Controllers\OnboardingController;
use App\Http\Controllers\PrintController;
use App\Http\Controllers\SaasController;
use App\Http\Controllers\TimelineController;
use App\Http\Controllers\VendorController;
use App\Http\Middleware\EnsureAdminAccess;
use App\Http\Middleware\EnsureWeddingProfile;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Web Utama: SaaS Brand Landing Page, Marketing & Google Auth (irsyad)
|--------------------------------------------------------------------------
*/

Route::get('/', [SaasController::class, 'landing'])->name('saas.landing');
Route::get('/saas', [SaasController::class, 'landing'])->name('saas.showcase');

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'loginWithPassword'])->name('login.password');
Route::post('/auth/google', [AuthController::class, 'loginWithGoogle'])->name('auth.google');
Route::post('/auth/admin', [AuthController::class, 'loginAsAdmin'])->name('auth.admin');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

/*
|--------------------------------------------------------------------------
| Customer Onboarding: Create the wedding profile before workspace access
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function (): void {
    Route::get('/onboarding', [OnboardingController::class, 'index'])->name('onboarding.index');
    Route::post('/onboarding', [OnboardingController::class, 'store'])->name('onboarding.store');
});

/*
|--------------------------------------------------------------------------
| Authenticated Customer Workspace: 5 Core Wedding Modules
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', EnsureWeddingProfile::class])->group(function (): void {
    // Module #5: Executive Dashboard & Couple Profile (Lead: irsyad)
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');
    Route::patch('/profile', [DashboardController::class, 'updateProfile'])->name('dashboard.updateProfile');
    Route::get('/print/master-guide', [PrintController::class, 'masterGuide'])->name('print.masterGuide');

    // Module #1: Budget Planner (Owner: marsha)
    Route::prefix('budget')->name('budget.')->group(function (): void {
        Route::get('/', [BudgetController::class, 'index'])->name('index');
        Route::post('/', [BudgetController::class, 'store'])->name('store');
        Route::patch('/cap', [BudgetController::class, 'updateCap'])->name('updateCap');
        Route::put('/{budgetItem}', [BudgetController::class, 'update'])->name('update');
        Route::delete('/{budgetItem}', [BudgetController::class, 'destroy'])->name('destroy');
    });

    // Module #2: Wedding Checklist (Owner: dinda)
    Route::prefix('checklist')->name('checklist.')->group(function (): void {
        Route::get('/', [ChecklistController::class, 'index'])->name('index');
        Route::post('/', [ChecklistController::class, 'store'])->name('store');
        Route::put('/{weddingTask}', [ChecklistController::class, 'update'])->name('update');
        Route::patch('/{weddingTask}/toggle', [ChecklistController::class, 'toggle'])->name('toggle');
        Route::delete('/{weddingTask}', [ChecklistController::class, 'destroy'])->name('destroy');
    });

    // Module #3: Wedding Timeline (Owner: adam)
    Route::prefix('timeline')->name('timeline.')->group(function (): void {
        Route::get('/', [TimelineController::class, 'index'])->name('index');
        Route::post('/', [TimelineController::class, 'store'])->name('store');
        Route::patch('/{weddingTask}/status', [TimelineController::class, 'updateStatus'])->name('updateStatus');
        Route::post('/rundowns', [TimelineController::class, 'storeRundown'])->name('storeRundown');
    });

    // Module #4: Vendor Management (Owner: adam)
    Route::prefix('vendors')->name('vendors.')->group(function (): void {
        Route::get('/', [VendorController::class, 'index'])->name('index');
        Route::post('/', [VendorController::class, 'store'])->name('store');
        Route::put('/{vendor}', [VendorController::class, 'update'])->name('update');
        Route::patch('/{vendor}/payment', [VendorController::class, 'updatePayment'])->name('updatePayment');
        Route::delete('/{vendor}', [VendorController::class, 'destroy'])->name('destroy');
        Route::post('/{vendor}/payments', [VendorController::class, 'storePayment'])->name('storePayment');
    });

    // Module #6: Buku Tamu Digital & RSVP (Owner: dinda)
    Route::post('/guests/bulk', [GuestController::class, 'bulkStore'])
        ->name('guests.bulkStore');

    // Export CSV Rekapitulasi Tamu
    Route::get('/guests/export-csv', [GuestController::class, 'exportCsv'])
        ->name('guests.exportCsv');

    Route::resource('guests', GuestController::class)->except(['create', 'show', 'edit']);
});

/*
|--------------------------------------------------------------------------
| Hidden & Private Admin / WO Backoffice Console (Role: admin only — irsyad)
|--------------------------------------------------------------------------
*/
Route::prefix('admin')
    ->name('admin.')
    ->middleware(['auth', EnsureAdminAccess::class])
    ->group(function (): void {
        Route::get('/', [AdminController::class, 'index'])->name('index');
        Route::post('/escalate-overdue', [AdminController::class, 'escalateOverdue'])->name('escalateOverdue');
        Route::patch('/vendors/{vendor}/verify', [AdminController::class, 'verifyVendor'])->name('verifyVendor');
        Route::patch('/vendors/{vendor}/paid', [AdminController::class, 'markPaid'])->name('markPaid');
    });
