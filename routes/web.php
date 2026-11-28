<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\Dashboard\AdminKermaDashboardController;
use App\Http\Controllers\Dashboard\AdminPijarSuarDashboardController;
use App\Http\Controllers\Dashboard\DashboardRedirectController;
use App\Http\Controllers\Dashboard\DosenDashboardController;
use App\Http\Controllers\Dashboard\KaprodiDashboardController;
use App\Http\Controllers\Dashboard\SuperAdminDashboardController;
use App\Http\Controllers\PortalController;
use App\Http\Controllers\PublicController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Halaman Publik
|--------------------------------------------------------------------------
*/

Route::get('/home', PublicController::class)->name('home');
Route::redirect('/', '/home');

/*
|--------------------------------------------------------------------------
| Autentikasi
|--------------------------------------------------------------------------
*/

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'create'])->name('login');
    Route::post('/login', [AuthController::class, 'store'])->name('login.store');
});

Route::post('/logout', [AuthController::class, 'destroy'])
    ->middleware('auth')
    ->name('logout');

/*
|--------------------------------------------------------------------------
| Dashboard (wajib login)
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->prefix('dashboard')->name('portal.')->group(function () {

    // Pengarah: /dashboard -> dashboard sesuai role
    Route::get('/', DashboardRedirectController::class)->name('dashboard');

    // Dashboard per role
    Route::get('/super-admin', [SuperAdminDashboardController::class, 'index'])
        ->middleware('role:super_admin')
        ->name('dashboard.super_admin');

    Route::get('/admin-pijar-suar', [AdminPijarSuarDashboardController::class, 'index'])
        ->middleware('role:admin_pijar_suar')
        ->name('dashboard.admin_pijar_suar');

    Route::get('/admin-kerma', [AdminKermaDashboardController::class, 'index'])
        ->middleware('role:admin_kerma')
        ->name('dashboard.admin_kerma');

    Route::get('/kaprodi', [KaprodiDashboardController::class, 'index'])
        ->middleware('role:kaprodi')
        ->name('dashboard.kaprodi');

    Route::get('/dosen', [DosenDashboardController::class, 'index'])
        ->middleware('role:dosen')
        ->name('dashboard.dosen');

    // Pengajuan
    Route::get('/pengajuan', [PortalController::class, 'submissions'])
        ->name('submissions');

    Route::get('/pengajuan/buat', [PortalController::class, 'createSubmission'])
        ->middleware('role:kaprodi')
        ->name('submissions.create');

    Route::post('/pengajuan', [PortalController::class, 'storeSubmission'])
        ->middleware('role:kaprodi')
        ->name('submissions.store');

    Route::get('/pengajuan/{id}', [PortalController::class, 'showSubmission'])
        ->name('submissions.show');

    Route::patch('/pengajuan/{id}/keputusan', [PortalController::class, 'decide'])
        ->middleware('role:super_admin,admin_pijar_suar')
        ->name('submissions.decide');

    Route::patch('/pengajuan/{id}/penugasan', [PortalController::class, 'assign'])
        ->middleware('role:super_admin,admin_pijar_suar,admin_kerma')
        ->name('submissions.assign');

    // Data master
    Route::get('/data/{type}', [PortalController::class, 'directory'])
        ->middleware('role:super_admin,admin_pijar_suar,admin_kerma')
        ->name('directory');

    Route::post('/data/{type}/{id?}', [PortalController::class, 'saveDirectory'])
        ->middleware('role:super_admin,admin_pijar_suar,admin_kerma')
        ->name('directory.save');

    // Jadwal
    Route::get('/jadwal', [PortalController::class, 'schedules'])
        ->name('schedules');

    // Bahan mengajar
    Route::get('/bahan-mengajar', [PortalController::class, 'materials'])
        ->name('materials');

    Route::get('/bahan-mengajar/{submission}', [PortalController::class, 'editMaterial'])
        ->middleware('role:dosen,kaprodi')
        ->name('materials.edit');

    Route::put('/bahan-mengajar/{submission}', [PortalController::class, 'saveMaterial'])
        ->middleware('role:dosen,kaprodi')
        ->name('materials.save');

    // Administrasi
    Route::get('/administrasi/{type}', [PortalController::class, 'administration'])
        ->middleware('role:super_admin')
        ->name('administration');

    Route::post('/administrasi/{type}', [PortalController::class, 'saveAdministration'])
        ->middleware('role:super_admin')
        ->name('administration.save');

    // Dokumen
    Route::get('/dokumen', [PortalController::class, 'documents'])
        ->name('documents');

    Route::post('/dokumen', [PortalController::class, 'storeDocument'])
        ->middleware('role:super_admin,admin_pijar_suar')
        ->name('documents.store');
});