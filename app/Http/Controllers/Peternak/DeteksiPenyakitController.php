<?php

namespace App\Http\Controllers\Peternak;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use OpenAI; // <- dari openai-php/client
use App\Models\DeteksiPenyakit;
use App\Models\DeteksiFoto;
use Barryvdh\DomPDF\Facade\Pdf;


class DeteksiPenyakitController extends Controller
{
    public function index()
    {
        return view('peternak.deteksi-penyakit.index');
    }



    public function process(Request $request)
    {
        // ==========================
        // 1. VALIDASI
        // ==========================
        $request->validate([
            'foto.*' => 'required|image|mimes:jpeg,png,jpg|max:4096',
            'gejala' => 'required|min:20',
            'perilaku' => 'required|array|min:1',
            'makanan' => 'nullable|string',
            'lingkungan' => 'nullable|string',
            'riwayat_kesehatan' => 'nullable|string',
            'nama_burung' => 'nullable|string|max:100'
        ]);

        $peternak = Auth::user()->peternak;

        // ==========================
        // 2. CEK KUOTA PAKET
        // ==========================
        $isPro = $peternak->jenis_akun === 'pro';
        $kuota = $isPro ? 8 : 3;

        if ($peternak->deteksi_terpakai >= $kuota) {
            return back()->with('error', "Kuota deteksi paket " . strtoupper($peternak->jenis_akun) . " telah habis.");
        }


        // ==========================
        // 3. SIMPAN FOTO DAN BUAT BASE64
        // ==========================
        $fotoBase64List = [];
        $fotoPaths = [];

        foreach ($request->file('foto') as $image) {

            $path = $image->store('deteksi_foto', 'public');
            $fotoPaths[] = $path;

            $fullPath = storage_path("app/public/" . $path);
            $base64 = base64_encode(file_get_contents($fullPath));

            $fotoBase64List[] = [
                "type" => "image_url",
                "image_url" => [
                    "url" => "data:image/jpeg;base64," . $base64
                ]
            ];
        }


        // ==========================
        // 4. KLIENT OPENAI
        // ==========================
        $client = OpenAI::client(env('OPENAI_API_KEY'));


        // ==========================
        // 5. PERSIAPKAN PESAN KE MODEL
        // ==========================
        $messages = [
            [
                "role" => "system",
                "content" => "Anda adalah dokter hewan spesialis burung Murai Batu, dengan fokus pada evaluasi visual dan gejala klinis. Analisis harus berbasis bukti, tidak boleh mengarang, dan tidak boleh menggunakan istilah medis yang tidak relevan.

Diagnosis harus dibangun dari dua sumber data:
Analisis Foto
Analisis Gejala Tekstual
Anda wajib mengikuti struktur dan batasan berikut.
---
## **TAHAP 1 — ANALISIS FOTO (WAJIB, TIDAK BOLEH DI SKIP)**
Lakukan secara berurutan:
### **1. Validasi Foto**
Tentukan:
* Apakah foto menampilkan seekor burung? (YA/TIDAK)
* Jika YA, apakah kemungkinan besar merupakan burung Murai Batu? (YA/TIDAK)
Jika TIDAK ada burung →
**Validasi Foto:** Foto tidak menampilkan burung. Analisis dilakukan hanya berdasarkan gejala teks.
Jika burung tapi bukan Murai Batu →
**Validasi Foto:** Burung terdeteksi, tetapi bukan burung Murai Batu. Analisis tetap dilakukan berdasarkan gejala teks.
### **2. Analisis Klinis Dari Foto**
Wajib periksa, tanpa mengarang detail yang tidak terlihat:
* Kondisi bulu: (rapi / kusam / mengembang)
* Mata: (cerah / sayu / tertutup / berair)
* Posisi tubuh: (tegap / lesu / menunduk)
* Pernafasan: (normal / tampak megap-megap jika terlihat)
* Paruh & hidung: (bersih / ada cairan)
* Aktivitas fisik yang tampak
* Tanda stres visual
Jika bagian tertentu tidak terlihat → tulis **“tidak terlihat pada foto”**, bukan mengarang.
### **3. Skor Kualitas Foto (0–100)**
Seberapa besar foto membantu diagnosis? Beri skor dan alasan jelas.
Contoh: *“Skor 65 — foto cukup jelas untuk menilai kondisi bulu dan mata, tetapi bagian dada tidak terlihat.”*
### **4. Kesimpulan Sementara dari Foto**
Nyatakan apakah temuan foto:
* Mendukung gejala teks
* Netral
* Berkonflik
---
## **TAHAP 2 — ANALISIS GEJALA TEKS**
Gunakan semua data yang diberikan peternak:
* Perilaku
* Nafsu makan
* Pola kicau
* Jenis pakan
* Kebersihan kandang
* Riwayat penyakit
* Durasi gejala
* Faktor stres lingkungan
Tidak boleh mengarang gejala yang tidak ada di teks.
Berikan interpretasi klinis berdasarkan pola gejala yang umum pada Murai Batu.
---
## **TAHAP 3 — INTEGRASI FOTO + GEJALA (HARUS DIGABUNGKAN)**
Jelaskan:
* Bagaimana hasil foto + gejala saling mendukung
* Jika ada konflik, jelaskan perbedaannya
* Diagnosis dibuat berdasarkan bukti mayoritas
* Berikan tingkat kepercayaan (%) yang realistis
* Tentukan tingkat keparahan berdasarkan gabungan keduanya
---
## **📌 FORMAT OUTPUT WAJIB (STRICT FORMAT)**
Tidak boleh keluar dari format berikut:
### **1. Validasi & Analisis Foto**
* Validasi Foto: …
* Temuan Klinis dari Foto: …
* Skor Kualitas Foto: …
* Kesimpulan Foto: …
### **2. Analisis Gejala Teks**

(Analisis klinis berbasis gejala)
### **3. Integrasi Foto + Gejala — Diagnosis Final**
* **Diagnosis Utama:**
* **Alasan Klinis:** (wajib berdasarkan bukti foto + teks)
* **Kemungkinan Penyebab:**
* **Tingkat Keparahan:** ringan / sedang / berat
* **Tingkat Kepercayaan (%):**
* **Rekomendasi Tindakan:**
* **Perlu Dibawa ke Dokter Hewan?:** YA/TIDAK + alasan
### **4. Catatan Penting**
Diagnosis AI hanya pendukung dan tidak menggantikan pemeriksaan dokter hewan secara langsung.
"
            ],
            [
                "role" => "user",
                "content" => [
                    [
                        "type" => "text",
                        "text" =>
                        "Nama Burung: " . ($request->nama_burung ?? "-") . "\n" .
                            "Gejala: " . $request->gejala . "\n" .
                            "Perilaku: " . implode(", ", $request->perilaku) . "\n" .
                            "Makanan: " . ($request->makanan ?? "-") . "\n" .
                            "Lingkungan: " . ($request->lingkungan ?? "-") . "\n" .
                            "Riwayat Kesehatan: " . ($request->riwayat_kesehatan ?? "-")
                    ],
                    ...$fotoBase64List
                ]
            ]
        ];


        // ==========================
        // 6. PANGGIL API
        // ==========================
        try {
            $response = $client->chat()->create([
                'model' => env('OPENAI_MODEL', 'gpt-4o-mini'),
                'messages' => $messages
            ]);
        } catch (\Exception $e) {
            return back()->with('error', "Gagal menghubungi OpenAI API: " . $e->getMessage());
        }

        $output = $response->choices[0]->message->content ?? "Tidak ada respons model.";


        // ==========================
        // 7. SIMPAN HASIL KE DATABASE
        // ==========================
        $deteksi = DeteksiPenyakit::create([
            'peternak_id'       => $peternak->id,
            'nama_burung'       => $request->nama_burung,
            'gejala'            => $request->gejala,
            'perilaku'          => json_encode($request->perilaku),
            'makanan'           => $request->makanan,
            'lingkungan'        => $request->lingkungan,
            'riwayat_kesehatan' => $request->riwayat_kesehatan,
            'hasil_analisis'    => $output,
            'diagnosis_utama'   => $this->extractDiagnosis($output),
            'tingkat_kepercayaan' => $this->extractConfidence($output),
            'rekomendasi'       => $this->extractRecommendation($output),
            'is_saved'          => true,
        ]);

        foreach ($fotoPaths as $p) {
            DeteksiFoto::create([
                'deteksi_id' => $deteksi->id,
                'foto_path' => $p
            ]);
        }

        $peternak->increment('deteksi_terpakai');


        // ==========================
        // 8. REDIRECT HASIL
        // ==========================
        return redirect()->route('peternak.deteksi-penyakit.hasil', $deteksi->id);
    }


