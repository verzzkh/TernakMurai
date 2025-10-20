<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    use HasFactory;

    protected $table = 'transaksi';

    protected $fillable = ['peternak_id','tanggal','tipe','kategori','jumlah','nama_item','deskripsi','anakan_id','indukan_id','ring_referensi'];
}
