<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Anakan;
use App\Models\HasilAnalisaBreeding;
use App\Models\Kandang;
use App\Models\Pairing;
use App\Models\Perkawinan;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class BreedingLifecycleService
{
    public const STATUS_KOSONG = 'kosong';
    public const STATUS_BERTELUR = 'bertelur';
    public const STATUS_MENGERAM = 'mengeram';
    public const HASIL_BERHASIL = 'berhasil';
    public const HASIL_GAGAL = 'gagal';

    public function __construct(
        private PairingService $pairingService
    ) {}

    private const TRANSITIONS = [
        self::STATUS_KOSONG => [self::STATUS_BERTELUR],
        self::STATUS_BERTELUR => [self::STATUS_MENGERAM, self::HASIL_GAGAL],
        self::STATUS_MENGERAM => [self::HASIL_BERHASIL, self::HASIL_GAGAL],
    ];

    public function validNextStatuses(Kandang $kandang): array
    {
        $statuses = self::TRANSITIONS[$kandang->status] ?? [];

        if (
            $this->pairingService->isAvailable()
            && 
            $kandang->status === self::STATUS_KOSONG
            && $kandang->indukan_jantan_id
            && $kandang->indukan_betina_id
        ) {
            $pairing = $this->pairingService->getOrCreate(
                $kandang->peternak_id,
                (int) $kandang->indukan_jantan_id,
                (int) $kandang->indukan_betina_id
            );

            if ($pairing->isDihentikan()) {
                return [];
            }
        }

        return $statuses;
    }

    public function statusLabels(): array
    {
        return [
            self::STATUS_KOSONG => 'Kosong',
            self::STATUS_BERTELUR => 'Bertelur',
            self::STATUS_MENGERAM => 'Mengeram',
            self::HASIL_BERHASIL => 'Berhasil',
            self::HASIL_GAGAL => 'Gagal',
        ];
    }

    public function pairingAvailable(): bool
    {
        return $this->pairingService->isAvailable();
    }

    public function getPairingForKandang(Kandang $kandang)
    {
        return $this->pairingService->forKandang($kandang);
    }

    public function activatePairing(Pairing $pairing): void
    {
        $this->pairingService->activate($pairing);
    }

    public function applyAnalysisFollowUp(int $analysisId, int $peternakId, string $followUp): string
    {
        return DB::transaction(function () use ($analysisId, $peternakId, $followUp) {
            $analysisQuery = HasilAnalisaBreeding::query()
                ->where('peternak_id', $peternakId)
                ->whereKey($analysisId);

            if ($this->pairingService->isAvailable()) {
                $analysisQuery->with('pairing');
            }

            /** @var HasilAnalisaBreeding $analysis */
            $analysis = $analysisQuery->lockForUpdate()->firstOrFail();

            if ($analysis->tindak_lanjut_peternak) {
                throw ValidationException::withMessages([
                    'tindak_lanjut_peternak' => 'Tindak lanjut peternak untuk analisa ini sudah dipilih dan tidak dapat diubah.',
                ]);
            }

            $message = 'Tindak lanjut peternak berhasil disimpan.';

            if ($followUp === 'stop') {
                $pairing = $this->pairingService->isAvailable()
                    ? ($analysis->pairing
                        ?? $this->pairingService->getOrCreate($peternakId, (int) $analysis->jantan_id, (int) $analysis->betina_id))
                    : null;

                $this->createFailedTripFromAnalysisStop($analysis, $peternakId, $pairing);

                if ($pairing?->exists) {
                    $this->pairingService->stop($pairing);
                }

                $message = 'Tindak lanjut disimpan, trip gagal dicatat, dan pairing diubah menjadi dihentikan.';
            }

            $analysis->update([
                'tindak_lanjut_peternak' => $followUp,
            ]);

            return $message;
        });
    }

    public function transition(Kandang $kandang, string $nextStatus): Kandang
    {
        $this->ensureCompletePair($kandang);
        $this->ensureTransitionAllowed($kandang, $nextStatus);

        if (in_array($nextStatus, [self::HASIL_BERHASIL, self::HASIL_GAGAL], true)) {
            throw ValidationException::withMessages([
                'status' => 'Status berhasil atau gagal harus diproses melalui finalisasi trip breeding.',
            ]);
        }

        if ($nextStatus === self::STATUS_BERTELUR) {
            $this->pairingService->ensureCanStartBreeding(
                $kandang->peternak_id,
                (int) $kandang->indukan_jantan_id,
                (int) $kandang->indukan_betina_id
            );
        }

        $kandang->update(['status' => $nextStatus]);

        return $kandang->refresh();
    }

    public function recordFailedTrip(Kandang $kandang, int $peternakId, string $tanggalGagal, ?string $catatan = null): Perkawinan
    {
        $this->ensureCompletePair($kandang);
        $this->ensureTransitionAllowed($kandang, self::HASIL_GAGAL);

        return DB::transaction(function () use ($kandang, $peternakId, $tanggalGagal, $catatan) {
            $pairing = $this->pairingService->isAvailable()
                ? $this->pairingService->getOrCreate(
                    $peternakId,
                    (int) $kandang->indukan_jantan_id,
                    (int) $kandang->indukan_betina_id
                )
                : null;

            $payload = [
                'peternak_id' => $peternakId,
                'kandang_id' => $kandang->id,
                'indukan_jantan_id' => $kandang->indukan_jantan_id,
                'indukan_betina_id' => $kandang->indukan_betina_id,
                'nomor_trip' => Perkawinan::generateNomorTripPasangan(
                    $peternakId,
                    $kandang->indukan_jantan_id,
                    $kandang->indukan_betina_id
                ),
                'tanggal_kawin' => $tanggalGagal,
                'catatan' => $catatan,
                'status' => self::HASIL_GAGAL,
            ];

            if ($pairing?->exists) {
                $payload['pairing_id'] = $pairing->id;
            }

            $perkawinan = Perkawinan::create($payload);

            if ($pairing) {
                $this->pairingService->markHistoricalChange($pairing, $perkawinan->updated_at ?? now());
            }
            $this->emptyKandangAfterFinalResult($kandang);

            return $perkawinan;
        });
    }

    public function recordSuccessfulTripWithAnakan(Kandang $kandang, int $peternakId, array $data): array
    {
        $this->ensureCompletePair($kandang);
        $this->ensureTransitionAllowed($kandang, self::HASIL_BERHASIL);

        return DB::transaction(function () use ($kandang, $peternakId, $data) {
            $pairing = $this->pairingService->isAvailable()
                ? $this->pairingService->getOrCreate(
                    $peternakId,
                    (int) $kandang->indukan_jantan_id,
                    (int) $kandang->indukan_betina_id
                )
                : null;

            $payload = [
                'peternak_id' => $peternakId,
                'kandang_id' => $kandang->id,
                'indukan_jantan_id' => $kandang->indukan_jantan_id,
                'indukan_betina_id' => $kandang->indukan_betina_id,
                'nomor_trip' => Perkawinan::generateNomorTripPasangan(
                    $peternakId,
                    $kandang->indukan_jantan_id,
                    $kandang->indukan_betina_id
                ),
                'tanggal_kawin' => $data['tanggal_lahir'],
                'catatan' => 'Perkawinan otomatis dibuat saat berhasil/menetas (' . $data['tanggal_lahir'] . ')',
                'status' => self::HASIL_BERHASIL,
            ];

            if ($pairing?->exists) {
                $payload['pairing_id'] = $pairing->id;
            }

            $perkawinan = Perkawinan::create($payload);

            $data['perkawinan_id'] = $perkawinan->id;
            $data['kandang_id'] = $kandang->id;
            $data['sumber_anakan'] = 'internal';

            $inserted = $this->createAnakans($data, $kandang, $peternakId);

            if ($pairing) {
                $this->pairingService->markHistoricalChange($pairing, $perkawinan->updated_at ?? now());
            }
            $this->emptyKandangAfterFinalResult($kandang);

            return [
                'perkawinan' => $perkawinan,
                'anakans' => $inserted,
            ];
        });
    }

    public function ensureTransitionAllowed(Kandang $kandang, string $nextStatus): void
    {
        if (!in_array($nextStatus, $this->validNextStatuses($kandang), true)) {
            $labels = $this->statusLabels();
            $allowed = collect($this->validNextStatuses($kandang))
                ->map(fn (string $status) => $labels[$status] ?? $status)
                ->implode(', ');

            throw ValidationException::withMessages([
                'status' => 'Transisi status tidak valid. Status ' . ($labels[$kandang->status] ?? $kandang->status)
                    . ' hanya boleh dilanjutkan ke: ' . ($allowed ?: 'tidak ada pilihan') . '.',
            ]);
        }
    }

    private function ensureCompletePair(Kandang $kandang): void
    {
        if (!$kandang->indukan_jantan_id || !$kandang->indukan_betina_id) {
            throw ValidationException::withMessages([
                'status' => 'Status breeding hanya dapat diubah jika terdapat pasangan Indukan Jantan dan Betina yang lengkap di kandang.',
            ]);
        }
    }

    private function emptyKandangAfterFinalResult(Kandang $kandang): void
    {
        $kandang->update(['status' => self::STATUS_KOSONG]);
    }

    private function createFailedTripFromAnalysisStop(
        HasilAnalisaBreeding $analysis,
        int $peternakId,
        ?Pairing $pairing = null
    ): Perkawinan {
        $tanggalStop = now();
        $kandangId = Kandang::query()
            ->where('peternak_id', $peternakId)
            ->where('indukan_jantan_id', $analysis->jantan_id)
            ->where('indukan_betina_id', $analysis->betina_id)
            ->orderByDesc('updated_at')
            ->value('id');

        $payload = [
            'peternak_id' => $peternakId,
            'kandang_id' => $kandangId,
            'indukan_jantan_id' => $analysis->jantan_id,
            'indukan_betina_id' => $analysis->betina_id,
            'nomor_trip' => Perkawinan::generateNomorTripPasangan(
                $peternakId,
                (int) $analysis->jantan_id,
                (int) $analysis->betina_id
            ),
            'tanggal_kawin' => $tanggalStop->toDateString(),
            'catatan' => 'Pairing dihentikan melalui tindak lanjut stop pada hasil evaluasi.',
            'status' => self::HASIL_GAGAL,
        ];

        if ($pairing?->exists) {
            $payload['pairing_id'] = $pairing->id;
        }

        $perkawinan = Perkawinan::create($payload);

        if ($pairing?->exists) {
            $this->pairingService->markHistoricalChange($pairing, $tanggalStop);
        }

        return $perkawinan;
    }

    private function createAnakans(array $data, Kandang $kandang, int $peternakId): array
    {
        $jumlah = (int) ($data['jumlah_anakan'] ?? 1);
        $tanggalLahir = $data['tanggal_lahir'] ?? now();
        $jenisKelaminRaw = $data['jenis_kelamin'] ?? 'tidak_diketahui';
        $jenisKelaminList = is_array($jenisKelaminRaw) ? array_values($jenisKelaminRaw) : [$jenisKelaminRaw];
        $fotoList = request()->hasFile('foto_anakan')
            ? (is_array(request()->file('foto_anakan')) ? array_values(request()->file('foto_anakan')) : [request()->file('foto_anakan')])
            : [];

        $inserted = [];

        for ($i = 0; $i < $jumlah; $i++) {
            $fotoPath = null;

            if (!empty($fotoList[$i])) {
                $fotoPath = $fotoList[$i]->store("anakans/{$peternakId}", 'public');
            }

            $nomorRing = null;
            if (isset($data['nomor_ring']) && is_array($data['nomor_ring'])) {
                $nomorRing = $data['nomor_ring'][$i + 1] ?? null;
            } elseif (isset($data['nomor_ring']) && is_string($data['nomor_ring'])) {
                $nomorRing = $data['nomor_ring'];
            }

            $inserted[] = Anakan::create([
                'peternak_id' => $peternakId,
                'kandang_id' => $data['kandang_id'],
                'perkawinan_id' => $data['perkawinan_id'],
                'indukan_jantan_id' => $kandang->indukan_jantan_id,
                'indukan_betina_id' => $kandang->indukan_betina_id,
                'nomor_ring' => $nomorRing,
                'tanggal_lahir' => $tanggalLahir,
                'jenis_kelamin' => $jenisKelaminList[$i] ?? 'tidak_diketahui',
                'status_pertumbuhan' => $data['status_pertumbuhan'] ?? 'trotol',
                'deskripsi_karakteristik' => $data['deskripsi_karakteristik'] ?? null,
                'foto_path' => $fotoPath,
                'sumber_anakan' => 'internal',
            ]);
        }

        return $inserted;
    }
}
