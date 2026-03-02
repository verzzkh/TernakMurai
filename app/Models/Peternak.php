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
    ];

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
}