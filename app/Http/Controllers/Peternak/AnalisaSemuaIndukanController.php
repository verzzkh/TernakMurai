<?php

declare(strict_types=1);

namespace App\Http\Controllers\Peternak;

use App\Http\Controllers\Controller;
use App\Models\Indukan;
use App\Models\Perkawinan;
use App\Models\Anakan;
use Illuminate\Support\Facades\Auth;
use OpenAI;

class AnalisaSemuaIndukanController extends Controller
{
    public function index()
    {
        // Ambil peternak ID dari user login
        $user = Auth::user();
        $peternakId = $user->peternak->id ?? null;

        if (! $peternakId) {
            abort(403, 'Peternak tidak ditemukan untuk user ini.');
        }

        /*
        |--------------------------------------------------------------------------
        | 1. Ambil semua data indukan, perkawinan, dan anakan
        |--------------------------------------------------------------------------
        */

        $indukans = Indukan::where('peternak_id', $peternakId)
            ->with([
                'perkawinansJantan.anakans',
                'perkawinansBetina.anakans',
                'anakansSebagaiJantan',
                'anakansSebagaiBetina',
            ])
            ->get();

        $perkawinans = Perkawinan::where('peternak_id', $peternakId)
            ->with([
                'indukanJantan',
                'indukanBetina',
                'anakans'
            ])
            ->get();

        $anakans = Anakan::where('peternak_id', $peternakId)
            ->with([
                'perkawinan.indukanJantan',
                'perkawinan.indukanBetina'
            ])
            ->get();

        /*
        |--------------------------------------------------------------------------
        | 2. Siapkan payload untuk AI
        |--------------------------------------------------------------------------
        */

        $payload = [
            'indukan' => $indukans->map(function (Indukan $i) {
                $totalTrip = $i->perkawinansJantan->count() + $i->perkawinansBetina->count();
                $totalAnakan = $i->anakans->count();

                return [
                    'id'                => $i->id,
                    'nama'              => $i->nama,
                    'nomor_ring'        => $i->nomor_ring,
                    'jenis_kelamin'     => $i->jenis_kelamin,
                    'umur'              => $i->age['formatted'] ?? null,
                    'karakteristik'     => $i->karakteristik,
                    'prestasi'          => $i->prestasi,
                    'catatan'           => $i->catatan,
                    'total_trip'        => $totalTrip,
                    'total_anakan'      => $totalAnakan,
                ];
            })->values(),

            'perkawinan' => $perkawinans->map(function (Perkawinan $p) {
                return [
                    'id'              => $p->id,
                    'nomor_trip'      => $p->nomor_trip,
                    'tanggal_kawin'   => optional($p->tanggal_kawin)->toDateString(),
                    'kandang_id'      => $p->kandang_id,
                    'indukan_jantan_id' => $p->indukan_jantan_id,
                    'indukan_betina_id' => $p->indukan_betina_id,
                    'total_anakan'    => $p->anakans->count(),
                ];
            })->values(),

            'anakan' => $anakans->map(function (Anakan $a) {
                return [
                    'id'                      => $a->id,
                    'nomor_ring'              => $a->nomor_ring,
                    'indukan_jantan_id'       => $a->indukan_jantan_id,
                    'indukan_betina_id'       => $a->indukan_betina_id,
                    'perkawinan_id'           => $a->perkawinan_id,
                    'tanggal_lahir'           => optional($a->tanggal_lahir)->toDateString(),
                    'jenis_kelamin'           => $a->jenis_kelamin,
                    'status_pertumbuhan'      => $a->status_pertumbuhan,
                    'deskripsi_karakteristik' => $a->deskripsi_karakteristik,
                    'catatan_perubahan'       => $a->catatan_perubahan,
                    'status_penjualan'        => $a->status_penjualan,
                    'umur'                    => $a->age['formatted'] ?? null,
                ];
            })->values(),
        ];

        /*
        |--------------------------------------------------------------------------
        | 3. Generate Prompt untuk AI
        |--------------------------------------------------------------------------
        */

        $prompt = $this->generatePromptForAI($payload);

        /*
        |--------------------------------------------------------------------------
        | 4. Panggil OpenAI (pakai openai-php/client)
        |--------------------------------------------------------------------------
        */

        try {
            $client = OpenAI::client(env('OPENAI_API_KEY'));

            $response = $client->chat()->create([
                'model'    => env('OPENAI_MODEL', 'gpt-4o-mini'),
                'messages' => [
                    [
                        'role'    => 'system',
                        'content' => 'Anda adalah ahli genetika & breeding burung Murai Batu. Analisis harus berdasarkan data riil, tidak boleh mengarang.'
                    ],
                    [
                        'role'    => 'user',
                        'content' => $prompt
                    ]
                ],
            ]);
        } catch (\Exception $e) {
            return "Gagal menghubungi API AI: " . $e->getMessage();
        }

        $hasilAnalisa = $response->choices[0]->message->content ?? 'Tidak ada hasil analisa.';

        /*
        |--------------------------------------------------------------------------
        | 5. Kirim ke view
        |--------------------------------------------------------------------------
        */

        return view('peternak.indukan.analisa-semua', compact('hasilAnalisa'));
        return "VIEW YANG DIGUNAKAN: " . view()->exists('peternak.indukan.analisa-semua');

    }


    private function generatePromptForAI(array $data): string
    {
        $json = json_encode($data, JSON_PRETTY_PRINT);

        return <<<PROMPT
Berikut data lengkap seluruh indukan, perkawinan, dan anakan:

$json

Tugas Anda:

1. Analisis Performa Indukan
- Indukan paling produktif
- Indukan paling tidak produktif
- Jelaskan alasannya dari total trip & total anakan

2. Analisis Kualitas Anakan
- Pola kualitas keturunan berdasarkan karakteristik anakan & catatan_perubahan
- Pasangan induk yang menghasilkan keturunan konsisten bagus
- Pasangan yang menghasilkan keturunan lemah / sering sakit (jika ada)

3. Rekomendasi 3 Pasangan Perkawinan
Untuk setiap rekomendasi:
- Jantan #ID × Betina #ID
- Alasan berdasarkan karakteristik indukan, performa keturunan, dan produktivitas
- Prediksi kualitas anakan

4. Pasangan yang Tidak Direkomendasikan
Jika ada tanda keturunan bermasalah, jelaskan datanya.

5. Evaluasi Risiko Inbreeding
Hindari rekomendasi pasangan yang punya hubungan keluarga dekat.

Gunakan format heading yang rapi & mudah dibaca.
PROMPT;
    }
}
