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
});
