<?php

declare(strict_types=1);

namespace App\Http\Controllers\Peternak;

use App\Http\Controllers\Controller;
use App\Models\Indukan;
use App\Models\Anakan;
use App\Models\Perkawinan;
use App\Models\HasilAnalisaBreeding;

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
        $riwayat = HasilAnalisaBreeding::where('peternak_id',Auth::user()->peternak->id)
                    ->with(['jantan','betina'])
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

    $data = HasilAnalisaBreeding::with(['jantan','betina'])
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
            'indikator_jantan' => ['aktif_berkicau','mendekati_betina'],
            'indikator_betina' => ['nafsu_makan_meningkat','aktif_membuat_sarang'],
        ],
        'simatupang_2022_risiko' => [
            'risiko_umum' => ['stres lingkungan','adaptasi awal','produktif rendah'],
        ],
    ];

    $prompt = $this->generatePrompt(
        $jantan,$betina,
        $anakansPasangan,$anakansJantanGlobal,$anakansBetinaGlobal,
        $perkawinanPasangan,$payloadJurnal
    );

    try {
        $client = OpenAI::client(env('OPENAI_API_KEY'));

        $response = $client->chat()->create([
            'model'=>env('OPENAI_MODEL','gpt-4o-mini'),
            'messages'=>[
                ['role'=>'system','content'=>'Anda adalah sistem SPK breeding Murai Batu.'],
                ['role'=>'user','content'=>$prompt]
            ],
            'temperature'=>0.2,
        ]);

        $hasilAnalisa = $response->choices[0]->message->content;

    } catch (\OpenAI\Exceptions\RateLimitException $e) {
        $hasilAnalisa = $this->fallbackAnalisa($jantan,$betina); // 🔥 anti putus
    } catch (\Exception $e) {
        $hasilAnalisa = $this->fallbackAnalisa($jantan,$betina);
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
        'hasilAnalisa','jantan','betina'
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
    $jumlahAnakanPasangan = $anakansPasangan->count();

$ringkasanAnakanPasangan = $anakansPasangan->map(function ($a) {
    return "- {$a->nama_anakan} ({$a->jenis_kelamin}), status: {$a->status}, menetas: {$a->tanggal_menetas}";
})->implode("\n");

$ringkasanAnakanPasangan = $ringkasanAnakanPasangan ?: "Belum ada anakan tercatat diantara kedua pasangan ini.";

$jumlahAnakanJantanGlobal = $anakansJantanGlobal->count();
$jumlahAnakanBetinaGlobal = $anakansBetinaGlobal->count();

$ringkasanGlobalText = [];

if ($jumlahAnakanJantanGlobal > 0) {
    $ringkasanGlobalText[] =
        "Jantan memiliki {$jumlahAnakanJantanGlobal} anakan dari pasangan lain (data sistem).";
}

if ($jumlahAnakanBetinaGlobal > 0) {
    $ringkasanGlobalText[] =
        "Betina memiliki {$jumlahAnakanBetinaGlobal} anakan dari pasangan lain (data sistem).";
}

$ringkasanGlobalText = $ringkasanGlobalText
    ? implode("\n", $ringkasanGlobalText)
    : "Tidak ada data anakan dari pasangan lain dalam sistem.";



    $jumlahPerkawinan = $perkawinanPasangan->count();
    $perkawinanTerakhir = $perkawinanPasangan->first();

    $ringkasanAnakanJantan = $anakansJantanGlobal
        ->pluck('deskripsi_karakteristik')
        ->filter()
        ->take(5)
        ->implode('; ') ?: 'Belum tersedia';

    $ringkasanAnakanBetina = $anakansBetinaGlobal
        ->pluck('deskripsi_karakteristik')
        ->filter()
        ->take(5)
        ->implode('; ') ?: 'Belum tersedia';

        $tanggalKawinTerakhir = $perkawinanTerakhir
    ? $perkawinanTerakhir->tanggal_kawin
    : 'Tidak ada data';

$hasilPerkawinanTerakhir = $perkawinanTerakhir
    ? ($perkawinanTerakhir->hasil ?? 'Tidak ada data')
    : 'Tidak ada data';


    $jurnalJson = json_encode($payloadJurnal, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

    /*  🔥 Masukkan ini DI SINI sebelum heredoc return */
    $perilakuJantan = 
        "- Aktif Kicau: " . ($jantan->aktif_kicau ? "Ya" : "Tidak") . "\n" .
        "- Mendekati Betina: " . ($jantan->mendekati_betina ? "Ya" : "Tidak") . "\n" .
        "- Temperamen: " . ($jantan->temperamen ?: "-");

    $perilakuBetina = 
        "- Nafsu Makan Meningkat: " . ($betina->nafsu_makan_meningkat ? "Ya" : "Tidak") . "\n" .
        "- Aktif Membuat Sarang: " . ($betina->aktif_buat_sarang ? "Ya" : "Tidak") . "\n" .
        "- Temperamen: " . ($betina->temperamen ?: "-");

        $aktifKicauText = $jantan->aktif_kicau ? 'Ya' : 'Tidak';
$mendekatiBetinaText = $jantan->mendekati_betina ? 'Ya' : 'Tidak';

$nafsuMakanText = $betina->nafsu_makan_meningkat ? 'Ya' : 'Tidak';
$buatSarangText = $betina->aktif_buat_sarang ? 'Ya' : 'Tidak';

/**
 * Evaluasi rule-based (BUKAN AI)
 */
$indikatorJantanLengkap = $jantan->aktif_kicau && $jantan->mendekati_betina;
$indikatorBetinaLengkap = $betina->nafsu_makan_meningkat && $betina->aktif_buat_sarang;

$kesimpulanJantan = $indikatorJantanLengkap
    ? 'Indikator kesiapan jantan terpenuhi.'
    : 'Indikator kesiapan jantan belum terpenuhi.';

$kesimpulanBetina = $indikatorBetinaLengkap
    ? 'Indikator kesiapan betina terpenuhi.'
    : 'Indikator kesiapan betina belum terpenuhi.';

    /*  🔥 END BLOCK  */

    return <<<PROMPT

========================
TUGAS ANDA
========================
Susun laporan analisis breeding sesuai format berikut, lengkap, rinci,
dan gunakan bahasa objektif berbasis data:

A. Ringkasan Data Indukan  
- A. Ringkasan Singkat
- Jantan: {$jantan->nomor_ring} ({$jantan->nama})
Perilaku jantan:
{$perilakuJantan}

- Betina: {$betina->nomor_ring} ({$betina->nama})
Perilaku betina:
{$perilakuBetina}
ATURAN PERILAKU:
- Gunakan nilai Ya/Tidak persis seperti data sistem.
- DILARANG mengubah atau menyimpulkan ulang perilaku indukan.
- Jika nilai = Tidak, tuliskan sebagai Tidak.
- Jangan menambahkan perilaku yang tidak tercantum.

B. Evaluasi Kecocokan Jantan × Betina  

Evaluasi berbasis aturan sistem:
- Kesiapan jantan:
  {$kesimpulanJantan}
- Kesiapan betina:
  {$kesimpulanBetina}

Catatan:
Evaluasi dilakukan berdasarkan aturan perilaku yang telah ditetapkan sistem.
Tidak dilakukan interpretasi ulang terhadap data perilaku indukan.
Evaluasi ini bukan prediksi hasil anakan atau genetika.

C. Evaluasi Riwayat Breeding & Anakan  
1. Data sistem pasangan ini:
- Jumlah anakan dari pasangan ini: {$jumlahAnakanPasangan}
- Rincian anakan:
{$ringkasanAnakanPasangan}

2. Riwayat produksi indukan secara keseluruhan:
{$ringkasanGlobalText}

Ringkasan karakteristik anakan (konteks historis):
- Jantan: {$ringkasanAnakanJantan}
- Betina: {$ringkasanAnakanBetina}

Riwayat perkawinan pasangan:
- Jumlah perkawinan tercatat: {$jumlahPerkawinan}
- Perkawinan terakhir:
  - Tanggal kawin: {$tanggalKawinTerakhir}
  - Hasil: {$hasilPerkawinanTerakhir}


Catatan penting:
- Gunakan HANYA data yang tertulis di atas.
- DILARANG menyebutkan angka, karakter, atau hasil yang tidak tercantum.
- Jika data tidak tersedia, tuliskan secara eksplisit:
  "Tidak ada data sistem."

3. Kesimpulan bagian ini → jelaskan status reproduksi pasangan ini

D. Evaluasi Standar Reproduksi (berdasarkan jurnal Putranto 2018)  
- Jika ada anakan → bandingkan dengan standar 2–4 telur, daya tetas, dsb  
- Jika tidak ada anakan → tulis "belum dapat dievaluasi terhadap standar"

E. Evaluasi Kesiapan Produksi Berdasarkan Perilaku (Saputro 2016)  
- Jika jantan aktif kicau + mendekati betina → catat sebagai indikator kesiapan  
- Jika betina nafsu makan meningkat + membuat sarang → catat indikator kesiapan  
- Jika salah satu tidak ada → tulis "indikator belum lengkap"  

F. Risiko & Catatan Manajemen  
- Sebutkan potensi risiko realistis (dominansi, stres, adaptasi kandang)  
- Tanpa klaim ekstrem atau prediksi biologis pasti  

G. Rekomendasi Akhir Sistem (WAJIB MEMILIH SALAH SATU)

Gunakan rule:
- Jika belum pernah menghasilkan anakan → **Rekomendasi: UJI COBA TERBATAS**
- Jika pernah menghasilkan anakan → **Rekomendasi: LAYAK DILANJUTKAN**
- Jika temuan risiko serius → **TIDAK DIREKOMENDASIKAN SEMENTARA**

Tambahkan alasan berbasis data singkat dan jelas.

H. Rekomendasi Tindakan Praktis  
- Langkah yang harus dilakukan peternak  
- Hal yang dihindari  
- Waktu evaluasi ulang yang disarankan  

I. Potensi Tantangan Awal
Berikan daftar kendala awal yang mungkin terjadi berdasarkan perilaku dan riwayat breeding.
Gunakan pendekatan rule-based, contoh:
- betina belum membuat sarang → adaptasi awal
- jantan terlalu dominan → potensi agresi
- belum ada riwayat pasangan → butuh waktu observasi

J. Early Warning – Kondisi Stop Pairing
Berikan batasan kapan breeder harus menghentikan pairing sementara.
Contoh rule:
- terjadi agresivitas jantan berulang
- betina drop konsumsi atau terlihat stress
- tidak ada respon interaksi 30 hari
- luka fisik atau tanda teror/kecemasan pada salah satu indukan

K. Rekomendasi Perawatan & Pemulihan (Jika Diperlukan)

Jika pada evaluasi ditemukan indikator yang belum terpenuhi atau risiko:
- Berikan saran perawatan atau adaptasi bersifat umum.
- Sertakan durasi pemantauan (misalnya 7–14 hari).
- Sertakan hal yang perlu dihindari.
- Tekankan bahwa evaluasi ulang tetap diperlukan.

Jika semua indikator terpenuhi, tuliskan:
"Tidak diperlukan perawatan tambahan saat ini."



Gunakan bahasa operasional, tidak berspekulasi, tanpa kata mungkin/probabilitas.
Fokus pada keputusan berbasis aturan jurnal & fakta data sistem.

========================
BATASAN BAHASA
========================
Gunakan bahasa profesional, objektif, dan instruktif.
Hindari prediksi genetika, hindari klaim hasil anakan.
Tujuan laporan adalah **memandu keputusan**, bukan memprediksi


PROMPT;
    }

    private function fallbackAnalisa($jantan,$betina)
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
