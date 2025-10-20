<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Transaksi extends Model
{
    use HasFactory;

    protected $table = 'transaksi';

    protected $fillable = [
        'peternak_id',
        'tanggal',
        'tipe',
        'kategori',
        'jumlah',
        'nama_item',
        'deskripsi',
        'anakan_id',
        'indukan_id',
        'ring_referensi',
    ];

    protected function casts(): array
    {
        return [
            'tanggal' => 'date',
            'jumlah' => 'decimal:2',
        ];
    }

    public function peternak(): BelongsTo
    {
        return $this->belongsTo(Peternak::class);
    }

    public function anakan(): BelongsTo
    {
        return $this->belongsTo(Anakan::class);
    }

    public function indukan(): BelongsTo
    {
        return $this->belongsTo(Indukan::class);
    }
}
