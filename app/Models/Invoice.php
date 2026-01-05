<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    protected $table = 'invoices';

    protected $fillable = ['peternak_id','periode_mulai','periode_selesai','harga','status','metode','bukti_path','paid_at'];
}
