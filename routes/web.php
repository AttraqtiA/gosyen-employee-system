<?php

use App\Http\Controllers\UserDayController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome')
    ->name('welcome');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

// Route::view('absen', 'absen')
//     ->middleware(['auth', 'verified'])
//     ->name('absen');
Route::get('/absen', [UserDayController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('absen');

Route::post('/absen/new', [UserDayController::class, 'store'])
    ->middleware(['auth', 'verified'])
    ->name('absen.store');

Route::put('/absen/update/{user_day}', [UserDayController::class, 'update'])
    ->middleware(['auth', 'verified'])
    ->name('absen.update');

Route::view('daftar_absen', 'daftar_absen')
    ->middleware(['auth', 'verified', 'supervisor'])
    ->name('daftar_absen');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');



require __DIR__ . '/auth.php';
