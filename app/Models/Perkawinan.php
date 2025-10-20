<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Perkawinan extends Model
{
    use HasFactory;

    protected $table = 'perkawinan';

    protected $fillable = ['kandang_id', 'indukan_jantan_id', 'indukan_betina_id', 'nomor_trip', 'tanggal_kawin', 'catatan'];
}
