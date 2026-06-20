<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Anakan;
use App\Models\HasilAnalisaBreeding;
use App\Models\Kandang;
use App\Models\Pairing;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\ValidationException;

class PairingService
{
    public function isAvailable(): bool
    {
        return Schema::hasTable('pairings')
            && Schema::hasColumn('perkawinan', 'pairing_id')
            && Schema::hasColumn('hasil_analisa_breeding', 'pairing_id')
            && Schema::hasColumn('hasil_analisa_breeding', 'historical_reference_at');
    }

    public function getOrCreate(int $peternakId, int $jantanId, int $betinaId): Pairing
    {
        if (!$this->isAvailable()) {
            throw ValidationException::withMessages([
                'pairing' => 'Fitur pairing belum tersedia di database. Jalankan migrasi terlebih dahulu.',
            ]);
        }

        $pairing = Pairing::firstOrCreate(
            [
                'peternak_id' => $peternakId,
                'indukan_jantan_id' => $jantanId,
                'indukan_betina_id' => $betinaId,
            ],
            [
                'status' => Pairing::STATUS_AKTIF,
                'last_historical_change_at' => now(),
            ]
        );

        if (!$pairing->last_historical_change_at) {
            $pairing->update(['last_historical_change_at' => now()]);
        }

        return $pairing;
    }

    public function forKandang(Kandang $kandang): ?Pairing
    {
        if (!$this->isAvailable()) {
            return null;
        }

        if (!$kandang->indukan_jantan_id || !$kandang->indukan_betina_id) {
            return null;
        }

        return $this->getOrCreate($kandang->peternak_id, $kandang->indukan_jantan_id, $kandang->indukan_betina_id);
    }

    public function ensureCanStartBreeding(int $peternakId, int $jantanId, int $betinaId): Pairing
    {
        if (!$this->isAvailable()) {
            return new Pairing([
                'peternak_id' => $peternakId,
                'indukan_jantan_id' => $jantanId,
                'indukan_betina_id' => $betinaId,
                'status' => Pairing::STATUS_AKTIF,
                'last_historical_change_at' => now(),
            ]);
        }

        $pairing = $this->getOrCreate($peternakId, $jantanId, $betinaId);

        if ($pairing->isDihentikan()) {
            throw ValidationException::withMessages([
                'status' => 'Pairing ini berstatus dihentikan. Aktifkan kembali melalui manajemen pairing sebelum memulai trip breeding baru.',
            ]);
        }

        return $pairing;
    }

    public function getAnalysisStatus(Pairing $pairing): array
    {
        if (!$this->isAvailable()) {
            return [
                'allowed' => true,
                'reason' => 'pairing_belum_tersedia',
                'latest_analysis' => null,
            ];
        }

        $latestAnalysis = $this->getLatestAnalysis($pairing);

        if ($pairing->isDihentikan()) {
            return [
                'allowed' => false,
                'reason' => 'dihentikan',
                'latest_analysis' => $latestAnalysis,
            ];
        }

        if (!$latestAnalysis) {
            return [
                'allowed' => true,
                'reason' => 'belum_pernah_dianalisa',
                'latest_analysis' => null,
            ];
        }

        if (!$this->hasHistoricalChangesSinceLatestAnalysis($pairing, $latestAnalysis)) {
            return [
                'allowed' => false,
                'reason' => 'tidak_ada_perubahan_histori',
                'latest_analysis' => $latestAnalysis,
            ];
        }

        return [
            'allowed' => true,
            'reason' => 'histori_berubah',
            'latest_analysis' => $latestAnalysis,
        ];
    }

    public function getLatestAnalysis(Pairing $pairing): ?HasilAnalisaBreeding
    {
        if (!$this->isAvailable()) {
            return null;
        }

        if ($pairing->relationLoaded('lastAnalysis') && $pairing->lastAnalysis) {
            return $pairing->lastAnalysis;
        }

        if ($pairing->last_analysis_id) {
            return HasilAnalisaBreeding::find($pairing->last_analysis_id);
        }

        return $pairing->analyses()->latest('tanggal_analisa')->first();
    }

