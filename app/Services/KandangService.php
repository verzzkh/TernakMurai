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
        private Kandang $kandang
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

        return $this->kandang->create($data);
    }

    /**
     * Update kandang
     */
    public function updateKandang(Kandang $kandang, array $data): bool
    {
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
        $menetasCount = $this->kandang->where('peternak_id', $peternak->id)
            ->where('status', 'menetas')->count();

        return [
            'total' => $totalKandang,
            'kosong' => $kosongCount,
            'bertelur' => $bertelurCount,
            'mengeram' => $mengeramCount,
            'menetas' => $menetasCount,
        ];
    }

    /**
     * Get available indukan for kandang
     */
    public function getAvailableIndukan(Peternak $peternak): array
    {
        $indukanJantan = $peternak->indukans()
            ->where('jenis_kelamin', 'jantan')
            ->orderBy('nomor_ring')
            ->get();

        $indukanBetina = $peternak->indukans()
            ->where('jenis_kelamin', 'betina')
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

    /**
     * Update kandang status
     */
    public function updateStatus(Kandang $kandang, string $status): bool
    {
        return $kandang->update(['status' => $status]);
    }
}
