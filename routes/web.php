<?php

use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\Auth\AdminAuthController;
use App\Http\Controllers\Admin\JalurPendaftaranController;
use App\Http\Controllers\Admin\GelombangController;
use App\Http\Controllers\Admin\BiayaPendaftaranController;
use App\Http\Controllers\Admin\PeriodePendaftaranController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\PendaftarController;
use App\Http\Controllers\Admin\LaporanController;
use App\Http\Controllers\Mahasiswa\PendaftaranController as MahasiswaPendaftaranController;
use App\Http\Controllers\Mahasiswa\DashboardController as MahasiswaDashboardController;
use App\Http\Controllers\Mahasiswa\RiwayatPendaftaranController;
use App\Http\Controllers\Mahasiswa\PengumumanController;
use App\Http\Controllers\Mahasiswa\PembayaranController;

use Illuminate\Support\Facades\Route;

Route::get('/welcome', function () {
    return view('welcome');
});

// Halaman login utama (mahasiswa/google login)
Route::get('/', function () {
    return view('auth.login');
})->name('login')->middleware('redirect.if.authenticated');

// Google OAuth routes
Route::get('/auth/google', [GoogleController::class, 'redirectToGoogle'])->name('google.redirect');
Route::get('/auth/google/callback', [GoogleController::class, 'handleGoogleCallback'])->name('google.callback');
Route::post('/auth/google/logout', [GoogleController::class, 'logout'])->name('google.logout');

Route::prefix('mahasiswa')->group(function () {
    // Dashboard routes for Google users
    Route::get('/dashboard/pmb', [MahasiswaDashboardController::class, 'index'])
        ->middleware('auth:google')
        ->name('google.dashboard');

    // PMB Mahasiswa: Periode yang dibuka
    Route::get('/pmb/pendaftaran', [MahasiswaPendaftaranController::class, 'index'])
        ->middleware('auth:google')
        ->name('pmb.pendaftaran.index');

    // Detail periode pendaftaran
    Route::get('/pmb/pendaftaran/{periode}', [MahasiswaPendaftaranController::class, 'show'])
        ->middleware('auth:google')
        ->name('pmb.pendaftaran.show');

    // Riwayat pendaftaran (student) - lihat semua pendaftar milik akun Google yang login
    Route::get('/pmb/riwayat', [RiwayatPendaftaranController::class, 'index'])
        ->middleware('auth:google')
        ->name('pmb.riwayat.index');

    // Edit dan update dokumen untuk pendaftar dengan status rejected
    Route::get('/pmb/riwayat/{id}/edit', [RiwayatPendaftaranController::class, 'edit'])
        ->middleware('auth:google')
        ->name('pmb.riwayat.edit');

    Route::put('/pmb/riwayat/{id}', [RiwayatPendaftaranController::class, 'update'])
        ->middleware('auth:google')
        ->name('pmb.riwayat.update');

    // Pengumuman - lihat pengumuman status pendaftaran
    Route::get('/pmb/pengumuman', [PengumumanController::class, 'index'])
        ->middleware('auth:google')
        ->name('pmb.pengumuman.index');

    // Registration form (start) and submit
    Route::get('/pmb/pendaftaran/{periode}/daftar', [MahasiswaPendaftaranController::class, 'create'])
        ->middleware('auth:google')
        ->name('pmb.daftar.mulai');

    Route::post('/pmb/pendaftaran/{periode}/daftar', [MahasiswaPendaftaranController::class, 'store'])
        ->middleware('auth:google')
        ->name('pmb.daftar.store');

    // Pembayaran routes
    Route::get('/pmb/pembayaran', [PembayaranController::class, 'index'])
        ->middleware('auth:google')
        ->name('pmb.pembayaran.index');

    Route::get('/pmb/pembayaran/{id}', [PembayaranController::class, 'show'])
        ->middleware('auth:google')
        ->name('pmb.pembayaran.tambah');

    Route::post('/pmb/pembayaran/{id}', [PembayaranController::class, 'store'])
        ->middleware('auth:google')
        ->name('pmb.pembayaran.store');

    // Route untuk melihat bukti pembayaran
    Route::get('/pmb/pembayaran/{id}/bukti', [PembayaranController::class, 'showBukti'])
        ->middleware('auth:google')
        ->name('pmb.pembayaran.bukti');
});

// Halaman login admin
Route::get('/login-admin', [AdminAuthController::class, 'showLoginForm'])
    ->name('login.admin')
    ->middleware('redirect.if.authenticated');
Route::post('/admin/login', [AdminAuthController::class, 'login'])->name('admin.login');
Route::post('/admin/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');

// Master Data PMB Routes
Route::middleware('admin')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');

    Route::resource('jalur-pendaftaran', JalurPendaftaranController::class);
    Route::resource('gelombang', GelombangController::class);
    Route::resource('biaya-pendaftaran', BiayaPendaftaranController::class);
    Route::resource('periode-pendaftaran', PeriodePendaftaranController::class);

    Route::get('/pendaftar/menunggu', [PendaftarController::class, 'indexMenunggu'])->name('pendaftar.index');
    Route::get('/pendaftar/diterima', [PendaftarController::class, 'indexDiterima'])->name('pendaftar.diterima');
    Route::get('/pendaftar/{id}', [PendaftarController::class, 'show'])->name('pendaftar.show');

    // PATCH route untuk update status pendaftar (admin)
    Route::patch('/admin/pendaftar/{id}/update-status', [PendaftarController::class, 'updateStatus'])->name('pendaftar.update-status');

    // AJAX Routes for dynamic dropdowns
    Route::get('/ajax/biaya-by-jalur', [PeriodePendaftaranController::class, 'getBiayaByJalur'])->name('ajax.biaya-by-jalur');

    // Laporan Routes
    Route::prefix('admin/laporan')->name('admin.laporan.')->group(function () {
        Route::get('/pendaftar', [LaporanController::class, 'indexPendaftar'])->name('pendaftar');
        Route::get('/pendaftar/export', [LaporanController::class, 'exportPendaftar'])->name('pendaftar.export');
        Route::get('/pembayaran', [LaporanController::class, 'indexPembayaran'])->name('pembayaran');
        Route::get('/pembayaran/export', [LaporanController::class, 'exportPembayaran'])->name('pembayaran.export');
    });
});
