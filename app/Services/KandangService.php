<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Kandang;
use App\Models\Peternak;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;


class KandangService
{


    public function __construct(
        private Kandang $kandang,
        private BreedingLifecycleService $breedingLifecycleService,
        private PairingService $pairingService
    ) {}

    /**
     * Get paginated kandang for peternak
     */
    public function getPaginatedKandang(Peternak $peternak, array $filters = []): LengthAwarePaginator
    {
        $query = $this->kandang->where('peternak_id', $peternak->id)
            ->with(['indukanJantan', 'indukanBetina', 'anakans']);

        // Apply filters
        if (! empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('nomor_kandang', 'like', "%{$search}%")
                    ->orWhere('deskripsi_kandang', 'like', "%{$search}%");
            });
        }

        if (! empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (! empty($filters['sort_by'])) {
            $sortDirection = $filters['sort_direction'] ?? 'desc';
            $query->orderBy($filters['sort_by'], $sortDirection);
        } else {
            $query->latest();
        }

        return $query->paginate(12);
    }

    /**
     * Get kandang by ID for peternak
     */
    public function getKandangById(Peternak $peternak, int $id): ?Kandang
    {
        return $this->kandang->where('peternak_id', $peternak->id)
            ->with(['indukanJantan', 'indukanBetina', 'anakans', 'perkawinans'])
            ->find($id);
    }

    /**
     * Create new kandang
     */
    public function createKandang(Peternak $peternak, array $data): Kandang
    {
        $data['peternak_id'] = $peternak->id;

        if (
            ($data['status'] ?? null) === BreedingLifecycleService::STATUS_BERTELUR
            && !empty($data['indukan_jantan_id'])
            && !empty($data['indukan_betina_id'])
        ) {
            $this->pairingService->ensureCanStartBreeding(
                $peternak->id,
                (int) $data['indukan_jantan_id'],
                (int) $data['indukan_betina_id']
            );
        }

        return $this->kandang->create($data);
    }

    /**
     * Update status kandang
     */
    public function updateKandang(Kandang $kandang, array $data): bool
    {
        unset($data['status']);

        return $kandang->update($data);
    }

    /**
     * Get all indukan for peternak
     */

public function getAvailableIndukanForEdit(Peternak $peternak, Kandang $currentKandang): array
{
    // Ambil semua ID indukan yang sedang aktif di kandang NON-kosong
    $busyJantanIds = Kandang::where('peternak_id', $peternak->id)
        ->whereIn('status', ['bertelur', 'mengeram'])
        ->whereNotNull('indukan_jantan_id')
        ->where('id', '!=', $currentKandang->id)
        ->pluck('indukan_jantan_id');

    $busyBetinaIds = Kandang::where('peternak_id', $peternak->id)
        ->whereIn('status', ['bertelur', 'mengeram'])
        ->whereNotNull('indukan_betina_id')
        ->where('id', '!=', $currentKandang->id)
        ->pluck('indukan_betina_id');

    // Ambil semua jantan yang tidak sedang sibuk atau jantan di kandang ini
    $indukanJantan = $peternak->indukans()
        ->where('jenis_kelamin', 'jantan')
        ->where(function ($q) use ($busyJantanIds, $currentKandang) {
            $q->whereNotIn('id', $busyJantanIds)
              ->orWhere('id', $currentKandang->indukan_jantan_id);
        })
        ->orderBy('nomor_ring')
        ->get();

    // Ambil semua betina yang tidak sedang sibuk atau betina di kandang ini
    $indukanBetina = $peternak->indukans()
        ->where('jenis_kelamin', 'betina')
        ->where(function ($q) use ($busyBetinaIds, $currentKandang) {
            $q->whereNotIn('id', $busyBetinaIds)
              ->orWhere('id', $currentKandang->indukan_betina_id);
        })
        ->orderBy('nomor_ring')
        ->get();

    return [
        'jantan' => $indukanJantan,
        'betina' => $indukanBetina,
    ];
}


    /**
     * Update kandang with rolling indukan
     */
