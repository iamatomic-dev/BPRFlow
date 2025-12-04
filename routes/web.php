<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Dashboard\DashboardController;
use App\Http\Controllers\Nasabah\NasabahProfileController;
use App\Http\Controllers\Nasabah\PengajuanKreditController;
use App\Http\Controllers\Nasabah\RiwayatKreditController;
use App\Http\Controllers\Nasabah\SimulasiKreditController;

use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminPengajuanController;
use App\Http\Controllers\Admin\AdminSlikController;
use App\Http\Controllers\Admin\AdminAngsuranController;
use App\Http\Controllers\Admin\AdminLaporanController;

use App\Http\Controllers\Manager\ManagerDashboardController;
use App\Http\Controllers\Manager\ManagerRekomendasiController;
use App\Http\Controllers\Manager\ManagerAngsuranController;

use App\Http\Controllers\Direktur\DirekturDashboardController;
use App\Http\Controllers\Direktur\DirekturPersetujuanController;

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    if (Auth::check()) {
        $user = Auth::user();
        if ($user->hasRole('Nasabah')) {
            return redirect()->route('nasabah.dashboard');
        } elseif ($user->hasRole('Admin')) {
            return redirect()->route('admin.dashboard');
        } elseif ($user->hasRole('Manager')) {
            return redirect()->route('manager.dashboard');
        } elseif ($user->hasRole('Direktur')) {
            return redirect()->route('direktur.dashboard');
        }
    }
    return view('welcome');
});

// ====================== NASABAH ======================
Route::middleware(['auth', 'role:Nasabah', 'verified'])->group(function () {
    Route::get('/nasabah/dashboard', [DashboardController::class, 'index'])->name('nasabah.dashboard');
});

Route::middleware(['auth', 'role:Nasabah'])->group(function () {

    Route::controller(PengajuanKreditController::class)
        ->prefix('pengajuan')
        ->name('pengajuan.')
        ->group(function () {
            Route::get('/step-1', 'createStep1')->name('step1');
            Route::post('/step-1', 'postStep1')->name('step1.post');

            Route::get('/step-2', 'createStep2')->name('step2');
            Route::post('/step-2', 'postStep2')->name('step2.post');
            Route::get('/back-step-1', 'backToStep1')->name('back.step1');

            Route::get('/step-3', 'createStep3')->name('step3');
            Route::post('/step-3', 'postStep3')->name('step3.post');
            Route::post('/upload-temp', 'uploadTemp')->name('upload.temp');
            Route::get('/back-step-2', 'backToStep2')->name('back.step2');

            Route::get('/review', 'createReview')->name('review');
            Route::post('/review', 'postReview')->name('review.post');
            Route::get('/back-step-3', 'backToStep3')->name('back.step3');
        });

    Route::controller(RiwayatKreditController::class)
        ->prefix('riwayat')
        ->name('riwayat.')
        ->group(function () {
            Route::get('/kredit', 'index')->name('index');
            Route::get('/kredit/aktif', 'aktif')->name('aktif');
            Route::get('/kredit/{id}', 'show')->name('show');
        });

    Route::controller(SimulasiKreditController::class)
        ->prefix('simulasi')
        ->name('simulasi.')
        ->group(function () {
            Route::get('/kredit', 'index')->name('index');
            Route::post('/kredit/hitung', 'calculate')->name('calculate');
        });

    Route::controller(NasabahProfileController::class)
        ->prefix('nasabah')
        ->name('nasabah.')
        ->group(function () {
            Route::get('/profile', 'edit')->name('profile-edit');
            Route::patch('/profile', 'update')->name('profile-update');
            Route::put('/password', 'updatePassword')->name('password-update');
        });
});

