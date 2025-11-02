<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Indukan;
use App\Models\Peternak;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class IndukanService
{
    public function __construct(
        private Indukan $indukan
    ) {}

    /**
     * Get paginated indukan for peternak
     */
    public function getPaginatedIndukan(Peternak $peternak, array $filters = []): LengthAwarePaginator
    {
        $query = $this->indukan->where('peternak_id', $peternak->id);

        // Apply filters
        if (! empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('nomor_ring', 'like', "%{$search}%")
                    ->orWhere('nama', 'like', "%{$search}%");
            });
        }

        if (! empty($filters['jenis_kelamin'])) {
            $query->where('jenis_kelamin', $filters['jenis_kelamin']);
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
     * Get indukan by ID for peternak
     */
    public function getIndukanById(Peternak $peternak, int $id): ?Indukan
    {
        return $this->indukan->where('peternak_id', $peternak->id)->find($id);
    }

    /**
     * Create new indukan
     */
    public function createIndukan(Peternak $peternak, array $data): Indukan
    {
        $data['peternak_id'] = $peternak->id;

        return $this->indukan->create($data);
    }

    /**
     * Update indukan
     */
    public function updateIndukan(Indukan $indukan, array $data): bool
    {
        return $indukan->update($data);
    }

    /**
     * Delete indukan
     */
    public function deleteIndukan(Indukan $indukan): bool
    {
        return $indukan->delete();
    }

    /**
     * Get indukan statistics for peternak
     */
    public function getIndukanStats(Peternak $peternak): array
    {
        $totalIndukan = $this->indukan->where('peternak_id', $peternak->id)->count();
        $jantanCount = $this->indukan->where('peternak_id', $peternak->id)
            ->where('jenis_kelamin', 'jantan')->count();
        $betinaCount = $this->indukan->where('peternak_id', $peternak->id)
            ->where('jenis_kelamin', 'betina')->count();

        return [
            'total' => $totalIndukan,
            'jantan' => $jantanCount,
            'betina' => $betinaCount,
        ];
    }

    /**
     * Get indukan jantan for dropdown
     */
    public function getIndukanJantan(Peternak $peternak): Collection
    {
        return $this->indukan->where('peternak_id', $peternak->id)
            ->where('jenis_kelamin', 'jantan')
            ->orderBy('nomor_ring')
            ->get();
    }

    /**
     * Get indukan betina for dropdown
     */
    public function getIndukanBetina(Peternak $peternak): Collection
    {
        return $this->indukan->where('peternak_id', $peternak->id)
            ->where('jenis_kelamin', 'betina')
            ->orderBy('nomor_ring')
            ->get();
    }

    /**
     * Get indukan with anakan count
     */
    public function getIndukanWithAnakanCount(Peternak $peternak): Collection
    {
        return $this->indukan->where('peternak_id', $peternak->id)
            ->withCount(['anakans'])
            ->orderBy('nomor_ring')
            ->get();
    }
}
