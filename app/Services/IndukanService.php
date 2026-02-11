<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Indukan;
use App\Models\Peternak;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Storage;

use Carbon\Carbon;

class IndukanService
{
    public function __construct(
        private Indukan $indukan
    ) {}

    /**
     * Upload foto indukan (konsisten dengan AnakanService)
     */
    private function uploadPhoto($file, int $peternakId): ?string
    {
        if (! $file) {
            return null;
        }

        return $file->store("indukan/{$peternakId}", 'public');
    }

    public function getIndukanWithBreeding(Indukan $indukan): Indukan
{
    return $indukan->load([
        'anakansSebagaiJantan.perkawinan',
        'anakansSebagaiBetina.perkawinan',
        'kandangsJantan',
        'kandangsBetina',

        // 🔥 INI YANG KURANG
        'perkawinansJantan.anakans',
        'perkawinansBetina.anakans',
        'perkawinansJantan.indukanBetina',
        'perkawinansBetina.indukanJantan',
    ]);
}

public function calculatePerformaGlobal(Indukan $indukan): array
{
    $allPerkawinans = collect()
        ->merge($indukan->perkawinansJantan ?? collect())
        ->merge($indukan->perkawinansBetina ?? collect());

    $totalTrip = $allPerkawinans->count();
    $berhasil = $allPerkawinans->where('status', 'berhasil')->count();
    $gagal = $allPerkawinans->where('status', 'gagal')->count();
    $totalAnakan = $allPerkawinans->sum(fn($p) => $p->anakans->count());

    $successRate = $totalTrip > 0
        ? round(($berhasil / $totalTrip) * 100)
        : 0;

    $avgAnakan = $totalTrip > 0
        ? round($totalAnakan / $totalTrip, 2)
        : 0;

    return [
        'total_trip' => $totalTrip,
        'berhasil' => $berhasil,
        'gagal' => $gagal,
        'total_anakan' => $totalAnakan,
        'success_rate' => $successRate,

    ];
}



    public function calculateAge(Carbon $tanggalLahir): array
    {
        $months = $tanggalLahir->diffInMonths(now());

        if ($months < 12) {
            $formatted = "{$months} bulan";
        } else {
            $years = round($months / 12, 1);
            $formatted = str_replace('.', ',', (string) $years) . ' tahun';
        }

        return [
            'months' => $months,
            'formatted' => $formatted,
        ];
    }

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
     * Create new indukan + upload foto
     */
public function createIndukan(Peternak $peternak, array $data): Indukan
{
    $data['peternak_id'] = $peternak->id;
    if (!empty($data['foto_indukan'])) {
        $data['foto_path'] = $this->uploadPhoto($data['foto_indukan'], $peternak->id);
        unset($data['foto_indukan']);
    }
        $data['aktif_kicau']            = request()->boolean('aktif_kicau');
    $data['mendekati_betina']       = request()->boolean('mendekati_betina');
    $data['nafsu_makan_meningkat']  = request()->boolean('nafsu_makan_meningkat');
    $data['aktif_buat_sarang']      = request()->boolean('aktif_buat_sarang');
    return $this->indukan->create($data);
}

public function updateFotoIndukan(Indukan $indukan, $file): bool
{
    // hapus foto lama
    if ($indukan->foto_path && Storage::disk('public')->exists($indukan->foto_path)) {
        Storage::disk('public')->delete($indukan->foto_path);
    }

    // upload baru
    $path = $file->store("peternak/{$indukan->peternak_id}/indukan", 'public');

    return $indukan->update([
        'foto_path' => $path
    ]);
}




    /**
     * Update indukan + replace foto lama jika ada
     */
    public function updateIndukan(Indukan $indukan, array $data): bool
    {
        // Jika ada foto baru → hapus foto lama + upload baru
        if (request()->hasFile('foto_indukan')) {

            // Hapus foto lama
            if ($indukan->foto_path && Storage::disk('public')->exists($indukan->foto_path)) {
                Storage::disk('public')->delete($indukan->foto_path);
            }

            // Upload foto baru
            $data['foto_path'] = $this->uploadPhoto(
                request()->file('foto_indukan'),
                $indukan->peternak_id
            );
        }
          $data['aktif_kicau']              = request()->boolean('aktif_kicau');
    $data['mendekati_betina']         = request()->boolean('mendekati_betina');
    $data['nafsu_makan_meningkat']    = request()->boolean('nafsu_makan_meningkat');
    $data['aktif_buat_sarang']        = request()->boolean('aktif_buat_sarang');

        return $indukan->update($data);
    }

    /**
     * Delete indukan
     */
    public function deleteIndukan(Indukan $indukan): bool
    {
        // Hapus foto ketika indukan dihapus
        if ($indukan->foto_path && Storage::disk('public')->exists($indukan->foto_path)) {
            Storage::disk('public')->delete($indukan->foto_path);
        }

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
