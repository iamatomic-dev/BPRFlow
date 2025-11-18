<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Dashboard\DashboardController;
use App\Http\Controllers\Nasabah\PengajuanKreditController;
use App\Http\Controllers\Nasabah\StatusKreditController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Dashboard\AdminController;
use App\Http\Controllers\Dashboard\ManagerController;
use App\Http\Controllers\Dashboard\DirekturController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
});

// ====================== NASABAH ======================
Route::middleware(['auth', 'role:Nasabah', 'verified'])->group(function () {
    Route::get('/nasabah/dashboard', [DashboardController::class, 'index'])->name('nasabah.dashboard');
});

Route::middleware(['auth', 'role:Nasabah'])
    ->prefix('pengajuan')
    ->name('pengajuan.')
    ->group(function () {
        Route::get('/step-1', [PengajuanKreditController::class, 'createStep1'])->name('step1');
        Route::post('/step-1', [PengajuanKreditController::class, 'postStep1'])->name('step1.post');

        Route::get('/step-2', [PengajuanKreditController::class, 'createStep2'])->name('step2');
        Route::post('/step-2', [PengajuanKreditController::class, 'postStep2'])->name('step2.post');
        Route::get('/back-step-1', [PengajuanKreditController::class, 'backToStep1'])->name('back.step1');

        Route::get('/step-3', [PengajuanKreditController::class, 'createStep3'])->name('step3');
        Route::post('/step-3', [PengajuanKreditController::class, 'postStep3'])->name('step3.post');
        Route::post('/upload-temp', [PengajuanKreditController::class, 'uploadTemp'])->name('upload.temp');
        Route::get('/back-step-2', [PengajuanKreditController::class, 'backToStep2'])->name('back.step2');

        Route::get('/review', [PengajuanKreditController::class, 'review'])->name('review');
        Route::get('/back-step-3', [PengajuanKreditController::class, 'backToStep3'])->name('back.step3');

        Route::post('/submit', [PengajuanKreditController::class, 'submit'])->name('submit');


        Route::get('/status-kredit', [StatusKreditController::class, 'index'])->name('status-kredit');
    });

// ====================== ADMIN ======================
Route::middleware(['auth', 'role:Admin'])->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
});

// ====================== MANAGER ======================
Route::middleware(['auth', 'role:Manager'])->group(function () {
    Route::get('/manager/dashboard', [ManagerController::class, 'index'])->name('manager.dashboard');
});

// ====================== DIREKTUR ======================
Route::middleware(['auth', 'role:Direktur'])->group(function () {
    Route::get('/direktur/dashboard', [DirekturController::class, 'index'])->name('direktur.dashboard');
});

// ====================== PROFILE ======================
Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// ====================== LOGOUT ======================
Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
    ->middleware('auth')
    ->name('logout');

require __DIR__ . '/auth.php';
