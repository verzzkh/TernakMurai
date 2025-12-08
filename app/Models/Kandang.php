<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

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

    // ====== RELASI DASAR ======

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


    // ====== RELASI TAMBAHAN: Anakan dari indukan jantan aktif ======

 public function anakansAktif()
{
    return $this->hasMany(Anakan::class)
        ->whereHas('perkawinan', function ($q) {
            $q->where('indukan_jantan_id', $this->indukan_jantan_id)
              ->where('indukan_betina_id', $this->indukan_betina_id);
        });
}


    public function anakansBetinaAktif(): HasMany
{
    return $this->hasMany(Anakan::class)
                ->where('indukan_betina_id', $this->indukan_betina_id)
                ->orderByDesc('tanggal_lahir');
}

public function anakansPasanganAktif()
{
    return $this->hasMany(Anakan::class)
        ->whereHas('perkawinan', function ($query) {
            $query->where('indukan_jantan_id', $this->indukan_jantan_id)
                  ->where('indukan_betina_id', $this->indukan_betina_id);
        });
}


    // ====== Validasi akses kepemilikan ======

    public function resolveRouteBinding($value, $field = null)
    {
        $user = Auth::user();

        return $this->where('id', $value)
                    ->where('peternak_id', $user->peternak->id)
                    ->firstOrFail();
    }
    /**
 * Relasi virtual untuk mengambil indukan jantan & betina sebagai satu koleksi.
 */
public function indukan()
{
    return collect([
        $this->indukanJantan,
        $this->indukanBetina
    ])->filter(); // hilangkan null
}

}
