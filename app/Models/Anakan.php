<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Anakan extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'anakan';

    protected $fillable = [
        'peternak_id','kandang_id','perkawinan_id','nomor_ring','tanggal_lahir','jenis_kelamin','status_pertumbuhan','deskripsi_karakteristik','catatan_perubahan','catatan_penjualan','harga','status_penjualan','tanggal_jual','foto_path'
    ];
}
