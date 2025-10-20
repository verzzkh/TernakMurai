<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeteksiPenyakit extends Model
{
    use HasFactory;

    protected $table = 'deteksi_penyakit';

    protected $fillable = ['peternak_id','nama_burung','foto_path','gejala','perilaku','riwayat_kesehatan','lingkungan','makanan','hasil_analisis','diagnosis_utama','tingkat_kepercayaan','rekomendasi','is_saved'];
}
