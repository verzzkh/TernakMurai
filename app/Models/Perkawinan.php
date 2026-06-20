<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;

class Perkawinan extends Model
{
    use HasFactory;

    protected $table = 'perkawinan';

    protected $fillable = [
         'peternak_id',        // ✅ tambahkan baris ini
        'pairing_id',
        'kandang_id',
        'indukan_jantan_id',
        'indukan_betina_id',
        'nomor_trip',
        'tanggal_kawin',
        'catatan',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_kawin' => 'date',
        ];
    }

    public function kandang(): BelongsTo
    {
        return $this->belongsTo(Kandang::class);
    }

    public function pairing(): BelongsTo
    {
        return $this->belongsTo(Pairing::class);
    }

    public function indukanJantan(): BelongsTo
    {
        return $this->belongsTo(Indukan::class, 'indukan_jantan_id');
    }

    public function indukanBetina(): BelongsTo
    {
        return $this->belongsTo(Indukan::class, 'indukan_betina_id');
    }

    public function anakans(): HasMany
    {
        return $this->hasMany(Anakan::class);
    }
    public function peternak(): BelongsTo
{
    return $this->belongsTo(Peternak::class);
}

    public static function generateNomorTrip(int $peternakId): string
    {
        $lastTrip = static::where('peternak_id', $peternakId)
            ->orderByDesc('id')
            ->value('nomor_trip');

        if ($lastTrip && preg_match('/(\d+)$/', (string)$lastTrip, $matches)) {
            $nextNumber = (int) $matches[1] + 1;
        } else {
            $nextNumber = 1;
        }

        return sprintf('TRIP-%03d', $nextNumber);
    }

    public static function generateNomorTripPasangan(
    int $peternakId,
    int $jantanId,
    int $betinaId
): int {

    $lastTrip = static::where('peternak_id', $peternakId)
        ->where('indukan_jantan_id', $jantanId)
        ->where('indukan_betina_id', $betinaId)
        ->max('nomor_trip');

    return $lastTrip ? $lastTrip + 1 : 1;
}


public static function generateNomorTripKandang(int $peternakId, int $kandangId): string
{
    $nextNumber = 1;

    // Ambil data kandang aktif
    $kandang = \App\Models\Kandang::find($kandangId);
    if (! $kandang) {
        return sprintf('TRIP-%03d', $nextNumber);
    }

    // Ambil perkawinan terakhir
    $lastPerkawinan = static::where('peternak_id', $peternakId)
        ->where('kandang_id', $kandangId)
        ->orderByDesc('id')
        ->first();

    // Kalau belum ada, langsung TRIP-001
    if (! $lastPerkawinan) {
        return sprintf('TRIP-%03d', $nextNumber);
    }

    // Cek pasangan lama vs pasangan baru
    $pairChanged = (
        $lastPerkawinan->indukan_jantan_id !== $kandang->indukan_jantan_id ||
        $lastPerkawinan->indukan_betina_id !== $kandang->indukan_betina_id
    );

    // Kalau pasangan sama, lanjutkan
    if (! $pairChanged && preg_match('/TRIP-(\d+)$/', $lastPerkawinan->nomor_trip, $matches)) {
        $nextNumber = (int) $matches[1] + 1;
    } else {
        // Kalau pasangan berbeda → reset ke 1,
        // tapi pastikan tidak bentrok dengan data lama
        $nextNumber = 1;
        $nomorTrip = sprintf('TRIP-%03d', $nextNumber);

        // Kalau TRIP-001 sudah pernah ada di kandang ini (dari pasangan lama), naikkan angka
        while (
            static::where('kandang_id', $kandangId)
                ->where('nomor_trip', $nomorTrip)
                ->exists()
        ) {
            $nextNumber++;
            $nomorTrip = sprintf('TRIP-%03d', $nextNumber);
        }

        return $nomorTrip;
    }

    // Kalau pasangan sama, tetap pastikan tidak duplikat
    $nomorTrip = sprintf('TRIP-%03d', $nextNumber);
    while (
        static::where('kandang_id', $kandangId)
            ->where('nomor_trip', $nomorTrip)
            ->exists()
    ) {
        $nextNumber++;
        $nomorTrip = sprintf('TRIP-%03d', $nextNumber);
    }

    return $nomorTrip;
}



    /**
     * Get count of anakan from this perkawinan
     */
    public function getAnakanCount(): int
    {
        return $this->anakans()->count();
    }

    public function isBerhasil(): bool
{
    return $this->status === 'berhasil';
}

public function isGagal(): bool
{
    return $this->status === 'gagal';
}

    /**
     * Get active anakan count (not sold)
     */
    public function getActiveAnakanCount(): int
    {
        return $this->anakans()->where('status_penjualan', 'belum_dijual')->count();
    }
}
