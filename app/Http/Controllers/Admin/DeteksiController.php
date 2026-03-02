<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\HasilAnalisaBreeding;

class DeteksiController extends Controller
{
    public function index(Request $request)
    {
        $q = $request->input('q');

        // Use HasilAnalisaBreeding (evaluasi breeding) as the data source.
        // Limit text/column-based searches to related `peternak` to avoid
        // querying unknown columns on the evaluation table.
        $query = HasilAnalisaBreeding::with('peternak')
            ->when($q, function ($b) use ($q) {
                $b->whereHas('peternak', fn($p) => $p->where('nama_peternakan', 'like', "%{$q}%")
                    ->orWhere('nomor_handphone', 'like', "%{$q}%"));
            })
            ->latest();

        $deteksi = $query->paginate(20);

        return view('admin.deteksi.index', compact('deteksi', 'q'));
    }

    public function show($id)
    {
        // Only eager-load relations that actually exist on the model to avoid
        // RelationNotFoundException if e.g. `fotos` relation is not defined.
        $model = new HasilAnalisaBreeding();
        $relations = ['peternak'];

        if (method_exists($model, 'fotos')) {
            $relations[] = 'fotos';
        }

        $deteksi = HasilAnalisaBreeding::with($relations)->findOrFail($id);

        return view('admin.deteksi.show', compact('deteksi'));
    }
}
