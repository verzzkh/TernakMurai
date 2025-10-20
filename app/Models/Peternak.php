<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Peternak extends Model
{
    use HasFactory;

    protected $table = 'peternak';

    protected $fillable = [
        'user_id',
        'nama_peternakan',
        'alamat',
        'nomor_handphone',
        'foto_profil',
        'jenis_akun',
        'pro_berlaku_hingga',
        'periode_deteksi',
        'deteksi_terpakai',
    ];

    protected function casts(): array
    {
        return [
            'pro_berlaku_hingga' => 'date',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function indukans(): HasMany
    {
        return $this->hasMany(Indukan::class);
    }

    public function kandangs(): HasMany
    {
        return $this->hasMany(Kandang::class);
    }

    public function anakans(): HasMany
    {
        return $this->hasMany(Anakan::class);
    }

    public function transaksis(): HasMany
    {
        return $this->hasMany(Transaksi::class);
    }

    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class);
    }

    public function deteksiPenyakits(): HasMany
    {
        return $this->hasMany(DeteksiPenyakit::class);
    }

    /**
     * Check if peternak has pro account and it's still valid
     */
    public function isPro(): bool
    {
        return $this->jenis_akun === 'pro' &&
               ($this->pro_berlaku_hingga === null || $this->pro_berlaku_hingga >= now());
    }

    /**
     * Check if peternak can add more anakan (free account limit: 20)
     */
    public function canAddAnakan(): bool
    {
        if ($this->isPro()) {
            return true;
        }

        return $this->anakans()->where('status_penjualan', 'belum_dijual')->count() < 20;
    }

    /**
     * Get count of active anakan (not sold)
     */
    public function getActiveAnakanCount(): int
    {
        return $this->anakans()->where('status_penjualan', 'belum_dijual')->count();
    }

    /**
     * Get remaining anakan slots for free accounts
     */
    public function getRemainingAnakanSlots(): int
    {
        if ($this->isPro()) {
            return -1; // Unlimited
        }

        return max(0, 20 - $this->getActiveAnakanCount());
    }
}
