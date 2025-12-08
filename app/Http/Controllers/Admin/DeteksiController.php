<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DeteksiPenyakit;

class DeteksiController extends Controller
{
    public function index(Request $request)
    {
        $q = $request->input('q');

        $query = DeteksiPenyakit::with('peternak')
            ->when($q, function ($b) use ($q) {
                $b->where('nama_burung', 'like', "%{$q}%")
                    ->orWhere('diagnosis_utama', 'like', "%{$q}%")
                    ->orWhereHas('peternak', fn($p) => $p->where('nama_peternakan', 'like', "%{$q}%"));
            })
            ->latest();

        $deteksi = $query->paginate(20);

        return view('admin.deteksi.index', compact('deteksi', 'q'));
    }

    public function show($id)
    {
        $deteksi = DeteksiPenyakit::with('peternak', 'fotos')->findOrFail($id);

        return view('admin.deteksi.show', compact('deteksi'));
    }
}
