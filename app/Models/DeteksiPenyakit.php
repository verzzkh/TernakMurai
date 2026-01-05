<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DeteksiPenyakit extends Model
{
    protected $table = 'deteksi_penyakit';

    protected $fillable = [
        'peternak_id',
        'nama_burung',
        'foto_path',
        'gejala',
        'perilaku',
        'riwayat_kesehatan',
        'lingkungan',
        'makanan',
        'hasil_analisis',
        'diagnosis_utama',
        'tingkat_kepercayaan',
        'tingkat_keparahan',
        'perlu_dokter',
        'is_saved',
    ];

    protected $casts = [
        'perilaku' => 'array',
        'hasil_analisis' => 'array',
        'perlu_dokter' => 'boolean',
    ];

    public function peternak()
    {
        return $this->belongsTo(Peternak::class, 'peternak_id');
    }

    public function fotos()
    {
        return $this->hasMany(DeteksiFoto::class, 'deteksi_id');
    }
}
