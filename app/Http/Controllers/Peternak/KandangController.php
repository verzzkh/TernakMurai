<?php

declare(strict_types=1);

namespace App\Http\Controllers\Peternak;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreKandangRequest;
use App\Http\Requests\UpdateKandangRequest;
use App\Models\Kandang;
use App\Services\KandangService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class KandangController extends Controller
{
    public function __construct(
        private KandangService $kandangService
    ) {}

    public function index(Request $request): View
    {
        $peternak = Auth::user()->peternak;

        $filters = [
            'search' => $request->get('search'),
            'status' => $request->get('status'),
            'sort_by' => $request->get('sort_by'),
            'sort_direction' => $request->get('sort_direction'),
        ];

        $kandangs = $this->kandangService->getPaginatedKandang($peternak, $filters);
        $stats = $this->kandangService->getKandangStats($peternak);

        return view('peternak.kandang.index', compact('kandangs', 'stats', 'filters'));
    }

    public function create(): View
    {
        $peternak = Auth::user()->peternak;
        $indukan = $this->kandangService->getAvailableIndukan($peternak);

        return view('peternak.kandang.create', compact('indukan'));
    }

    public function store(StoreKandangRequest $request): RedirectResponse
    {
        $peternak = Auth::user()->peternak;

        $this->kandangService->createKandang($peternak, $request->validated());

        return redirect()->route('peternak.kandang.index')
            ->with('success', 'Kandang berhasil ditambahkan.');
    }

    public function show(Kandang $kandang): View
    {
        $peternak = Auth::user()->peternak;

        // Ensure kandang belongs to current peternak
        if ($kandang->peternak_id !== $peternak->id) {
            abort(404);
        }

        $kandang = $this->kandangService->getKandangById($peternak, $kandang->id);

        return view('peternak.kandang.detail', compact('kandang'));
    }

    public function edit(Kandang $kandang): View
    {
        $peternak = Auth::user()->peternak;

        // Ensure kandang belongs to current peternak
        if ($kandang->peternak_id !== $peternak->id) {
            abort(404);
        }

        $indukan = $this->kandangService->getAvailableIndukan($peternak);

        return view('peternak.kandang.edit', compact('kandang', 'indukan'));
    }

    public function update(UpdateKandangRequest $request, Kandang $kandang): RedirectResponse
    {
        $peternak = Auth::user()->peternak;

        // Ensure kandang belongs to current peternak
        if ($kandang->peternak_id !== $peternak->id) {
            abort(404);
        }

        $this->kandangService->updateKandang($kandang, $request->validated());

        return redirect()->route('peternak.kandang.show', $kandang)
            ->with('success', 'Kandang berhasil diperbarui.');
    }

    public function destroy(Kandang $kandang): RedirectResponse
    {
        $peternak = Auth::user()->peternak;

        // Ensure kandang belongs to current peternak
        if ($kandang->peternak_id !== $peternak->id) {
            abort(404);
        }

        $this->kandangService->deleteKandang($kandang);

        return redirect()->route('peternak.kandang.index')
            ->with('success', 'Kandang berhasil dihapus.');
    }
}
