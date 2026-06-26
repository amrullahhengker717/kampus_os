<?php

use App\Http\Controllers\PostController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

Route::middleware('auth')->group(function () {
    // Campus Feed Routes
    Route::get('/feed', [PostController::class, 'index'])->name('feed.index');
    Route::post('/feed', [PostController::class, 'store'])->name('feed.store');
    // Student Forum Routes
    Route::get('/forum', [\App\Http\Controllers\ThreadController::class, 'index'])->name('forum.index');
    Route::get('/forum/{thread:slug}', [\App\Http\Controllers\ThreadController::class, 'show'])->name('forum.show');

    // Classroom Booking Routes
    Route::get('/bookings', [\App\Http\Controllers\BookingController::class, 'index'])->name('bookings.index');
});

require __DIR__.'/auth.php';
