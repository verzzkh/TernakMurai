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

    protected $fillable = [
        'peternak_id',
        'kandang_id',
        'perkawinan_id',
        'nomor_ring',
        'tanggal_lahir',
        'jenis_kelamin',
        'status_pertumbuhan',
        'deskripsi_karakteristik',
        'catatan_perubahan',
        'catatan_penjualan',
        'harga',
        'status_penjualan',
        'tanggal_jual',
        'foto_path',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_lahir' => 'date',
            'tanggal_jual' => 'date',
            'catatan_perubahan' => 'array',
        ];
    }

    public function peternak(): BelongsTo
    {
        return $this->belongsTo(Peternak::class);
    }

    public function kandang(): BelongsTo
    {
        return $this->belongsTo(Kandang::class);
    }

    public function perkawinan(): BelongsTo
    {
        return $this->belongsTo(Perkawinan::class);
    }

    public function transaksis()
    {
        return $this->hasMany(Transaksi::class);
    }

    /**
     * Calculate age from tanggal_lahir
     */
    public function getAgeAttribute(): array
    {
        if (! $this->tanggal_lahir) {
            return ['days' => 0, 'months' => 0, 'formatted' => 'Tidak diketahui'];
        }

        $birthDate = Carbon::parse($this->tanggal_lahir);
        $now = Carbon::now();

        $days = $birthDate->diffInDays($now);
        $months = $birthDate->diffInMonths($now);

        $formatted = $days < 30 ? "{$days} hari" : "{$months} bulan";

        return [
            'days' => $days,
            'months' => $months,
            'formatted' => $formatted,
        ];
    }

    /**
     * Check if anakan is active (not sold)
     */
    public function isActive(): bool
    {
        return $this->status_penjualan === 'belum_dijual';
    }

    /**
     * Get siblings (anakan from same perkawinan)
     */
    public function siblings()
    {
        if (! $this->perkawinan_id) {
            return collect();
        }

        return static::where('perkawinan_id', $this->perkawinan_id)
            ->where('id', '!=', $this->id)
            ->get();
    }
}
