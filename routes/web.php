<?php

declare(strict_types=1);

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BudgetController;
use App\Http\Controllers\ChecklistController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\OnboardingController;
use App\Http\Controllers\VendorController;
use App\Http\Middleware\EnsureWeddingProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/', function (Request $request) {
    if (! $request->user()) {
        return view('landing');
    }

    return $request->user()->weddingProfile()->exists()
        ? redirect()->route('dashboard.index')
        : redirect()->route('onboarding.index');
})->name('home');

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::get('/auth/google/redirect', [AuthController::class, 'redirectToGoogle'])->name('auth.google.redirect');
Route::get('/auth/google/callback', [AuthController::class, 'handleGoogleCallback'])->name('auth.google.callback');
Route::post('/auth/google', [AuthController::class, 'loginWithGoogle'])->name('auth.google');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::prefix('admin')->name('admin.')->group(function (): void {
    Route::get('/login', [AdminController::class, 'showLogin'])->name('login');
    Route::post('/login', [AdminController::class, 'login'])->name('login.submit');
    Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/export', [AdminController::class, 'exportCsv'])->name('export');
    Route::post('/profiles/{profile}/impersonate', [AdminController::class, 'impersonate'])->name('profiles.impersonate');
    Route::post('/stop-impersonate', [AdminController::class, 'stopImpersonate'])->name('stopImpersonate');
    Route::delete('/profiles/{profile}', [AdminController::class, 'destroyProfile'])->name('profiles.destroy');
    Route::post('/logout', [AdminController::class, 'logout'])->name('logout');
});
Route::middleware('auth')->group(function (): void {
    Route::get('/onboarding', [OnboardingController::class, 'index'])->name('onboarding.index');
    Route::post('/onboarding', [OnboardingController::class, 'store'])->name('onboarding.store');
});

Route::middleware(['auth', EnsureWeddingProfile::class])->group(function (): void {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');
    Route::get('/profile/edit', [DashboardController::class, 'editProfile'])->name('dashboard.editProfile');
    Route::patch('/profile', [DashboardController::class, 'updateProfile'])->name('dashboard.updateProfile');

    Route::prefix('budget')->name('budget.')->group(function (): void {
        Route::get('/', [BudgetController::class, 'index'])->name('index');
        Route::get('/create', [BudgetController::class, 'create'])->name('create');
        Route::post('/', [BudgetController::class, 'store'])->name('store');
        Route::patch('/cap', [BudgetController::class, 'updateCap'])->name('updateCap');
        Route::get('/{budgetItem}/edit', [BudgetController::class, 'edit'])->name('edit');
        Route::put('/{budgetItem}', [BudgetController::class, 'update'])->name('update');
        Route::delete('/{budgetItem}', [BudgetController::class, 'destroy'])->name('destroy');
    });

    Route::prefix('checklist')->name('checklist.')->group(function (): void {
        Route::get('/', [ChecklistController::class, 'index'])->name('index');
        Route::get('/create', [ChecklistController::class, 'create'])->name('create');
        Route::post('/', [ChecklistController::class, 'store'])->name('store');
        Route::get('/{weddingTask}/edit', [ChecklistController::class, 'edit'])->name('edit');
        Route::put('/{weddingTask}', [ChecklistController::class, 'update'])->name('update');
        Route::patch('/{weddingTask}/toggle', [ChecklistController::class, 'toggle'])->name('toggle');
        Route::delete('/{weddingTask}', [ChecklistController::class, 'destroy'])->name('destroy');
    });

    Route::get('/timeline', fn () => redirect()->route('checklist.index'))->name('timeline.index');

    Route::prefix('vendors')->name('vendors.')->group(function (): void {
        Route::get('/', [VendorController::class, 'index'])->name('index');
        Route::get('/create', [VendorController::class, 'create'])->name('create');
        Route::post('/', [VendorController::class, 'store'])->name('store');
        Route::get('/{vendor}/edit', [VendorController::class, 'edit'])->name('edit');
        Route::get('/{vendor}/payments/create', [VendorController::class, 'createPayment'])->name('payments.create');
        Route::put('/{vendor}', [VendorController::class, 'update'])->name('update');
        Route::delete('/{vendor}', [VendorController::class, 'destroy'])->name('destroy');
        Route::post('/{vendor}/payments', [VendorController::class, 'storePayment'])->name('storePayment');
    });

});