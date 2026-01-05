<?php

declare(strict_types=1);

namespace App\Http\Controllers\Peternak;

use App\Http\Controllers\Controller;
use App\Http\Requests\SellAnakanRequest;
use App\Http\Requests\StoreAnakanDariKandangRequest;
use App\Http\Requests\StoreAnakanDariLuarRequest;
use App\Http\Requests\UpdateAnakanRequest;
use App\Http\Requests\UpdateStatusAnakanRequest;
use App\Models\Anakan;
use App\Services\AnakanService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Support\Facades\Log;

class AnakanController extends Controller
{
    public function __construct(
        private AnakanService $anakanService
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $peternak = Auth::user()->peternak;

        $query = $peternak->anakans()
            ->with(['kandang', 'perkawinan.indukanJantan', 'perkawinan.indukanBetina'])
            ->latest();

        // Apply filters
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->where('nomor_ring', 'like', "%{$search}%")
                    ->orWhereHas('perkawinan.indukanJantan', function ($q) use ($search) {
                        $q->where('nomor_ring', 'like', "%{$search}%");
                    });
            });
        }

        if ($request->filled('jenis_kelamin')) {
            $query->where('jenis_kelamin', $request->get('jenis_kelamin'));
        }

        if ($request->filled('status_pertumbuhan')) {
            $query->where('status_pertumbuhan', $request->get('status_pertumbuhan'));
        }

        if ($request->filled('status_penjualan')) {
            $query->where('status_penjualan', $request->get('status_penjualan'));
        }

        $anakans = $query->paginate(12);

        // Add age calculation to each anakan
        $anakans->getCollection()->each(function ($anakan) {
            $anakan->age = $this->anakanService->calculateAge($anakan->tanggal_lahir);
        });


        $stats = $this->anakanService->getAnakanStats($peternak);

