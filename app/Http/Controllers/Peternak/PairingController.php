<?php

declare(strict_types=1);

namespace App\Http\Controllers\Peternak;

use App\Http\Controllers\Controller;
use App\Models\Pairing;
use App\Services\PairingService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class PairingController extends Controller
{
    public function __construct(
        private PairingService $pairingService
    ) {}

    public function index(): View
    {
        $peternakId = Auth::user()->peternak->id;
        $pairings = $this->pairingService->getManagementList($peternakId);

        return view('peternak.pairings.index', compact('pairings'));
    }

    public function updateStatus(Request $request, Pairing $pairing): RedirectResponse
    {
        $peternakId = Auth::user()->peternak->id;

        if ($pairing->peternak_id !== $peternakId) {
            abort(404);
        }

        $validated = $request->validate([
            'status' => 'required|in:aktif,dihentikan',
        ]);

        if ($validated['status'] === Pairing::STATUS_AKTIF) {
            $this->pairingService->activate($pairing);
            $message = 'Pairing berhasil diaktifkan kembali.';
        } else {
            $this->pairingService->stop($pairing);
            $message = 'Pairing berhasil dihentikan.';
        }

        return redirect()
            ->route('peternak.pairings.index')
            ->with('success', $message);
    }
}
