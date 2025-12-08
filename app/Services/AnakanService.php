<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Anakan;
use App\Models\Kandang;
use App\Models\Transaksi;
use Carbon\Carbon;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AnakanService
{
    /**
     * Generate unique ring number for peternak
     */
    public function generateRingNumber(int $peternakId, string $jenisKelamin): string
    {
        $prefix = match ($jenisKelamin) {
            'jantan' => 'MB-J',
            'betina' => 'MB-B',
            default => 'MB-X'
        };

        // Get the highest ring number for this peternak with this prefix
        $lastRing = Anakan::where('peternak_id', $peternakId)
            ->where('nomor_ring', 'like', $prefix.'-%')
            ->orderBy('nomor_ring', 'desc')
            ->value('nomor_ring');

        if ($lastRing) {
            $lastNumber = (int) substr($lastRing, -3);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        return $prefix.'-'.str_pad((string) $newNumber, 3, '0', STR_PAD_LEFT);
    }

    /**
     * Store anakan from kandang (di dalam ANAKAN)
     */
 public function storeFromKandang(array $data, int $peternakId)
{
    $jumlah = (int) ($data['jumlah_anakan'] ?? 1);
    $tanggalLahir = $data['tanggal_lahir'];

    // Lightweight logging to help diagnose intermittent failures when adding
    // anakans repeatedly from the same kandang. Will log counts of genders
    // and uploaded files present in the request.
    \Illuminate\Support\Facades\Log::info('[AnakanService] storeFromKandang start', [
        'peternak_id' => $peternakId,
        'jumlah' => $jumlah,
        'jenis_kelamin_raw_type' => is_array($data['jenis_kelamin'] ?? null) ? 'array' : 'scalar',
        'request_file_keys' => array_keys(request()->files->all()),
    ]);

    /**
     * 🔍 Normalize jenis_kelamin:
     * kadang dikirim campur — pastikan jadi array berindeks numerik
     */
    $jenisKelaminRaw = $data['jenis_kelamin'] ?? 'tidak_diketahui';
    if (is_array($jenisKelaminRaw)) {
        // Kadang arraynya berbentuk ['jantan', 'betina'] atau [1 => 'jantan', 2 => 'betina']
        $jenisKelaminList = array_values($jenisKelaminRaw);
    } else {
        $jenisKelaminList = [$jenisKelaminRaw];
    }

    /**
     * 🔍 Normalisasi foto juga jadi array
     */
    $fotoList = request()->hasFile('foto_anakan')
        ? (is_array(request()->file('foto_anakan'))
            ? array_values(request()->file('foto_anakan'))
            : [request()->file('foto_anakan')])
        : [];

    $inserted = [];

    for ($i = 0; $i < $jumlah; $i++) {
        $fotoPath = null;
        if (!empty($fotoList[$i])) {
            $fotoPath = $this->uploadPhoto($fotoList[$i], $peternakId);
        }
        $anakan = \App\Models\Anakan::create([
            'peternak_id' => $peternakId,
            'kandang_id' => $data['kandang_id'] ?? null,
            'perkawinan_id' => $data['perkawinan_id'] ?? null,
            'nomor_ring' => $data['nomor_ring'] ?? null,
             'indukan_jantan_id'       => $data['indukan_jantan_id'] ?? null, // ✅ simpan jantan manual dari form
            'indukan_betina_id'       => $data['indukan_betina_id'] ?? null, // ✅ simpan betina manual dari form
            'tanggal_lahir' => $tanggalLahir,
            'jenis_kelamin' => $jenisKelaminList[$i] ?? 'tidak_diketahui', // ✅ sudah pasti string
            'status_pertumbuhan' => $data['status_pertumbuhan'] ?? 'trotol',
            'deskripsi_karakteristik' => $data['deskripsi_karakteristik'] ?? null,
            'foto_path' => $fotoPath,
            'sumber_anakan' => 'internal',
        ]);

        $inserted[] = $anakan;
    }

    return $inserted;
}



    /**
     * Store anakan from luar (purchased)
     */
    public function storeFromLuar(array $validatedData, int $peternakId): Anakan
{
    try {
        DB::beginTransaction();

        $anakan = new Anakan;
        $anakan->peternak_id = $peternakId;
        $anakan->kandang_id = null;
        $anakan->perkawinan_id = null;
        $anakan->nomor_ring = $this->generateRingNumber($peternakId, $validatedData['jenis_kelamin_luar']);
        $anakan->tanggal_lahir = $validatedData['tanggal_lahir'];
        $anakan->jenis_kelamin = $validatedData['jenis_kelamin_luar'];
        $anakan->status_pertumbuhan = $validatedData['status'];
        $anakan->asal_penjual = $validatedData['asal_penjual'];
        $anakan->harga = $validatedData['harga'];
        $anakan->status_penjualan = 'belum_dijual';
        $anakan->deskripsi_karakteristik = $validatedData['catatan_luar'] ?? null;

        // ✅ Tambahkan baris ini:
        $anakan->sumber_anakan = 'eksternal';

        // Handle photo upload
        if (isset($validatedData['foto_anakan_luar'])) {
            $anakan->foto_path = $this->uploadPhoto($validatedData['foto_anakan_luar'], $peternakId);
        }

        $anakan->save();

        DB::commit();

        Log::info('[AnakanService] Anakan from luar created', [
            'peternak_id' => $peternakId,
            'anakan_id' => $anakan->id,
            'ring' => $anakan->nomor_ring,
            'sumber' => $anakan->sumber_anakan,
        ]);

        return $anakan;
    } catch (\Exception $e) {
        DB::rollBack();
        Log::error('[AnakanService] Error creating anakan from luar', [
            'peternak_id' => $peternakId,
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString(),
        ]);
        throw $e;
    }
}


    /**
     * Update anakan information
     */
   public function updateAnakan(Anakan $anakan, array $validatedData): bool
{
    try {
        $oldGender = $anakan->jenis_kelamin;

        // Fields yang boleh di-update via updateAnakan
        $updatableFields = [
            'jenis_kelamin',
            'nomor_ring',
            'harga',
            'deskripsi_karakteristik',
        ];

        // Ambil hanya key yang memang dikirim (array_key_exists) dan tidak null
        $toUpdate = [];
        foreach ($updatableFields as $field) {
            if (array_key_exists($field, $validatedData) && $validatedData[$field] !== null) {
                $toUpdate[$field] = $validatedData[$field];
            }
        }

        // Jika ada nomor_ring yang harus di-generate karena perubahan gender dari 'tidak_diketahui'
        if (
            array_key_exists('jenis_kelamin', $validatedData)
            && $oldGender === 'tidak_diketahui'
            && $validatedData['jenis_kelamin'] !== 'tidak_diketahui'
        ) {
            $toUpdate['nomor_ring'] = $this->generateRingNumber($anakan->peternak_id, $validatedData['jenis_kelamin']);
        }

        // Apply update parsial jika ada
        if (!empty($toUpdate)) {
            $anakan->fill($toUpdate);
        }

        // Handle photo upload (pastikan $validatedData['foto_anakan'] adalah instance UploadedFile)
        if (array_key_exists('foto_anakan', $validatedData) && $validatedData['foto_anakan']) {
            if ($anakan->foto_path) {
                Storage::disk('public')->delete($anakan->foto_path);
            }
            $anakan->foto_path = $this->uploadPhoto($validatedData['foto_anakan'], $anakan->peternak_id);
        }

        $anakan->save();

        Log::info('[AnakanService] Anakan updated', [
            'id' => $anakan->id,
            'updated_fields' => array_keys($toUpdate),
            'validated_keys' => array_keys($validatedData),
        ]);

        return true;
    } catch (\Throwable $e) {
        Log::error('[AnakanService] Error updating anakan', [
            'id' => $anakan->id,
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString(),
        ]);
        return false;
    }
}
    /**
     * Update anakan status
     */
    public function updateStatus(Anakan $anakan, array $validatedData): bool
    {
        try {
            $oldStatus = $anakan->status_pertumbuhan;
            $oldHarga = $anakan->harga;

            $anakan->status_pertumbuhan = $validatedData['status_pertumbuhan'];
            $anakan->harga = $validatedData['harga'];

            // Add to change history
            $changes = $anakan->catatan_perubahan ?? [];
            $changes[] = [
                'tanggal' => now()->toDateString(),
                'status_lama' => $oldStatus,
                'status_baru' => $validatedData['status_pertumbuhan'],
                'harga_lama' => $oldHarga,
                'harga_baru' => $validatedData['harga'],
                'catatan' => $validatedData['catatan_perubahan'] ?? null,
            ];
            $anakan->catatan_perubahan = $changes;

            $anakan->save();

            Log::info('[AnakanService] Anakan status updated', [
                'anakan_id' => $anakan->id,
                'ring' => $anakan->nomor_ring,
                'old_status' => $oldStatus,
                'new_status' => $validatedData['status_pertumbuhan'],
            ]);

            return true;
        } catch (\Exception $e) {
            Log::error('[AnakanService] Error updating anakan status', [
                'anakan_id' => $anakan->id,
                'error' => $e->getMessage(),
            ]);

            return false;
        }
    }

    public function getAnakanStats($peternak)
{
    return [
        'total'  => $peternak->anakans()->count(),
        'jantan' => $peternak->anakans()->where('jenis_kelamin', 'jantan')->count(),
        'betina' => $peternak->anakans()->where('jenis_kelamin', 'betina')->count(),
    ];
}

    /**
     * Sell anakan
     */
    public function sellAnakan(Anakan $anakan, array $data): bool
{
    try {
        $hargaJual = (int) $data['harga_jual'];

        // 1️⃣ Update status penjualan + harga baru
        $anakan->update([
            'status_penjualan' => 'terjual',
            'tanggal_jual'     => $data['tanggal_jual'],
            'catatan_penjualan' => $data['catatan_penjualan'] ?? null,

            // 🔥 Update harga agar sesuai harga jual
            'harga'            => $hargaJual,
        ]);

        // 2️⃣ Catat transaksi pemasukan
        Transaksi::create([
            'peternak_id' => $anakan->peternak_id,
            'nama_item'   => 'Penjualan anakan ' . ($anakan->nomor_ring ?? ''),
            'deskripsi'   => $data['catatan_penjualan'] ?? '-',
            'tipe'        => 'pemasukan',
            'kategori'    => 'penjualan_anakan',
            'jumlah'      => $hargaJual,
            'tanggal'     => $data['tanggal_jual'],
        ]);

        return true;

    } catch (\Exception $e) {
        Log::error("Gagal menjual anakan: " . $e->getMessage());
        return false;
    }
}

    /**
     * Calculate age from birth date
     */
    public function calculateAge(Carbon $tanggalLahir): array
    {
        $now = Carbon::now();
        $days = $tanggalLahir->diffInDays($now);
        $months = $tanggalLahir->diffInMonths($now);

        $formatted = $days < 30 ? "{$days} hari" : "{$months} bulan";

        return [
            'days' => $days,
            'months' => $months,
            'formatted' => $formatted,
        ];
    }

    /**
     * Delete anakan (soft delete)
     */
    public function deleteAnakan(Anakan $anakan): bool
    {
        try {
            // Delete photo file
            if ($anakan->foto_path) {
                Storage::disk('public')->delete($anakan->foto_path);
            }

            $anakan->delete(); // Soft delete

            Log::info('[AnakanService] Anakan deleted', [
                'anakan_id' => $anakan->id,
                'ring' => $anakan->nomor_ring,
            ]);

            return true;
        } catch (\Exception $e) {
            Log::error('[AnakanService] Error deleting anakan', [
                'anakan_id' => $anakan->id,
                'error' => $e->getMessage(),
            ]);

            return false;
        }
    }

    /**
     * Create single anakan from kandang
     */
    private function createSingleAnakan(array $validatedData, Kandang $kandang, int $peternakId): Anakan
    {
        $anakan = new Anakan;
        $anakan->peternak_id = $peternakId;
        $anakan->kandang_id = $kandang->id;
        $anakan->perkawinan_id = $kandang->perkawinans()->latest()->first()?->id;
        $anakan->nomor_ring = $this->generateRingNumber($peternakId, $validatedData['jenis_kelamin']);
        $anakan->tanggal_lahir = $kandang->created_at->toDateString(); // Use kandang creation date
        $anakan->jenis_kelamin = $validatedData['jenis_kelamin'];
        $anakan->status_pertumbuhan = 'trotol';
        $anakan->status_penjualan = 'belum_dijual';
        $anakan->deskripsi_karakteristik = $validatedData['catatan'] ?? null;

        // Handle photo upload
        if (isset($validatedData['foto_anakan'])) {
            $anakan->foto_path = $this->uploadPhoto($validatedData['foto_anakan'], $peternakId);
        }

        $anakan->save();

        return $anakan;
    }

    /**
     * Create multiple anakan from kandang
     */
    private function createMultipleAnakans(array $validatedData, Kandang $kandang, int $peternakId): array
    {
        $anakans = [];
        $jumlahAnakan = (int) $validatedData['jumlah_anakan'];

        for ($i = 1; $i <= $jumlahAnakan; $i++) {
            $anakan = new Anakan;
            $anakan->peternak_id = $peternakId;
            $anakan->kandang_id = $kandang->id;
            $anakan->perkawinan_id = $kandang->perkawinans()->latest()->first()?->id;
            $anakan->nomor_ring = $this->generateRingNumber($peternakId, $validatedData["jenis_kelamin_{$i}"]);
            $anakan->tanggal_lahir = $kandang->created_at->toDateString();
            $anakan->jenis_kelamin = $validatedData["jenis_kelamin_{$i}"];
            $anakan->status_pertumbuhan = 'trotol';
            $anakan->status_penjualan = 'belum_dijual';
            $anakan->deskripsi_karakteristik = $validatedData['catatan'] ?? null;

            // Handle photo upload
            if (isset($validatedData["foto_anakan_{$i}"])) {
                $anakan->foto_path = $this->uploadPhoto($validatedData["foto_anakan_{$i}"], $peternakId);
            }

            $anakan->save();
            $anakans[] = $anakan;
        }

        return $anakans;
    }

    /**
     * Upload photo and return path
     */
    private function uploadPhoto(UploadedFile $file, int $peternakId): string
    {
        $filename = Str::uuid().'.'.$file->getClientOriginalExtension();
        $path = "anakan/{$peternakId}/{$filename}";

        $file->storeAs("anakan/{$peternakId}", $filename, 'public');

        return $path;
    }

    /**
     * Create purchase transaction for anakan from luar
     */
    // Note: createPurchaseTransaction removed because purchase transactions are no
    // longer created automatically. If in future you want to re-enable financial
    // recording, reintroduce a dedicated call site or user-confirmed flow.
}
