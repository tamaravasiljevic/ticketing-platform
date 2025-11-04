<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\Auth\SocialLoginController;
use App\Http\Controllers\TicketController;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


Route::get('/google', [SocialLoginController::class, 'redirectToGoogle'])->name('google.login');
Route::get('/google/callback', [SocialLoginController::class, 'handleGoogleCallback'])->name('google.callback');
Route::get('/facebook/redirect', [SocialLoginController::class, 'redirectToFacebook'])->name('facebook.login');
Route::get('/facebook/callback', [SocialLoginController::class, 'handleFacebookCallback'])->name('facebook.callback');
Route::get('/github', [SocialLoginController::class, 'redirectToGitHub'])->name('github.login');
Route::get('/github/callback', [SocialLoginController::class, 'handleGitHubCallback']);

require __DIR__.'/auth.php';
