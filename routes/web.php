<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Dashboard\DashboardController;
use App\Http\Controllers\User\PengajuanKreditController as UserPengajuanKreditController;
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
    // Bisa redirect ke login atau welcome
    return redirect()->route('login');
});

// ====================== NASABAH ======================
Route::middleware(['auth', 'role:Nasabah', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('nasabah.dashboard');
    Route::get('/pengajuan', [UserPengajuanKreditController::class, 'index'])->name('pengajuan.index');
    Route::post('/pengajuan', [UserPengajuanKreditController::class, 'store'])->name('pengajuan.store');
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
