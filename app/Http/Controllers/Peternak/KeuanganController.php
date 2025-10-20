<?php

declare(strict_types=1);

namespace App\Http\Controllers\Peternak;

use App\Http\Controllers\Controller;

class KeuanganController extends Controller
{
    /**
     * Display the pencatatan keuangan page.
     */
    public function index(): \Illuminate\View\View
    {
        return view('peternak.pencatatan');
    }
}
