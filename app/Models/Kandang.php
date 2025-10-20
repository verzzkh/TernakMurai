<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Kandang extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'kandang';

    protected $fillable = [
        'peternak_id',
        'nomor_kandang',
        'deskripsi_kandang',
        'status',
        'indukan_jantan_id',
        'indukan_betina_id',
    ];

    public function peternak(): BelongsTo
    {
        return $this->belongsTo(Peternak::class);
    }

    public function anakans(): HasMany
    {
        return $this->hasMany(Anakan::class);
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
}
