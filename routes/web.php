<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Dashboard\DashboardController;
use App\Http\Controllers\Dashboard\AdminController;
use App\Http\Controllers\Dashboard\ManagerController;
use App\Http\Controllers\Dashboard\DirekturController;

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

        Route::get('/review', [PengajuanKreditController::class, 'createReview'])->name('review');
        Route::post('/review', [PengajuanKreditController::class, 'postReview'])->name('review.post');
        Route::get('/back-step-3', [PengajuanKreditController::class, 'backToStep3'])->name('back.step3');
    });

Route::middleware(['auth', 'role:Nasabah'])
    ->prefix('riwayat')
    ->name('riwayat.')
    ->group(function () {
        Route::get('/kredit', [RiwayatKreditController::class, 'index'])->name('index');
        Route::get('/kredit/{id}', [RiwayatKreditController::class, 'show'])->name('show');
    });

Route::middleware(['auth', 'role:Nasabah'])
    ->prefix('simulasi')
    ->name('simulasi.')
    ->group(function () {
        Route::get('/kredit', [SimulasiKreditController::class, 'index'])->name('index');
        Route::post('/kredit/hitung', [SimulasiKreditController::class, 'calculate'])->name('calculate');
    });

Route::middleware(['auth', 'role:Nasabah'])
    ->prefix('nasabah')
    ->name('nasabah.')
    ->group(function () {
        Route::get('/profile', [NasabahProfileController::class, 'edit'])->name('profile-edit');
        Route::patch('/profile', [NasabahProfileController::class, 'update'])->name('profile-update');
        Route::put('/password', [NasabahProfileController::class, 'updatePassword'])->name('password-update');
    });


// ====================== ADMIN ======================
Route::middleware(['auth', 'role:Admin'])->group(function () {
    Route::get('/admin/dashboard2', [AdminController::class, 'index'])->name('admin.dashboard2');
});

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
                Route::get('/nasabah', 'nasabah')->name('nasabah');
                Route::get('/pengajuan', 'pengajuan')->name('pengajuan');
                Route::get('/analisis', 'analisis')->name('analisis');
                Route::get('/monitoring', 'monitoring')->name('monitoring');
                Route::get('/rekapitulasi', 'rekapitulasi')->name('rekapitulasi');
            });
    });

// ====================== MANAGER ======================
Route::middleware(['auth', 'role:Manager'])->group(function () {
    Route::get('/manager/dashboard', [ManagerController::class, 'index'])->name('manager.dashboard');
});

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
        Route::controller(AdminLaporanController::class)
            ->prefix('laporan')
            ->name('laporan.')
            ->group(function () {
                Route::get('/', 'index')->name('index');
                Route::get('/nasabah', 'nasabah')->name('nasabah');
                Route::get('/pengajuan', 'pengajuan')->name('pengajuan');
                Route::get('/analisis', 'analisis')->name('analisis');
                Route::get('/monitoring', 'monitoring')->name('monitoring');
                Route::get('/rekapitulasi', 'rekapitulasi')->name('rekapitulasi');
            });
    });

// ====================== DIREKTUR ======================
Route::middleware(['auth', 'role:Direktur'])->group(function () {
    Route::get('/direktur/dashboard', [DirekturController::class, 'index'])->name('direktur.dashboard');
});

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
        Route::controller(AdminLaporanController::class)
            ->prefix('laporan')
            ->name('laporan.')
            ->group(function () {
                Route::get('/', 'index')->name('index');
                Route::get('/nasabah', 'nasabah')->name('nasabah');
                Route::get('/pengajuan', 'pengajuan')->name('pengajuan');
                Route::get('/analisis', 'analisis')->name('analisis');
                Route::get('/monitoring', 'monitoring')->name('monitoring');
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
