<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Indukan extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'indukan';

    protected $fillable = ['peternak_id', 'nomor_ring', 'nama', 'jenis_kelamin', 'tanggal_lahir', 'catatan', 'prestasi', 'karakteristik'];
}
