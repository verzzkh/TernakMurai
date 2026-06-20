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
            'aktif_kicau',
    'mendekati_betina',
    'nafsu_makan_meningkat',
    'aktif_buat_sarang',
    'temperamen',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_lahir' => 'date',
                'aktif_kicau' => 'boolean',
        'mendekati_betina' => 'boolean',
        'nafsu_makan_meningkat' => 'boolean',
        'aktif_buat_sarang' => 'boolean',
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

    public function pairingsSebagaiJantan(): HasMany
    {
        return $this->hasMany(Pairing::class, 'indukan_jantan_id');
    }

    public function perkawinansBetina(): HasMany
    {
        return $this->hasMany(Perkawinan::class, 'indukan_betina_id');
    }

    public function pairingsSebagaiBetina(): HasMany
    {
        return $this->hasMany(Pairing::class, 'indukan_betina_id');
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
            'months'    => 0,
            'formatted' => 'Tidak diketahui',
        ];
    }

    // 🔒 PAKSA BULAN INTEGER
    $months = (int) $this->tanggal_lahir->diffInMonths(now());

    // < 12 bulan → tampil bulan
    if ($months < 12) {
        return [
            'months'    => $months,
            'formatted' => $months . ' bulan',
        ];
    }

    // >= 12 bulan → tahun (1 desimal dari BULAN BULAT)
    $years = round($months / 12, 1);
    $years = str_replace('.', ',', (string) $years);

    return [
        'months'    => $months,
        'formatted' => $years . ' tahun',
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
