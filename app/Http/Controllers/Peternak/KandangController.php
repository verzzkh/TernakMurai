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
use App\Http\Requests\StoreAnakanDariDalamKandangRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;
use App\Services\AnakanService;




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



/**
 * Tampilkan form untuk menambahkan anakan dari dalam kandang (saat menetas)
 */
public function createAnak(Kandang $kandang): \Illuminate\View\View
{
    $peternak = Auth::user()->peternak;

    // Pastikan kandang milik peternak yang login
    if ($kandang->peternak_id !== $peternak->id) {
        abort(404);
    }

    // Ambil indukan jantan & betina dari kandang untuk ditampilkan di form
    $indukanJantan = $kandang->indukanJantan;
    $indukanBetina = $kandang->indukanBetina;

    // Kirim data ke view
    return view('peternak.kandang.createAnak', compact('kandang', 'indukanJantan', 'indukanBetina'));
}




  public function edit(Kandang $kandang): View
{
    $peternak = Auth::user()->peternak;

    if ($kandang->peternak_id !== $peternak->id) {
        abort(404);
    }

     // Tentukan apakah boleh edit pasangan indukan
    $canEditIndukan = in_array($kandang->status, ['kosong']);

    // Ambil indukan hanya jika bisa edit
    $indukan = $canEditIndukan
        ? $this->kandangService->getAvailableIndukanForEdit($peternak, $kandang)
        : ['jantan' => collect(), 'betina' => collect()];

    return view('peternak.kandang.edit', compact('kandang', 'indukan', 'canEditIndukan'));
}


    public function update(UpdateKandangRequest $request, Kandang $kandang): RedirectResponse
{
    $peternak = Auth::user()->peternak;

    // Pastikan kandang milik peternak yang sedang login
    if ($kandang->peternak_id !== $peternak->id) {
        abort(404);
    }

    $data = $request->validated();

    // Jika status menetas, arahkan ke form createAnak dan ubah status jadi kosong
    if ($data['status'] === 'menetas') {
        // Ubah status menjadi kosong
        $kandang->update(['status' => 'kosong']);

        // Redirect langsung ke halaman form createAnak milik kandang ini
        return redirect()
            ->route('peternak.kandang.createAnak', $kandang->id)
            ->with('info', 'Status "Menetas" terdeteksi. Silakan isi form penambahan anakan.');
    }

   // Gunakan rolling-aware update
    $this->kandangService->updateKandangWithRolling($kandang, $data);

    return redirect()
        ->route('peternak.kandang.show', $kandang)
        ->with('success', 'Kandang berhasil diperbarui.');
}





public function storeAnakan(\App\Http\Requests\StoreAnakanDariDalamKandangRequest $request, Kandang $kandang)
{
    $peternak = Auth::user()->peternak;

    if ($kandang->peternak_id !== $peternak->id) {
        abort(404);
    }

    // Use the FormRequest validated() so prepareForValidation() runs and
    // any merged fields (peternak_id, sumber_anakan) are present for rules.
    $validated = $request->validated();

    $result = $this->kandangService->storeAnakanFromKandang($validated, $kandang, $peternak->id);

    if ($result['success']) {
        return redirect()
            ->route('peternak.kandang.show', $kandang)
            ->with('success', $result['message']);
    }

    // If backend reported failure, keep input and show message
    return back()->with('error', $result['message'])->withInput();
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
