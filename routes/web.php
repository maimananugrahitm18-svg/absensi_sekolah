<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin;
use App\Http\Controllers\Guru;
use App\Http\Controllers\Siswa;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', fn() => redirect()->route('login'));

// Auth routes
Route::get('/login',  [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::match(['get', 'post'], '/logout', [AuthController::class, 'logout'])->name('logout');
Route::match(['get', 'post'], '/demo-login', [AuthController::class, 'demoLogin'])->name('demo.login');

// ─── Admin Routes ────────────────────────────────────────────────────────────
Route::prefix('admin')->name('admin.')->middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/dashboard', [Admin\DashboardController::class, 'index'])->name('dashboard');
    Route::post('/demo/reset', [Admin\DashboardController::class, 'resetDemoData'])->name('demo.reset');

    // Siswa
    Route::resource('siswa', Admin\SiswaController::class);

    // Guru
    Route::resource('guru', Admin\GuruController::class);

    // Kelas (uses singular route model binding: 'kela')
    Route::get('/kelas',             [Admin\KelasController::class, 'index'])->name('kelas.index');
    Route::get('/kelas/create',      [Admin\KelasController::class, 'create'])->name('kelas.create');
    Route::post('/kelas',            [Admin\KelasController::class, 'store'])->name('kelas.store');
    Route::get('/kelas/{kela}/edit', [Admin\KelasController::class, 'edit'])->name('kelas.edit');
    Route::put('/kelas/{kela}',      [Admin\KelasController::class, 'update'])->name('kelas.update');
    Route::delete('/kelas/{kela}',   [Admin\KelasController::class, 'destroy'])->name('kelas.destroy');

    // Mata Pelajaran
    Route::resource('mapel', Admin\MataPelajaranController::class);

    // Jadwal
    Route::resource('jadwal', Admin\JadwalController::class);

    // Tahun Ajaran
    Route::resource('tahun-ajaran', Admin\TahunAjaranController::class);

    // Rekap Absensi
    Route::get('/rekap',         [Admin\RekapAbsensiController::class, 'index'])->name('rekap.index');
    Route::get('/rekap/export',  [Admin\RekapAbsensiController::class, 'exportCsv'])->name('rekap.export');

    // Pengajuan Izin (Admin hanya memantau)
    Route::get('/pengajuan-izin',                 [Admin\PengajuanIzinController::class, 'index'])->name('pengajuan-izin.index');
    Route::get('/pengajuan-izin/{pengajuanIzin}', [Admin\PengajuanIzinController::class, 'show'])->name('pengajuan-izin.show');

    // Presensi Guru (Admin View)
    Route::get('/presensi-guru',        [Admin\PresensiGuruController::class, 'index'])->name('presensi-guru.index');
    Route::get('/presensi-guru/export', [Admin\PresensiGuruController::class, 'export'])->name('presensi-guru.export');
});

// ─── Guru Routes ─────────────────────────────────────────────────────────────
Route::prefix('guru')->name('guru.')->middleware(['auth', 'role:guru'])->group(function () {
    Route::get('/dashboard', [Guru\DashboardController::class, 'index'])->name('dashboard');

    // Presensi Datang & Pulang Guru
    Route::get('/presensi',        [Guru\PresensiGuruController::class, 'index'])->name('presensi.index');
    Route::post('/presensi/masuk',  [Guru\PresensiGuruController::class, 'absenMasuk'])->name('presensi.masuk');
    Route::post('/presensi/pulang', [Guru\PresensiGuruController::class, 'absenPulang'])->name('presensi.pulang');

    // Absensi Siswa
    Route::get('/absensi',                             [Guru\AbsensiController::class, 'index'])->name('absensi.index');
    Route::get('/absensi/rekap',                       [Guru\AbsensiController::class, 'rekap'])->name('absensi.rekap');
    Route::get('/absensi/{jadwal}/isi',                [Guru\AbsensiController::class, 'create'])->name('absensi.create');
    Route::post('/absensi/{jadwal}',                   [Guru\AbsensiController::class, 'store'])->name('absensi.store');

    // Pengajuan Izin Siswa (Verifikasi & ACC oleh Guru)
    Route::get('/pengajuan-izin',                          [Guru\PengajuanIzinController::class, 'index'])->name('pengajuan-izin.index');
    Route::get('/pengajuan-izin/{pengajuanIzin}',          [Guru\PengajuanIzinController::class, 'show'])->name('pengajuan-izin.show');
    Route::post('/pengajuan-izin/{pengajuanIzin}/approve', [Guru\PengajuanIzinController::class, 'approve'])->name('pengajuan-izin.approve');
    Route::post('/pengajuan-izin/{pengajuanIzin}/reject',  [Guru\PengajuanIzinController::class, 'reject'])->name('pengajuan-izin.reject');
});

// ─── Siswa Routes ─────────────────────────────────────────────────────────────
Route::prefix('siswa')->name('siswa.')->middleware(['auth', 'role:siswa'])->group(function () {
    Route::get('/dashboard', [Siswa\DashboardController::class, 'index'])->name('dashboard');

    // Riwayat Absensi
    Route::get('/riwayat', [Siswa\RiwayatAbsensiController::class, 'index'])->name('riwayat');

    // Pengajuan Izin
    Route::get('/pengajuan-izin',        [Siswa\PengajuanIzinController::class, 'index'])->name('pengajuan-izin.index');
    Route::get('/pengajuan-izin/create', [Siswa\PengajuanIzinController::class, 'create'])->name('pengajuan-izin.create');
    Route::post('/pengajuan-izin',       [Siswa\PengajuanIzinController::class, 'store'])->name('pengajuan-izin.store');
});
