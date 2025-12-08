<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DeteksiFoto extends Model
{
    protected $table = 'deteksi_foto';

    protected $fillable = [
        'deteksi_id',
        'foto_path',
    ];

public function deteksi()
{
    return $this->belongsTo(DeteksiPenyakit::class, 'deteksi_id');
}

}
