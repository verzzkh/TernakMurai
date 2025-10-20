<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Peternak extends Model
{
    use HasFactory;

    protected $table = 'peternak';

    protected $fillable = [
        'user_id', 'nama_peternakan', 'alamat', 'nomor_handphone', 'foto_profil', 'jenis_akun', 'pro_berlaku_hingga', 'periode_deteksi', 'deteksi_terpakai'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
