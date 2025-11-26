<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DoaController;
use App\Http\Controllers\TagController;
use App\Http\Controllers\HomeController;
use App\Http\Middleware\AdminMiddleware;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/doa/random', [DoaController::class, 'random'])->name('doa.random');
Route::get('/doa/{doa}', [DoaController::class, 'show'])->name('doa.show');
Route::get('/tags/{tag}/doa', [DoaController::class, 'indexByTag'])->name('doa.indexByTag');
Route::post('/doa/previous', [DoaController::class, 'previous'])->name('doa.previous');
Route::get('/doa/next', [DoaController::class, 'next'])->name('doa.next');
Route::post('/doa/{doa}/visibility', [DoaController::class, 'manageVisibility'])->name('doa.visibility');
Route::post('/doa/{doa}/propose', [DoaController::class, 'propose'])->name('doa.propose');

// Doa Management Routes (Admin Only)
Route::middleware([AdminMiddleware::class])->group(function () {
    Route::get('/doa', [DoaController::class, 'index'])->name('doa.index'); // List all doa (admin)
    Route::get('/doa/create', [DoaController::class, 'create'])->name('doa.create'); // Create form
    Route::post('/doa', [DoaController::class, 'store'])->name('doa.store'); // Store new doa
    Route::get('/doa/{doa}/edit', [DoaController::class, 'edit'])->name('doa.edit'); // Edit form
    Route::put('/doa/{doa}', [DoaController::class, 'update'])->name('doa.update'); // Update doa
    Route::delete('/doa/{doa}', [DoaController::class, 'destroy'])->name('doa.destroy'); // Delete doa
});

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
