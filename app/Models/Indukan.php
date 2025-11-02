<?php

declare(strict_types=1);

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Indukan extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'indukan';

    protected $fillable = [
        'peternak_id',
        'nomor_ring',
        'nama',
        'jenis_kelamin',
        'tanggal_lahir',
        'catatan',
        'prestasi',
        'karakteristik',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_lahir' => 'date',
        ];
    }

    public function peternak(): BelongsTo
    {
        return $this->belongsTo(Peternak::class);
    }

    public function kandangsJantan(): HasMany
    {
        return $this->hasMany(Kandang::class, 'indukan_jantan_id');
    }

    public function kandangsBetina(): HasMany
    {
        return $this->hasMany(Kandang::class, 'indukan_betina_id');
    }

    public function perkawinansJantan(): HasMany
    {
        return $this->hasMany(Perkawinan::class, 'indukan_jantan_id');
    }

    public function perkawinansBetina(): HasMany
    {
        return $this->hasMany(Perkawinan::class, 'indukan_betina_id');
    }

    public function anakans(): HasMany
    {
        return $this->hasMany(Anakan::class, 'perkawinan_id')
            ->whereHas('perkawinan', function ($query) {
                $query->where('indukan_jantan_id', $this->id);
            });
    }

    /**
     * Calculate age from tanggal_lahir
     */
    public function getAgeAttribute(): array
    {
        if (! $this->tanggal_lahir) {
            return ['days' => 0, 'months' => 0, 'years' => 0, 'formatted' => 'Tidak diketahui'];
        }

        $birthDate = Carbon::parse($this->tanggal_lahir);
        $now = Carbon::now();

        $days = $birthDate->diffInDays($now);
        $months = $birthDate->diffInMonths($now);
        $years = $birthDate->diffInYears($now);

        $formatted = $years > 0 ? "{$years} tahun" : ($months > 0 ? "{$months} bulan" : "{$days} hari");

        return [
            'days' => $days,
            'months' => $months,
            'years' => $years,
            'formatted' => $formatted,
        ];
    }

    /**
     * Get all anakan from this indukan (as jantan)
     */
    public function getAllAnakans()
    {
        return Anakan::whereHas('perkawinan', function ($query) {
            $query->where('indukan_jantan_id', $this->id);
        })->get();
    }

    /**
     * Get count of anakan from this indukan
     */
    public function getAnakanCount(): int
    {
        return $this->getAllAnakans()->count();
    }

    /**
     * Check if indukan is male
     */
    public function isJantan(): bool
    {
        return $this->jenis_kelamin === 'jantan';
    }

    /**
     * Check if indukan is female
     */
    public function isBetina(): bool
    {
        return $this->jenis_kelamin === 'betina';
    }
}
