<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Peternak;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $q = $request->input('q');
        $jenis = $request->input('jenis');

        $query = Peternak::with('user')
            ->withCount(['kandangs', 'indukans', 'anakans'])
            ->when($q, function ($qBuilder, $q) {
                $qBuilder->where('nama_peternakan', 'like', "%{$q}%")
                    ->orWhereHas('user', function ($u) use ($q) {
                        $u->where('name', 'like', "%{$q}%")
                          ->orWhere('email', 'like', "%{$q}%");
                    });
            })
            ->when($jenis, fn($b) => $b->where('jenis_akun', $jenis))
            ->latest('id');

        $peternaks = $query->paginate(15)->withQueryString();

        return view('admin.users.index', compact('peternaks', 'q', 'jenis'));
    }
}