// ====================== ADMIN ======================
Route::middleware(['auth', 'role:Admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
        Route::controller(AdminPengajuanController::class)
            ->prefix('pengajuan')
            ->name('pengajuan.')
            ->group(function () {
                Route::get('/', 'index')->name('index');
                Route::get('/{id}', 'show')->name('show');
            });
        Route::controller(AdminSlikController::class)
            ->prefix('slik')
            ->name('slik.')
            ->group(function () {
                Route::get('/', 'index')->name('index');
                Route::get('/{id}/upload', 'edit')->name('edit');
                Route::put('/{id}', 'update')->name('update');
            });
        Route::controller(AdminAngsuranController::class)
            ->prefix('angsuran')
            ->name('angsuran.')
            ->group(function () {
                Route::get('/', 'index')->name('index');
                Route::get('/{id}', 'show')->name('show');
                Route::put('/bayar/{paymentId}', 'update')->name('update');
            });
        Route::controller(AdminLaporanController::class)
            ->prefix('laporan')
            ->name('laporan.')
            ->group(function () {
                Route::get('/pengajuan', 'pengajuan')->name('pengajuan');
                Route::get('/analisis', 'analisis')->name('analisis');
                Route::get('/monitoring', 'monitoring')->name('monitoring');
            });
    });

// ====================== MANAGER ======================
Route::middleware(['auth', 'role:Manager'])
    ->prefix('manager')
    ->name('manager.')
    ->group(function () {
        Route::get('/dashboard', [ManagerDashboardController::class, 'index'])->name('dashboard');

        Route::controller(ManagerRekomendasiController::class)
            ->prefix('rekomendasi')
            ->name('rekomendasi.')
            ->group(function () {
                Route::get('/', 'index')->name('index');
                Route::get('/riwayat', 'riwayat')->name('riwayat');
                Route::get('/{id}', 'show')->name('show');
                Route::put('/{id}', 'update')->name('update');
                Route::get('/{id}/detail', 'detail')->name('detail');
            });
        Route::controller(AdminAngsuranController::class)
            ->prefix('angsuran')
            ->name('angsuran.')
            ->group(function () {
                Route::get('/', 'index')->name('index');
                Route::get('/{id}', 'show')->name('show');
            });
        Route::controller(ManagerAngsuranController::class)
            ->prefix('angsuran')
            ->name('angsuran.')
            ->group(function () {
                Route::put('/reverse/{paymentId}', 'reverse')->name('reverse');
            });
        Route::controller(AdminLaporanController::class)
            ->prefix('laporan')
            ->name('laporan.')
            ->group(function () {
                Route::get('/', 'index')->name('index');
                Route::get('/pengajuan', 'pengajuan')->name('pengajuan');
                Route::get('/analisis', 'analisis')->name('analisis');
                Route::get('/monitoring', 'monitoring')->name('monitoring');
            });
    });

// ====================== DIREKTUR ======================
Route::middleware(['auth', 'role:Direktur'])
    ->prefix('direktur')
    ->name('direktur.')
    ->group(function () {
        Route::get('/dashboard', [DirekturDashboardController::class, 'index'])->name('dashboard');

        Route::controller(DirekturPersetujuanController::class)
            ->prefix('persetujuan')
            ->name('persetujuan.')
            ->group(function () {
                Route::get('/', 'index')->name('index');
                Route::get('/{id}', 'show')->name('show');
                Route::put('/{id}', 'update')->name('update');
            });
        Route::controller(AdminAngsuranController::class)
            ->prefix('angsuran')
            ->name('angsuran.')
            ->group(function () {
                Route::get('/', 'index')->name('index');
                Route::get('/{id}', 'show')->name('show');
            });
        Route::controller(AdminLaporanController::class)
            ->prefix('laporan')
            ->name('laporan.')
            ->group(function () {
                Route::get('/', 'index')->name('index');
                Route::get('/pengajuan', 'pengajuan')->name('pengajuan');
                Route::get('/analisis', 'analisis')->name('analisis');
                Route::get('/monitoring', 'monitoring')->name('monitoring');
                Route::get('/realisasi', 'realisasi')->name('realisasi');
                Route::get('/rekapitulasi', 'rekapitulasi')->name('rekapitulasi');
            });
    });

// ====================== PROFILE ======================
Route::middleware(['auth', 'role:Admin|Manager|Direktur'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    //Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// ====================== LOGOUT ======================
Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
    ->middleware('auth')
    ->name('logout');

require __DIR__ . '/auth.php';
