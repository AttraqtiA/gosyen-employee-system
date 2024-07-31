<?php

use App\Http\Controllers\UserDayController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome')
    ->name('welcome');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::get('/absen', [UserDayController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('absen');

Route::post('/absen/new', [UserDayController::class, 'store'])
    ->middleware(['auth', 'verified'])
    ->name('absen.store');

Route::put('/absen/update/{user_Day}', [UserDayController::class, 'update'])
    ->middleware(['auth', 'verified'])
    ->name('absen.update');


Route::get('/daftar_absen', [UserDayController::class, 'daftar_absen']) // Logic rolenya di UserDayController
    ->middleware(['auth', 'verified'])
    ->name('daftar_absen');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');



require __DIR__ . '/auth.php';
