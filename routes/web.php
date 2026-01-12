<?php

use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\Auth\AdminAuthController;
use App\Http\Controllers\Admin\JalurPendaftaranController;
use App\Http\Controllers\Admin\GelombangController;
use App\Http\Controllers\Admin\BiayaPendaftaranController;
use App\Http\Controllers\Admin\ProdiController;
use App\Http\Controllers\Admin\PeriodePendaftaranController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\PendaftarController;
use App\Http\Controllers\Admin\LaporanController;
use App\Http\Controllers\Admin\DokumenPendaftarController;
use App\Http\Controllers\Mahasiswa\PendaftaranController as MahasiswaPendaftaranController;
use App\Http\Controllers\Mahasiswa\DashboardController as MahasiswaDashboardController;
use App\Http\Controllers\Mahasiswa\RiwayatPendaftaranController;
use App\Http\Controllers\Mahasiswa\PengumumanController;
use App\Http\Controllers\Mahasiswa\PembayaranController;
use App\Http\Controllers\Mahasiswa\KelengkapanDokumenController;

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

    // Kelengkapan Dokumen routes (hanya untuk yang sudah membayar)
    Route::resource('pmb/dokumen', KelengkapanDokumenController::class)
        ->middleware('auth:google')
        ->names([
            'index' => 'pmb.dokumen.index',
            'create' => 'pmb.dokumen.create',
            'store' => 'pmb.dokumen.store',
            'show' => 'pmb.dokumen.show',
            'edit' => 'pmb.dokumen.edit',
            'update' => 'pmb.dokumen.update',
            'destroy' => 'pmb.dokumen.destroy'
        ]);
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
    Route::resource('prodi', ProdiController::class);
    Route::resource('periode-pendaftaran', PeriodePendaftaranController::class);
    Route::resource('dokumen-pendaftar', DokumenPendaftarController::class);

    Route::get('/pendaftar/pembayaran/menunggu', [PendaftarController::class, 'indexPembayaranMenunggu'])->name('pendaftar.pembayaran.menunggu');
    Route::get('/pendaftar/pembayaran/diterima', [PendaftarController::class, 'indexPembayaranDiterima'])->name('pendaftar.pembayaran.diterima');
    Route::get('/pendaftar/dokumen/menunggu', [PendaftarController::class, 'indexDokumenMenunggu'])->name('pendaftar.dokumen.menunggu');
    Route::get('/pendaftar/dokumen/diterima', [PendaftarController::class, 'indexDokumenDiterima'])->name('pendaftar.dokumen.diterima');
    Route::get('/pendaftar/{id}', [PendaftarController::class, 'show'])->name('pendaftar.show');
    
    // PATCH route untuk update status pembayaran (admin) - khusus menu pembayaran
    Route::patch('/admin/pendaftar/{id}/update-status-pembayaran', [PendaftarController::class, 'updateStatusPembayaran'])->name('pendaftar.update-status-pembayaran');
    
    // PATCH route untuk update status dokumen (admin) - khusus menu dokumen
    Route::patch('/admin/pendaftar/{id}/update-status-pendaftaran', [PendaftarController::class, 'updateStatusPendaftaran'])->name('pendaftar.update-status-pendaftaran');
    
    // PATCH route untuk update status dokumen pendaftar (admin) - khusus detail dokumen
    Route::patch('/admin/pendaftar/update-status-dokumen-pendaftar/{id}', [PendaftarController::class, 'updateStatusDokumenPendaftar'])->name('pendaftar.update-status-dokumen-pendaftar');

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
