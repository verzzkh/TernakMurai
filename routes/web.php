<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Peternak\AnakanController;
use App\Http\Controllers\Peternak\DashboardController;
use App\Http\Controllers\Peternak\DeteksiPenyakitController;
use App\Http\Controllers\Peternak\AnalisaSemuaIndukanController;
use App\Http\Controllers\Peternak\IndukanController;
use App\Http\Controllers\Peternak\KandangController;
use App\Http\Controllers\Peternak\KeuanganController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// ============================================================================
// GUEST ROUTES (Tidak perlu login)
// ============================================================================
Route::middleware('guest')->group(function () {
    Route::get('/', function () {
        return redirect()->route('login');
    });
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
    // Registration for peternak
    Route::get('/register', [\App\Http\Controllers\Auth\RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [\App\Http\Controllers\Auth\RegisterController::class, 'register']);
});

// ============================================================================
// UNIFIED DASHBOARD ROUTE (Redirects based on user role)
// ============================================================================
Route::middleware('auth')->get('/dashboard', function () {
    $role = Auth::user()->role;

    return match ($role) {
        'admin' => redirect()->route('admin.dashboard'),
        'peternak' => redirect()->route('peternak.dashboard'),
        default => redirect()->route('login'),
    };
})->name('dashboard');

// ============================================================================
// ADMIN ROUTES (Hanya admin yang bisa akses)
// ============================================================================
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    // Dashboard Admin
    // Admin dashboard - controller
    Route::get('/dashboard', [\App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');

    // Users management skeleton
    Route::get('/users', [\App\Http\Controllers\Admin\UserController::class, 'index'])->name('users.index');

    // Deteksi disease history
    Route::get('/deteksi', [\App\Http\Controllers\Admin\DeteksiController::class, 'index'])->name('deteksi.index');
    Route::get('/deteksi/{id}', [\App\Http\Controllers\Admin\DeteksiController::class, 'show'])->name('deteksi.show');

    // Paket & Kuota
    Route::get('/paket', [\App\Http\Controllers\Admin\PaketController::class, 'index'])->name('paket.index');

    // Pengaturan
    Route::get('/settings', [\App\Http\Controllers\Admin\SettingsController::class, 'index'])->name('settings.index');
    Route::post('/settings', [\App\Http\Controllers\Admin\SettingsController::class, 'update'])->name('settings.update');

    // // User Management
    // Route::get('/users', function () {
    //     return view('admin.users.index');
    // })->name('users.index');

    // // Peternak Management
    // Route::get('/peternak', function () {
    //     return view('admin.peternak.index');
    // })->name('peternak.index');

    // // Reports
    // Route::get('/reports', function () {
    //     return view('admin.reports.index');
    // })->name('reports.index');
});

// ============================================================================
// PETERNAK ROUTES (Hanya peternak yang bisa akses)
// ============================================================================
Route::middleware(['auth', 'peternak'])->prefix('peternak')->name('peternak.')->group(function () {
    // Dashboard Peternak
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Profile: show and update current user's profile
    Route::get('/profile', [\App\Http\Controllers\Peternak\ProfileController::class, 'show'])->name('profile.show');
    Route::put('/profile', [\App\Http\Controllers\Peternak\ProfileController::class, 'update'])->name('profile.update');

  // ❗ ROUTE UPDATE FOTO INDUKAN
    Route::put('/indukan/{indukan}/foto',
        [IndukanController::class, 'updateFoto']
    )->name('indukan.update-foto');

    Route::get('/indukan/analisa-semua', 
            [AnalisaSemuaIndukanController::class, 'index']
        )->name('indukan.analisaSemua');

    // Indukan Management
    Route::prefix('indukan')->name('indukan.')->group(function () {
        Route::get('/', [IndukanController::class, 'index'])->name('index');
        Route::get('/create', [IndukanController::class, 'create'])->name('create');
        Route::post('/', [IndukanController::class, 'store'])->name('store');
        Route::get('/{indukan}', [IndukanController::class, 'show'])->name('show');
        Route::get('/{indukan}/edit', [IndukanController::class, 'edit'])->name('edit');
        Route::put('/{indukan}', [IndukanController::class, 'update'])->name('update');
        Route::delete('/{indukan}', [IndukanController::class, 'destroy'])->name('destroy');
        
        
    });
  


  Route::prefix('kandang')->name('kandang.')->group(function () {
    Route::get('/', [KandangController::class, 'index'])->name('index');
    Route::get('/create', [KandangController::class, 'create'])->name('create');
    Route::post('/', [KandangController::class, 'store'])->name('store');
    Route::get('/{kandang}/create-anak', [KandangController::class, 'createAnak'])->name('createAnak');
    Route::post('/{kandang}/anakan', [KandangController::class, 'storeAnakan'])->name('anakan.store');
    Route::get('/{kandang}', [KandangController::class, 'show'])->name('show');
       // ✅ BARU – FORM GAGAL
    Route::get('/{kandang}/form-gagal',
        [KandangController::class, 'formGagal']
    )->name('formGagal');

    // ✅ BARU – STORE GAGAL
    Route::post('/{kandang}/store-gagal',
        [KandangController::class, 'storeGagal']
    )->name('storeGagal');
    Route::get('/{kandang}/edit', [KandangController::class, 'edit'])->name('edit');
    Route::put('/{kandang}', [KandangController::class, 'update'])->name('update');
    Route::delete('/{kandang}', [KandangController::class, 'destroy'])->name('destroy');
});


    // Anakan Management
    Route::prefix('anakan')->name('anakan.')->group(function () {
        Route::get('/', [AnakanController::class, 'index'])->name('index');
        Route::get('/create', [AnakanController::class, 'create'])->name('create');
       Route::post('/', [AnakanController::class, 'store'])->name('store');
        Route::get('/{id}', [AnakanController::class, 'show'])->name('show');
        Route::put('/{id}', [AnakanController::class, 'update'])->name('update');
        Route::put('/{id}/status', [AnakanController::class, 'updateStatus'])->name('updateStatus');
        Route::post('/{id}/sell', [AnakanController::class, 'sell'])->name('sell');
        Route::delete('/{id}', [AnakanController::class, 'destroy'])->name('destroy');
    });


// Analisa Breeding (Kecocokan Indukan)
Route::prefix('analisa-breeding')->name('analisaBreeding.')->group(function () {

    // Form
    Route::get('/form', 
        [\App\Http\Controllers\Peternak\AnalisaBreedingController::class, 'form']
    )->name('form');

    Route::post('/analisa', 
        [\App\Http\Controllers\Peternak\AnalisaBreedingController::class, 'analisa']
    )->name('analisa');

    // halaman hasil khusus (GET)
Route::get('/hasil', 
    [\App\Http\Controllers\Peternak\AnalisaBreedingController::class, 'hasil']
)->name('hasil');

     // ➤ Simpan hasil AI ke database (POST dari tombol Simpan)
    Route::post('/save', 
        [\App\Http\Controllers\Peternak\AnalisaBreedingController::class, 'save']
    )->name('save');

    // ➤ Riwayat semua analisa yang pernah disimpan
    Route::get('/riwayat', 
        [\App\Http\Controllers\Peternak\AnalisaBreedingController::class, 'riwayat']
    )->name('riwayat');
// ➤ Simpan tindak lanjut peternak (lanjut / pantau / stop)
Route::post('/{id}/tindak-lanjut',
    [\App\Http\Controllers\Peternak\AnalisaBreedingController::class, 'updateTindakLanjut']
)->name('updateTindakLanjut');

    // ➤ Detail 1 analisa + halaman untuk memberikan catatan lapangan
    Route::get('/detail/{id}', 
        [\App\Http\Controllers\Peternak\AnalisaBreedingController::class, 'detail']
    )->name('detail');

    // ➤ Simpan catatan setelah breeding (UPDATE)
Route::post('/update-catatan/{id}',
    [\App\Http\Controllers\Peternak\AnalisaBreedingController::class,'updateCatatan']
)->name('updateCatatan');

Route::delete('/hapus/{id}',
    [\App\Http\Controllers\Peternak\AnalisaBreedingController::class, 'hapus']
)->name('hapus');


});



 // Keuangan Management
Route::prefix('keuangan')->name('keuangan.')->group(function () {
    Route::get('/', [KeuanganController::class, 'index'])->name('index');
    Route::get('/create', [KeuanganController::class, 'create'])->name('create');
    Route::post('/', [KeuanganController::class, 'store'])->name('store');
 Route::put('/{transaksi}', [KeuanganController::class, 'update'])->name('update');
Route::get('/{transaksi}/edit', [KeuanganController::class, 'edit'])->name('edit');
  Route::delete('/{transaksi}', [KeuanganController::class, 'destroy'])->name('destroy');

});


    // Deteksi Penyakit
 
    Route::prefix('deteksi-penyakit')->name('deteksi-penyakit.')->group(function () {

        Route::get('/', 
            [DeteksiPenyakitController::class, 'index'])
            ->name('index');

        Route::post('/process', 
            [DeteksiPenyakitController::class, 'process'])
            ->name('process');

        Route::get('/hasil/{id}', 
            [DeteksiPenyakitController::class, 'hasil'])
            ->name('hasil');

            Route::get('/pdf/{id}', [DeteksiPenyakitController::class, 'pdf'])
    ->name('pdf');

    });
});

// ============================================================================
// AUTHENTICATED ROUTES (Semua user yang sudah login)
// ============================================================================
Route::middleware('auth')->group(function () {
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
});
