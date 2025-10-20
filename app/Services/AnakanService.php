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
     * Store anakan from kandang (breeding)
     */
    public function storeFromKandang(array $validatedData, int $peternakId): Anakan|array
    {
        try {
            DB::beginTransaction();

            $kandang = Kandang::findOrFail($validatedData['kandang_id']);
            $jumlahAnakan = (int) $validatedData['jumlah_anakan'];

            if ($jumlahAnakan === 1) {
                // Single anakan
                $anakan = $this->createSingleAnakan($validatedData, $kandang, $peternakId);
                DB::commit();

                Log::info('[AnakanService] Single anakan created', [
                    'peternak_id' => $peternakId,
                    'anakan_id' => $anakan->id,
                    'ring' => $anakan->nomor_ring,
                ]);

                return $anakan;
            } else {
                // Multiple anakan
                $anakans = $this->createMultipleAnakans($validatedData, $kandang, $peternakId);
                DB::commit();

                Log::info('[AnakanService] Multiple anakan created', [
                    'peternak_id' => $peternakId,
                    'count' => count($anakans),
                    'rings' => collect($anakans)->pluck('nomor_ring')->toArray(),
                ]);

                return $anakans;
            }
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('[AnakanService] Error creating anakan from kandang', [
                'peternak_id' => $peternakId,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            throw $e;
        }
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
            $anakan->harga = $validatedData['harga'];
            $anakan->status_penjualan = 'belum_dijual';
            $anakan->deskripsi_karakteristik = $validatedData['catatan_luar'] ?? null;

            // Handle photo upload
            if (isset($validatedData['foto_anakan_luar'])) {
                $anakan->foto_path = $this->uploadPhoto($validatedData['foto_anakan_luar'], $peternakId);
            }

            $anakan->save();

            // Create purchase transaction if harga_beli provided
            if (isset($validatedData['harga_beli']) && $validatedData['harga_beli'] > 0) {
                $this->createPurchaseTransaction($anakan, $validatedData);
            }

            DB::commit();

            Log::info('[AnakanService] Anakan from luar created', [
                'peternak_id' => $peternakId,
                'anakan_id' => $anakan->id,
                'ring' => $anakan->nomor_ring,
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
            $anakan->jenis_kelamin = $validatedData['jenis_kelamin'];
            $anakan->deskripsi_karakteristik = $validatedData['deskripsi_karakteristik'] ?? $anakan->deskripsi_karakteristik;

            // Regenerate ring number if gender changed from 'tidak_diketahui'
            if ($oldGender === 'tidak_diketahui' && $validatedData['jenis_kelamin'] !== 'tidak_diketahui') {
                $anakan->nomor_ring = $this->generateRingNumber($anakan->peternak_id, $validatedData['jenis_kelamin']);
            }

            // Handle photo update
            if (isset($validatedData['foto_anakan'])) {
                // Delete old photo
                if ($anakan->foto_path) {
                    Storage::disk('public')->delete($anakan->foto_path);
                }
                $anakan->foto_path = $this->uploadPhoto($validatedData['foto_anakan'], $anakan->peternak_id);
            }

            $anakan->save();

            Log::info('[AnakanService] Anakan updated', [
                'anakan_id' => $anakan->id,
                'ring' => $anakan->nomor_ring,
                'gender_changed' => $oldGender !== $validatedData['jenis_kelamin'],
            ]);

            return true;
        } catch (\Exception $e) {
            Log::error('[AnakanService] Error updating anakan', [
                'anakan_id' => $anakan->id,
                'error' => $e->getMessage(),
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

    /**
     * Sell anakan
     */
    public function sellAnakan(Anakan $anakan, array $validatedData): bool
    {
        try {
            DB::beginTransaction();

            $anakan->status_penjualan = 'terjual';
            $anakan->tanggal_jual = $validatedData['tanggal_jual'];
            $anakan->catatan_penjualan = $validatedData['catatan_penjualan'] ?? null;
            $anakan->save();

            // Create sale transaction
            Transaksi::create([
                'peternak_id' => $anakan->peternak_id,
                'tanggal' => $validatedData['tanggal_jual'],
                'tipe' => 'pemasukan',
                'kategori' => 'penjualan_anakan',
                'jumlah' => $validatedData['harga_jual'],
                'nama_item' => "Penjualan {$anakan->nomor_ring}",
                'deskripsi' => $validatedData['catatan_penjualan'] ?? "Penjualan anakan {$anakan->nomor_ring}",
                'anakan_id' => $anakan->id,
                'ring_referensi' => $anakan->nomor_ring,
            ]);

            DB::commit();

            Log::info('[AnakanService] Anakan sold', [
                'anakan_id' => $anakan->id,
                'ring' => $anakan->nomor_ring,
                'harga_jual' => $validatedData['harga_jual'],
            ]);

            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('[AnakanService] Error selling anakan', [
                'anakan_id' => $anakan->id,
                'error' => $e->getMessage(),
            ]);

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
    private function createPurchaseTransaction(Anakan $anakan, array $validatedData): void
    {
        Transaksi::create([
            'peternak_id' => $anakan->peternak_id,
            'tanggal' => $validatedData['tanggal_pembelian'],
            'tipe' => 'pengeluaran',
            'kategori' => 'pengeluaran_lainnya',
            'jumlah' => $validatedData['harga_beli'],
            'nama_item' => "Pembelian {$anakan->nomor_ring}",
            'deskripsi' => "Pembelian anakan dari {$validatedData['asal_penjual']}",
            'anakan_id' => $anakan->id,
            'ring_referensi' => $anakan->nomor_ring,
        ]);
    }
}