public function updateKandangWithRolling(Kandang $kandang, array $data): bool
{
    unset($data['status']);

    // Simpan pasangan lama
    $oldJantan = $kandang->indukan_jantan_id;
    $oldBetina = $kandang->indukan_betina_id;

    // === Rolling Betina ===
    if (!empty($data['indukan_betina_id']) && $data['indukan_betina_id'] != $oldBetina) {
        // Cari kandang lain yang pakai betina baru
        $otherKandang = Kandang::where('peternak_id', $kandang->peternak_id)
            ->where('id', '!=', $kandang->id)
            ->where('indukan_betina_id', $data['indukan_betina_id'])
            ->first();

        if ($otherKandang) {
            // Tukar betina antar kandang
            $otherKandang->update(['indukan_betina_id' => $oldBetina]);
        }
    }

    // === Rolling Jantan ===
    if (!empty($data['indukan_jantan_id']) && $data['indukan_jantan_id'] != $oldJantan) {
        // Cari kandang lain yang pakai jantan baru
        $otherKandang = Kandang::where('peternak_id', $kandang->peternak_id)
            ->where('id', '!=', $kandang->id)
            ->where('indukan_jantan_id', $data['indukan_jantan_id'])
            ->first();

        if ($otherKandang) {
            // Tukar jantan antar kandang
            $otherKandang->update(['indukan_jantan_id' => $oldJantan]);
        }
    }

    // Update data kandang ini
    return $kandang->update($data);
}



    /**
     * Delete kandang
     */
    public function deleteKandang(Kandang $kandang): bool
    {
        return $kandang->delete();
    }

    /**
     * Get kandang statistics for peternak
     */
    public function getKandangStats(Peternak $peternak): array
    {
        $totalKandang = $this->kandang->where('peternak_id', $peternak->id)->count();
        $kosongCount = $this->kandang->where('peternak_id', $peternak->id)
            ->where('status', 'kosong')->count();
        $bertelurCount = $this->kandang->where('peternak_id', $peternak->id)
            ->where('status', 'bertelur')->count();
        $mengeramCount = $this->kandang->where('peternak_id', $peternak->id)
            ->where('status', 'mengeram')->count();
        return [
            'total' => $totalKandang,
            'kosong' => $kosongCount,
            'bertelur' => $bertelurCount,
            'mengeram' => $mengeramCount,
        ];
    }

    /**
     * Get available indukan for kandang
     */
    public function getAvailableIndukan(Peternak $peternak): array
{
    // Ambil ID semua indukan yang sedang aktif di kandang lain
    $usedJantanIds = $peternak->kandangs()
        ->whereNotNull('indukan_jantan_id')
        ->pluck('indukan_jantan_id')
        ->toArray();

    $usedBetinaIds = $peternak->kandangs()
        ->whereNotNull('indukan_betina_id')
        ->pluck('indukan_betina_id')
        ->toArray();

    // Filter jantan & betina yang BELUM dipakai di kandang manapun
    $indukanJantan = $peternak->indukans()
        ->where('jenis_kelamin', 'jantan')
        ->whereNotIn('id', $usedJantanIds)
        ->orderBy('nomor_ring')
        ->get();

    $indukanBetina = $peternak->indukans()
        ->where('jenis_kelamin', 'betina')
        ->whereNotIn('id', $usedBetinaIds)
        ->orderBy('nomor_ring')
        ->get();

    return [
        'jantan' => $indukanJantan,
        'betina' => $indukanBetina,
    ];
}


    /**
     * Get kandang with anakan count
     */
    public function getKandangWithAnakanCount(Peternak $peternak): Collection
    {
        return $this->kandang->where('peternak_id', $peternak->id)
            ->withCount(['anakans'])
            ->with(['indukanJantan', 'indukanBetina'])
            ->orderBy('nomor_kandang')
            ->get();
    }

    public function getPerformaPasanganAktif(Kandang $kandang): array
{
    if (!$kandang->indukan_jantan_id || !$kandang->indukan_betina_id) {
        return [
            'total_trip' => 0,
            'berhasil' => 0,
            'gagal' => 0,
            'success_rate' => 0,
            'rata_anakan' => 0,
        ];
    }

    $perkawinans = \App\Models\Perkawinan::where('peternak_id', $kandang->peternak_id)
        ->where('indukan_jantan_id', $kandang->indukan_jantan_id)
        ->where('indukan_betina_id', $kandang->indukan_betina_id)
        ->withCount('anakans')
        ->get();

    $totalTrip = $perkawinans->count();
    $berhasil = $perkawinans->where('status', 'berhasil')->count();
    $gagal = $perkawinans->where('status', 'gagal')->count();
    $totalAnakan = $perkawinans->sum('anakans_count');

    $successRate = $totalTrip > 0
        ? round(($berhasil / $totalTrip) * 100)
        : 0;

    $rataAnakan = $totalTrip > 0
        ? round($totalAnakan / $totalTrip, 1)
        : 0;

    return [
        'total_trip' => $totalTrip,
        'berhasil' => $berhasil,
        'gagal' => $gagal,
        'success_rate' => $successRate,
        'rata_anakan' => $rataAnakan,
    ];
}


   public function storeAnakanFromKandang(array $data, Kandang $kandang, int $peternakId)
{
    $result = $this->breedingLifecycleService->recordSuccessfulTripWithAnakan($kandang, $peternakId, $data);

    return [
        'success' => true,
        'message' => 'Berhasil menambahkan ' . count($result['anakans']) . ' anakan pada Trip #' . $result['perkawinan']->nomor_trip,
        'data' => $result['anakans'],
    ];
}

}
