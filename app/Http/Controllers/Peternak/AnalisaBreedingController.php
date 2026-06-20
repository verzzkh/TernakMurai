<?php

declare(strict_types=1);

namespace App\Http\Controllers\Peternak;

use App\Http\Controllers\Controller;
use App\Models\Anakan;
use App\Models\HasilAnalisaBreeding;
use App\Models\Indukan;
use App\Models\Perkawinan;
use App\Services\BreedingLifecycleService;
use App\Services\PairingService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use OpenAI;

class AnalisaBreedingController extends Controller
{
    public function __construct(
        private PairingService $pairingService,
        private BreedingLifecycleService $breedingLifecycleService
    ) {}

    /**
     * Form pemilihan indukan
     */
    public function form()
    {
        $peternakId = Auth::user()->peternak->id;

        return view('peternak.kandang.formAnalisa', [
            'jantan' => Indukan::where('peternak_id', $peternakId)
                ->where('jenis_kelamin', 'jantan')
                ->orderBy('nomor_ring')
                ->get(),

            'betina' => Indukan::where('peternak_id', $peternakId)
                ->where('jenis_kelamin', 'betina')
                ->orderBy('nomor_ring')
                ->get(),
        ]);
    }

    /**
     * Riwayat Penyimpanan Analisa
     */
    public function riwayat()
    {
        $riwayat = $this->pairingService->analysisQueryForPeternak(Auth::user()->peternak->id)
            ->latest('tanggal_analisa')
            ->get();

        return view('peternak.kandang.riwayatAnalisa', compact('riwayat'));
    }

    /**
     * Simpan hasil analisa ke riwayat
     */
    public function save(Request $request)
    {
        $request->validate([
            'pairing_id' => 'nullable',
            'jantan_id' => 'required|exists:indukan,id',
            'betina_id' => 'required|exists:indukan,id',
            'hasil_ai' => 'required',
            'rekomendasi' => 'required|in:uji_coba,lanjut,stop',
            'historical_reference_at' => 'nullable|date',
        ]);

        $peternakId = Auth::user()->peternak->id;
        if (!$this->pairingService->isAvailable()) {
            HasilAnalisaBreeding::create([
                'peternak_id' => $peternakId,
                'jantan_id' => $request->jantan_id,
                'betina_id' => $request->betina_id,
                'hasil_ai' => $request->hasil_ai,
                'rekomendasi' => $request->rekomendasi,
                'tanggal_analisa' => now(),
            ]);

            return redirect()
                ->route('peternak.analisaBreeding.riwayat')
                ->with('success', 'Hasil analisa berhasil disimpan ke riwayat.');
        }

        $pairing = $this->pairingService->getOrCreate($peternakId, (int) $request->jantan_id, (int) $request->betina_id);
        $request->validate([
            'pairing_id' => 'required|exists:pairings,id',
            'historical_reference_at' => 'required|date',
        ]);

        if ((int) $pairing->id !== (int) $request->pairing_id) {
            return redirect()
                ->route('peternak.analisaBreeding.form')
                ->with('error', 'Data pairing tidak konsisten. Silakan ulangi proses analisa.');
        }

        $latestAnalysis = $this->pairingService->getLatestAnalysis($pairing);
        $historicalReferenceAt = Carbon::parse($request->historical_reference_at);

        if ($pairing->isDihentikan()) {
            if ($latestAnalysis) {
                return redirect()
                    ->route('peternak.analisaBreeding.detail', $latestAnalysis->id)
                    ->with('info', 'Pairing ini sudah dihentikan. Sistem menampilkan hasil analisa terakhir.');
            }

            return redirect()
                ->route('peternak.analisaBreeding.riwayat')
                ->with('error', 'Pairing ini sudah dihentikan dan tidak dapat menyimpan analisa baru.');
        }

        if (
            $latestAnalysis
            && $latestAnalysis->historical_reference_at
            && $latestAnalysis->historical_reference_at->equalTo($historicalReferenceAt)
        ) {
            return redirect()
                ->route('peternak.analisaBreeding.detail', $latestAnalysis->id)
                ->with('info', 'Belum ada perubahan data historis sejak analisa terakhir.');
        }

        $analisa = HasilAnalisaBreeding::create([
            'peternak_id' => $peternakId,
            'pairing_id' => $pairing->id,
            'jantan_id' => $request->jantan_id,
            'betina_id' => $request->betina_id,
            'hasil_ai' => $request->hasil_ai,
            'rekomendasi' => $request->rekomendasi,
            'tanggal_analisa' => now(),
            'historical_reference_at' => $historicalReferenceAt,
        ]);

        $this->pairingService->registerAnalysis($pairing, $analisa);

        return redirect()
            ->route('peternak.analisaBreeding.riwayat')
            ->with('success', 'Hasil analisa berhasil disimpan ke riwayat.');
    }

    public function updateTindakLanjut(Request $request, $id)
    {
        $request->validate([
            'tindak_lanjut_peternak' => 'required|in:lanjut,pantau,stop',
        ]);

        $peternakId = Auth::user()->peternak->id;

        try {
            $message = $this->breedingLifecycleService->applyAnalysisFollowUp(
                (int) $id,
                $peternakId,
                $request->tindak_lanjut_peternak
            );

            return redirect()
                ->back()
                ->with('success', $message);
        } catch (\Illuminate\Validation\ValidationException $exception) {
            return redirect()
                ->back()
                ->with('error', collect($exception->errors())->flatten()->first() ?? 'Tindak lanjut tidak dapat diproses.');
        }
    }

    public function hapus($id)
    {
        $peternakId = Auth::user()->peternak->id;

        $data = HasilAnalisaBreeding::where('peternak_id', $peternakId)->findOrFail($id);
        $data->delete();

        return redirect()
            ->route('peternak.analisaBreeding.riwayat')
            ->with('success', 'Riwayat analisa berhasil dihapus.');
    }

    public function detail($id)
    {
        $peternakId = Auth::user()->peternak->id;

        $detailQuery = HasilAnalisaBreeding::with(['jantan', 'betina'])
            ->where('peternak_id', $peternakId);

        if ($this->pairingService->isAvailable()) {
            $detailQuery->with('pairing');
        }

        $data = $detailQuery->findOrFail($id);
        $characteristicCoverage = $this->buildCharacteristicCoverageSummary(
            Anakan::where('peternak_id', $peternakId)
                ->where('indukan_jantan_id', $data->jantan_id)
                ->where('indukan_betina_id', $data->betina_id)
                ->get()
        );

        return view('peternak.kandang.detailAnalisa', compact('data', 'characteristicCoverage'));
    }

    public function updateCatatan(Request $request, $id)
    {
        $request->validate([
            'catatan_user' => 'nullable|string|max:5000',
        ]);

        $peternakId = Auth::user()->peternak->id;

        $analisa = HasilAnalisaBreeding::where('peternak_id', $peternakId)
            ->findOrFail($id);

        $analisa->update([
            'catatan_user' => $request->catatan_user,
        ]);

        return redirect()
            ->back()
            ->with('success', 'Catatan berhasil disimpan.');
    }

