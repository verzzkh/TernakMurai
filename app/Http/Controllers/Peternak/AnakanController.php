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
        $anakans->getCollection()->transform(function ($anakan) {
            $anakan->age = $this->anakanService->calculateAge($anakan->tanggal_lahir);

            return $anakan;
        });

        return view('peternak.anakan.index', compact('anakans', 'peternak'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request): View|RedirectResponse
    {
        $peternak = Auth::user()->peternak;

        // Check if can add anakan (free account limit)
        if (! $peternak->canAddAnakan()) {
            return redirect()->route('peternak.anakan.index')
                ->with('error', 'Akun gratis hanya dapat menambah maksimal 20 anakan aktif. Upgrade ke Pro untuk menambah lebih banyak.');
        }

        // Get kandangs with status 'menetas'
        $kandangs = $peternak->kandangs()
            ->where('status', 'menetas')
            ->with(['indukanJantan', 'indukanBetina'])
            ->get();

        // Get indukan if indukan_id is provided
        $selectedIndukan = null;
        if ($request->has('indukan_id')) {
            $selectedIndukan = $peternak->indukans()->find($request->get('indukan_id'));
        }

        // Get kandang if kandang_id is provided
        $selectedKandang = null;
        if ($request->has('kandang_id')) {
            $selectedKandang = $peternak->kandangs()->find($request->get('kandang_id'));
        }

        return view('peternak.anakan.create', compact('kandangs', 'peternak', 'selectedIndukan', 'selectedKandang'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $peternak = Auth::user()->peternak;

        // Check limit for free accounts
        if (! $peternak->canAddAnakan()) {
            return redirect()->back()
                ->with('error', 'Akun gratis hanya dapat menambah maksimal 20 anakan aktif. Upgrade ke Pro untuk menambah lebih banyak.');
        }

        try {
            $sumberAnakan = $request->input('sumber_anakan');

            if ($sumberAnakan === 'peternakan') {
                $validatedData = app(StoreAnakanDariKandangRequest::class)->validated();
                $result = $this->anakanService->storeFromKandang($validatedData, $peternak->id);

                if (is_array($result)) {
                    $message = "Berhasil menambahkan {$result[0]->jumlah_anakan} anakan dari kandang.";
                } else {
                    $message = 'Berhasil menambahkan anakan dari kandang.';
                }
            } else {
                $validatedData = app(StoreAnakanDariLuarRequest::class)->validated();
                $this->anakanService->storeFromLuar($validatedData, $peternak->id);
                $message = 'Berhasil menambahkan anakan dari luar.';
            }

            return redirect()->route('peternak.anakan.index')
                ->with('success', $message);

        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat menyimpan anakan: '.$e->getMessage());
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
    public function destroy(string $id): JsonResponse
    {
        $peternak = Auth::user()->peternak;
        $anakan = $peternak->anakans()->findOrFail($id);

        $success = $this->anakanService->deleteAnakan($anakan);

        if ($success) {
            return response()->json([
                'success' => true,
                'message' => 'Anakan berhasil dihapus.',
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Gagal menghapus anakan.',
        ], 500);
    }
}
