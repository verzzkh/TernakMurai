<?php

declare(strict_types=1);

namespace App\Http\Controllers\Peternak;

use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    /**
     * Display the dashboard page.
     */
    public function index(): \Illuminate\View\View
    {
        return view('peternak.dashboard');
    }
}
