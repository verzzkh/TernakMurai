<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Peternak;
use App\Models\Kandang;
use App\Models\Indukan;
use App\Models\Anakan;
use App\Models\HasilAnalisaBreeding;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // Basic counts
        $totalUsers = User::whereHas('peternak')->count();
        $totalPeternak = Peternak::count();

        // legacy package/jenis_akun removed; keep zero to avoid view errors
        $userPro = 0;
        $userFree = 0;

        $totalKandang = Kandang::count();
        $totalIndukan = Indukan::count();
        $totalAnakan = Anakan::count();

        // Last 12 months labels + data
        $labels = [];
        $userGrowth = [];
        $deteksiGrowth = [];
        for ($i = 11; $i >= 0; $i--) {
            $start = Carbon::now()->subMonths($i)->startOfMonth();
            $end = Carbon::now()->subMonths($i)->endOfMonth();
            $labels[] = $start->format('M Y');

            $userGrowth[] = User::whereHas('peternak')
                ->whereBetween('created_at', [$start, $end])
                ->count();

            // Use `tanggal_analisa` as the timestamp for evaluations
            $deteksiGrowth[] = HasilAnalisaBreeding::whereBetween('tanggal_analisa', [$start, $end])->count();
        }

        // Latest data
        $latestUsers = User::whereHas('peternak')->with('peternak')->latest()->take(5)->get();
        $latestDeteksi = HasilAnalisaBreeding::with('peternak')
            ->orderByDesc('tanggal_analisa')
            ->take(5)
            ->get();

        return view('admin.dashboard.index', compact(
            'totalUsers', 'userPro', 'userFree', 'totalKandang', 'totalIndukan', 'totalAnakan',
            'labels', 'userGrowth', 'deteksiGrowth', 'latestUsers', 'latestDeteksi'
        ));
    }
}
