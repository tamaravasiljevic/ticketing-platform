<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\CheckinController;
use App\Http\Controllers\CartController;

// Use POST for login to align with best practices
Route::post('/login', [AuthController::class, 'login']);

Route::post('/tickets/purchase', [TicketController::class, 'purchase'])->middleware('auth:sanctum');

Route::post('/check-in', [CheckinController::class, 'checkIn'])->middleware('auth:sanctum');

Route::prefix('/cart')->group(function () {
    Route::post('/',[CartController::class, 'addItem']);
    Route::get('/', [CartController::class, 'getItems']);
    Route::post('/checkout', [CartController::class, 'checkout']);
})->middleware('auth:sanctum');
