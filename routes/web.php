<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

// Gunakan route view jika Anda hanya ingin menampilkan view yang berisi komponen Livewire
Route::get('/', function () {
    return view('index'); // Pastikan Anda memiliki view ini
})->name('doa.index');

Route::middleware(['web'])->group(function () {
    Route::post('/login', [AuthController::class, 'login'])->name('login');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');
    Route::post('/register', [AuthController::class, 'register'])->name('register');

    Route::post('/forgot-password', [AuthController::class, 'forgotPassword'])->name('password.email');
    Route::get('/reset-password/{token}', [AuthController::class, 'showResetForm'])->name('password.reset');
    Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.update');
});