    private function buildTripKonteksIndukanLain(
        int $peternakId,
        Indukan $jantan,
        Indukan $betina
    ): string {

        $teks = [];

        // ==============================
        // JANTAN DENGAN BETINA LAIN
        // ==============================
        $tripJantanLain = Perkawinan::where('peternak_id', $peternakId)
            ->where('indukan_jantan_id', $jantan->id)
            ->where('indukan_betina_id', '!=', $betina->id)
            ->get()
            ->groupBy('indukan_betina_id');

        if ($tripJantanLain->isNotEmpty()) {

            $teks[] = 'Riwayat jantan dengan pasangan lain:';

            foreach ($tripJantanLain as $betinaId => $trips) {

                $jumlahTrip = $trips->count();
                $jumlahGagal = $trips->where('status', 'gagal')->count();
                $jumlahBerhasil = $jumlahTrip - $jumlahGagal;

                $successRate = $jumlahTrip > 0
                    ? round(($jumlahBerhasil / $jumlahTrip) * 100)
                    : 0;

                $namaBetina = Indukan::find($betinaId)?->nama ?? 'Indukan tidak diketahui';

                $teks[] =
                    "- {$jantan->nama} × {$namaBetina}: "
                    ."{$jumlahTrip} trip "
                    ."({$jumlahBerhasil} berhasil, {$jumlahGagal} gagal), "
                    ."success rate {$successRate}%";
            }
        }

        // ==============================
        // BETINA DENGAN JANTAN LAIN
        // ==============================
        $tripBetinaLain = Perkawinan::where('peternak_id', $peternakId)
            ->where('indukan_betina_id', $betina->id)
            ->where('indukan_jantan_id', '!=', $jantan->id)
            ->get()
            ->groupBy('indukan_jantan_id');

        if ($tripBetinaLain->isNotEmpty()) {

            $teks[] = "\nRiwayat betina dengan pasangan lain:";

            foreach ($tripBetinaLain as $jantanId => $trips) {

                $jumlahTrip = $trips->count();
                $jumlahGagal = $trips->where('status', 'gagal')->count();
                $jumlahBerhasil = $jumlahTrip - $jumlahGagal;

                $successRate = $jumlahTrip > 0
                    ? round(($jumlahBerhasil / $jumlahTrip) * 100)
                    : 0;

                $namaJantan = Indukan::find($jantanId)?->nama ?? 'Indukan tidak diketahui';

                $teks[] =
                    "- {$betina->nama} × {$namaJantan}: "
                    ."{$jumlahTrip} trip "
                    ."({$jumlahBerhasil} berhasil, {$jumlahGagal} gagal), "
                    ."success rate {$successRate}%";
            }
        }

        if (empty($teks)) {
            return 'Tidak terdapat riwayat trip breeding indukan dengan pasangan lain.';
        }

        return implode("\n", $teks);
    }

    private function buildFaseProduksiIndukan(
        int $peternakId,
        Indukan $indukan,
        string $peran // 'jantan' atau 'betina'
    ): array {

        $query = Perkawinan::where('peternak_id', $peternakId);

        if ($peran === 'jantan') {
            $query->where('indukan_jantan_id', $indukan->id);
        } else {
            $query->where('indukan_betina_id', $indukan->id);
        }

        // Ambil semua lalu urutkan terbaru dulu
        $perkawinans = $query
            ->orderByDesc('tanggal_kawin')
            ->get()
            ->groupBy(function ($p) use ($peran) {
                return $peran === 'jantan'
                    ? $p->indukan_betina_id
                    : $p->indukan_jantan_id;
            });

        $fase = [];

        foreach ($perkawinans as $pasanganId => $trips) {

            $totalTrip = $trips->count();
            $berhasil = $trips->where('status', '!=', 'gagal')->count();
            $gagal = $totalTrip - $berhasil;

            $successRate = $totalTrip > 0
                ? round(($berhasil / $totalTrip) * 100)
                : 0;

            $statusFase = match (true) {
                $successRate >= 70 => 'stabil',
                $successRate >= 40 => 'fluktuatif',
                default => 'rendah'
            };

            $pasanganNama = Indukan::find($pasanganId)?->nama ?? 'Tidak diketahui';

            $fase[] = [
                'pasangan' => $indukan->nama.' × '.$pasanganNama,
                'periode_mulai' => $trips->last()->tanggal_kawin,
                'periode_selesai' => $trips->first()->tanggal_kawin,
                'total_trip' => $totalTrip,
                'berhasil' => $berhasil,
                'gagal' => $gagal,
                'success_rate' => $successRate,
                'status_fase' => $statusFase,
            ];
        }

        // BATASI HANYA 3 FASE TERBARU
        $fase = array_slice($fase, 0, 3);

        return $fase;
    }

    private function formatFaseProduksiText(array $faseData, string $label): string
    {
        if (empty($faseData)) {
            return "{$label} belum memiliki riwayat fase produksi dengan pasangan lain.";
        }

        $text = ["Riwayat fase produksi {$label} (maksimal 3 fase terbaru):"];

        foreach ($faseData as $index => $fase) {

            $text[] =
                'Fase '.($index + 1).": {$fase['pasangan']} | "
                ."{$fase['total_trip']} trip "
                ."({$fase['berhasil']} berhasil, {$fase['gagal']} gagal) | "
                ."status fase: {$fase['status_fase']}";
        }

        return implode("\n", $text);
    }

    /**
     * Proses analisis breeding
     */
    public function analisa(Request $request): RedirectResponse
    {
        // Perpanjang batas waktu eksekusi menjadi 5 menit (300 detik)
        // karena request ke API AI (Gemini) terkadang membutuhkan waktu lebih lama.
        set_time_limit(300);

        $request->validate([
            'jantan_id' => 'required|exists:indukan,id',
            'betina_id' => 'required|exists:indukan,id',
        ]);

        $peternakId = Auth::user()->peternak->id;

        $jantan = Indukan::where('peternak_id', $peternakId)->findOrFail($request->jantan_id);
        $betina = Indukan::where('peternak_id', $peternakId)->findOrFail($request->betina_id);

        if (!$this->pairingService->isAvailable()) {
            return $this->runLegacyAnalysisFlow($peternakId, $jantan, $betina);
        }

        $pairing = $this->pairingService->getOrCreate($peternakId, $jantan->id, $betina->id);
        $analysisStatus = $this->pairingService->getAnalysisStatus($pairing);

        if (!$analysisStatus['allowed']) {
            $latestAnalysis = $analysisStatus['latest_analysis'];

            if ($latestAnalysis) {
                $message = $analysisStatus['reason'] === 'dihentikan'
                    ? 'Pairing ini berstatus dihentikan. Sistem menampilkan hasil analisa terakhir.'
                    : 'Belum ada perubahan data historis sejak analisa terakhir. Sistem menampilkan hasil terakhir.';

                return redirect()
                    ->route('peternak.analisaBreeding.detail', $latestAnalysis->id)
                    ->with('info', $message);
            }

            return redirect()
                ->route('peternak.analisaBreeding.riwayat')
                ->with('error', 'Analisa baru tidak dapat dibuat untuk pairing ini.');
        }

        $anakansPasangan = Anakan::where('peternak_id', $peternakId)
            ->where('indukan_jantan_id', $jantan->id)
            ->where('indukan_betina_id', $betina->id)
            ->get();

        $anakansJantanGlobal = $jantan->anakansSebagaiJantan()
            ->with(['indukanJantan', 'indukanBetina'])
            ->get();

        $anakansBetinaGlobal = $betina->anakansSebagaiBetina()
            ->with(['indukanJantan', 'indukanBetina'])
            ->get();

        $perkawinanPasangan = Perkawinan::where('peternak_id', $peternakId)
            ->where('indukan_jantan_id', $jantan->id)
            ->where('indukan_betina_id', $betina->id)
            ->orderByDesc('tanggal_kawin')
            ->get();

        $konteksTripIndukanLain = $this->buildTripKonteksIndukanLain(
            $peternakId,
            $jantan,
            $betina
        );

        // ======================================================
        // FASE PRODUKSI INDUKAN (MAKS 3 TERBARU)
        // ======================================================

        $faseJantan = $this->buildFaseProduksiIndukan(
            $peternakId,
            $jantan,
            'jantan'
        );

        $faseBetina = $this->buildFaseProduksiIndukan(
            $peternakId,
            $betina,
            'betina'
        );

        $konteksFaseProduksi =
            $this->formatFaseProduksiText($faseJantan, 'jantan')."\n\n".
            $this->formatFaseProduksiText($faseBetina, 'betina');

        $tindakLanjutPeternakGlobal = HasilAnalisaBreeding::where('peternak_id', $peternakId)
            ->whereNotNull('tindak_lanjut_peternak')
            ->orderByDesc('tanggal_analisa')
            ->take(5) // cukup 5 terakhir
            ->pluck('tindak_lanjut_peternak');

        // ======================================================
        // HITUNG REKOMENDASI SISTEM
        // ======================================================
        $rekomendasiSistem = $this->tentukanRekomendasiSistem(
            $perkawinanPasangan
        );

        $payloadJurnal = [
            'putranto_2018_reproduksi' => [
                'aturan' => [
                ],
            ],
            'saputro_2016_perilaku' => [
                'indikator_jantan' => ['aktif_berkicau', 'mendekati_betina'],
                'indikator_betina' => ['nafsu_makan_meningkat', 'aktif_membuat_sarang'],
            ],
            'simatupang_2022_risiko' => [
                'risiko_umum' => ['stres lingkungan', 'adaptasi awal', 'produktif rendah'],
            ],
        ];

        $prompt = $this->generatePrompt(
            $jantan,
            $betina,
            $anakansPasangan,
            $anakansJantanGlobal,
            $anakansBetinaGlobal,
            $konteksTripIndukanLain,
            $konteksFaseProduksi,
            $perkawinanPasangan,
            $payloadJurnal,
            $tindakLanjutPeternakGlobal,
            $rekomendasiSistem,
        );
        $characteristicCoverage = $this->buildCharacteristicCoverageSummary($anakansPasangan);

        try {
            $client = OpenAI::client(env('OPENAI_API_KEY'));

            $response = $client->chat()->create([
                'model' => env('OPENAI_MODEL', 'gpt-4o-mini'),
                'messages' => [
                    ['role' => 'system', 'content' => 'Anda adalah sistem SPK breeding Murai Batu berbasis analisis manajerial'],
                    ['role' => 'user', 'content' => $prompt],
                ],
                'temperature' => 0.2,
            ]);

            $hasilAnalisa = $response->choices[0]->message->content;
        } catch (\OpenAI\Exceptions\RateLimitException $e) {
            $hasilAnalisa = $this->fallbackAnalisa($jantan, $betina, $rekomendasiSistem, $characteristicCoverage);
        } catch (\Exception $e) {
            $hasilAnalisa = $this->fallbackAnalisa($jantan, $betina, $rekomendasiSistem, $characteristicCoverage);
        }

        return redirect()->route('peternak.analisaBreeding.hasil')->with([
            'hasilAnalisa' => $hasilAnalisa,
            'pairing_id' => $pairing->id,
            'jantan_id' => $jantan->id,
            'betina_id' => $betina->id,
            'rekomendasi' => $rekomendasiSistem,
            'historical_reference_at' => optional($pairing->last_historical_change_at)->toDateTimeString(),
            'characteristicCoverage' => $characteristicCoverage,
        ]);
    }

