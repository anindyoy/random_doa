<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DoaController;
use App\Http\Controllers\TagController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/doa/random', [DoaController::class, 'random'])->name('doa.random');
Route::get('/doa/{doa}', [DoaController::class, 'show'])->name('doa.show');
Route::get('/tags/{tag}/doas', [DoaController::class, 'indexByTag'])->name('doa.indexByTag');
Route::post('/doa/previous', [DoaController::class, 'previous'])->name('doa.previous');
Route::get('/doa/next', [DoaController::class, 'next'])->name('doa.next');
Route::post('/doa/{doa}/visibility', [DoaController::class, 'manageVisibility'])->name('doa.visibility');
Route::post('/doa/{doa}/propose', [DoaController::class, 'propose'])->name('doa.propose');

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');