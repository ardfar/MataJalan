<?php

use App\Http\Controllers\AdminKycController;
use App\Http\Controllers\KycController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\WebController;
use App\Http\Middleware\EnsureIsAdmin;
use App\Http\Middleware\EnsureKycVerified;
use Illuminate\Support\Facades\Route;

// Public Routes
Route::get('/', [WebController::class, 'index'])->name('home');
Route::post('/search', [WebController::class, 'search'])->name('vehicle.search');
Route::get('/vehicle/{plate_number}', [WebController::class, 'show'])->name('vehicle.show');

// Protected Routes (Login required)
Route::middleware(['auth'])->group(function () {
    // Rating might require login
    Route::get('/vehicle/{plate_number}/rate', [WebController::class, 'rate'])->name('vehicle.rate');
    Route::post('/vehicle/{plate_number}/rate', [WebController::class, 'storeRating'])->name('vehicle.storeRating');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Profile Routes
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// KYC Routes (Requires Auth & Email Verification)
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/kyc', [KycController::class, 'index'])->name('kyc.index');
    Route::post('/kyc', [KycController::class, 'store'])->name('kyc.store');
});

// Admin Routes (Requires Auth, Email Verification & Admin Status)
Route::middleware(['auth', 'verified', EnsureIsAdmin::class])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/kyc', [AdminKycController::class, 'index'])->name('kyc.index');
    Route::get('/kyc/{user}', [AdminKycController::class, 'show'])->name('kyc.show');
    Route::get('/kyc/{user}/download', [AdminKycController::class, 'download'])->name('kyc.download');
    Route::patch('/kyc/{user}/approve', [AdminKycController::class, 'approve'])->name('kyc.approve');
    Route::patch('/kyc/{user}/reject', [AdminKycController::class, 'reject'])->name('kyc.reject');
});

require __DIR__.'/auth.php';