    public function hasil()
    {
        if (! session('hasilAnalisa')) {
            return redirect()->route('peternak.analisaBreeding.form')
                ->with('error', 'Silakan lakukan analisa terlebih dahulu.');
        }

        $jantan = Indukan::find(session('jantan_id'));
        $betina = Indukan::find(session('betina_id'));
        $hasilAnalisa = session('hasilAnalisa');
        $pairingId = session('pairing_id');
        $rekomendasi = session('rekomendasi');
        $historicalReferenceAt = session('historical_reference_at');
        $characteristicCoverage = session('characteristicCoverage');

        return view('peternak.kandang.hasilAnalisa', compact(
            'hasilAnalisa',
            'pairingId',
            'jantan',
            'betina',
            'rekomendasi',
            'historicalReferenceAt',
            'characteristicCoverage'
        ));
    }

    private function runLegacyAnalysisFlow(int $peternakId, Indukan $jantan, Indukan $betina): RedirectResponse
    {
        $anakansPasangan = Anakan::where('peternak_id', $peternakId)
            ->where('indukan_jantan_id', $jantan->id)
            ->where('indukan_betina_id', $betina->id)
            ->get();

        $anakansJantanGlobal = $jantan->anakansSebagaiJantan()
            ->with(['indukanJantan', 'indukanBetina'])
            ->get();

        $anakansBetinaGlobal = $betina->anakansSebagaiBetina()
            ->with(['indukanJantan', 'indukanBetina'])
            ->get();

        $perkawinanPasangan = Perkawinan::where('peternak_id', $peternakId)
            ->where('indukan_jantan_id', $jantan->id)
            ->where('indukan_betina_id', $betina->id)
            ->orderByDesc('tanggal_kawin')
            ->get();

        $konteksTripIndukanLain = $this->buildTripKonteksIndukanLain($peternakId, $jantan, $betina);
        $faseJantan = $this->buildFaseProduksiIndukan($peternakId, $jantan, 'jantan');
        $faseBetina = $this->buildFaseProduksiIndukan($peternakId, $betina, 'betina');
        $konteksFaseProduksi =
            $this->formatFaseProduksiText($faseJantan, 'jantan')."\n\n".
            $this->formatFaseProduksiText($faseBetina, 'betina');

        $tindakLanjutPeternakGlobal = HasilAnalisaBreeding::where('peternak_id', $peternakId)
            ->whereNotNull('tindak_lanjut_peternak')
            ->orderByDesc('tanggal_analisa')
            ->take(5)
            ->pluck('tindak_lanjut_peternak');

        $rekomendasiSistem = $this->tentukanRekomendasiSistem($perkawinanPasangan);

        $payloadJurnal = [
            'putranto_2018_reproduksi' => ['aturan' => []],
            'saputro_2016_perilaku' => [
                'indikator_jantan' => ['aktif_berkicau', 'mendekati_betina'],
                'indikator_betina' => ['nafsu_makan_meningkat', 'aktif_membuat_sarang'],
            ],
            'simatupang_2022_risiko' => [
                'risiko_umum' => ['stres lingkungan', 'adaptasi awal', 'produktif rendah'],
            ],
        ];

        $prompt = $this->generatePrompt(
            $jantan,
            $betina,
            $anakansPasangan,
            $anakansJantanGlobal,
            $anakansBetinaGlobal,
            $konteksTripIndukanLain,
            $konteksFaseProduksi,
            $perkawinanPasangan,
            $payloadJurnal,
            $tindakLanjutPeternakGlobal,
            $rekomendasiSistem,
        );
        $characteristicCoverage = $this->buildCharacteristicCoverageSummary($anakansPasangan);

        try {
            $client = OpenAI::client(env('OPENAI_API_KEY'));
            $response = $client->chat()->create([
                'model' => env('OPENAI_MODEL', 'gpt-4o-mini'),
                'messages' => [
                    ['role' => 'system', 'content' => 'Anda adalah sistem SPK breeding Murai Batu berbasis analisis manajerial'],
                    ['role' => 'user', 'content' => $prompt],
                ],
                'temperature' => 0.2,
            ]);

            $hasilAnalisa = $response->choices[0]->message->content;
        } catch (\Throwable $e) {
            $hasilAnalisa = $this->fallbackAnalisa($jantan, $betina, $rekomendasiSistem, $characteristicCoverage);
        }

        return redirect()->route('peternak.analisaBreeding.hasil')->with([
            'hasilAnalisa' => $hasilAnalisa,
            'pairing_id' => null,
            'jantan_id' => $jantan->id,
            'betina_id' => $betina->id,
            'rekomendasi' => $rekomendasiSistem,
            'historical_reference_at' => now()->toDateTimeString(),
            'characteristicCoverage' => $characteristicCoverage,
        ]);
    }

