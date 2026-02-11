<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Kandang;
use App\Models\Peternak;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;


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
     * Update status kandang
     */
    public function updateKandang(Kandang $kandang, array $data): bool
    {
        if (isset($data['status'])) {
            $kandang->status = $data['status'];
        }
        return $kandang->update($data);
    }

    /**
     * Get all indukan for peternak
     */

public function getAvailableIndukanForEdit(Peternak $peternak, Kandang $currentKandang): array
{
    // Ambil semua ID indukan yang sedang aktif di kandang NON-kosong
    $busyJantanIds = Kandang::where('peternak_id', $peternak->id)
        ->whereIn('status', ['bertelur', 'mengeram', 'menetas'])
        ->whereNotNull('indukan_jantan_id')
        ->where('id', '!=', $currentKandang->id)
        ->pluck('indukan_jantan_id');

    $busyBetinaIds = Kandang::where('peternak_id', $peternak->id)
        ->whereIn('status', ['bertelur', 'mengeram', 'menetas'])
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
    // 1) Buat Perkawinan (trip) BARU di luar transaksi anakan
    $perkawinan = \App\Models\Perkawinan::create([
        'peternak_id'        => $peternakId,                         // pastikan sudah ada di $fillable
        'kandang_id'         => $kandang->id,
        'indukan_jantan_id'  => $kandang->indukan_jantan_id,
        'indukan_betina_id'  => $kandang->indukan_betina_id,
           'nomor_trip'         => \App\Models\Perkawinan::generateNomorTripPasangan(
        $peternakId,
        $kandang->indukan_jantan_id,
        $kandang->indukan_betina_id
    ),
    'tanggal_kawin'      => $data['tanggal_lahir'], // ✅ PENTING
       'catatan'            => 'Perkawinan otomatis dibuat saat menetas (' . $data['tanggal_lahir'] . ')',
    ]);

    // 2) Lengkapi data untuk anakan
    $data['perkawinan_id']   = $perkawinan->id;
    $data['kandang_id']      = $kandang->id;
    $data['sumber_anakan']   = 'internal';

    // Normalisasi jumlah & jenis_kelamin
    $jumlah         = (int) ($data['jumlah_anakan'] ?? 1);
    $tanggalLahir   = $data['tanggal_lahir'] ?? now();

    $jenisKelaminRaw  = $data['jenis_kelamin'] ?? 'tidak_diketahui';
    $jenisKelaminList = is_array($jenisKelaminRaw) ? array_values($jenisKelaminRaw) : [$jenisKelaminRaw];

    // Normalisasi foto -> array
    $fotoList = request()->hasFile('foto_anakan')
        ? (is_array(request()->file('foto_anakan')) ? array_values(request()->file('foto_anakan')) : [request()->file('foto_anakan')])
        : [];

    // 3) Transaksi hanya untuk insert anak & update status kandang
    DB::beginTransaction();
    try {
        $inserted = [];

        for ($i = 0; $i < $jumlah; $i++) {
    $fotoPath = null;
    if (!empty($fotoList[$i])) {
        $fotoPath = $fotoList[$i]->store("anakans/{$peternakId}", 'public');
    }

    // Ambil nomor ring sesuai indeks (ingat: form dimulai dari [1])
    $nomorRing = null;
    if (isset($data['nomor_ring']) && is_array($data['nomor_ring'])) {
        $nomorRing = $data['nomor_ring'][$i + 1] ?? null;
    } elseif (isset($data['nomor_ring']) && is_string($data['nomor_ring'])) {
        $nomorRing = $data['nomor_ring'];
    }

    $anakan = \App\Models\Anakan::create([
        'peternak_id'             => $peternakId,
        'kandang_id'              => $data['kandang_id'],
        'perkawinan_id'           => $data['perkawinan_id'],
        'indukan_jantan_id'       => $kandang->indukan_jantan_id, // pastikan ikut masuk
        'indukan_betina_id'       => $kandang->indukan_betina_id, // ini juga
        'nomor_ring'              => $nomorRing,
        'tanggal_lahir'           => $tanggalLahir,
        'jenis_kelamin'           => $jenisKelaminList[$i] ?? 'tidak_diketahui',
        'status_pertumbuhan'      => $data['status_pertumbuhan'] ?? 'trotol',
        'deskripsi_karakteristik' => $data['deskripsi_karakteristik'] ?? null,
        'foto_path'               => $fotoPath,
        'sumber_anakan'           => 'internal',
    ]);

    $inserted[] = $anakan;
}

        // Update status kandang → kosong (pakai update/save sesuai fillable)
        if (in_array('status', $kandang->getFillable() ?? [])) {
            $kandang->update(['status' => 'kosong']);
        } else {
            $kandang->status = 'kosong';
            $kandang->save();
        }

        DB::commit();

        return [
            'success' => true,
            'message' => 'Berhasil menambahkan ' . count($inserted) . ' anakan pada Trip #' . $perkawinan->nomor_trip,
            'data'    => $inserted,
        ];
    } catch (\Throwable $th) {
        DB::rollBack();
        return [
            'success' => false,
            'message' => 'Gagal menyimpan anakan: ' . $th->getMessage(),
        ];
    }
}


}
