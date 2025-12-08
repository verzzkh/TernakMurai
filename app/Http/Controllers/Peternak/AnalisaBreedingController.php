<?php

declare(strict_types=1);

namespace App\Http\Controllers\Peternak;

use App\Http\Controllers\Controller;
use App\Models\Indukan;
use App\Models\Perkawinan;
use App\Models\Anakan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use OpenAI;

class AnalisaBreedingController extends Controller
{
    /**
     * Tampilkan form pemilihan indukan jantan × betina
     */
    public function form()
    {
        $peternakId = Auth::user()->peternak->id;

        $jantan = Indukan::where('peternak_id', $peternakId)
            ->where('jenis_kelamin', 'jantan')
            ->orderBy('nomor_ring')
            ->get();

        $betina = Indukan::where('peternak_id', $peternakId)
            ->where('jenis_kelamin', 'betina')
            ->orderBy('nomor_ring')
            ->get();

        return view('peternak.kandang.formAnalisa', compact('jantan', 'betina'));
    }


    /**
     * Proses analisis kecocokan indukan
     */
    public function analisa(Request $request)
    {
        $request->validate([
            'jantan_id' => 'required|exists:indukan,id',
            'betina_id' => 'required|exists:indukan,id',
        ]);

        $peternakId = Auth::user()->peternak->id;

        // Pastikan indukan milik peternak yang login
        $jantan = Indukan::where('peternak_id', $peternakId)
            ->with(['perkawinansJantan.anakans'])
            ->findOrFail($request->jantan_id);

        $betina = Indukan::where('peternak_id', $peternakId)
            ->with(['perkawinansBetina.anakans'])
            ->findOrFail($request->betina_id);

        /*
        |--------------------------------------------------------------------------
        | Ambil riwayat perkawinan bersama (jantan × betina ini)
        |--------------------------------------------------------------------------
        */
        $riwayatPerkawinan = Perkawinan::where('peternak_id', $peternakId)
            ->where('indukan_jantan_id', $jantan->id)
            ->where('indukan_betina_id', $betina->id)
            ->with('anakans')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Ambil semua anakan yang berasal dari kedua indukan ini
        |--------------------------------------------------------------------------
        */
        $anakans = Anakan::where('peternak_id', $peternakId)
            ->where('indukan_jantan_id', $jantan->id)
            ->where('indukan_betina_id', $betina->id)
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Susun payload untuk AI
        |--------------------------------------------------------------------------
        */
        $payload = [
            'indukan_jantan' => [
                'id'           => $jantan->id,
                'nomor_ring'   => $jantan->nomor_ring,
                'nama'         => $jantan->nama,
                'umur'         => $jantan->age['formatted'] ?? null,
                'karakteristik'=> $jantan->karakteristik,
                'prestasi'     => $jantan->prestasi,
                'catatan'      => $jantan->catatan,
                'total_trip'   => $jantan->perkawinansJantan->count(),
                'total_anakan' => $jantan->anakansSebagaiJantan->count(),
            ],

            'indukan_betina' => [
                'id'           => $betina->id,
                'nomor_ring'   => $betina->nomor_ring,
                'nama'         => $betina->nama,
                'umur'         => $betina->age['formatted'] ?? null,
                'karakteristik'=> $betina->karakteristik,
                'prestasi'     => $betina->prestasi,
                'catatan'      => $betina->catatan,
                'total_trip'   => $betina->perkawinansBetina->count(),
                'total_anakan' => $betina->anakansSebagaiBetina->count(),
            ],

            'riwayat_perkawinan' => $riwayatPerkawinan->map(function ($p) {
                return [
                    'nomor_trip'     => $p->nomor_trip,
                    'tanggal_kawin'  => optional($p->tanggal_kawin)->toDateString(),
                    'total_anakan'   => $p->anakans->count(),
                ];
            }),

            'riwayat_anakan' => $anakans->map(function ($a) {
                return [
                    'nomor_ring'        => $a->nomor_ring,
                    'tanggal_lahir'     => optional($a->tanggal_lahir)->toDateString(),
                    'jenis_kelamin'     => $a->jenis_kelamin,
                    'status_pertumbuhan'=> $a->status_pertumbuhan,
                    'karakteristik'     => $a->deskripsi_karakteristik,
                    'harga'             => $a->harga,
                ];
            }),
        ];

        $prompt = $this->generatePrompt($payload);

        /*
        |--------------------------------------------------------------------------
        | Panggil OpenAI
        |--------------------------------------------------------------------------
        */
        try {
            $client = OpenAI::client(env('OPENAI_API_KEY'));

            $response = $client->chat()->create([
                'model' => env('OPENAI_MODEL', 'gpt-4o-mini'),
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => 'Anda adalah ahli breeding Murai Batu. Analisis harus berbasis data riil dari catatan peternakan.'
                    ],
                    [
                        'role' => 'user',
                        'content' => $prompt
                    ]
                ]
            ]);
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menghubungi AI: ' . $e->getMessage());
        }

        $hasilAnalisa = $response->choices[0]->message->content ?? 'Tidak ada hasil analisa.';

        return view('peternak.kandang.hasilAnalisa', compact('hasilAnalisa', 'jantan', 'betina'));
    }

    /**
     * Prompt AI untuk analisa breeding
     */
    private function generatePrompt(array $payload): string
    {
        $json = json_encode($payload, JSON_PRETTY_PRINT);

        return <<<PROMPT
Berikut data dua indukan Murai Batu yang akan dianalisis:

$json

Tugas Anda:

1. Analisis Kecocokan Jantan × Betina
   - Kesesuaian karakteristik
   - Kesesuaian genetik & sifat dominan
   - Potensi masalah (overfighter, terlalu jinak, agresif, dll)

2. Analisis Riwayat Breeding
   - Jika mereka pernah kawin sebelumnya, bagaimana hasilnya?
   - Kualitas & konsistensi anak sebelumnya

3. Prediksi Kualitas Anak
   - Potensi mental
   - Potensi fisik (postur, warna, ekor)
   - Potensi harga

4. Risiko & Catatan Penting
   - Risiko kesehatan
   - Risiko inbreeding

5. Rekomendasi
   - Layak dikawinkan? (YA/TIDAK)
   - Tingkat kecocokan (0–100%)
   - Ringkasan alasan yang mudah dipahami

Gunakan bahasa Indonesia yang rapi dan sistematis.
PROMPT;
    }
}
