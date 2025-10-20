<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FotoIndukan extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'foto_indukan';

    protected $fillable = ['peternak_id', 'indukan_id', 'path', 'caption', 'is_cover'];
}
