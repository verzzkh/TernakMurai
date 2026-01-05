<?php

declare(strict_types=1);

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Anakan extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'anakan';

    /**
     * Kolom yang dapat diisi secara mass-assignment.
     */
    protected $fillable = [
        'peternak_id',
        'kandang_id',
        'perkawinan_id',
        'sumber_anakan',              // ✅ kolom baru: internal / eksternal
        'indukan_jantan_id',
        'indukan_betina_id',
        'indukan_jantan_info',
        'indukan_betina_info',
        'nomor_ring',
        'tanggal_lahir',
        'jenis_kelamin',
        'status_pertumbuhan',
        'deskripsi_karakteristik',
        'catatan_perubahan',
        'catatan_penjualan',
        'harga',
        'harga_beli',
        'asal_penjual',
        'status_penjualan',
        'tanggal_jual',
        'foto_path',
    ];

    /**
     * Casting otomatis ke tipe data PHP.
     */
    protected function casts(): array
    {
        return [
            'tanggal_lahir' => 'date',
            'tanggal_jual' => 'date',
            'catatan_perubahan' => 'array',
            'catatan_penjualan' => 'array',
        ];
    }

    /**
     * Relasi ke tabel Peternak.
     */
    public function peternak(): BelongsTo
    {
        return $this->belongsTo(Peternak::class);
    }

    /**
     * Relasi ke tabel Kandang.
     */
    public function kandang(): BelongsTo
    {
        return $this->belongsTo(Kandang::class);
    }

    /**
     * Relasi ke tabel Perkawinan.
     */
    public function perkawinan(): BelongsTo
    {
        return $this->belongsTo(Perkawinan::class);
    }

    /**
     * Relasi ke transaksi (jika ada log jual beli atau catatan lain).
     */
    public function transaksis()
    {
        return $this->hasMany(Transaksi::class);
    }

    /**
     * Attribute tambahan: umur anakan.
     */
   public function getAgeAttribute(): array
{
    if (! $this->tanggal_lahir) {
        return [
            'days'      => 0,
            'months'    => 0,
            'years'     => 0,
            'formatted' => 'Tidak diketahui',
        ];
    }

    $birthDate = Carbon::parse($this->tanggal_lahir);
    $now = Carbon::now();

    $days   = $birthDate->diffInDays($now);
    $months = $birthDate->diffInMonths($now);
    $years  = $birthDate->diffInYears($now);

    // PAKSA bulan menjadi integer selalu
    $months = intval($months);

    // Jika umur < 1 tahun → pakai bulan
    if ($years < 1) {
        return [
            'days'      => $days,
            'months'    => $months,
            'years'     => 0,
            'formatted' => $months . ' bulan',
        ];
    }

    // Jika umur >= 1 tahun → 1 angka di belakang koma
    $yearsDecimal = round($days / 365, 1);

    return [
        'days'      => $days,
        'months'    => $months,
        'years'     => $yearsDecimal,
        'formatted' => $yearsDecimal . ' tahun',
    ];
}




    /**
     * Cek apakah anakan masih aktif (belum dijual).
     */
    public function isActive(): bool
    {
        return $this->status_penjualan === 'belum_dijual' || is_null($this->status_penjualan);
    }

    /**
     * Ambil saudara (anakan dari perkawinan yang sama).
     */
    public function siblings()
    {
        if (!$this->perkawinan_id) {
            return collect();
        }

        return static::where('perkawinan_id', $this->perkawinan_id)
            ->where('id', '!=', $this->id)
            ->get();
    }

    /**
     * Scope untuk filter berdasarkan sumber anakan.
     */
    public function scopeSumber($query, string $sumber)
    {
        return $query->where('sumber_anakan', $sumber);
    }

    public function getFotoUrlAttribute(): ?string
    {
        if (!$this->foto_path) {
            return asset('images/default-bird.png'); // fallback default image
        }

        return asset('storage/' . $this->foto_path);
    }
}
