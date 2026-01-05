<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Transaksi;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class KeuanganService
{
    public function listForPeternak($peternakId, $filters)
{
    $query = Transaksi::where('peternak_id', $peternakId);

    // 🔍 SEARCH
    if (!empty($filters['search'])) {
        $query->where(function ($q) use ($filters) {
            $q->where('nama_item', 'like', '%' . $filters['search'] . '%')
              ->orWhere('deskripsi', 'like', '%' . $filters['search'] . '%')
              ->orWhere('kategori', 'like', '%' . $filters['search'] . '%');
        });
    }

    // 🟨 TYPE FILTER
    if ($filters['type'] !== 'all') {
        $query->where('tipe', $filters['type'] === 'income'
            ? 'pemasukan'
            : 'pengeluaran');
    }

    // 🔽 SORT
    switch ($filters['sort']) {
        case 'oldest':
            $query->orderBy('tanggal', 'asc');
            break;

        case 'amount_low':
            $query->orderBy('jumlah', 'asc');
            break;

        case 'amount_high':
            $query->orderBy('jumlah', 'desc');
            break;

        default:
            $query->orderBy('tanggal', 'desc');
    }

    return $query->paginate(10)->withQueryString();
}

    public function store(array $data, int $peternakId): Transaksi
    {
        return DB::transaction(function () use ($data, $peternakId) {
            $data['peternak_id'] = $peternakId;
            return Transaksi::create($data);
        });
    }

    public function update(Transaksi $transaksi, array $data): Transaksi
    {
        return DB::transaction(function () use ($transaksi, $data) {
            $transaksi->update($data);
            return $transaksi;
        });
    }

    public function delete(Transaksi $transaksi): void
    {
        $transaksi->delete();
    }

    public function getSummaryForPeternak(int $peternakId): array
{
    $totalPemasukan = Transaksi::where('peternak_id', $peternakId)
        ->where('tipe', 'pemasukan')
        ->sum('jumlah');

    $totalPengeluaran = Transaksi::where('peternak_id', $peternakId)
        ->where('tipe', 'pengeluaran')
        ->sum('jumlah');

    return [
        'pemasukan' => $totalPemasukan,
        'pengeluaran' => $totalPengeluaran,
        'saldo' => $totalPemasukan - $totalPengeluaran,
    ];
}

public function getKategoriList(): array
{
    return [
        'pemasukan' => ['Penjualan Anakan', 'Penjualan Burung Lomba', 'Lainnya'],
        'pengeluaran' => ['Pakan', 'Obat', 'Perawatan', 'Pembelian Burung', 'Lainnya'],
    ];
}

}
