<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Peternak\AnakanController;
use App\Http\Controllers\Peternak\DashboardController;
use App\Http\Controllers\Peternak\DeteksiPenyakitController;
use App\Http\Controllers\Peternak\KandangController;
use App\Http\Controllers\Peternak\KeuanganController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// ============================================================================
// GUEST ROUTES (Tidak perlu login)
// ============================================================================
Route::middleware('guest')->group(function () {
    Route::get('/', function () {
        return redirect()->route('login');
    });
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
});

// ============================================================================
// UNIFIED DASHBOARD ROUTE (Redirects based on user role)
// ============================================================================
Route::middleware('auth')->get('/dashboard', function () {
    $user = Auth::user();
    $role = $user->getRole();

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
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('dashboard');

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

    // Kandang Management
    Route::prefix('kandang')->name('kandang.')->group(function () {
        Route::get('/', [KandangController::class, 'index'])->name('index');
        Route::get('/create', [KandangController::class, 'create'])->name('create');
        Route::post('/', [KandangController::class, 'store'])->name('store');
        Route::get('/{id}', [KandangController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [KandangController::class, 'edit'])->name('edit');
        Route::put('/{id}', [KandangController::class, 'update'])->name('update');
        Route::delete('/{id}', [KandangController::class, 'destroy'])->name('destroy');
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

    // Pencatatan Keuangan
    Route::get('/pencatatan', [KeuanganController::class, 'index'])->name('pencatatan');

    // Deteksi Penyakit
    Route::prefix('deteksi-penyakit')->name('deteksi-penyakit.')->group(function () {
        Route::get('/', [DeteksiPenyakitController::class, 'index'])->name('index');
        Route::post('/process', [DeteksiPenyakitController::class, 'process'])->name('process');
        Route::get('/result', [DeteksiPenyakitController::class, 'result'])->name('result');
    });
});

// ============================================================================
// AUTHENTICATED ROUTES (Semua user yang sudah login)
// ============================================================================
Route::middleware('auth')->group(function () {
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
});