    // ==========================
    // Halaman hasil
    // ==========================
    public function hasil($id)
    {
        $deteksi = DeteksiPenyakit::with('fotos')->findOrFail($id);
        return view('peternak.deteksi-penyakit.hasil', compact('deteksi'));
    }
    // ==========================
    // UTILITIES: Ekstraksi data
    // ==========================

    private function extractDiagnosis($text)
    {
        // Cari pola: "### Diagnosis Utama:" lalu ambil baris setelahnya (tanpa "- ")
        if (preg_match('/Diagnosis Utama[:\-]*\s*\R[-*\s]*(.+)/i', $text, $match)) {
            return trim($match[1]);
        }

        // fallback: cari kata "Diagnosis:" biasa
        if (preg_match('/Diagnosis[:\-]*\s*(.+)/i', $text, $match)) {
            return trim($match[1]);
        }

        return null;
    }

    private function extractConfidence($text)
    {
        // Prioritas: cari di bagian "Tingkat Kepercayaan"
        if (preg_match('/Tingkat Kepercayaan[:\-]*\s*([0-9]{1,3})\s*%/i', $text, $match)) {
            return (int) $match[1];
        }

        // fallback: ambil angka persen pertama yang muncul
        if (preg_match('/([0-9]{1,3})\s*%/', $text, $match)) {
            return (int) $match[1];
        }

        return null;
    }

    private function extractRecommendation($text)
    {
        // Cari "Rekomendasi Tindakan" atau "Rekomendasi:"
        if (preg_match('/Rekomendasi(?: Tindakan)?[:\-]*\s*\R(.+)/i', $text, $match)) {
            return trim($match[1]);
        }

        return null;
    }


    public function pdf($id)
    {
        $deteksi = DeteksiPenyakit::with('fotos')->findOrFail($id);

        $pdf = Pdf::loadView('peternak.deteksi-penyakit.pdf', [
            'deteksi' => $deteksi
        ]);

        $filename = 'Laporan-Deteksi-' . $deteksi->id . '.pdf';

        return $pdf->download($filename);
    }
}
