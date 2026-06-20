<?php

namespace App\Http\Controllers\Peternak;

use App\Http\Controllers\Controller;
use App\Models\Anakan;
use App\Models\Indukan;
use App\Models\Kandang;
use App\Models\Transaksi;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        // Ambil profil peternak dari user yang sedang login (agar data dashboard sesuai pemilik).
        $peternak = Auth::user()->peternak;

        // ======================================================================
        // 1. Statistik Utama
        // ======================================================================
        // Hitung total data utama breeding milik peternak untuk ditampilkan sebagai ringkasan.
        $totalKandang = Kandang::where('peternak_id', $peternak->id)->count();
        $totalIndukan = Indukan::where('peternak_id', $peternak->id)->count();
        $totalAnakan = Anakan::where('peternak_id', $peternak->id)->count();

        // Keuangan bulan berjalan: hitung pemasukan, pengeluaran, dan profit bulan ini.
        $year = now()->year;
        $month = now()->month;

        // Total pemasukan pada bulan berjalan.
        $incomeMonth = Transaksi::where('peternak_id', $peternak->id)
            ->where('tipe', 'pemasukan')
            ->whereMonth('tanggal', $month)
            ->whereYear('tanggal', $year)
            ->sum('jumlah');

        // Total pengeluaran pada bulan berjalan.
        $expenseMonth = Transaksi::where('peternak_id', $peternak->id)
            ->where('tipe', 'pengeluaran')
            ->whereMonth('tanggal', $month)
            ->whereYear('tanggal', $year)
            ->sum('jumlah');

        // Profit bulan ini = pemasukan - pengeluaran.
        $profitMonth = $incomeMonth - $expenseMonth;

        // ======================================================================
        // 2. Bar Chart — Jumlah Anakan per kandang
        // ======================================================================
        // Ambil daftar kandang beserta indukan untuk membentuk dataset chart populasi anakan per kandang.
        $kandangList = Kandang::where('peternak_id', $peternak->id)
            ->with(['indukanJantan', 'indukanBetina'])
            ->get();

        // Bentuk array data yang siap dipakai di JavaScript chart.
        $kandangChart = $kandangList->map(function ($k) {
            $namaPasangan = '';
            if ($k->indukan_jantan_id || $k->indukan_betina_id) {
                $jantan = $k->indukanJantan ? ($k->indukanJantan->nama ?? $k->indukanJantan->nomor_ring) : '?';
                $betina = $k->indukanBetina ? ($k->indukanBetina->nama ?? $k->indukanBetina->nomor_ring) : '?';
                $namaPasangan = " ({$jantan} × {$betina})";
            }

            return [
                'nama' => ($k->nomor_kandang ?? "Kandang {$k->id}") . $namaPasangan,
                'jumlah' => $k->anakansAktif->count(), // ambil jumlah anakan dari pasangan aktif
            ];
        });

        // ======================================================================
        // 3. Pie Chart — Status Kandang (pakai kolom kandang.status)
        // ======================================================================
        // Hitung jumlah kandang berdasarkan status untuk pie chart.
        $kandangStatus = [
            'kosong' => Kandang::where('peternak_id', $peternak->id)->where('status', 'kosong')->count(),
            'bertelur' => Kandang::where('peternak_id', $peternak->id)->where('status', 'bertelur')->count(),
            'mengeram' => Kandang::where('peternak_id', $peternak->id)->where('status', 'mengeram')->count(),
        ];

        // ======================================================================
        // 4. Aktivitas Terbaru
        // ======================================================================
        // Ambil transaksi terbaru untuk ditampilkan sebagai aktivitas terakhir.
        $recentActivities = Transaksi::where('peternak_id', $peternak->id)
            ->latest()
            ->take(5)
            ->get();

        // ======================================================================
        // Return ke View
        // ======================================================================
        // Kirim semua data ringkasan + dataset chart ke view dashboard peternak.
        return view('peternak.dashboard', compact(
            'totalKandang',
            'totalIndukan',
            'totalAnakan',
            'incomeMonth',
            'expenseMonth',
            'profitMonth',
            'kandangChart',
            'kandangStatus',
            'recentActivities'
        ));
    }
}
