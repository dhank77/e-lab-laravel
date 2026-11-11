<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\LabController;
use App\Http\Controllers\LoanController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => auth()->check() ? redirect()->route('dashboard') : view('welcome'))->name('home');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:10,1');
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/dashboard', DashboardController::class)->name('dashboard');

    // Admin: kelola lab, barang, dan peminjaman
    Route::middleware('admin')->prefix('admin')->group(function () {
        Route::resource('labs', LabController::class)->except(['index', 'show']);
        Route::resource('items', ItemController::class)->except(['show']);
        Route::get('/loans', [LoanController::class, 'manage'])->name('admin.loans.index');
        Route::patch('/loans/{loan}', [LoanController::class, 'updateStatus'])->name('admin.loans.update');
    });

    // Semua user: lihat data lab & barang
    Route::get('/labs', [LabController::class, 'index'])->name('labs.index');
    Route::get('/labs/{lab}', [LabController::class, 'show'])->name('labs.show');

    // Peminjaman milik user
    Route::get('/loans', [LoanController::class, 'index'])->name('loans.index');
    Route::get('/items/{item}/borrow', [LoanController::class, 'create'])->name('loans.create');
    Route::post('/items/{item}/borrow', [LoanController::class, 'store'])->name('loans.store');
    Route::delete('/loans/{loan}', [LoanController::class, 'cancel'])->name('loans.cancel');
});
