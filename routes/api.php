<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\Auth\AuthController;

Route::post('/ticket/purchase', [TicketController::class, 'purchase'])->middleware('auth:sanctum');

// Use POST for login to align with best practices
Route::post('/login', [AuthController::class, 'login']);