return view('peternak.anakan.index', compact('anakans', 'peternak', 'stats'));

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request): View|RedirectResponse
    {
        $peternak = Auth::user()->peternak;

        // Check if can add anakan (free account limit)
        $totalAktif = $peternak->anakans()
            ->where('status_penjualan', 'belum_dijual')
            ->count();

        if (! $peternak->isPro() && $totalAktif >= 30) {
            return redirect()->route('peternak.anakan.index')
                ->with('error', 'Akun gratis hanya dapat menambah maksimal 30 anakan aktif. Upgrade ke Pro untuk menambah lebih banyak.');
        }

        $indukanJantan = $peternak->indukans()->where('jenis_kelamin', 'jantan')->get();
        $indukanBetina = $peternak->indukans()->where('jenis_kelamin', 'betina')->get();

        return view('peternak.anakan.create', compact('indukanJantan', 'indukanBetina'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $peternak = Auth::user()->peternak;

        try {
            $sumberAnakan = $request->input('sumber_anakan');
            $jumlahBaru = (int) $request->input('jumlah_anakan', 1);

            // 🔒 Validasi kuota akun Free
            if (! $peternak->isPro()) {
                $totalAktif = $peternak->anakans()
                    ->where('status_penjualan', 'belum_dijual')
                    ->count();

                if (($totalAktif + $jumlahBaru) > 30) {
                    return back()
                        ->withInput()
                        ->with('error', "Akun gratis hanya dapat menambah maksimal 30 anakan aktif. Saat ini sudah ada {$totalAktif} anakan.");
                }
            }

            // 🧩 Proses validasi dan simpan sesuai sumber anakan
            if ($sumberAnakan === 'peternakan' || $sumberAnakan === 'internal') {
                // ✅ Tambah dari dalam (kandang)
                $validatedData = $request->validate(
                    (new StoreAnakanDariKandangRequest())->rules(),
                    (new StoreAnakanDariKandangRequest())->messages()
                );

                $validatedData['sumber_anakan'] = 'internal';
                $validatedData['peternak_id'] = $peternak->id;

                // Ensure kandang_id is passed to service (hidden input in form)
                $validatedData['kandang_id'] = $request->input('kandang_id');

                // 🔍 Cek pasangan indukan
                $indukanJantanId = $request->input('indukan_jantan_id');
                $indukanBetinaId = $request->input('indukan_betina_id');

                $perkawinan = null;
                if ($indukanJantanId && $indukanBetinaId) {
                    $perkawinan = \App\Models\Perkawinan::firstOrCreate(
                        [
                            'indukan_jantan_id' => $indukanJantanId,
                            'indukan_betina_id' => $indukanBetinaId,
                        ],
                        [
                            'nomor_trip' => 'AUTO-' . strtoupper(\Illuminate\Support\Str::random(5)),
                            'tanggal_kawin' => now(),
                            'catatan' => 'Perkawinan otomatis dibuat saat tambah anakan.',
                        ]
                    );

                    $validatedData['perkawinan_id'] = $perkawinan->id;
                }

                // 🔁 Buat anakan via service (service sudah menangani multiple, file uploads, dan normalisasi)
                $result = $this->anakanService->storeFromKandang($validatedData, $peternak->id);

                if (is_array($result)) {
                    $message = 'Berhasil menambahkan ' . count($result) . ' anakan dari pasangan indukan.';
                } else {
                    $message = 'Berhasil menambahkan anakan dari pasangan indukan.';
                }
            }

            // 🧩 Tambahkan kembali bagian untuk sumber eksternal
            elseif ($sumberAnakan === 'eksternal' || $sumberAnakan === 'luar') {
                $validatedData = $request->validate(
                    (new StoreAnakanDariLuarRequest())->rules(),
                    (new StoreAnakanDariLuarRequest())->messages()
                );

                $validatedData['sumber_anakan'] = 'eksternal'; // pastikan tidak tertimpa

                // Gunakan service yang sudah ada
                $this->anakanService->storeFromLuar($validatedData, $peternak->id);

                $message = 'Berhasil menambahkan anakan dari luar.';
            }

            // ✅ Redirect sukses
            return redirect()
                ->route('peternak.anakan.index')
                ->with('success', $message ?? 'Anakan berhasil ditambahkan.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Validation failed - redirect back with validation messages and old input
            return back()
                ->withErrors($e->errors())
                ->withInput();
        } catch (\Exception $e) {
            // Log unexpected exception and return friendly error
            Log::error('[AnakanController::store] Unexpected error when storing anakan', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'user_id' => Auth::id(),
            ]);

            return back()->with('error', 'Terjadi kesalahan saat menyimpan anakan. Silakan coba lagi.')->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id): View
    {
        $peternak = Auth::user()->peternak;

        $anakan = $peternak->anakans()
            ->with(['kandang.indukanJantan', 'kandang.indukanBetina', 'perkawinan'])
            ->findOrFail($id);

        $anakan->age = $this->anakanService->calculateAge($anakan->tanggal_lahir);
        $siblings = $anakan->siblings();

        return view('peternak.anakan.detail', compact('anakan', 'siblings'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAnakanRequest $request, string $id): JsonResponse
    {
        $peternak = Auth::user()->peternak;
        $anakan = $peternak->anakans()->findOrFail($id);

        $success = $this->anakanService->updateAnakan($anakan, $request->validated());

        if ($success) {
            return response()->json([
                'success' => true,
                'message' => 'Data anakan berhasil diperbarui.',
                'anakan' => $anakan->fresh(),
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Gagal memperbarui data anakan.',
        ], 500);
    }

    /**
     * Update anakan status
     */
    public function updateStatus(UpdateStatusAnakanRequest $request, string $id): JsonResponse
    {
        $peternak = Auth::user()->peternak;
        $anakan = $peternak->anakans()->findOrFail($id);

        $success = $this->anakanService->updateStatus($anakan, $request->validated());

        if ($success) {
            return response()->json([
                'success' => true,
                'message' => 'Status anakan berhasil diperbarui.',
                'anakan' => $anakan->fresh(),
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Gagal memperbarui status anakan.',
        ], 500);
    }

    /**
     * Sell anakan
     */
    public function sell(SellAnakanRequest $request, string $id): RedirectResponse
    {
        $peternak = Auth::user()->peternak;
        $anakan = $peternak->anakans()->findOrFail($id);

        if ($anakan->status_penjualan === 'terjual') {
            return redirect()->back()
                ->with('error', 'Anakan ini sudah terjual.');
        }

        $success = $this->anakanService->sellAnakan($anakan, $request->validated());

        if ($success) {
            return redirect()->route('peternak.anakan.index')
                ->with('success', "Anakan {$anakan->nomor_ring} berhasil dijual dan transaksi telah dicatat.");
        }

        return redirect()->back()
            ->with('error', 'Gagal memproses penjualan anakan.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
{
    $peternak = Auth::user()->peternak;

    $anakan = $peternak->anakans()->findOrFail($id);

    try{
        $this->anakanService->deleteAnakan($anakan);

        return redirect()->back()->with('success', 'Anakan berhasil dihapus.');
    }catch(\Exception $e){
        return redirect()->back()->with('error','Gagal menghapus anakan: '.$e->getMessage());
    }
}

}
