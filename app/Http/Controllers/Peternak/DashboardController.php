<?php

namespace App\Http\Controllers\Peternak;

use App\Http\Controllers\Controller;
use App\Models\Anakan;
use App\Models\Indukan;
use App\Models\Kandang;
use App\Models\Peternak;
use App\Models\Transaksi;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
{
    $peternak = Auth::user()->peternak;

    // ======================================================================
    // 1. Statistik Utama
    // ======================================================================
    $totalKandang = Kandang::where('peternak_id', $peternak->id)->count();
    $totalIndukan = Indukan::where('peternak_id', $peternak->id)->count();
    $totalAnakan  = Anakan::where('peternak_id', $peternak->id)->count();

    // Keuangan bulan berjalan
    $year  = now()->year;
    $month = now()->month;

    $incomeMonth = Transaksi::where('peternak_id', $peternak->id)
        ->where('tipe', 'pemasukan')
        ->whereMonth('tanggal', $month)
        ->whereYear('tanggal', $year)
        ->sum('jumlah');

    $expenseMonth = Transaksi::where('peternak_id', $peternak->id)
        ->where('tipe', 'pengeluaran')
        ->whereMonth('tanggal', $month)
        ->whereYear('tanggal', $year)
        ->sum('jumlah');

    $profitMonth = $incomeMonth - $expenseMonth;


    // ======================================================================
    // 2. Bar Chart — Jumlah Anakan per kandang
    // ======================================================================
    $kandangList = Kandang::where('peternak_id', $peternak->id)->get();

    $kandangChart = $kandangList->map(function ($k) {
        return [
            'nama'   => $k->nomor_kandang ?? "Kandang {$k->id}",
            'jumlah' => $k->anakans()->count(), // ambil anakan saja
        ];
    });


    // ======================================================================
    // 3. Pie Chart — Status Kandang (pakai kolom kandang.status)
    // ======================================================================
    $kandangStatus = [
        'kosong'   => Kandang::where('peternak_id', $peternak->id)->where('status', 'kosong')->count(),
        'bertelur' => Kandang::where('peternak_id', $peternak->id)->where('status', 'bertelur')->count(),
        'mengeram' => Kandang::where('peternak_id', $peternak->id)->where('status', 'mengeram')->count(),
    ];


    // ======================================================================
    // 4. Aktivitas Terbaru
    // ======================================================================
    $recentActivities = Transaksi::where('peternak_id', $peternak->id)
        ->latest()
        ->take(5)
        ->get();


    // ======================================================================
    // 5. Status Paket
    // ======================================================================
    $paket = $peternak->jenis_akun; // free / pro
    $kuota = $paket === 'pro' ? 8 : 3;
    $dipakai = $peternak->deteksi_terpakai ?? 0;
    $sisa = $kuota - $dipakai;
    $masaAktif = $peternak->pro_berlaku_hingga;


    // ======================================================================
    // Return ke View
    // ======================================================================
    return view('peternak.dashboard', compact(
        'totalKandang',
        'totalIndukan',
        'totalAnakan',
        'incomeMonth',
        'expenseMonth',
        'profitMonth',
        'kandangChart',
        'kandangStatus',
        'recentActivities',
        'paket',
        'kuota',
        'dipakai',
        'sisa',
        'masaAktif'
    ));
}

}
