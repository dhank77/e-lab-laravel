<?php

use App\Http\Controllers\AdminLoanController;
use App\Http\Controllers\EquipmentController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\StudentLoanController;
use App\Http\Controllers\StudentRegistrationController;
use App\Http\Middleware\RequireRole;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/dashboard');
Route::get('/guest', [EquipmentController::class, 'catalog'])->name('guest.catalog');

Route::middleware('guest')->group(function () {
    Route::view('/login', 'login')->name('login');
    Route::post('/login', [LoginController::class, 'store'])->name('login.store');
});

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [LoginController::class, 'dashboard'])->name('dashboard');

    Route::middleware(RequireRole::class.':mahasiswa')->prefix('mahasiswa')->name('mahasiswa.')->group(function () {
        Route::get('/dashboard', [StudentLoanController::class, 'catalog'])->name('dashboard');
        Route::get('/pengajuan', [StudentLoanController::class, 'index'])->name('requests');
        Route::post('/pengajuan', [StudentLoanController::class, 'store'])->name('requests.store');
        Route::patch('/pengajuan/{loan}/batalkan', [StudentLoanController::class, 'cancel'])->name('requests.cancel');
    });

    Route::middleware(RequireRole::class.':admin')->prefix('petugas')->name('petugas.')->group(function () {
        Route::get('/dashboard', [EquipmentController::class, 'dashboard'])->name('dashboard');
        Route::get('/mahasiswa/daftar', [StudentRegistrationController::class, 'create'])->name('students.create');
        Route::post('/mahasiswa/daftar', [StudentRegistrationController::class, 'store'])->name('students.store');
        Route::get('/alat', [EquipmentController::class, 'index'])->name('equipment.index');
        Route::get('/kategori', [EquipmentController::class, 'categories'])->name('categories');

        Route::get('/pengajuan', [AdminLoanController::class, 'index'])->name('requests');
        Route::patch('/pengajuan/{loan}', [AdminLoanController::class, 'process'])->name('requests.process');
        Route::get('/riwayat', [AdminLoanController::class, 'history'])->name('history');

        Route::post('/alat', [EquipmentController::class, 'store'])->name('equipment.store');
        Route::get('/alat/{equipment}', [EquipmentController::class, 'show'])->name('equipment.show');
        Route::get('/alat/{equipment}/edit', [EquipmentController::class, 'edit'])->name('equipment.edit');
        Route::put('/alat/{equipment}', [EquipmentController::class, 'update'])->name('equipment.update');
        Route::delete('/alat/{equipment}', [EquipmentController::class, 'destroy'])->name('equipment.destroy');
        Route::get('/alat/{equipment}/foto', [EquipmentController::class, 'photo'])->name('equipment.photo');
    });

    Route::post('/logout', [LoginController::class, 'destroy'])->name('logout');
});