    // ======================================================
    // MEKANISME EVALUASI DETERMINISTIK UNTUK REKOMENDASI SISTEM
    // ======================================================
    private function tentukanRekomendasiSistem($perkawinanPasangan): string
    {
        // ==================================================
        // TAHAP 1: VALIDASI DATA HISTORIS
        // ==================================================

        $totalTrip = $perkawinanPasangan->count();

        // Validasi defensif: tidak ada data
        if ($totalTrip === 0) {
            return 'uji_coba';
        }

        // Minimum Sample Rule (T < 3)
        if ($totalTrip < 3) {
            return 'uji_coba';
        }

        // ==================================================
        // TAHAP 2: DETEKSI KEGAGALAN BERUNTUN (HARD STOP)
        // ==================================================

        $sorted = $perkawinanPasangan
            ->sortByDesc('tanggal_kawin')
            ->values();

        $streakGagal = 0;

        foreach ($sorted as $trip) {
            if ($trip->status === 'gagal') {
                $streakGagal++;
                if ($streakGagal >= 3) {
                    return 'stop';
                }
            } else {
                break;
            }
        }

        // ==================================================
        // TAHAP 3: PERHITUNGAN SUCCESS RATE HISTORIS (SR20)
        // ==================================================

        $window20 = $sorted->take(min(20, $totalTrip));
        $total20 = $window20->count();
        $berhasil20 = $window20->where('status', '!=', 'gagal')->count();

        $successRate20 = $total20 > 0
            ? $berhasil20 / $total20
            : 0;

        // ==================================================
        // TAHAP 4: PERHITUNGAN SUCCESS RATE JANGKA PENDEK (SR5)
        // ==================================================

        $window5 = $sorted->take(min(5, $totalTrip));
        $total5 = $window5->count();
        $berhasil5 = $window5->where('status', '!=', 'gagal')->count();

        $successRate5 = $total5 > 0
            ? $berhasil5 / $total5
            : 0;

        // ==================================================
        // TAHAP 5: PERHITUNGAN INDIKATOR TREN (Δ)
        // ==================================================

        $delta = $successRate5 - $successRate20;

        // ==================================================
        // TAHAP 6: KLASIFIKASI REKOMENDASI AKHIR
        // ==================================================

        if ($successRate20 < 0.40 || $delta <= -0.30) {
            return 'stop';
        }

        if ($successRate20 < 0.70 || $delta <= -0.15) {
            return 'uji_coba';
        }

        return 'lanjut';
    }

