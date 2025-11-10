<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\CheckinController;

// Use POST for login to align with best practices
Route::post('/login', [AuthController::class, 'login']);

Route::post('/tickets/purchase', [TicketController::class, 'purchase'])->middleware('auth:sanctum');

Route::post('/check-in', [CheckinController::class, 'checkIn'])->middleware('auth:sanctum');
