<?php

declare(strict_types=1);

namespace App\Http\Controllers\Peternak;

use App\Http\Controllers\Controller;
use App\Http\Requests\SellAnakanRequest;
use App\Http\Requests\StoreAnakanDariKandangRequest;
use App\Http\Requests\StoreAnakanDariLuarRequest;
use App\Http\Requests\UpdateAnakanRequest;
use App\Http\Requests\UpdateStatusAnakanRequest;
use App\Models\Anakan;
use App\Services\AnakanService;
use App\Services\PairingService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class AnakanController extends Controller
{
    public function __construct(
        private AnakanService $anakanService,
        private PairingService $pairingService
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        // Ambil profil peternak dari user yang sedang login (scope data per akun).
        $peternak = Auth::user()->peternak;

        // Query dasar daftar anakan, sekaligus eager-load relasi untuk menghindari N+1 query.
        $query = $peternak->anakans()
            ->with(['kandang', 'perkawinan.indukanJantan', 'perkawinan.indukanBetina'])
            ->latest();

        // Filter pencarian berdasarkan nomor ring anakan dan nomor ring indukan jantan.
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->where('nomor_ring', 'like', "%{$search}%")
                    ->orWhereHas('perkawinan.indukanJantan', function ($q) use ($search) {
                        $q->where('nomor_ring', 'like', "%{$search}%");
                    });
            });
        }

        // Filter berdasarkan jenis kelamin anakan.
        if ($request->filled('jenis_kelamin')) {
            $query->where('jenis_kelamin', $request->get('jenis_kelamin'));
        }

        // Filter berdasarkan status pertumbuhan (trotol/pastol/lomba).
        if ($request->filled('status_pertumbuhan')) {
            $query->where('status_pertumbuhan', $request->get('status_pertumbuhan'));
        }

        // Filter berdasarkan status penjualan (belum_dijual/terjual).
        if ($request->filled('status_penjualan')) {
            $query->where('status_penjualan', $request->get('status_penjualan'));
        }

        // Ambil hasil paginasi untuk ditampilkan pada UI.
        $anakans = $query->paginate(12);

        // Tambahkan atribut umur ke setiap anakan untuk kebutuhan tampilan (tanpa menyimpan ke database).
        $anakans->getCollection()->each(function ($anakan) {
            $anakan->age = $this->anakanService->calculateAge($anakan->tanggal_lahir);
        });

        // Ambil statistik ringkas anakan untuk kebutuhan ringkasan halaman.
        $stats = $this->anakanService->getAnakanStats($peternak);

        return view('peternak.anakan.index', compact('anakans', 'peternak', 'stats'));

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request): View|RedirectResponse
    {
        // Ambil profil peternak untuk mengambil daftar indukan yang dimiliki.
        $peternak = Auth::user()->peternak;

        // Ambil kandidat indukan jantan dan betina untuk dihubungkan dengan data anakan/perkawinan.
        $indukanJantan = $peternak->indukans()->where('jenis_kelamin', 'jantan')->get();
        $indukanBetina = $peternak->indukans()->where('jenis_kelamin', 'betina')->get();

        // Render halaman form pembuatan anakan.
        return view('peternak.anakan.create', compact('indukanJantan', 'indukanBetina'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        // Ambil profil peternak sebagai pemilik data yang akan dibuat.
        $peternak = Auth::user()->peternak;

        try {
            // Tentukan sumber anakan (internal dari kandang / eksternal dari luar).
            $sumberAnakan = $request->input('sumber_anakan');
            $jumlahBaru = (int) $request->input('jumlah_anakan', 1);

            // 🧩 Proses validasi dan simpan sesuai sumber anakan
            if ($sumberAnakan === 'peternakan' || $sumberAnakan === 'internal') {
                // ✅ Tambah dari dalam (kandang)
                // Validasi input menggunakan rules & messages dari Form Request.
                $validatedData = $request->validate(
                    (new StoreAnakanDariKandangRequest)->rules(),
                    (new StoreAnakanDariKandangRequest)->messages()
                );

                // Normalisasi nilai sumber anakan dan kaitkan dengan peternak.
                $validatedData['sumber_anakan'] = 'internal';
                $validatedData['peternak_id'] = $peternak->id;

                // Pastikan kandang_id ikut terkirim ke service (umumnya dari input hidden di form).
                $validatedData['kandang_id'] = $request->input('kandang_id');

                // 🔍 Jika indukan jantan & betina dipilih, pastikan data perkawinan tersedia.
                $indukanJantanId = $request->input('indukan_jantan_id');
                $indukanBetinaId = $request->input('indukan_betina_id');

                $perkawinan = null;
                if ($indukanJantanId && $indukanBetinaId) {
                    $pairing = $this->pairingService->ensureCanStartBreeding(
                        $peternak->id,
                        (int) $indukanJantanId,
                        (int) $indukanBetinaId
                    );

                    $createAttributes = [
                        'nomor_trip' => 'AUTO-'.strtoupper(\Illuminate\Support\Str::random(5)),
                        'tanggal_kawin' => now(),
                        'catatan' => 'Perkawinan otomatis dibuat saat tambah anakan.',
                        'status' => 'berhasil',
                    ];

                    if ($pairing->exists) {
                        $createAttributes['pairing_id'] = $pairing->id;
                    }

                    // Buat data perkawinan otomatis jika belum ada, agar anakan bisa terhubung dengan pasangan indukan.
                    $perkawinan = \App\Models\Perkawinan::firstOrCreate(
                        [
                            'peternak_id' => $peternak->id,
                            'indukan_jantan_id' => $indukanJantanId,
                            'indukan_betina_id' => $indukanBetinaId,
                        ],
                        $createAttributes
                    );

                    // Kaitkan anakan yang dibuat dengan data perkawinan tersebut.
                    $validatedData['perkawinan_id'] = $perkawinan->id;
                }

                // 🔁 Simpan anakan lewat service agar logika bisnis terpusat dan controller tetap tipis.
                $result = $this->anakanService->storeFromKandang($validatedData, $peternak->id);

                // Susun pesan sukses berdasarkan hasil penyimpanan.
                if (is_array($result)) {
                    $message = 'Berhasil menambahkan '.count($result).' anakan dari pasangan indukan.';
                } else {
                    $message = 'Berhasil menambahkan anakan dari pasangan indukan.';
                }
            }

            // 🧩 Tambahkan kembali bagian untuk sumber eksternal
            elseif ($sumberAnakan === 'eksternal' || $sumberAnakan === 'luar') {
                // Validasi input eksternal menggunakan rules & messages dari Form Request.
                $validatedData = $request->validate(
                    (new StoreAnakanDariLuarRequest)->rules(),
                    (new StoreAnakanDariLuarRequest)->messages()
                );

                // Normalisasi nilai sumber eksternal.
                $validatedData['sumber_anakan'] = 'eksternal'; // pastikan tidak tertimpa

                // Proses simpan lewat service agar konsisten dengan alur bisnis.
                $this->anakanService->storeFromLuar($validatedData, $peternak->id);

                $message = 'Berhasil menambahkan anakan dari luar.';
            }

            // ✅ Redirect sukses
            return redirect()
                ->route('peternak.anakan.index')
                ->with('success', $message ?? 'Anakan berhasil ditambahkan.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Jika validasi gagal, kembalikan ke form dengan pesan error dan input sebelumnya.
            return back()
                ->withErrors($e->errors())
                ->withInput();
        } catch (\Exception $e) {
            // Catat error tak terduga untuk debugging, lalu tampilkan pesan ramah ke pengguna.
            Log::error('[AnakanController::store] Unexpected error when storing anakan', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'user_id' => Auth::id(),
            ]);

            return back()->with('error', 'Terjadi kesalahan saat menyimpan anakan. Silakan coba lagi.')->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id): View
    {
        // Ambil profil peternak agar akses data selalu dibatasi pada pemiliknya.
        $peternak = Auth::user()->peternak;

        // Ambil detail anakan dan relasi yang dibutuhkan untuk halaman detail.
        $anakan = $peternak->anakans()
            ->with(['kandang.indukanJantan', 'kandang.indukanBetina', 'perkawinan'])
            ->findOrFail($id);

        // Hitung umur untuk kebutuhan tampilan.
        $anakan->age = $this->anakanService->calculateAge($anakan->tanggal_lahir);

        // Ambil daftar saudara (siblings) sebagai informasi tambahan.
        $siblings = $anakan->siblings();

        // Render halaman detail.
        return view('peternak.anakan.detail', compact('anakan', 'siblings'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAnakanRequest $request, string $id): JsonResponse
    {
        // Ambil profil peternak agar update tidak bisa lintas pemilik.
        $peternak = Auth::user()->peternak;
        $anakan = $peternak->anakans()->findOrFail($id);

        // Jalankan update melalui service agar aturan bisnis terpusat.
        $success = $this->anakanService->updateAnakan($anakan, $request->validated());

        if ($success) {
            // Respon sukses untuk kebutuhan AJAX.
            return response()->json([
                'success' => true,
                'message' => 'Data anakan berhasil diperbarui.',
                'anakan' => $anakan->fresh(),
            ]);
        }

        // Respon gagal untuk kebutuhan AJAX.
        return response()->json([
            'success' => false,
            'message' => 'Gagal memperbarui data anakan.',
        ], 500);
    }

    /**
     * Update anakan status
     */
    public function updateStatus(UpdateStatusAnakanRequest $request, string $id): JsonResponse
    {
        // Ambil profil peternak agar update status tidak bisa lintas pemilik.
        $peternak = Auth::user()->peternak;
        $anakan = $peternak->anakans()->findOrFail($id);

        // Update status lewat service agar konsisten dan mudah dirawat.
        $success = $this->anakanService->updateStatus($anakan, $request->validated());

        if ($success) {
            // Respon sukses untuk kebutuhan AJAX.
            return response()->json([
                'success' => true,
                'message' => 'Status anakan berhasil diperbarui.',
                'anakan' => $anakan->fresh(),
            ]);
        }

        // Respon gagal untuk kebutuhan AJAX.
        return response()->json([
            'success' => false,
            'message' => 'Gagal memperbarui status anakan.',
        ], 500);
    }

    /**
     * Sell anakan
     */
    public function sell(SellAnakanRequest $request, string $id): RedirectResponse
    {
        // Ambil profil peternak agar aksi penjualan hanya untuk data miliknya.
        $peternak = Auth::user()->peternak;
        $anakan = $peternak->anakans()->findOrFail($id);

        // Cegah penjualan ulang untuk anakan yang sudah terjual.
        if ($anakan->status_penjualan === 'terjual') {
            return redirect()->back()
                ->with('error', 'Anakan ini sudah terjual.');
        }

        // Proses penjualan dan pencatatan transaksi lewat service.
        $success = $this->anakanService->sellAnakan($anakan, $request->validated());

        if ($success) {
            // Redirect sukses kembali ke halaman daftar.
            return redirect()->route('peternak.anakan.index')
                ->with('success', "Anakan {$anakan->nomor_ring} berhasil dijual dan transaksi telah dicatat.");
        }

        // Redirect gagal jika proses penjualan bermasalah.
        return redirect()->back()
            ->with('error', 'Gagal memproses penjualan anakan.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // Ambil profil peternak agar penghapusan tidak bisa lintas pemilik.
        $peternak = Auth::user()->peternak;

        // Cari anakan berdasarkan kepemilikan peternak.
        $anakan = $peternak->anakans()->findOrFail($id);

        try {
            // Hapus data anakan melalui service agar konsisten dengan aturan bisnis.
            $this->anakanService->deleteAnakan($anakan);

            // Kembalikan user ke halaman sebelumnya dengan pesan sukses.
            return redirect()->back()->with('success', 'Anakan berhasil dihapus.');
        } catch (\Exception $e) {
            // Tampilkan pesan error jika gagal menghapus.
            return redirect()->back()->with('error', 'Gagal menghapus anakan: '.$e->getMessage());
        }
    }
}