    /**
     * Prompt AI (SPK Akademis)
     */
    private function generatePrompt(
        Indukan $jantan,
        Indukan $betina,
        $anakansPasangan,
        $anakansJantanGlobal,
        $anakansBetinaGlobal,
        string $konteksTripIndukanLain,
        string $konteksFaseProduksi,
        $perkawinanPasangan,
        array $payloadJurnal,
        $riwayatTindakLanjutPeternakGlobal,
        string $rekomendasiSistem
    ): string {
        $konteksKeputusanPeternak = '';

        if (
            isset($riwayatTindakLanjutPeternakGlobal)
            && $riwayatTindakLanjutPeternakGlobal->count() > 0
        ) {
            $ringkasanKeputusan = $riwayatTindakLanjutPeternakGlobal
                ->countBy()
                ->map(fn ($jumlah, $aksi) => strtoupper($aksi).": {$jumlah}x")
                ->implode(', ');

            $konteksKeputusanPeternak = <<<TEXT

========================
KONTEKS RIWAYAT KEPUTUSAN PETERNAK
========================
Berdasarkan riwayat analisa breeding sebelumnya,
peternak tercatat mengambil tindak lanjut sebagai berikut:
{$ringkasanKeputusan}

Catatan:
Data ini digunakan sebagai konteks gaya pengelolaan peternak
secara umum dan tidak digunakan sebagai dasar utama evaluasi
atau penilaian benar atau salah keputusan peternak.
TEXT;
        }

        /* =========================================================
         * A. DATA DASAR ANAKAN & PERKAWINAN
         * ========================================================= */
        $jumlahAnakanPasangan = $anakansPasangan->count();
        $jumlahAnakanJantanGlobal = $anakansJantanGlobal->count();
        $jumlahAnakanBetinaGlobal = $anakansBetinaGlobal->count();

        $jumlahPerkawinan = $perkawinanPasangan->count();
        $perkawinanTerakhir = $perkawinanPasangan->first();
        $coverageKarakteristikPasangan = $this->buildCharacteristicCoverageSummary($anakansPasangan);
        $konteksCoverageKarakteristik = $this->formatCharacteristicCoverageForPrompt($coverageKarakteristikPasangan);

        $tanggalKawinTerakhir = $perkawinanTerakhir
            ? (
                $perkawinanTerakhir->tanggal_kawin instanceof \Carbon\CarbonInterface
                    ? $perkawinanTerakhir->tanggal_kawin->toDateString()
                    : \Illuminate\Support\Carbon::parse($perkawinanTerakhir->tanggal_kawin)->toDateString()
            )
            : 'Tidak ada data';
            
        $statusKawinTerakhir = $perkawinanTerakhir
            ? strtoupper($perkawinanTerakhir->status)
            : 'TIDAK ADA DATA';

        $catatanKawinTerakhir = ($perkawinanTerakhir && $perkawinanTerakhir->status === 'gagal' && !empty($perkawinanTerakhir->catatan))
            ? "\n  - Catatan kegagalan: {$perkawinanTerakhir->catatan}"
            : "";

        /* =========================================================
        * KONTEKS RIWAYAT PRODUKSI INDIVIDU INDUKAN
        * (WAJIB SELALU ADA – DIPAKAI DI PROMPT)
        * ========================================================= */

        // anakan jantan dari pasangan LAIN
        $jumlahAnakanJantanLain = $anakansJantanGlobal
            ->where('indukan_betina_id', '!=', $betina->id)
            ->count();

        // anakan betina dari pasangan LAIN
        $jumlahAnakanBetinaLain = $anakansBetinaGlobal
            ->where('indukan_jantan_id', '!=', $jantan->id)
            ->count();

        /* =========================================================
        * B. RINCIAN ANAKAN PASANGAN INI (MAX 5 TERBARU)
        * ========================================================= */
        $ringkasanAnakanPasangan = $anakansPasangan
            ->sortByDesc('tanggal_menetas')
            ->take(5)
            ->values()
            ->map(function ($a, $i) {
                $detail = [];

                if ($a->jenis_kelamin) {
                    $detail[] = "Jenis kelamin: {$a->jenis_kelamin}";
                }
                if ($a->status) {
                    $detail[] = "Status: {$a->status}";
                }
                if ($a->tanggal_menetas) {
                    $detail[] = "Menetas: {$a->tanggal_menetas}";
                }

                return $detail
                    ? '- Anakan '.($i + 1).': '.implode(', ', $detail)
                    : '- Anakan '.($i + 1).': Data detail belum tersedia.';
            })
            ->implode("\n");

        $ringkasanAnakanPasangan = $ringkasanAnakanPasangan
            ?: 'Belum ada anakan tercatat di antara kedua pasangan ini.';

        /* =========================================================
        * C. RINGKASAN KARAKTERISTIK – HASIL PASANGAN INI
        * ========================================================= */
        $ringkasanKarakteristikPasanganIni = '';

        if ($jumlahAnakanPasangan > 0) {
            $ringkasanKarakteristikPasanganIni = $anakansPasangan
                ->sortByDesc('tanggal_menetas')
                ->take(5)
                ->pluck('deskripsi_karakteristik')
                ->filter()
                ->unique()
                ->map(fn ($d) => "- {$d}")
                ->implode("\n");

            $ringkasanKarakteristikPasanganIni = $ringkasanKarakteristikPasanganIni
                ?: '- Data karakteristik anakan hasil pasangan ini belum tersedia.';
        }
        /* =========================================================
        * D. RINGKASAN KARAKTERISTIK – HASIL PASANGAN LAIN (KONTEKS)
        * ========================================================= */

        $ringkasanKarakteristikPasanganLain = '';

        $anakansPasanganLain = collect()
            ->merge($anakansJantanGlobal)
            ->merge($anakansBetinaGlobal)
            ->reject(
                fn ($a) => $a->indukan_jantan_id === $jantan->id &&
                    $a->indukan_betina_id === $betina->id
            )
            ->filter(fn ($a) => ! empty($a->deskripsi_karakteristik));

        if ($anakansPasanganLain->isNotEmpty()) {

            // Kelompokkan berdasarkan pasangan indukan
            $grouped = $anakansPasanganLain->groupBy(
                fn ($a) => $a->indukan_jantan_id.'-'.$a->indukan_betina_id
            );

            $blok = [];

            foreach ($grouped as $pairKey => $anakans) {

                $contoh = $anakans->first();

                $namaJantan = $contoh->indukanJantan->nama ?? 'Jantan Tidak Diketahui';
                $namaBetina = $contoh->indukanBetina->nama ?? 'Betina Tidak Diketahui';

                $karakteristik = $anakans
                    ->sortByDesc('tanggal_menetas')
                    ->take(5)
                    ->pluck('deskripsi_karakteristik')
                    ->filter()
                    ->unique()
                    ->map(fn ($d) => "  • {$d}")
                    ->implode("\n");

                if ($karakteristik) {
                    $blok[] =
                        "- {$namaJantan} × {$namaBetina}:\n{$karakteristik}";
                }
            }

            $ringkasanKarakteristikPasanganLain = $blok
                ? implode("\n\n", $blok)
                : '- Data karakteristik anakan dari pasangan lain belum tersedia.';
        }

        // =========================================================
        // TRIP BREEDING (5 TERAKHIR) – KHUSUS PASANGAN INI
        // =========================================================

        $tripPasangan = $perkawinanPasangan
            ->sortByDesc('tanggal_kawin')
            ->take(5)
            ->sortBy('tanggal_kawin') // Urutkan kembali dari yang terlama ke terbaru (di antara 5 terakhir)
            ->values();

        $ringkasanTripText = [];

        $totalTrip = $perkawinanPasangan->count();
        $tripPasanganCount = $tripPasangan->count();
        $startOffset = max(1, $totalTrip - $tripPasanganCount + 1);

        foreach ($tripPasangan as $i => $trip) {

            $jumlahAnakanTrip = $trip->anakans->count();
            $statusTrip = $trip->status === 'gagal'
                ? 'GAGAL'
                : 'BERHASIL';

            $tanggalKawinTrip = $trip->tanggal_kawin instanceof \Carbon\CarbonInterface
                ? $trip->tanggal_kawin->toDateString()
                : \Illuminate\Support\Carbon::parse($trip->tanggal_kawin)->toDateString();

            $actualTripNumber = $startOffset + $i;

            $catatanTrip = ($trip->status === 'gagal' && !empty($trip->catatan))
                ? " | Catatan gagal: {$trip->catatan}"
                : '';

            $ringkasanTripText[] =
                '- Trip '.$actualTripNumber.
                " | Tanggal kawin: {$tanggalKawinTrip}".
                " | Status: {$statusTrip}".
                " | Jumlah anakan: {$jumlahAnakanTrip}{$catatanTrip}";
        }

        $totalGagal = $perkawinanPasangan->where('status', 'gagal')->count();
        $totalBerhasil = $totalTrip - $totalGagal;

        $successRate = $totalTrip > 0
            ? round(($totalBerhasil / $totalTrip) * 100)
            : 0;

        $ringkasanTripText = $ringkasanTripText
            ? implode("\n", $ringkasanTripText)
            : 'Belum terdapat data trip breeding yang dapat dievaluasi.';

        /* =========================================================
         * E. RIWAYAT PRODUKSI INDUKAN
         * ========================================================= */
        $ringkasanGlobalText = [];

        if ($jumlahAnakanJantanGlobal > 0) {
            $ringkasanGlobalText[] =
                "Jantan memiliki {$jumlahAnakanJantanGlobal} anakan secara keseluruhan.";
        }

        if ($jumlahAnakanBetinaGlobal > 0) {
            $ringkasanGlobalText[] =
                "Betina memiliki {$jumlahAnakanBetinaGlobal} anakan secara keseluruhan.";
        }

        $ringkasanGlobalText = $ringkasanGlobalText
            ? implode("\n", $ringkasanGlobalText)
            : 'Tidak ada data anakan dari pasangan ini secara keseluruhan.';

        /* =========================================================
         * F. DATA PERILAKU INDUKAN
         * ========================================================= */
        $perilakuJantan =
            '- Aktif Kicau: '.($jantan->aktif_kicau ? 'Ya' : 'Tidak')."\n".
            '- Mendekati Betina: '.($jantan->mendekati_betina ? 'Ya' : 'Tidak')."\n".
            '- Temperamen: '.($jantan->temperamen ?: '-');

        $perilakuBetina =
            '- Nafsu Makan Meningkat: '.($betina->nafsu_makan_meningkat ? 'Ya' : 'Tidak')."\n".
            '- Aktif Membuat Sarang: '.($betina->aktif_buat_sarang ? 'Ya' : 'Tidak')."\n".
            '- Temperamen: '.($betina->temperamen ?: '-');

        /* =========================================================
        * G. EVALUASI RULE-BASED
        * ========================================================= */

        $indikatorJantanLengkap = $jantan->aktif_kicau && $jantan->mendekati_betina;
        $indikatorBetinaLengkap = $betina->nafsu_makan_meningkat && $betina->aktif_buat_sarang;

        $kesimpulanJantan = $indikatorJantanLengkap
            ? 'Indikator kesiapan jantan terpenuhi.'
            : 'Indikator kesiapan jantan masih ada yang belum terpenuhi.';

        $kesimpulanBetina = $indikatorBetinaLengkap
            ? 'Indikator kesiapan betina terpenuhi.'
            : 'Indikator kesiapan betina masih ada yang belum terpenuhi.';

        $blokKarakteristik = '';

        if ($ringkasanKarakteristikPasanganIni) {
            $blokKarakteristik .= "
            Ringkasan karakteristik anakan (hasil pasangan ini):
            {$ringkasanKarakteristikPasanganIni}

Informasi kelengkapan data karakteristik pasangan ini:
{$konteksCoverageKarakteristik}";
        }

        if ($ringkasanKarakteristikPasanganLain) {
            $blokKarakteristik .= "
Ringkasan karakteristik anakan (hasil pasangan lain, sebagai konteks historis):
{$ringkasanKarakteristikPasanganLain}

Catatan:
Data ini digunakan sebagai konteks pengalaman indukan dan tidak dijadikan
bukti kecocokan pasangan yang dianalisis.
";
        }
        /*  END BLOCK */

        $rekomendasiFormat = str_replace('_', ' ', $rekomendasiSistem);

        return <<<PROMPT

========================
TUGAS ANDA
========================
Susun laporan analisis breeding Murai Batu sebagai
sistem pendukung keputusan (Decision Support System).

Laporan harus disusun secara terstruktur, objektif,
dan berbasis data faktual tanpa spekulasi biologis.

{$konteksKeputusanPeternak}


========================
PRINSIP ANALISIS SISTEM
========================
- Sistem diperbolehkan menarik implikasi manajerial TERBATAS
  berdasarkan hubungan logis antar data.
- Sistem DILARANG memprediksi hasil biologis, genetika,
  kualitas anakan, atau tingkat keberhasilan menetas.
- Sistem hanya menggunakan data yang tersedia di sistem.
- Jika terdapat perbedaan antara riwayat historis dan
  kondisi perilaku saat ini, jelaskan secara objektif.
- Analisis bertujuan membantu pengambilan keputusan peternak,
  bukan menentukan kebenaran biologis.
  berdasarkan data riwayat breeding yang tersedia.
- Klasifikasi ini bersifat manajerial dan kontekstual,
  bukan penilaian biologis atau prediksi hasil breeding.

========================
FORMAT LAPORAN
========================

A. Ringkasan Singkat Indukan

- Jantan: {$jantan->nomor_ring} ({$jantan->nama})
Perilaku jantan:
{$perilakuJantan}

- Betina: {$betina->nomor_ring} ({$betina->nama})
Perilaku betina:
{$perilakuBetina}

ATURAN PERILAKU:
- Gunakan nilai Ya/Tidak persis seperti data sistem.
- DILARANG mengubah, menafsirkan ulang,
  atau menambahkan perilaku yang tidak tercatat.
- Jika nilai = Tidak, tuliskan sebagai Tidak.

------------------------------------------------

B. Evaluasi Kecocokan Jantan × Betina

Evaluasi dilakukan berdasarkan aturan perilaku sistem:

- Kesiapan jantan:
  {$kesimpulanJantan}

- Kesiapan betina:
  {$kesimpulanBetina}

Catatan:
Evaluasi ini berbasis aturan perilaku yang telah
ditetapkan sistem dan bukan prediksi hasil breeding,
genetika, atau kualitas anakan.Interpretasi kecocokan perilaku ini
perlu dibaca bersama dengan status pasangan breeding
berdasarkan riwayat historis,
karena indikator perilaku yang sama
dapat memiliki makna manajerial yang berbeda
pada pasangan baru dan pasangan mapan.

------------------------------------------------

C. Evaluasi Riwayat Breeding & Interpretasi Manajerial

Tujuan bagian ini adalah mengevaluasi riwayat produksi
secara manajerial dan membandingkannya dengan kondisi
perilaku indukan saat ini.

1. Konteks Riwayat Produksi Individu Indukan

- Riwayat pasangan ini:
  Pasangan ini telah menghasilkan {$jumlahAnakanPasangan} anakan.

Catatan:
Riwayat produksi individu indukan digunakan sebagai
indikator pengalaman reproduksi.


2. Riwayat perkawinan pasangan:

- Total trip: {$totalTrip}
- Trip berhasil: {$totalBerhasil}
- Trip gagal: {$totalGagal}
- Tingkat keberhasilan (success rate): {$successRate}%

- Perkawinan terakhir:
  - Tanggal kawin: {$tanggalKawinTerakhir}
  - Status: {$statusKawinTerakhir}{$catatanKawinTerakhir}
  Gunakan ringkasan ini untuk:
- menilai apakah hasil breeding cenderung konsisten,
- mengidentifikasi pengulangan hasil tanpa perbaikan,
- mendukung keputusan lanjutan secara bertahap.
Jika trip terakhir berstatus GAGAL,
jelaskan sebagai kondisi risiko yang memerlukan evaluasi segera.

  ------------------------------------------------
3.  Evaluasi Periode Breeding (Trip)

Bagian ini menyajikan ringkasan beberapa trip breeding terakhir
khusus untuk pasangan jantan dan betina yang dianalisis.

Ringkasan 5 trip terakhir pasangan ini:
{$ringkasanTripText}

Catatan:
- Trip breeding didefinisikan sebagai satu periode perkawinan
  yang diakhiri dengan ada atau tidaknya hasil anakan.
- Evaluasi ini bersifat deskriptif untuk membantu mengidentifikasi
  pola hasil breeding secara kuantitatif, seperti kecenderungan
  peningkatan, penurunan, atau kestabilan hasil antar trip.
- Apabila jumlah trip masih terbatas, hasil evaluasi tidak
  disimpulkan sebagai pola jangka panjang dan tetap memerlukan
  pemantauan lanjutan.
- Data ini tidak digunakan untuk prediksi biologis atau genetika.

------------------------------------------------
------------------------------------------------
4 Konteks Historis Trip Indukan (Pasangan Lain)

4.1 Catatan Karakteristik Anakan
Gunakan data karakteristik anakan hasil pasangan ini
{$konteksTripIndukanLain}
{$blokKarakteristik}


------------------------------------------------
Ringkasan Fase Produksi Indukan:
{$konteksFaseProduksi}

Sistem WAJIB menggunakan data fase produksi ini
untuk memahami apakah indukan menunjukkan:

- pola peningkatan performa,
- pola penurunan performa,
- atau perbedaan performa antar pasangan.
Catatan:
Data ini digunakan sebagai konteks pengalaman reproduksi
dan kestabilan hasil breeding indukan secara individual.
Sistem WAJIB mempertimbangkan konteks ini
sebelum menyusun kesimpulan manajerial.

------------------------------------------------

4.2 Evaluasi Karakteristik Anakan

Gunakan data karakteristik anakan yang tersedia untuk
menyusun evaluasi kualitatif hasil breeding secara
objektif dan deskriptif.

Informasi kelengkapan data karakteristik:
- Total anakan pasangan ini: {$coverageKarakteristikPasangan['total_anakan']}
- Anakan yang sudah memiliki data karakteristik: {$coverageKarakteristikPasangan['anakan_dengan_karakteristik']}
- Tingkat kelengkapan data karakteristik: {$coverageKarakteristikPasangan['coverage_persen']}%
- Status kelengkapan data: {$coverageKarakteristikPasangan['status_label']}

Jangan hanya mengulang informasi kelengkapan data. Fokus utama bagian ini adalah
mengevaluasi pola karakteristik anakan yang tersedia, seperti suara, bentuk tubuh,
kondisi fisik, mental, kaki, paruh, mata, atau catatan kesehatan jika ada.

Jika tingkat kelengkapan data belum 100%, sisipkan catatan keterbatasan secara
singkat dan natural. Jika tingkat kelengkapan data sudah tinggi, terutama di atas 80%,
jelaskan bahwa sebagian besar data sudah tersedia atau bahwa evaluasi memiliki dasar
data yang cukup kuat, tetapi tetap berbasis pada data yang tersedia.

Evaluasi karakteristik anakan ini bukan merupakan kesimpulan final terhadap seluruh hasil breeding pasangan,
melainkan evaluasi kualitatif berdasarkan anakan yang sudah memiliki data karakteristik.
Jika masih terdapat anakan yang belum memiliki data karakteristik, jangan menyimpulkan kualitas seluruh anakan secara absolut.
Gunakan frasa seperti:
- "berdasarkan anakan yang telah memiliki data karakteristik"
- "pada data karakteristik yang tersedia"
- "sejauh data yang tercatat"
- "evaluasi ini masih bersifat sementara karena belum seluruh anakan memiliki data karakteristik"

Jika data karakteristik menunjukkan pola positif, jelaskan bahwa pola tersebut terlihat pada anakan yang sudah tercatat,
bukan sebagai hasil final untuk seluruh anakan pasangan.

Hindari pengulangan kalimat yang sama dengan ringkasan kelengkapan data di bagian atas
dan hindari kesimpulan absolut terhadap seluruh anakan jika masih ada data yang belum tercatat.

Jika terdapat perbedaan hasil karakteristik
antara pasangan aktif dan pasangan sebelumnya,
jelaskan secara eksplisit.
------------------------------------------------

4.3 Analisis Perbandingan Wajib

Sistem WAJIB melakukan perbandingan eksplisit antara:

- Performa pasangan aktif saat ini
- Performa indukan yang sama dengan pasangan lain sebelumnya

Langkah analisis yang WAJIB dilakukan:

1. Bandingkan tingkat keberhasilan (success rate).
2. Bandingkan jumlah trip dan konsistensi hasil.
3. Jelaskan apakah performa meningkat, menurun,
   atau menunjukkan pola berbeda.
4. Jelaskan implikasi manajerial dari perbedaan tersebut.
5. WAJIB menyebut pasangan pembanding secara eksplisit
   (contoh: "dibandingkan dengan pasangan sebelumnya Cakra × Ratna").\
6. DILARANG menyimpulkan faktor genetika atau biologis.
- Tingkat keberhasilan (success rate) cukup disebutkan secara eksplisit satu kali.
- Pada bagian selanjutnya gunakan istilah deskriptif seperti:
  "rendah", "menurun", "konsisten", atau "tidak stabil".
- Hindari pengulangan angka yang sama di lebih dari dua bagian laporan.
Analisis ini harus ditulis sebagai paragraf khusus
dan tidak boleh dilewati atau diringkas secara umum.

------------------------------------------------

4.4 Kesimpulan bagian ini:

Jelaskan status reproduksi pasangan ini secara manajerial
berdasarkan:

- Riwayat jumlah trip,
- Konsistensi hasil breeding,
- Hasil perbandingan dengan pasangan lain.

Dalam kesimpulan ini, sistem wajib menetapkan status pasangan breeding
(secara kontekstual), misalnya sebagai:

- pasangan baru dicoba,
- pasangan menunjukkan kestabilan awal,
- pasangan belum konsisten,
- pasangan memerlukan pengawasan lebih ketat.

Penetapan status pasangan bersifat manajerial dan deskriptif,
bukan penilaian biologis dan bukan prediksi keberhasilan breeding.

------------------------------------------------


D. Evaluasi Kesiapan Produksi Berdasarkan Perilaku
(berdasarkan indikator perilaku Saputro 2016)

Gunakan aturan berikut:
- Jika jantan aktif kicau DAN mendekati betina
  → indikator kesiapan jantan terpenuhi.
- Jika betina nafsu makan meningkat DAN aktif membuat sarang
  → indikator kesiapan betina terpenuhi.
- Jika salah satu indikator bernilai Tidak
  → indikator kesiapan belum lengkap.

Catatan:
Setiap indikator kesiapan yang belum lengkap
WAJIB ditindaklanjuti dengan rekomendasi tindakan praktis
pada bagian H.

------------------------------------------------

E. Risiko & Catatan Manajemen

Sebutkan potensi risiko manajerial yang realistis
berdasarkan data perilaku dan riwayat breeding,
misalnya:
- dominansi jantan,
- stres adaptasi,
- ketidaksiapan lingkungan.

Catatan: Hindari klaim ekstrem dan hindari prediksi biologis pasti.

------------------------------------------------

F. Rekomendasi Akhir Sistem
(Berbasis Evaluasi Engine + Analisis Manajerial)

Sistem telah melakukan evaluasi kuantitatif
berbasis tren historis (20 trip terakhir dibanding 5 terbaru).

Rekomendasi awal sistem:
REKOMENDASI: {$rekomendasiFormat}

Instruksi:

1. Gunakan rekomendasi engine ini sebagai dasar utama.
2. Jelaskan alasan keputusan tersebut secara FAKTUAL dan OBJEKTIF dengan mengacu pada data evaluasi engine di atas.
   - Prioritaskan penjelasan berdasarkan rule aktif dan alasan rule yang diberikan.
   - Gunakan success rate, delta, dan tren performa sebagai dasar penjelasan.
   - Sebutkan trip yang gagal secara spesifik jika ada.
   - Jika rekomendasi STOP karena delta performa negatif, fokuskan pada penurunan success rate terbaru vs histori.
3. AI DILARANG mengubah klasifikasi rekomendasi engine.
4. AI hanya berfungsi memperjelas alasan manajerial di balik keputusan sistem berdasarkan data evaluasi yang tersedia.
5. Gunakan data evaluasi engine hanya sebagai dasar reasoning internal.
   Hasil akhir WAJIB disajikan dalam bentuk narasi natural dan kontekstual,
   bukan dalam bentuk daftar indikator, output debug, atau pengulangan data evaluasi engine.

6. Jangan menampilkan ulang label seperti:
   - Success rate 20 trip terakhir
   - Delta performa
   - Data evaluasi engine

   Gunakan informasi tersebut untuk membangun penjelasan naratif yang mengalir dan mudah dipahami.

7. Penjelasan akhir harus berbentuk paragraf naratif yang menyatukan:
   - histori breeding,
   - tingkat keberhasilan,
   - konsistensi performa,
   - kondisi trip terbaru,
   - dan tren performa
   ke dalam penjelasan yang kontekstual.
  
  8. Jangan menggunakan indikator kesiapan indukan, perilaku mendekat, respon biologis, atau observasi non-historis sebagai dasar utama keputusan, kecuali muncul pada data evaluasi engine.
   Namun, untuk rekomendasi STOP, AI tetap wajib menambahkan catatan bahwa kegagalan breeding dapat dipengaruhi 
   faktor eksternal sebagai konteks manajerial, bukan sebagai penyebab pasti.

   9. Jika total trip masih rendah atau belum ada histori breeding,
   maka jelaskan bahwa rekomendasi diberikan karena
   data historis breeding belum cukup untuk evaluasi performa.

Jika rekomendasi adalah:
- "lanjut" → jelaskan faktor kestabilan produksi berdasarkan data evaluasi.
- "uji coba" → jelaskan faktor fluktuasi atau ketidakstabilan berdasarkan data evaluasi.
- "stop" → jelaskan indikasi penurunan performa atau risiko berulang berdasarkan data evaluasi.

Aturan khusus untuk narasi rekomendasi STOP:
1. Label rekomendasi tetap STOP karena berasal dari engine deterministik, namun narasi WAJIB menjelaskan bahwa rekomendasi ini bersifat pendukung keputusan.
2. Hindari kalimat final seperti:
   - "pairing ini tidak layak"
   - "harus dihentikan"
   - "tidak boleh dilanjutkan"
3. Gunakan bahasa seperti:
   - "perlu dipertimbangkan untuk dihentikan sementara"
   - "perlu evaluasi lanjutan sebelum dilanjutkan"
   - "peternak perlu meninjau faktor eksternal sebelum mengambil keputusan akhir"
   - "rekomendasi ini bersifat pendukung keputusan, bukan keputusan mutlak"
4. Jika histori keberhasilan sebelumnya cukup baik, WAJIB jelaskan keseimbangan antara keberhasilan historis dan kegagalan pada trip terbaru. Jangan langsung menyimpulkan pairing buruk secara absolut.
5. WAJIB sebutkan bahwa kegagalan breeding dapat dipengaruhi faktor eksternal seperti kondisi lingkungan, musim breeding, cuaca, kondisi fisik indukan, stres indukan, pakan, atau kondisi kandang.
6. Narasi akhir harus menempatkan rekomendasi STOP sebagai ajakan untuk evaluasi manajerial yang lebih hati-hati, bukan vonis akhir terhadap kualitas pairing dan di sesuaikan dengan poin 5.
7. Untuk rekomendasi STOP, faktor eksternal TIDAK BOLEH disebut secara umum saja. Sistem WAJIB menyebutkannya secara eksplisit di dalam narasi, minimal 3 faktor dari daftar berikut:
   - kondisi lingkungan,
   - musim breeding,
   - cuaca,
   - kondisi fisik indukan,
   - stres indukan,
   - pakan,
   - kondisi kandang.
8. Setelah menyebut faktor eksternal, sistem WAJIB memberi konteks manajerial singkat mengapa faktor-faktor tersebut perlu ditinjau pada kasus ini, misalnya karena kegagalan berulang pada trip terbaru dapat dipengaruhi oleh perubahan kestabilan kandang, kondisi fisik indukan, atau kualitas perawatan harian. Penjelasan ini harus tetap bersifat hati-hati dan tidak boleh menyatakan faktor eksternal sebagai penyebab pasti.
9. Narasi STOP WAJIB terdiri dari dua lapis penjelasan yang utuh:
   - lapisan pertama menjelaskan data historis dan alasan evaluasi engine,
   - lapisan kedua menjelaskan faktor eksternal apa saja yang perlu ditinjau ulang oleh peternak sebelum keputusan akhir diambil.
10. Jika faktor eksternal disebut, gunakan bentuk kalimat natural seperti:
   - "Selain pola kegagalan pada trip terbaru, peternak juga perlu meninjau kondisi lingkungan, kondisi fisik indukan, pakan, dan kestabilan kandang..."
   - "Konteks ini penting karena penurunan performa tidak selalu berdiri sendiri dan dapat berkaitan dengan stres indukan, cuaca, atau perubahan manajemen harian..."

Aturan Konsistensi Penjelasan:

1. Penjelasan WAJIB konsisten dengan data historis yang tersedia.
2. Jika data menunjukkan kondisi stabil (success rate tinggi, tidak ada kegagalan, dan tren tidak menurun),
   maka JANGAN menyatakan bahwa performa perlu ditingkatkan atau tidak stabil.
3. Jangan membuat penilaian negatif atau perbaikan jika tidak ada indikator yang mendukung dalam data.
4. Jangan menambahkan asumsi di luar indikator evaluasi berbasis data historis.
5. jika ada mengarahkan perbandingan dengan pasangan sebelumnya, di beritahu nama dan nomor ringnya, jangan hanya sebelumnya. dan berlakukan ini ke semua yang ada membahas pasangan sebelumnya.
6. Gunakan istilah:
   - "stabil" jika performa tinggi dan konsisten
   - "fluktuatif" jika performa tidak konsisten
   - "menurun" jika terdapat tren penurunan atau kegagalan yang sering terjadi

Catatan:
Rekomendasi ini bersifat manajerial dan kontekstual,
bukan prediksi biologis.


------------------------------------------------

------------------------------------------------

G. Rekomendasi Tindakan Praktis
(Berbasis Perilaku + Konteks Historis)

Bagian ini menyajikan rekomendasi tindakan manajerial
yang disusun dengan mempertimbangkan:

1. Indikator perilaku saat ini,
2. Riwayat trip pasangan aktif,
3. Tren performa historis,
4. Fase produksi indukan pada pasangan lain,
5. Hasil perbandingan yang telah dianalisis pada bagian 3.

Instruksi penyusunan:

1 Evaluasi Perilaku
- Identifikasi indikator yang bernilai "Tidak".
- Jika ada indikator belum terpenuhi,
  berikan tindakan korektif spesifik
  beserta durasi pengamatan dan waktu evaluasi ulang.

2  Evaluasi Konteks Historis
- Jika seluruh indikator bernilai "Ya",
  sistem TIDAK BOLEH langsung menyimpulkan
  bahwa tidak diperlukan tindakan.

3 Sistem WAJIB mengevaluasi:
  - apakah performa pasangan aktif menurun,
  - apakah pasangan sebelumnya lebih stabil,
  - apakah terdapat pola kegagalan berulang.

Jika terdapat:
- penurunan performa,
- ketidakstabilan hasil,
- atau perbedaan signifikan dibanding pasangan lain,

maka rekomendasi harus mencerminkan
pengawasan tambahan, jeda pairing,
atau evaluasi manajemen kandang,
meskipun indikator perilaku saat ini terpenuhi.

3 Jika performa stabil DAN indikator perilaku terpenuhi,
jelaskan bahwa cukup dilakukan pemantauan rutin.

Catatan penting:
- Rekomendasi bersifat manajerial dan kontekstual.
- Tidak boleh bertentangan dengan rekomendasi engine pada bagian F.
- Tidak boleh menyimpulkan faktor genetika atau biologis.
- Rekomendasi harus konsisten dengan tren historis yang telah dijelaskan.
- Sistem WAJIB konsisten dengan bagian D dan tidak boleh menyatakan indikator terpenuhi jika sebelumnya dinyatakan tidak terpenuhi.

H. Potensi Tantangan Awal

Bagian ini menjelaskan potensi tantangan awal yang dapat muncul
berdasarkan indikator perilaku yang belum terpenuhi
pada pasangan indukan yang dianalisis.

Langkah penyusunan:
1. Identifikasi indikator perilaku yang bernilai "Tidak".
2. Untuk setiap indikator tersebut, jelaskan tantangan awal
   yang secara umum dapat muncul di kandang.
3. Jika seluruh indikator bernilai "Ya",
   jelaskan bahwa tidak terdapat tantangan awal yang signifikan,
   namun pemantauan rutin tetap disarankan.

Catatan:
Tantangan yang disampaikan bersifat sinyal awal,
bukan penentu kegagalan breeding,
dan perlu disikapi dengan pengamatan bertahap
sebelum diambil keputusan lanjutan.

------------------------------------------------

I. Early Warning - Kondisi Stop Pairing

Berikan batasan kondisi kapan pairing
perlu dihentikan sementara, seperti:
- terdapat agresivitas jantan yang berulang,
- penurunan kondisi betina,
- tidak ada respon interaksi dalam 30 hari,
- luka fisik atau tanda stres berat.

========================
BATASAN BAHASA
========================
- Gunakan bahasa profesional, objektif, dan instruktif.
- DILARANG menggunakan kata: "mungkin", "diperkirakan",
  "berpotensi secara genetika", atau istilah spekulatif lain.
- Fokus pada keputusan berbasis data sistem dan aturan perilaku.
- Rekomendasi sistem bersifat pendukung keputusan
  dan tetap memerlukan observasi lapangan oleh peternak.

PROMPT;


    }

