<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Kandang extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'kandang';

    protected $fillable = ['peternak_id', 'nomor_kandang', 'deskripsi_kandang', 'status', 'indukan_jantan_id', 'indukan_betina_id'];
}
