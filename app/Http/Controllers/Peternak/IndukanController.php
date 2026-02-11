<?php

declare(strict_types=1);

namespace App\Http\Controllers\Peternak;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreIndukanRequest;
use App\Http\Requests\UpdateIndukanRequest;
use App\Models\Indukan;
use App\Services\IndukanService;
use App\Services\AnakanService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
class IndukanController extends Controller
{
    public function __construct(
        private IndukanService $indukanService,
        private AnakanService $anakanService
    ) {}
    public function index(Request $request): View
    {
        $peternak = Auth::user()->peternak;
        
        $filters = [
            'search' => $request->get('search'),
            'jenis_kelamin' => $request->get('jenis_kelamin'),
            'sort_by' => $request->get('sort_by'),
            'sort_direction' => $request->get('sort_direction'),
        ];
    // Ambil data indukan (pagination)
    $indukans = $this->indukanService->getPaginatedIndukan($peternak, $filters);

        $stats = $this->indukanService->getIndukanStats($peternak);
        return view('peternak.indukan.index', compact('indukans', 'stats', 'filters'));
    }
    public function create(): View
    {
        return view('peternak.indukan.create');
    }


    
  public function store(StoreIndukanRequest $request): RedirectResponse
{
    $peternak = Auth::user()->peternak;
    $data = $request->validated();
    if ($request->hasFile('foto_indukan')) {
        $data['foto_indukan'] = $request->file('foto_indukan');
    }
    $this->indukanService->createIndukan($peternak, $data);

    return redirect()->route('peternak.indukan.index')
        ->with('success', 'Indukan berhasil ditambahkan.');
}

   public function show(Indukan $indukan): View
{
    $peternak = Auth::user()->peternak;

    if ($indukan->peternak_id !== $peternak->id) {
        abort(404);
    }

    $indukan->load([
        'anakansSebagaiJantan.perkawinan',
        'anakansSebagaiBetina.perkawinan',
        'kandangsJantan',
        'kandangsBetina'
    ]);
     $indukan = $this->indukanService->getIndukanWithBreeding($indukan);
     $performa = $this->indukanService->calculatePerformaGlobal($indukan);


    return view('peternak.indukan.detail', compact('indukan', 'performa'));
}

public function updateFoto(Request $request, Indukan $indukan)
{
    $request->validate([
        'foto_indukan' => ['required', 'image', 'mimes:jpeg,jpg,png', 'max:2048']
    ]);

    $peternak = Auth::user()->peternak;
    if ($indukan->peternak_id !== $peternak->id) {
        return response()->json([
            'success' => false,
            'message' => 'Tidak diizinkan.'
        ], 403);
    }

    $updated = $this->indukanService->updateFotoIndukan($indukan, $request->file('foto_indukan'));

    if ($updated) {
        return response()->json(['success' => true]);
    }

    return response()->json(['success' => false], 500);
}



    public function edit(Indukan $indukan): View
    {
        $peternak = Auth::user()->peternak;

        // Ensure indukan belongs to current peternak
        if ($indukan->peternak_id !== $peternak->id) {
            abort(404);
        }

        return view('peternak.indukan.edit', compact('indukan'));
    }

    public function update(UpdateIndukanRequest $request, Indukan $indukan): RedirectResponse
    {
        $peternak = Auth::user()->peternak;

        // Ensure indukan belongs to current peternak
        if ($indukan->peternak_id !== $peternak->id) {
            abort(404);
        }

        $this->indukanService->updateIndukan($indukan, $request->validated());

        return redirect()->route('peternak.indukan.show', $indukan)
            ->with('success', 'Indukan berhasil diperbarui.');
    }

    public function destroy(Indukan $indukan): RedirectResponse
    {
        $peternak = Auth::user()->peternak;

        // Ensure indukan belongs to current peternak
        if ($indukan->peternak_id !== $peternak->id) {
            abort(404);
        }

        $this->indukanService->deleteIndukan($indukan);

     return redirect()->back()->with('success', 'Indukan berhasil dihapus.');

    }
}
