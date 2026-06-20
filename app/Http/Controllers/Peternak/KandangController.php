<?php

declare(strict_types=1);

namespace App\Http\Controllers\Peternak;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreKandangRequest;
use App\Http\Requests\UpdateKandangRequest;
use App\Models\Kandang;
use App\Models\Pairing;
use App\Services\BreedingLifecycleService;
use App\Services\KandangService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Requests\StoreAnakanDariDalamKandangRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;




class KandangController extends Controller
{
    public function __construct(
        private KandangService $kandangService,
        private BreedingLifecycleService $breedingLifecycleService
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
            $performa = $this->kandangService->getPerformaPasanganAktif($kandang);
            $validNextStatuses = $this->breedingLifecycleService->validNextStatuses($kandang);
            $statusLabels = $this->breedingLifecycleService->statusLabels();
            $currentPairing = $this->breedingLifecycleService->pairingAvailable()
                ? $this->breedingLifecycleService->getPairingForKandang($kandang)
                : null;

            return view('peternak.kandang.detail', compact('kandang', 'performa', 'validNextStatuses', 'statusLabels', 'currentPairing'));
        }



/**
 * Tampilkan form untuk menambahkan anakan dari dalam kandang (saat menetas)
 */
public function createAnak(Kandang $kandang): View|RedirectResponse
{
    $peternak = Auth::user()->peternak;

    // Pastikan kandang milik peternak yang login
    if ($kandang->peternak_id !== $peternak->id) {
        abort(404);
    }

    if (!$kandang->indukan_jantan_id || !$kandang->indukan_betina_id) {
        return redirect()->route('peternak.kandang.show', $kandang)
            ->with('error', 'Kandang harus memiliki pasangan Indukan Jantan dan Betina yang lengkap sebelum anakan dapat menetas.');
    }

    $this->breedingLifecycleService->ensureTransitionAllowed($kandang, BreedingLifecycleService::HASIL_BERHASIL);

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
    $validNextStatuses = $this->breedingLifecycleService->validNextStatuses($kandang);
    $statusLabels = $this->breedingLifecycleService->statusLabels();
    $currentPairing = $this->breedingLifecycleService->pairingAvailable()
        ? $this->breedingLifecycleService->getPairingForKandang($kandang)
        : null;

    // Ambil indukan hanya jika bisa edit
    $indukan = $canEditIndukan
        ? $this->kandangService->getAvailableIndukanForEdit($peternak, $kandang)
        : ['jantan' => collect(), 'betina' => collect()];

    return view('peternak.kandang.edit', compact('kandang', 'indukan', 'canEditIndukan', 'validNextStatuses', 'statusLabels', 'currentPairing'));
}

public function update(UpdateKandangRequest $request, Kandang $kandang): RedirectResponse
{
    $peternak = Auth::user()->peternak;

    if ($kandang->peternak_id !== $peternak->id) {
        abort(404);
    }

    $data = $request->validated();

    /*
    |--------------------------------------------------------------------------
    | STATUS GAGAL → Redirect ke Form Input Tanggal
    |--------------------------------------------------------------------------
    */
    $newStatus = $data['status'] ?? $kandang->status;
    $statusChanged = $newStatus !== $kandang->status;

    if ($statusChanged && $newStatus === BreedingLifecycleService::HASIL_GAGAL) {
        $this->breedingLifecycleService->ensureTransitionAllowed($kandang, BreedingLifecycleService::HASIL_GAGAL);

        return redirect()
            ->route('peternak.kandang.formGagal', $kandang->id)
            ->with('warning', 'Silakan isi tanggal kegagalan trip.');
    }

    /*
    |--------------------------------------------------------------------------
    | STATUS MENETAS → Redirect ke Form Tambah Anakan
    |--------------------------------------------------------------------------
    */
    if ($statusChanged && $newStatus === BreedingLifecycleService::HASIL_BERHASIL) {
        $this->breedingLifecycleService->ensureTransitionAllowed($kandang, BreedingLifecycleService::HASIL_BERHASIL);

        return redirect()
            ->route('peternak.kandang.createAnak', $kandang->id)
            ->with('info', 'Silakan isi data anakan untuk menyelesaikan trip berhasil.');
    }

    /*
    |--------------------------------------------------------------------------
    | STATUS NORMAL
    |--------------------------------------------------------------------------
    */
    if ($statusChanged) {
        $this->breedingLifecycleService->transition($kandang, $newStatus);
        unset($data['status']);
    }

    $this->kandangService->updateKandangWithRolling($kandang, $data);

    return redirect()
        ->route('peternak.kandang.show', $kandang)
        ->with('success', 'Kandang berhasil diperbarui.');
}

public function formGagal(Kandang $kandang)
{
    $peternak = Auth::user()->peternak;

    if ($kandang->peternak_id !== $peternak->id) {
        abort(404);
    }

    return view('peternak.kandang.formGagal', compact('kandang'));
}

public function storeGagal(Request $request, Kandang $kandang)
{
    $peternak = Auth::user()->peternak;

    if ($kandang->peternak_id !== $peternak->id) {
        abort(404);
    }

    $request->validate([
        'tanggal_gagal' => 'required|date',
        'catatan' => 'nullable|string|max:1000'
    ]);

    if (!$kandang->indukan_jantan_id || !$kandang->indukan_betina_id) {
        return redirect()
            ->route('peternak.kandang.show', $kandang)
            ->with('error', 'Gagal memproses. Kandang harus memiliki pasangan Indukan Jantan dan Betina yang lengkap.');
    }

    $this->breedingLifecycleService->recordFailedTrip(
        $kandang,
        $peternak->id,
        $request->tanggal_gagal,
        $request->catatan
    );

    return redirect()
        ->route('peternak.kandang.show', $kandang)
        ->with('error', 'Trip berhasil dicatat sebagai GAGAL.');
}





public function storeAnakan(\App\Http\Requests\StoreAnakanDariDalamKandangRequest $request, Kandang $kandang)
{
    $peternak = Auth::user()->peternak;

    if ($kandang->peternak_id !== $peternak->id) {
        abort(404);
    }

    if (!$kandang->indukan_jantan_id || !$kandang->indukan_betina_id) {
        return back()->with('error', 'Gagal memproses anakan. Kandang harus memiliki pasangan Indukan Jantan dan Betina yang lengkap.')->withInput();
    }

    // Use the FormRequest validated() so prepareForValidation() runs and
    // any merged fields (peternak_id, sumber_anakan) are present for rules.
    $validated = $request->validated();

    try {
        $result = $this->breedingLifecycleService->recordSuccessfulTripWithAnakan($kandang, $peternak->id, $validated);

        return redirect()
            ->route('peternak.kandang.show', $kandang)
            ->with('success', 'Berhasil menambahkan ' . count($result['anakans']) . ' anakan pada Trip #' . $result['perkawinan']->nomor_trip);
    } catch (\Throwable $th) {
        return back()->with('error', 'Gagal menyimpan anakan: ' . $th->getMessage())->withInput();
    }
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

    public function activatePairing(Kandang $kandang): RedirectResponse
    {
        $peternak = Auth::user()->peternak;

        if ($kandang->peternak_id !== $peternak->id) {
            abort(404);
        }

        if (!$this->breedingLifecycleService->pairingAvailable()) {
            return redirect()
                ->route('peternak.kandang.show', $kandang)
                ->with('error', 'Fitur pairing belum tersedia di database. Jalankan migrasi terlebih dahulu.');
        }

        $pairing = $this->breedingLifecycleService->getPairingForKandang($kandang);

        if (!$pairing) {
            return redirect()
                ->route('peternak.kandang.show', $kandang)
                ->with('error', 'Pairing untuk kandang ini belum tersedia.');
        }

        if ($pairing->status === Pairing::STATUS_AKTIF) {
            return redirect()
                ->route('peternak.kandang.show', $kandang)
                ->with('info', 'Pairing pada kandang ini sudah aktif.');
        }

        $this->breedingLifecycleService->activatePairing($pairing);

        return redirect()
            ->route('peternak.kandang.show', $kandang)
            ->with('success', 'Pairing berhasil diaktifkan kembali.');
    }
}