    private function buildCharacteristicCoverageSummary($anakansPasangan): array
    {
        $totalAnakan = $anakansPasangan->count();
        $anakanDenganKarakteristik = $anakansPasangan
            ->filter(fn ($anakan) => filled(trim((string) ($anakan->deskripsi_karakteristik ?? ''))))
            ->count();

        $coveragePersen = $totalAnakan > 0
            ? round(($anakanDenganKarakteristik / $totalAnakan) * 100, 1)
            : 0.0;

        $statusLabel = match (true) {
            $totalAnakan === 0 => 'belum ada data anakan',
            $coveragePersen >= 100 => 'lengkap',
            $coveragePersen >= 80 => 'cukup kuat',
            $coveragePersen >= 40 => 'parsial',
            default => 'terbatas',
        };

        $catatan = match (true) {
            $totalAnakan === 0 => 'Belum ada anakan tercatat untuk pairing ini.',
            $coveragePersen >= 100 => 'Data karakteristik sudah mencakup seluruh anakan pada pairing ini.',
            $coveragePersen >= 80 => 'Sebagian besar data karakteristik anakan sudah tersedia, sehingga evaluasi memiliki dasar data yang cukup kuat.',
            default => 'Data karakteristik anakan belum lengkap, sehingga evaluasi perlu dibaca sebagai gambaran dari data yang saat ini tersedia.',
        };

        return [
            'total_anakan' => $totalAnakan,
            'anakan_dengan_karakteristik' => $anakanDenganKarakteristik,
            'coverage_persen' => $coveragePersen,
            'status_label' => $statusLabel,
            'catatan' => $catatan,
            'is_partial' => $totalAnakan > 0 && $anakanDenganKarakteristik < $totalAnakan,
        ];
    }

