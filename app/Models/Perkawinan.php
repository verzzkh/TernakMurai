<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Perkawinan extends Model
{
    use HasFactory;

    protected $table = 'perkawinan';

    protected $fillable = [
        'kandang_id',
        'indukan_jantan_id',
        'indukan_betina_id',
        'nomor_trip',
        'tanggal_kawin',
        'catatan',
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

    /**
     * Get count of anakan from this perkawinan
     */
    public function getAnakanCount(): int
    {
        return $this->anakans()->count();
    }

    /**
     * Get active anakan count (not sold)
     */
    public function getActiveAnakanCount(): int
    {
        return $this->anakans()->where('status_penjualan', 'belum_dijual')->count();
    }
}
