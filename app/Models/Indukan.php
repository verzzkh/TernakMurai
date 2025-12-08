<?php

declare(strict_types=1);

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
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
        'foto_path',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_lahir' => 'date',
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */

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

    /**
     * Semua anakan yang dihasilkan indukan ini sebagai jantan.
     */
    public function anakansSebagaiJantan(): HasManyThrough
    {
        return $this->hasManyThrough(
            Anakan::class,
            Perkawinan::class,
            'indukan_jantan_id', // FK di tabel perkawinan
            'perkawinan_id',     // FK di tabel anakan
            'id',                // PK di tabel indukan
            'id'                 // PK di tabel perkawinan
        );
    }

    /**
     * Semua anakan yang dihasilkan indukan ini sebagai betina.
     */
    public function anakansSebagaiBetina(): HasManyThrough
    {
        return $this->hasManyThrough(
            Anakan::class,
            Perkawinan::class,
            'indukan_betina_id', // FK di tabel perkawinan
            'perkawinan_id',     // FK di tabel anakan
            'id',                // PK di tabel indukan
            'id'                 // PK di tabel perkawinan
        );
    }

    /*
    |--------------------------------------------------------------------------
    | ACCESSORS & HELPERS
    |--------------------------------------------------------------------------
    */

    /**
     * Gabungkan semua anakan dari indukan ini (baik sebagai jantan maupun betina).
     */
    public function getAllAnakans()
    {
        // Gunakan merge() agar hasil tetap berupa koleksi Eloquent
        return $this->anakansSebagaiJantan->merge($this->anakansSebagaiBetina);
    }

    /**
     * Hitung total anakan (jantan + betina) yang dihasilkan indukan ini.
     */
    public function getAnakanCount(): int
    {
        return $this->getAllAnakans()->count();
    }
    
    public function getAnakansAttribute()
{
    $anakansJantan = $this->anakansSebagaiJantan()->get();
    $anakansBetina = $this->anakansSebagaiBetina()->get();
    return $anakansJantan->merge($anakansBetina);
}

    /**
     * Hitung umur indukan dari tanggal lahir.
     */
public function getAgeAttribute(): array
{
    if (! $this->tanggal_lahir) {
        return [
            'days' => 0,
            'months' => 0,
            'years' => 0,
            'formatted' => 'Tidak diketahui',
        ];
    }

    $birthDate = Carbon::parse($this->tanggal_lahir);
    $now = Carbon::now();

    $days   = $birthDate->diffInDays($now);
    $months = $birthDate->diffInMonths($now);
    $years  = $birthDate->diffInYears($now);

    // Jika umur masih di bawah 1 tahun → pakai bulan
    if ($years < 1) {
        return [
            'days' => $days,
            'months' => $months,
            'years' => 0,
            'formatted' => "{$months} bulan",
        ];
    }

    // Jika umur >= 1 tahun → pakai tahun dengan 1 angka desimal
    $yearsDecimal = round($days / 365, 1);

    return [
        'days' => $days,
        'months' => $months,
        'years' => $yearsDecimal,
        'formatted' => "{$yearsDecimal} tahun",
    ];
}

    
    public function getFotoUrlAttribute(): string
{
    if (!$this->foto_path) {
        return asset('images/default-bird.png'); // fallback gambar default
    }

    return asset('storage/' . $this->foto_path);
}


    public function isJantan(): bool
    {
        return $this->jenis_kelamin === 'jantan';
    }

    public function isBetina(): bool
    {
        return $this->jenis_kelamin === 'betina';
    }
}
