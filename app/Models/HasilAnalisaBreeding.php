<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HasilAnalisaBreeding extends Model
{
    protected $table = 'hasil_analisa_breeding';

    protected $fillable = [
        'peternak_id',
        'jantan_id',
        'betina_id',
        'hasil_ai',
        'rekomendasi',
        'tindak_lanjut_peternak',
        'catatan_user',
        'tanggal_analisa',
    ];

    protected $casts = [
        'tanggal_analisa' => 'datetime',
    ];

    public function peternak(){
        return $this->belongsTo(Peternak::class,'peternak_id');
    }

    public function jantan(){
        return $this->belongsTo(Indukan::class,'jantan_id');
    }

    public function betina(){
        return $this->belongsTo(Indukan::class,'betina_id');
    }
}
