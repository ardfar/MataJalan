<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\VehicleController;
use App\Http\Controllers\RatingController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/vehicles', [VehicleController::class, 'index']);
Route::get('/vehicles/{plate_number}', [VehicleController::class, 'show']);
Route::post('/ratings', [RatingController::class, 'store']);
