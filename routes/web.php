<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PrendaController;
use App\Http\Controllers\ProduccionController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\PqrsController;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.attempt');
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.store');
});

Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

Route::middleware('auth')->prefix('admin')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
});

Route::middleware('auth')->prefix('prendas')->group(function () {
    Route::get('/', [PrendaController::class, 'index'])->name('prendas.index');
    Route::post('/', [PrendaController::class, 'store'])->name('prendas.store');
    Route::put('/{prenda}', [PrendaController::class, 'update'])->name('prendas.update');
    Route::delete('/{prenda}', [PrendaController::class, 'destroy'])->name('prendas.destroy');
});

Route::middleware('auth')->prefix('produccion')->group(function () {
    Route::get('/', [ProduccionController::class, 'index'])->name('produccion.index');
    Route::post('/', [ProduccionController::class, 'store'])->name('produccion.store');
});

Route::middleware('auth')->prefix('pqrs')->group(function () {
    Route::get('/', [PqrsController::class, 'index'])->name('pqrs.index');
    Route::post('/', [PqrsController::class, 'store'])->name('pqrs.store');
});
