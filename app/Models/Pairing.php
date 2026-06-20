<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Pairing extends Model
{
    use HasFactory;

    public const STATUS_AKTIF = 'aktif';
    public const STATUS_DIHENTIKAN = 'dihentikan';

    protected $table = 'pairings';

    protected $fillable = [
        'peternak_id',
        'indukan_jantan_id',
        'indukan_betina_id',
        'status',
        'last_historical_change_at',
        'last_analysis_id',
    ];

    protected function casts(): array
    {
        return [
            'last_historical_change_at' => 'datetime',
        ];
    }

    public function peternak(): BelongsTo
    {
        return $this->belongsTo(Peternak::class);
    }

    public function indukanJantan(): BelongsTo
    {
        return $this->belongsTo(Indukan::class, 'indukan_jantan_id');
    }

    public function indukanBetina(): BelongsTo
    {
        return $this->belongsTo(Indukan::class, 'indukan_betina_id');
    }

    public function perkawinans(): HasMany
    {
        return $this->hasMany(Perkawinan::class);
    }

    public function analyses(): HasMany
    {
        return $this->hasMany(HasilAnalisaBreeding::class, 'pairing_id');
    }

    public function lastAnalysis(): BelongsTo
    {
        return $this->belongsTo(HasilAnalisaBreeding::class, 'last_analysis_id');
    }

    public function isAktif(): bool
    {
        return $this->status === self::STATUS_AKTIF;
    }

    public function isDihentikan(): bool
    {
        return $this->status === self::STATUS_DIHENTIKAN;
    }
}
