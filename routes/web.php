<?php

use App\Http\Controllers\Admin\VehicleEditController as AdminVehicleEditController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\AdminKycController;
use App\Http\Controllers\AdminRatingController;
use App\Http\Controllers\AdminUserController;
use App\Http\Controllers\AdminVehicleSpecController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\KycController;
use App\Http\Controllers\MyVehicleController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\VehicleEditController;
use App\Http\Controllers\VehicleUserController;
use App\Http\Controllers\WebController;
use App\Http\Middleware\EnsureIsAdmin;
use App\Http\Middleware\EnsureKycVerified;
use Illuminate\Support\Facades\Route;

// Public Routes
Route::get('/', [WebController::class, 'index'])->name('home');
Route::get('/tutorial', [WebController::class, 'tutorial'])->name('tutorial');
Route::post('/search', [WebController::class, 'search'])->name('vehicle.search');
Route::get('/vehicle/{identifier}', [WebController::class, 'show'])->name('vehicle.show');

// Protected Routes (Login required)
Route::middleware(['auth'])->group(function () {
    // Rating might require login
    Route::get('/vehicle/{identifier}/rate', [WebController::class, 'rate'])->name('vehicle.rate');
    Route::post('/vehicle/{identifier}/rate', [WebController::class, 'storeRating'])->name('vehicle.storeRating');

    // Vehicle Registration
    Route::get('/vehicle/{identifier}/create', [WebController::class, 'create'])->name('vehicle.create');
    Route::post('/vehicle/{identifier}/store', [WebController::class, 'store'])->name('vehicle.store');
    
    // Vehicle Edits
    Route::get('/vehicle/{vehicle}/edit', [VehicleEditController::class, 'create'])->name('vehicle.edit');
    Route::post('/vehicle/{vehicle}/update-request', [VehicleEditController::class, 'store'])->name('vehicle.update-request');

    // Vehicle User Registration
    Route::get('/vehicle/{vehicle}/user/create', [VehicleUserController::class, 'create'])->name('vehicle.user.create');
    Route::post('/vehicle/{vehicle}/user/store', [VehicleUserController::class, 'store'])->name('vehicle.user.store');
    
    // These use model binding which we updated to use UUID
    Route::get('/vehicle/{vehicle}/registered', [WebController::class, 'registered'])->name('vehicle.registered');
    Route::post('/vehicle/{vehicle}/feedback', [WebController::class, 'storeFeedback'])->name('vehicle.storeFeedback');
});

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

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
    // Dashboard
    Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');

    // Users Management
    Route::resource('users', AdminUserController::class);

    // KYC Management
    Route::get('/kyc', [AdminKycController::class, 'index'])->name('kyc.index');
    Route::get('/kyc/{user}', [AdminKycController::class, 'show'])->name('kyc.show');
    Route::get('/kyc/{user}/download', [AdminKycController::class, 'download'])->name('kyc.download');
    Route::patch('/kyc/{user}/approve', [AdminKycController::class, 'approve'])->name('kyc.approve');
    Route::patch('/kyc/{user}/reject', [AdminKycController::class, 'reject'])->name('kyc.reject');

    // Rating Management
    Route::get('/ratings', [AdminRatingController::class, 'index'])->name('ratings.index');
    Route::get('/ratings/{rating}', [AdminRatingController::class, 'show'])->name('ratings.show');
    Route::patch('/ratings/{rating}/approve', [AdminRatingController::class, 'approve'])->name('ratings.approve');
    Route::patch('/ratings/{rating}/reject', [AdminRatingController::class, 'reject'])->name('ratings.reject');

    // Vehicle Edits Management
    Route::get('/vehicle-edits', [AdminVehicleEditController::class, 'index'])->name('vehicle-edits.index');
    Route::get('/vehicle-edits/{edit}/document', [AdminVehicleEditController::class, 'downloadDocument'])->name('vehicle-edits.download');
    Route::patch('/vehicle-edits/{edit}/approve', [AdminVehicleEditController::class, 'approve'])->name('vehicle-edits.approve');
    Route::patch('/vehicle-edits/{edit}/reject', [AdminVehicleEditController::class, 'reject'])->name('vehicle-edits.reject');

    // Vehicle Users Management
    Route::get('/vehicle-users', [VehicleUserController::class, 'index'])->name('vehicle-users.index');
    Route::get('/vehicle-users/{vehicleUser}', [VehicleUserController::class, 'show'])->name('vehicle-users.show');
    Route::get('/vehicle-users/{vehicleUser}/evidence', [VehicleUserController::class, 'downloadEvidence'])->name('vehicle-users.download');
    Route::patch('/vehicle-users/{vehicleUser}/update', [VehicleUserController::class, 'update'])->name('vehicle-users.update');

    // Vehicle Specs Management
    Route::delete('/vehicle-specs/bulk-delete', [AdminVehicleSpecController::class, 'bulkDestroy'])->name('vehicle-specs.bulk-delete');
    Route::resource('vehicle-specs', AdminVehicleSpecController::class)
        ->names([
            'index' => 'vehicle-specs.index',
            'create' => 'vehicle-specs.create',
            'store' => 'vehicle-specs.store',
            'edit' => 'vehicle-specs.edit',
            'update' => 'vehicle-specs.update',
            'destroy' => 'vehicle-specs.destroy',
        ]);
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/my-vehicles', [MyVehicleController::class, 'index'])->name('my-vehicles.index');
    Route::get('/my-vehicles/add', [MyVehicleController::class, 'create'])->name('my-vehicles.create');
    Route::post('/my-vehicles/check', [MyVehicleController::class, 'check'])->name('my-vehicles.check');
    Route::get('/my-vehicles/register/{plate}', [MyVehicleController::class, 'register'])->name('my-vehicles.register');
    Route::post('/my-vehicles/store', [MyVehicleController::class, 'store'])->name('my-vehicles.store');
});

require __DIR__.'/auth.php';
