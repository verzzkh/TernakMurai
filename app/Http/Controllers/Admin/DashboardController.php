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
use App\Models\DeteksiPenyakit;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // Basic counts
        $totalUsers = User::whereHas('peternak')->count();
        $totalPeternak = Peternak::count();
        $userPro = Peternak::where('jenis_akun', 'pro')->count();
        $userFree = max(0, $totalPeternak - $userPro);

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

            $deteksiGrowth[] = DeteksiPenyakit::whereBetween('created_at', [$start, $end])->count();
        }

        // Latest data
        $latestUsers = User::whereHas('peternak')->with('peternak')->latest()->take(5)->get();
        $latestDeteksi = DeteksiPenyakit::with('peternak')->latest()->take(5)->get();

        return view('admin.dashboard.index', compact(
            'totalUsers', 'userPro', 'userFree', 'totalKandang', 'totalIndukan', 'totalAnakan',
            'labels', 'userGrowth', 'deteksiGrowth', 'latestUsers', 'latestDeteksi'
        ));
    }
}
