<?php

use App\Http\Controllers\PostController;
use App\Http\Controllers\ThreadController;
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
    Route::get('/forum', [ThreadController::class, 'index'])->name('forum.index');
    Route::post('/forum', [ThreadController::class, 'store'])->name('forum.store');
    Route::get('/forum/{thread}', [ThreadController::class, 'show'])->name('forum.show');
    Route::get('/forum/{thread}/edit', [ThreadController::class, 'edit'])->name('forum.edit');
    Route::put('/forum/{thread}', [ThreadController::class, 'update'])->name('forum.update');
    Route::delete('/forum/{thread}', [ThreadController::class, 'destroy'])->name('forum.destroy');
    
    Route::post('/forum/{thread}/replies', [ThreadController::class, 'storeReply'])->name('forum.reply.store');
    Route::delete('/forum/replies/{reply}', [ThreadController::class, 'destroyReply'])->name('forum.reply.destroy');

    // Classroom Booking Routes
    Route::get('/bookings', [\App\Http\Controllers\BookingController::class, 'index'])->name('bookings.index');
});

require __DIR__.'/auth.php';
