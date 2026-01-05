<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Peternak;

class PaketController extends Controller
{
    public function index(Request $request)
    {
        // quick summary of packages
        $summary = Peternak::selectRaw('jenis_akun, COUNT(*) as total')
            ->groupBy('jenis_akun')
            ->pluck('total', 'jenis_akun')
            ->toArray();

        $peternaks = Peternak::with('user')
            ->latest()
            ->paginate(15);

        return view('admin.paket.index', compact('summary', 'peternaks'));
    }
}