    public function hasHistoricalChangesSinceLatestAnalysis(Pairing $pairing, ?HasilAnalisaBreeding $latestAnalysis = null): bool
    {
        if (!$this->isAvailable()) {
            return true;
        }

        $latestAnalysis ??= $this->getLatestAnalysis($pairing);

        if (!$latestAnalysis) {
            return true;
        }

        $reference = $latestAnalysis->historical_reference_at ?? $latestAnalysis->tanggal_analisa;
        $historicalChangeAt = $pairing->last_historical_change_at;

        if (!$historicalChangeAt) {
            return false;
        }

        return $historicalChangeAt->gt($reference);
    }

    public function registerAnalysis(Pairing $pairing, HasilAnalisaBreeding $analysis): void
    {
        if (!$this->isAvailable() || !$pairing->exists) {
            return;
        }

        $pairing->update([
            'last_analysis_id' => $analysis->id,
        ]);
    }

    public function markHistoricalChange(Pairing $pairing, Carbon|string|null $changedAt = null): void
    {
        if (!$this->isAvailable() || !$pairing->exists) {
            return;
        }

        $timestamp = $changedAt ? Carbon::parse($changedAt) : now();

        if (!$pairing->last_historical_change_at || $timestamp->gt($pairing->last_historical_change_at)) {
            $pairing->update(['last_historical_change_at' => $timestamp]);
        }
    }

    public function markHistoricalChangeByPair(int $peternakId, ?int $jantanId, ?int $betinaId, Carbon|string|null $changedAt = null): ?Pairing
    {
        if (!$this->isAvailable()) {
            return null;
        }

        if (!$jantanId || !$betinaId) {
            return null;
        }

        $pairing = $this->getOrCreate($peternakId, $jantanId, $betinaId);
        $this->markHistoricalChange($pairing, $changedAt);

        return $pairing;
    }

    public function markHistoricalChangeFromAnakan(Anakan $anakan): ?Pairing
    {
        if ($anakan->perkawinan?->pairing) {
            $pairing = $anakan->perkawinan->pairing;
            $this->markHistoricalChange($pairing, $anakan->updated_at ?? now());

            return $pairing;
        }

        return $this->markHistoricalChangeByPair(
            $anakan->peternak_id,
            $anakan->indukan_jantan_id,
            $anakan->indukan_betina_id,
            $anakan->updated_at ?? now()
        );
    }

    public function stop(Pairing $pairing): void
    {
        if (!$this->isAvailable() || !$pairing->exists) {
            return;
        }

        $pairing->update(['status' => Pairing::STATUS_DIHENTIKAN]);
    }

    public function activate(Pairing $pairing): void
    {
        if (!$this->isAvailable() || !$pairing->exists) {
            return;
        }

        $pairing->update(['status' => Pairing::STATUS_AKTIF]);
    }

    public function getManagementList(int $peternakId): Collection
    {
        if (!$this->isAvailable()) {
            return collect();
        }

        return Pairing::query()
            ->where('peternak_id', $peternakId)
            ->with(['indukanJantan', 'indukanBetina', 'lastAnalysis'])
            ->withCount(['perkawinans', 'analyses'])
            ->orderByRaw("CASE WHEN status = ? THEN 0 ELSE 1 END", [Pairing::STATUS_AKTIF])
            ->orderByDesc('updated_at')
            ->get();
    }

    public function analysisQueryForPeternak(int $peternakId): Builder
    {
        if (!$this->isAvailable()) {
            return HasilAnalisaBreeding::query()
                ->where('peternak_id', $peternakId)
                ->with(['jantan', 'betina']);
        }

        return HasilAnalisaBreeding::query()
            ->where('peternak_id', $peternakId)
            ->with(['jantan', 'betina', 'pairing']);
    }
}
