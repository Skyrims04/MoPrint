<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PublicDashboardController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\PrinterController;
use App\Http\Controllers\Admin\TonerStockController;
use App\Http\Controllers\Admin\DeviceActivityController;
use App\Http\Controllers\Admin\ImportHistoryController;

/*
|--------------------------------------------------------------------------
| PUBLIC ROUTES — Tidak perlu login (Viewer)
|--------------------------------------------------------------------------
*/
Route::get('/', [PublicDashboardController::class, 'index'])->name('home');
Route::get('/printers', [PublicDashboardController::class, 'printers'])->name('public.printers');
Route::get('/toner', [PublicDashboardController::class, 'toner'])->name('public.toner');

/*
|--------------------------------------------------------------------------
| ADMIN AUTH ROUTES — Login / Logout
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->name('admin.')->group(function () {

    // Login page & proses (hanya jika belum login)
    Route::middleware('guest')->group(function () {
        Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
        Route::post('/login', [AuthController::class, 'login'])->name('login.post');
    });

    // Logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    /*
    |--------------------------------------------------------------------------
    | ADMIN PROTECTED ROUTES — Wajib login
    |--------------------------------------------------------------------------
    */
    Route::middleware('auth')->group(function () {

        // Analytics / Laporan
        Route::get('/analytics', [\App\Http\Controllers\Admin\AnalyticsController::class, 'index'])->name('analytics');
        Route::get('/analytics/detail/{importId}', [\App\Http\Controllers\Admin\AnalyticsController::class, 'detail'])->name('analytics.detail');

        // Dashboard Admin
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

        // Data Printer — hanya lihat & hapus, input via import Excel
        Route::get('/printers', [PrinterController::class, 'index'])->name('printers.index');
        Route::delete('/printers/{printer}', [PrinterController::class, 'destroy'])->name('printers.destroy');

        // Manajemen Stok Toner per Gedung
        Route::get('/toner-stock', [TonerStockController::class, 'index'])->name('toner-stock.index');
        Route::put('/toner-stock/building/{building}', [TonerStockController::class, 'updateBuilding'])->name('toner-stock.update-building');

        // Aktivitas Perangkat
        Route::resource('device-activity', DeviceActivityController::class);

        // Import
        Route::get('/import-history', [ImportHistoryController::class, 'index'])->name('import-history.index');
        Route::get('/import-template', [ImportHistoryController::class, 'downloadTemplate'])->name('import-template');
        Route::post('/import', [ImportHistoryController::class, 'import'])->name('import');
    });
});