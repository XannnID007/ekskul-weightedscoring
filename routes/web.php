<?php
// routes/web.php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// Admin Controllers
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Siswa\JadwalController as SiswaJadwalController;

// Pembina Controllers
use App\Http\Controllers\Siswa\ProfilController as SiswaProfilController;
use App\Http\Controllers\Admin\LaporanController as AdminLaporanController;
use App\Http\Controllers\Pembina\GaleriController as PembinaGaleriController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;

// Siswa Controllers
use App\Http\Controllers\Siswa\DashboardController as SiswaDashboardController;
use App\Http\Controllers\Pembina\DashboardController as PembinaDashboardController;
use App\Http\Controllers\Siswa\PendaftaranController as SiswaPendaftaranController;
use App\Http\Controllers\Siswa\RekomendasiController as SiswaRekomendasiController;
use App\Http\Controllers\Pembina\PengumumanController as PembinaPengumumanController;
use App\Http\Controllers\Pembina\PendaftaranController as PembinaPendaftaranController;
use App\Http\Controllers\Admin\EkstrakurikulerController as AdminEkstrakurikulerController;
use App\Http\Controllers\Siswa\EkstrakurikulerController as SiswaEkstrakurikulerController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    if (auth()->check()) {
        $user = auth()->user();
        switch ($user->role) {
            case 'admin':
                return redirect()->route('admin.dashboard');
            case 'pembina':
                return redirect()->route('pembina.dashboard');
            case 'siswa':
                return redirect()->route('siswa.dashboard');
            default:
                return redirect()->route('login');
        }
    }
    return redirect()->route('login');
});

// Authentication routes sudah di-handle oleh laravel/ui
Auth::routes(['register' => true, 'verify' => true]);

Route::get('/redirect-by-role', function () {
    if (!auth()->check()) {
        return redirect()->route('login');
    }

    $user = auth()->user();

    switch ($user->role) {
        case 'admin':
            return redirect()->route('admin.dashboard');
        case 'pembina':
            return redirect()->route('pembina.dashboard');
        case 'siswa':
            return redirect()->route('siswa.dashboard');
        default:
            auth()->logout();
            return redirect()->route('login')
                ->with('error', 'Role tidak valid. Silakan hubungi administrator.');
    }
})->name('redirect.by.role');

Route::middleware(['throttle:5,1'])->group(function () {
    Route::post('/register', [RegisterController::class, 'register'])->name('register');
});

