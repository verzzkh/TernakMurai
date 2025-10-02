<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DeteksiPenyakitController;

Route::get('/', function () {
    return view('dashboard');
});



Route::get('/kandang', function () {
    return view('kandang');
});
// Route untuk halaman detail kandang
Route::get('/kandang/detail/{id}', function ($id) {
    // Di sini nantinya Anda akan mengambil data kandang berdasarkan ID
    return view('kandang.detail', ['id' => $id]);
});

// Route untuk halaman tambah kandang baru
Route::get('/kandang/create', function () {
    return view('kandang.create');
});

// Route untuk menyimpan data kandang baru (POST)
Route::post('/kandang/store', function () {
    // Di sini nantinya logika untuk menyimpan data kandang baru
    return redirect('/kandang');
})->name('kandang.store');




// Route untuk halaman tambah anakan
Route::get('/anakan/create', function () {
    return view('anakan.create');
});

// Route untuk menyimpan data anakan baru
Route::post('/anakan/store', function () {
    // Di sini nantinya logika untuk menyimpan data anakan baru
    return redirect('/anakan');
})->name('anakan.store');

// Route untuk halaman detail anakan
Route::get('/anakan/detail/{id}', function ($id) {
    // Di sini nantinya mengambil data anakan berdasarkan ID
    return view('anakan.detail', ['id' => $id]);
});



Route::get('/anakan', function () {
    return view('anakan');
});
Route::get('/pencatatan', function () {
    return view('pencatatan');
});



// Route untuk halaman deteksi penyakit
Route::get('/deteksi-penyakit', [DeteksiPenyakitController::class, 'index'])->name('deteksi-penyakit.index');
Route::post('/deteksi-penyakit/process', [DeteksiPenyakitController::class, 'process'])->name('deteksi-penyakit.process');
Route::get('/deteksi-penyakit/result', [DeteksiPenyakitController::class, 'result'])->name('deteksi-penyakit.result');