    private function formatCharacteristicCoverageForPrompt(array $coverage): string
    {
        return "- Total anakan: {$coverage['total_anakan']}\n"
            ."- Anakan yang sudah memiliki data karakteristik: {$coverage['anakan_dengan_karakteristik']}\n"
            ."- Tingkat kelengkapan data karakteristik: {$coverage['coverage_persen']}%\n"
            ."- Catatan: {$coverage['catatan']}";
    }

    private function fallbackAnalisa($jantan, $betina, $rekomendasiSistem, ?array $characteristicCoverage = null)
    {
        $rekomendasiFormat = str_replace('_', ' ', $rekomendasiSistem);
        $coverageCatatan = '';
        $stopCatatan = '';

        if ($characteristicCoverage) {
            $coverageSectionLabel = $rekomendasiSistem === 'stop' ? 'E' : 'D';
            $coverageCatatan = "\n{$coverageSectionLabel}. Coverage Karakteristik Anakan\n"
                ."Total anakan: {$characteristicCoverage['total_anakan']}\n"
                ."Anakan dengan data karakteristik: {$characteristicCoverage['anakan_dengan_karakteristik']}\n"
                ."Coverage: {$characteristicCoverage['coverage_persen']}%\n"
                ."Catatan: {$characteristicCoverage['catatan']}\n";
        }

        if ($rekomendasiSistem === 'stop') {
            $stopCatatan = "\nD. Catatan Khusus Rekomendasi STOP\n"
                ."Label STOP berasal dari evaluasi deterministik berbasis histori breeding.\n"
                ."Rekomendasi ini perlu dibaca sebagai sinyal kehati-hatian dan pertimbangan untuk menghentikan sementara pairing sambil melakukan evaluasi lanjutan.\n"
                ."Peternak tetap perlu meninjau faktor eksternal seperti kondisi lingkungan, musim breeding, cuaca, kondisi fisik indukan, stres, pakan, dan kondisi kandang sebelum mengambil keputusan akhir.\n"
                ."Konteks manajerialnya adalah bahwa kegagalan berulang pada trip terbaru tidak selalu berdiri sendiri, sehingga perubahan lingkungan kandang, kualitas pakan, kondisi fisik indukan, atau stabilitas perawatan harian perlu diperiksa kembali secara bertahap.\n";
        }

        return "# Laporan Analisis Breeding (Fallback Mode)

A. Ringkasan Singkat
- Jantan: {$jantan->nomor_ring} ({$jantan->nama})
- Betina: {$betina->nomor_ring} ({$betina->nama})

B. Rekomendasi Sistem (Berbasis Evaluasi Deterministik)
REKOMENDASI: {$rekomendasiFormat}

C. Catatan
Komponen AI tidak dapat diakses.
Rekomendasi di atas sepenuhnya dihasilkan oleh mekanisme evaluasi berbasis aturan deterministik.
{$stopCatatan}{$coverageCatatan}";
    }
}