// Routes yang memerlukan authentication
Route::middleware(['auth', 'verified'])->group(function () {

    // Profile routes (untuk semua role)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Admin Routes
    Route::middleware(['role:admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

        // Ekstrakurikuler Management
        Route::resource('ekstrakurikuler', AdminEkstrakurikulerController::class);
        Route::post('/ekstrakurikuler/{ekstrakurikuler}/toggle-status', [AdminEkstrakurikulerController::class, 'toggleStatus'])->name('ekstrakurikuler.toggle-status');

        // User Management
        Route::get('/user', [AdminUserController::class, 'index'])->name('user.index');
        Route::get('/user/create', [AdminUserController::class, 'create'])->name('user.create');
        Route::post('/user', [AdminUserController::class, 'store'])->name('user.store');
        Route::get('/user/{user}', [AdminUserController::class, 'show'])->name('user.show');
        Route::get('/user/{user}/edit', [AdminUserController::class, 'edit'])->name('user.edit');
        Route::put('/user/{user}', [AdminUserController::class, 'update'])->name('user.update');
        Route::delete('/user/{user}', [AdminUserController::class, 'destroy'])->name('user.destroy');
        Route::post('/user/import-siswa', [AdminUserController::class, 'importSiswa'])->name('user.import-siswa');

        // Laporan
        Route::get('/laporan', [AdminLaporanController::class, 'index'])->name('laporan');
        Route::post('/laporan/export', [AdminLaporanController::class, 'export'])->name('laporan.export');
    });

    // Pembina Routes
    Route::middleware(['role:pembina'])->prefix('pembina')->name('pembina.')->group(function () {
        Route::get('/dashboard', [PembinaDashboardController::class, 'index'])->name('dashboard');

        // Pendaftaran Management
        Route::get('/pendaftaran', [PembinaPendaftaranController::class, 'index'])->name('pendaftaran.index');
        Route::get('/pendaftaran/{pendaftaran}', [PembinaPendaftaranController::class, 'show'])->name('pendaftaran.show');
        Route::post('/pendaftaran/{pendaftaran}/approve', [PembinaPendaftaranController::class, 'approve'])->name('pendaftaran.approve');
        Route::post('/pendaftaran/{pendaftaran}/reject', [PembinaPendaftaranController::class, 'reject'])->name('pendaftaran.reject');

        // Pengumuman Management
        Route::resource('pengumuman', PembinaPengumumanController::class);

        // Galeri Management
        Route::resource('galeri', PembinaGaleriController::class);
        Route::delete('/galeri/{galeri}/force', [PembinaGaleriController::class, 'forceDelete'])->name('galeri.force-delete');
    });

    // Siswa Routes
    // Siswa Routes
    Route::middleware(['role:siswa'])->prefix('siswa')->name('siswa.')->group(function () {
        Route::get('/dashboard', [SiswaDashboardController::class, 'index'])->name('dashboard');

        // Profil Management - selalu bisa diakses
        Route::get('/profil', [SiswaProfilController::class, 'index'])->name('profil');
        Route::put('/profil', [SiswaProfilController::class, 'update'])->name('profil.update');

        // Routes untuk siswa yang BELUM terdaftar ekstrakurikuler
        Route::middleware(['ensure.student.not.registered'])->group(function () {
            // Rekomendasi
            Route::get('/rekomendasi', [SiswaRekomendasiController::class, 'index'])->name('rekomendasi');
            Route::post('/rekomendasi/regenerate', [SiswaRekomendasiController::class, 'regenerate'])->name('rekomendasi.regenerate');
            Route::get('/rekomendasi/{rekomendasi}', [SiswaRekomendasiController::class, 'detail'])->name('rekomendasi.detail');

            // Daftar ekstrakurikuler
            Route::post('/ekstrakurikuler/{ekstrakurikuler}/daftar', [SiswaEkstrakurikulerController::class, 'daftar'])->name('ekstrakurikuler.daftar');
        });

        // Ekstrakurikuler - bisa diakses semua siswa
        Route::get('/ekstrakurikuler', [SiswaEkstrakurikulerController::class, 'index'])->name('ekstrakurikuler.index');
        Route::get('/ekstrakurikuler/{ekstrakurikuler}', [SiswaEkstrakurikulerController::class, 'show'])->name('ekstrakurikuler.show');

        // Pendaftaran Status - selalu bisa diakses
        Route::get('/pendaftaran', [SiswaPendaftaranController::class, 'index'])->name('pendaftaran');
        Route::delete('/pendaftaran/{pendaftaran}', [SiswaPendaftaranController::class, 'cancel'])->name('pendaftaran.cancel');

        // Routes untuk siswa yang SUDAH terdaftar ekstrakurikuler
        Route::middleware(['ensure.student.registered'])->group(function () {
            Route::get('/jadwal', [SiswaJadwalController::class, 'index'])->name('jadwal');
            Route::get('/jadwal/calendar', [SiswaJadwalController::class, 'calendar'])->name('jadwal.calendar');
            Route::get('/jadwal/export', [SiswaJadwalController::class, 'exportCalendar'])->name('jadwal.export');

            Route::get('/galeri', [\App\Http\Controllers\Siswa\GaleriController::class, 'index'])->name('galeri.index');
            Route::get('/galeri/{galeri}', [\App\Http\Controllers\Siswa\GaleriController::class, 'show'])->name('galeri.show');

            // Pengumuman - BARU  
            Route::get('/pengumuman', [\App\Http\Controllers\Siswa\PengumumanController::class, 'index'])->name('pengumuman.index');
            Route::get('/pengumuman/{pengumuman}', [\App\Http\Controllers\Siswa\PengumumanController::class, 'show'])->name('pengumuman.show');
        });
    });
});
// API Routes untuk AJAX calls
Route::middleware(['auth'])->prefix('api')->name('api.')->group(function () {

    // Admin API
    Route::middleware(['role:admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/stats/ekstrakurikuler-populer', [AdminDashboardController::class, 'ekstrakurikulerPopuler']);
        Route::get('/stats/pendaftaran-bulanan', [AdminDashboardController::class, 'pendaftaranBulanan']);
        Route::post('/ekstrakurikuler/{ekstrakurikuler}/toggle-status', [AdminEkstrakurikulerController::class, 'toggleStatus']);
    });

    // Siswa API
    Route::middleware(['role:siswa'])->prefix('siswa')->name('siswa.')->group(function () {
        Route::get('/jadwal/events', [SiswaJadwalController::class, 'getEvents']);
        Route::get('/notifikasi', [SiswaDashboardController::class, 'getNotifikasi']);
        Route::post('/notifikasi/{id}/read', [SiswaDashboardController::class, 'markAsRead']);
    });
});
