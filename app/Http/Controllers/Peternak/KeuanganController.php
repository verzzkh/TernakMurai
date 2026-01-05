<?php

declare(strict_types=1);

namespace App\Http\Controllers\Peternak;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTransaksiRequest;
use App\Http\Requests\UpdateTransaksiRequest;
use App\Models\Transaksi;
use App\Services\KeuanganService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

/**
 * Controller untuk manajemen pencatatan keuangan peternak.
 * Menangani pemasukan dan pengeluaran berbasis transaksi.
 */
class KeuanganController extends Controller
{
    /**
     * Dependency Injection: KeuanganService
     */
    public function __construct(
        private KeuanganService $keuanganService
    ) {}

  /**
 * Menampilkan daftar transaksi keuangan milik peternak.
 */
public function index(Request $request): View
{
    $peternak = Auth::user()->peternak;

    // 🔍 Ambil filter dari query string (disesuaikan dengan UI)
    $filters = [
        'search' => $request->get('search'),
        'type'   => $request->get('type', 'all'), // income / expense / all
        'sort'   => $request->get('sort', 'latest'), // latest, oldest, amount_low, amount_high
    ];

    // Ambil data transaksi sesuai filter dari service
    $transaksi = $this->keuanganService->listForPeternak($peternak->id, $filters);

    // Summary pemasukan + pengeluaran
    $summary = $this->keuanganService->getSummaryForPeternak($peternak->id);

    // Jumlah penjualan anakan (kategori = penjualan_anakan)
    $salesCount = \App\Models\Transaksi::where('peternak_id', $peternak->id)
        ->where('tipe', 'pemasukan')
        ->where('kategori', 'penjualan_anakan')
        ->count();

        $chartData = $transaksi->map(function ($t) {
    return [
        'id'        => $t->id,
        'tanggal'   => $t->tanggal->format('Y-m-d'),
        'kategori'  => $t->kategori,
        'nama_item' => $t->nama_item,
        'deskripsi' => $t->deskripsi,
        'jumlah'    => (float)$t->jumlah,
        'tipe'      => $t->tipe
    ];
});


return view('peternak.keuangan.index', compact('transaksi', 'filters', 'summary', 'salesCount', 'chartData'));

}


    /**
     * Form tambah transaksi baru (pemasukan/pengeluaran).
     */
    public function create(): View
    {
        $kategoriList = $this->keuanganService->getKategoriList();
        return view('peternak.keuangan.create', compact('kategoriList'));
    }

    /**
     * Simpan transaksi baru ke database.
     */
    public function store(StoreTransaksiRequest $request): RedirectResponse
    {
        $peternakId = Auth::user()->peternak->id;

        $this->keuanganService->store($request->validated(), $peternakId);

        return redirect()
            ->route('peternak.keuangan.index')
            ->with('success', 'Transaksi berhasil ditambahkan.');
    }

    /**
     * Form edit transaksi.
     */
    public function edit(Transaksi $keuangan): View
    {
        $this->authorizeOwner($keuangan);

        $kategoriList = $this->keuanganService->getKategoriList();

        return view('peternak.keuangan.edit', [
            'keuangan' => $keuangan,
            'kategoriList' => $kategoriList,
        ]);
    }

    /**
     * Update data transaksi.
     */
public function update(UpdateTransaksiRequest $request, Transaksi $transaksi)
{
    $this->authorizeOwner($transaksi);

    $this->keuanganService->update($transaksi, $request->validated());

    return redirect()
        ->route('peternak.keuangan.index')
        ->with('success', 'Transaksi berhasil diperbarui.');
}

   /**
     * Cek apakah transaksi milik peternak yang sedang login.
     */
    private function authorizeOwner(Transaksi $transaksi): void
    {
        $peternakId = Auth::user()->peternak->id;
        if ($transaksi->peternak_id !== $peternakId) {
            abort(403, 'Anda tidak berhak mengakses transaksi ini.');
        }
    }

    /**
     * Hapus transaksi keuangan.
     */
  public function destroy(Transaksi $transaksi): RedirectResponse
{
    $this->authorizeOwner($transaksi);

    $this->keuanganService->delete($transaksi);

    return redirect()
        ->route('peternak.keuangan.index')
        ->with('success', 'Transaksi berhasil dihapus.');
}


 
}
