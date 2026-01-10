<?php

declare(strict_types=1);

namespace App\Http\Controllers\Peternak;

use App\Http\Controllers\Controller;
use App\Models\Indukan;
use App\Models\Anakan;
use App\Models\Perkawinan;
use App\Models\HasilAnalisaBreeding;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use OpenAI;

class AnalisaBreedingController extends Controller
{
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
        $riwayat = HasilAnalisaBreeding::where('peternak_id', Auth::user()->peternak->id)
            ->with(['jantan', 'betina'])
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
            'jantan_id' => 'required|exists:indukan,id',
            'betina_id' => 'required|exists:indukan,id',
            'hasil_ai'  => 'required',
            'rekomendasi' => 'required|in:uji_coba,lanjut,stop'
        ]);

        $peternakId = Auth::user()->peternak->id;

        HasilAnalisaBreeding::create([
            'peternak_id' => $peternakId,
            'jantan_id'   => $request->jantan_id,
            'betina_id'   => $request->betina_id,
            'hasil_ai'    => $request->hasil_ai,
            'rekomendasi' => $request->rekomendasi,
            'tanggal_analisa' => now(),
        ]);

        return redirect()
            ->route('peternak.analisaBreeding.riwayat')
            ->with('success', 'Hasil analisa berhasil disimpan ke riwayat.');
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

        $data = HasilAnalisaBreeding::with(['jantan', 'betina'])
            ->where('peternak_id', $peternakId)
            ->findOrFail($id);

        return view('peternak.kandang.detailAnalisa', compact('data'));
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



    /**
     * Proses analisis breeding
     */
    public function analisa(Request $request)
    {
        $request->validate([
            'jantan_id' => 'required|exists:indukan,id',
            'betina_id' => 'required|exists:indukan,id',
        ]);

        $peternakId = Auth::user()->peternak->id;

        $jantan = Indukan::where('peternak_id', $peternakId)->findOrFail($request->jantan_id);
        $betina = Indukan::where('peternak_id', $peternakId)->findOrFail($request->betina_id);

        $anakansPasangan = Anakan::where('peternak_id', $peternakId)
            ->where('indukan_jantan_id', $jantan->id)
            ->where('indukan_betina_id', $betina->id)
            ->get();

        $anakansJantanGlobal = $jantan->anakansSebagaiJantan;
        $anakansBetinaGlobal = $betina->anakansSebagaiBetina;

        $perkawinanPasangan = Perkawinan::where('peternak_id', $peternakId)
            ->where('indukan_jantan_id', $jantan->id)
            ->where('indukan_betina_id', $betina->id)
            ->orderByDesc('tanggal_kawin')
            ->get();

        $payloadJurnal = [
            'putranto_2018_reproduksi' => [
                'aturan' => [
                    'jumlah_telur_normal' => '2–4 butir',
                    'rata_rata_telur'     => 2.9,
                    'daya_tetas'          => '±94%',
                    'durasi_eram'         => '12–14 hari',
                    'umur_sapih'          => '±30 hari',
                ]
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
            $perkawinanPasangan,
            $payloadJurnal
        );

        try {
            $client = OpenAI::client(env('OPENAI_API_KEY'));

            $response = $client->chat()->create([
                'model' => env('OPENAI_MODEL', 'gpt-4o-mini'),
                'messages' => [
                    ['role' => 'system', 'content' => 'Anda adalah sistem SPK breeding Murai Batu.'],
                    ['role' => 'user', 'content' => $prompt]
                ],
                'temperature' => 0.2,
            ]);

            $hasilAnalisa = $response->choices[0]->message->content;
        } catch (\OpenAI\Exceptions\RateLimitException $e) {
            $hasilAnalisa = $this->fallbackAnalisa($jantan, $betina); // 🔥 anti putus
        } catch (\Exception $e) {
            $hasilAnalisa = $this->fallbackAnalisa($jantan, $betina);
        }

        return redirect()->route('peternak.analisaBreeding.hasil')->with([
            'hasilAnalisa' => $hasilAnalisa,
            'jantan_id'    => $jantan->id,
            'betina_id'    => $betina->id,
        ]);
    }


    public function hasil()
    {
        if (!session('hasilAnalisa')) {
            return redirect()->route('peternak.analisaBreeding.form')
                ->with('error', 'Silakan lakukan analisa terlebih dahulu.');
        }

        $jantan = Indukan::find(session('jantan_id'));
        $betina = Indukan::find(session('betina_id'));
        $hasilAnalisa = session('hasilAnalisa');

        return view('peternak.kandang.hasilAnalisa', compact(
            'hasilAnalisa',
            'jantan',
            'betina'
        ));
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
        $perkawinanPasangan,

        array $payloadJurnal
    ): string {


        /* =========================================================
     * A. DATA DASAR ANAKAN & PERKAWINAN
     * ========================================================= */
        $jumlahAnakanPasangan = $anakansPasangan->count();
        $jumlahAnakanJantanGlobal = $anakansJantanGlobal->count();
        $jumlahAnakanBetinaGlobal = $anakansBetinaGlobal->count();

        $jumlahPerkawinan = $perkawinanPasangan->count();
        $perkawinanTerakhir = $perkawinanPasangan->first();

        $tanggalKawinTerakhir = $perkawinanTerakhir
            ? $perkawinanTerakhir->tanggal_kawin
            : 'Tidak ada data';

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
                    ? "- Anakan " . ($i + 1) . ": " . implode(', ', $detail)
                    : "- Anakan " . ($i + 1) . ": Data detail belum tersedia.";
            })
            ->implode("\n");

        $ringkasanAnakanPasangan = $ringkasanAnakanPasangan
            ?: "Belum ada anakan tercatat di antara kedua pasangan ini.";

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
                ->map(fn($d) => "- {$d}")
                ->implode("\n");

            $ringkasanKarakteristikPasanganIni = $ringkasanKarakteristikPasanganIni
                ?: "- Data karakteristik anakan hasil pasangan ini belum tersedia.";
        }

        /* =========================================================
     * D. RINGKASAN KARAKTERISTIK – HASIL PASANGAN LAIN (KONTEKS)
     * ========================================================= */
        $ringkasanKarakteristikPasanganLain = '';

        $anakansPasanganLain = collect()
            ->merge($anakansJantanGlobal)
            ->merge($anakansBetinaGlobal)
            ->reject(
                fn($a) =>
                $a->indukan_jantan_id === $jantan->id &&
                    $a->indukan_betina_id === $betina->id
            );

        if ($anakansPasanganLain->count() > 0) {
            $ringkasanKarakteristikPasanganLain = $anakansPasanganLain
                ->sortByDesc('tanggal_menetas')
                ->take(5)
                ->pluck('deskripsi_karakteristik')
                ->filter()
                ->unique()
                ->map(fn($d) => "- {$d}")
                ->implode("\n");

            $ringkasanKarakteristikPasanganLain = $ringkasanKarakteristikPasanganLain
                ?: "- Data karakteristik anakan dari pasangan lain belum tersedia.";
        }

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
            : "Tidak ada data anakan dari pasangan ini secara keseluruhan.";

        /* =========================================================
     * F. DATA PERILAKU INDUKAN
     * ========================================================= */
        $perilakuJantan =
            "- Aktif Kicau: " . ($jantan->aktif_kicau ? "Ya" : "Tidak") . "\n" .
            "- Mendekati Betina: " . ($jantan->mendekati_betina ? "Ya" : "Tidak") . "\n" .
            "- Temperamen: " . ($jantan->temperamen ?: "-");

        $perilakuBetina =
            "- Nafsu Makan Meningkat: " . ($betina->nafsu_makan_meningkat ? "Ya" : "Tidak") . "\n" .
            "- Aktif Membuat Sarang: " . ($betina->aktif_buat_sarang ? "Ya" : "Tidak") . "\n" .
            "- Temperamen: " . ($betina->temperamen ?: "-");

        /* =========================================================
     * G. EVALUASI RULE-BASED
     * ========================================================= */

        $indikatorJantanLengkap = $jantan->aktif_kicau && $jantan->mendekati_betina;
        $indikatorBetinaLengkap = $betina->nafsu_makan_meningkat && $betina->aktif_buat_sarang;

        $kesimpulanJantan = $indikatorJantanLengkap
            ? 'Indikator kesiapan jantan terpenuhi.'
            : 'Indikator kesiapan jantan belum terpenuhi.';

        $kesimpulanBetina = $indikatorBetinaLengkap
            ? 'Indikator kesiapan betina terpenuhi.'
            : 'Indikator kesiapan betina belum terpenuhi.';

        $blokKarakteristik = '';

        if ($ringkasanKarakteristikPasanganIni) {
            $blokKarakteristik .= "
Ringkasan karakteristik anakan (hasil pasangan ini):
{$ringkasanKarakteristikPasanganIni}
";
        }

        if ($ringkasanKarakteristikPasanganLain) {
            $blokKarakteristik .= "
Ringkasan karakteristik anakan (hasil pasangan lain, sebagai konteks historis):
{$ringkasanKarakteristikPasanganLain}

Catatan:
Data ini digunakan sebagai konteks pengalaman indukan dan tidak dijadikan
bukti kecocokan pasangan yang dianalisis.
"; }
        /*  🔥 END BLOCK  */

        return <<<PROMPT

========================
TUGAS ANDA
========================
Susun laporan analisis breeding Murai Batu sebagai
sistem pendukung keputusan (Decision Support System).

Laporan harus disusun secara terstruktur, objektif,
dan berbasis data faktual tanpa spekulasi biologis.

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
genetika, atau kualitas anakan.

------------------------------------------------

C. Evaluasi Riwayat Breeding & Interpretasi Manajerial

Tujuan bagian ini adalah mengevaluasi riwayat produksi
secara manajerial dan membandingkannya dengan kondisi
perilaku indukan saat ini.

1. Konteks Riwayat Produksi Individu Indukan

- Riwayat pasangan ini:
  Pasangan ini telah menghasilkan {$jumlahAnakanPasangan} anakan.
  - Rincian anakan:
{$ringkasanAnakanPasangan}

- Riwayat jantan dengan pasangan lain:
  Jantan memiliki {$jumlahAnakanJantanLain} anakan
  dari pasangan lain di luar pasangan ini.

- Riwayat betina dengan pasangan lain:
  Betina memiliki {$jumlahAnakanBetinaLain} anakan
  dari pasangan lain di luar pasangan ini.

Catatan:
Riwayat produksi individu indukan digunakan sebagai
indikator pengalaman reproduksi.
Data ini tidak dijadikan bukti kecocokan pasangan
yang sedang dianalisis.


2. Riwayat perkawinan pasangan:
- Jumlah perkawinan tercatat: {$jumlahPerkawinan}
- Perkawinan terakhir:
  - Tanggal kawin: {$tanggalKawinTerakhir}

Catatan penting:
- Gunakan HANYA data yang tertulis di atas.
- DILARANG menyebutkan angka, karakter,
  atau hasil yang tidak tersedia di sistem.
- Jika data tidak tersedia, tuliskan secara eksplisit:
  "Tidak ada data sistem."

3. Kesimpulan bagian ini:
Jelaskan status reproduksi pasangan ini secara manajerial,
misalnya apakah pasangan telah terbukti produktif secara historis
dan apakah kondisi perilaku saat ini selaras atau tidak
dengan riwayat tersebut.

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

Hindari klaim ekstrem dan hindari prediksi biologis pasti.

------------------------------------------------

F. Rekomendasi Akhir Sistem
(WAJIB memilih salah satu)

Gunakan prinsip berikut:

1. Jika pasangan PERNAH menghasilkan anakan,
   namun indikator perilaku SAAT INI belum lengkap:
   → Rekomendasi: LAYAK DILANJUTKAN DENGAN PENGAWASAN

2. Jika pasangan BELUM pernah menghasilkan anakan:
   → Rekomendasi: UJI COBA TERBATAS

3. Jika ditemukan risiko serius dan berulang:
   → Rekomendasi: TIDAK DIREKOMENDASIKAN SEMENTARA

Sertakan alasan singkat berbasis data historis
dan kondisi perilaku saat ini.

------------------------------------------------

G. Rekomendasi Tindakan Praktis

Bagian ini menyajikan opsi tindakan yang dapat dipertimbangkan oleh peternak
berdasarkan indikator perilaku yang belum terpenuhi.
Pelaksanaan dapat disesuaikan dengan pengalaman dan kondisi lapangan.

Indikator 1

Indikator: Jantan belum menunjukkan perilaku mendekati betina.

→ Tindakan yang dapat dipertimbangkan:
Pengaturan ulang interaksi visual (misalnya menggunakan sekat/tirai sementara)
untuk mengurangi tekanan interaksi langsung pada fase awal.

→ Durasi pengamatan:
Sekitar 3–5 hari.

→ Evaluasi ulang disarankan:
Sekitar hari ke-7, dengan melihat apakah jantan mulai menunjukkan
ketertarikan atau pendekatan saat interaksi dibuka bertahap.

Indikator 2

Indikator: Nafsu makan betina belum meningkat.

→ Tindakan yang dapat dipertimbangkan:
Menjaga kondisi kandang tetap stabil (minim gangguan, rutinitas pakan konsisten,
tanpa perubahan posisi kandang) untuk membantu proses adaptasi.

→ Durasi pengamatan:
Sekitar 5–7 hari.

→ Evaluasi ulang disarankan:
Sekitar hari ke-7, dengan memperhatikan pola konsumsi pakan dan
aktivitas harian betina.

Catatan umum:

Selama masa pengamatan, tidak perlu terburu-buru melakukan perubahan besar.

Fokus utama adalah melihat arah perubahan indikator, bukan memaksakan hasil
dalam waktu singkat.

H. Potensi Tantangan Awal

Berdasarkan data perilaku dan riwayat breeding yang tersedia,
beberapa tantangan awal yang umumnya ditemui di kandang antara lain:

Adaptasi lingkungan kandang
Indikator nafsu makan betina yang belum meningkat sering muncul
pada fase penyesuaian awal terhadap kondisi kandang dan pasangan.

Interaksi awal yang belum stabil
Jantan yang belum mendekati betina dapat menandakan
proses pengenalan yang masih berjalan dan membutuhkan waktu.

Ketidaksinkronan sementara antara riwayat dan kondisi saat ini
Meskipun pasangan memiliki riwayat menghasilkan anakan,
kondisi perilaku saat ini dapat berbeda dan perlu disikapi
dengan pengamatan bertahap.

Catatan:
Tantangan di atas menunjukkan sinyal awal bahwa pasangan memerlukan
waktu adaptasi dan pemantauan lanjutan sebelum diambil keputusan berikutnya.

------------------------------------------------

I. Early Warning – Kondisi Stop Pairing

Berikan batasan kondisi kapan pairing
perlu dihentikan sementara, seperti:
- agresivitas jantan berulang,
- penurunan kondisi betina,
- tidak ada respon interaksi dalam 30 hari,
- luka fisik atau tanda stres berat.

------------------------------------------------

J. Rekomendasi Perawatan & Pemulihan (Jika Diperlukan)

Jika terdapat indikator yang belum terpenuhi
atau risiko yang teridentifikasi:
- Berikan saran perawatan atau adaptasi bersifat umum.
- Sertakan durasi pemantauan (misalnya 7–14 hari).
- Sertakan hal yang perlu dihindari.
- Tekankan perlunya evaluasi ulang.

Jika semua indikator terpenuhi, tuliskan:
"Tidak diperlukan perawatan tambahan saat ini."

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

    private function fallbackAnalisa($jantan, $betina)
    {
        return "# Laporan Analisis Breeding (Fallback Mode — AI Limit)
A. Ringkasan Singkat
- Jantan: {$jantan->nomor_ring} ({$jantan->nama})
- Betina: {$betina->nomor_ring} ({$betina->nama})

B. AI tidak bisa diakses karena batas penggunaan (rate limit).
Namun sistem tetap memberikan rekomendasi awal berdasarkan aturan breeding.

C. Rekomendasi Awal
- Lakukan uji coba perkawinan terbatas 14-30 hari.
- Pantau interaksi, kesiapan sarang & kondisi fisik harian.

D. Catatan
Ini bukan analisa AI penuh — hanya mode darurat untuk memastikan fitur tetap berjalan.
";
    }
}
