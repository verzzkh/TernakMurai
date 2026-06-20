<?php

use App\Models\Pairing;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pairings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('peternak_id')->constrained('peternak')->cascadeOnDelete();
            $table->foreignId('indukan_jantan_id')->constrained('indukan')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('indukan_betina_id')->constrained('indukan')->cascadeOnUpdate()->cascadeOnDelete();
            $table->enum('status', [Pairing::STATUS_AKTIF, Pairing::STATUS_DIHENTIKAN])->default(Pairing::STATUS_AKTIF);
            $table->timestamp('last_historical_change_at')->nullable();
            $table->unsignedBigInteger('last_analysis_id')->nullable();
            $table->timestamps();

            $table->unique(['peternak_id', 'indukan_jantan_id', 'indukan_betina_id'], 'pairings_unique_pair');
        });

        Schema::table('perkawinan', function (Blueprint $table) {
            $table->foreignId('pairing_id')->nullable()->after('peternak_id')->constrained('pairings')->nullOnDelete();
        });

        Schema::table('hasil_analisa_breeding', function (Blueprint $table) {
            $table->foreignId('pairing_id')->nullable()->after('peternak_id')->constrained('pairings')->nullOnDelete();
            $table->timestamp('historical_reference_at')->nullable()->after('tanggal_analisa');
        });

        $this->backfillPairings();
    }

    public function down(): void
    {
        Schema::table('hasil_analisa_breeding', function (Blueprint $table) {
            $table->dropConstrainedForeignId('pairing_id');
            $table->dropColumn('historical_reference_at');
        });

        Schema::table('perkawinan', function (Blueprint $table) {
            $table->dropConstrainedForeignId('pairing_id');
        });

        Schema::dropIfExists('pairings');
    }

    private function backfillPairings(): void
    {
        $candidates = collect()
            ->merge(
                DB::table('perkawinan')
                    ->select('peternak_id', 'indukan_jantan_id as jantan_id', 'indukan_betina_id as betina_id')
                    ->whereNotNull('peternak_id')
                    ->whereNotNull('indukan_jantan_id')
                    ->whereNotNull('indukan_betina_id')
                    ->get()
            )
            ->merge(
                DB::table('hasil_analisa_breeding')
                    ->select('peternak_id', 'jantan_id', 'betina_id')
                    ->whereNotNull('peternak_id')
                    ->whereNotNull('jantan_id')
                    ->whereNotNull('betina_id')
                    ->get()
            )
            ->merge(
                DB::table('kandang')
                    ->select('peternak_id', 'indukan_jantan_id as jantan_id', 'indukan_betina_id as betina_id')
                    ->whereNull('deleted_at')
                    ->whereNotNull('peternak_id')
                    ->whereNotNull('indukan_jantan_id')
                    ->whereNotNull('indukan_betina_id')
                    ->get()
            )
            ->unique(fn ($row) => implode(':', [$row->peternak_id, $row->jantan_id, $row->betina_id]))
            ->values();

        foreach ($candidates as $candidate) {
            $pairingId = DB::table('pairings')->insertGetId([
                'peternak_id' => $candidate->peternak_id,
                'indukan_jantan_id' => $candidate->jantan_id,
                'indukan_betina_id' => $candidate->betina_id,
                'status' => Pairing::STATUS_AKTIF,
                'last_historical_change_at' => $this->resolveHistoricalChangeAt(
                    (int) $candidate->peternak_id,
                    (int) $candidate->jantan_id,
                    (int) $candidate->betina_id
                ),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            DB::table('perkawinan')
                ->where('peternak_id', $candidate->peternak_id)
                ->where('indukan_jantan_id', $candidate->jantan_id)
                ->where('indukan_betina_id', $candidate->betina_id)
                ->update(['pairing_id' => $pairingId]);

            DB::table('hasil_analisa_breeding')
                ->where('peternak_id', $candidate->peternak_id)
                ->where('jantan_id', $candidate->jantan_id)
                ->where('betina_id', $candidate->betina_id)
                ->update([
                    'pairing_id' => $pairingId,
                    'historical_reference_at' => DB::raw('tanggal_analisa'),
                ]);

            $lastAnalysisId = DB::table('hasil_analisa_breeding')
                ->where('pairing_id', $pairingId)
                ->orderByDesc('tanggal_analisa')
                ->value('id');

            if ($lastAnalysisId) {
                DB::table('pairings')
                    ->where('id', $pairingId)
                    ->update(['last_analysis_id' => $lastAnalysisId]);
            }
        }
    }

    private function resolveHistoricalChangeAt(int $peternakId, int $jantanId, int $betinaId): Carbon
    {
        $timestamps = [
            DB::table('perkawinan')
                ->where('peternak_id', $peternakId)
                ->where('indukan_jantan_id', $jantanId)
                ->where('indukan_betina_id', $betinaId)
                ->max('updated_at'),
            DB::table('anakan')
                ->where('peternak_id', $peternakId)
                ->where('indukan_jantan_id', $jantanId)
                ->where('indukan_betina_id', $betinaId)
                ->max('updated_at'),
        ];

        $filtered = collect($timestamps)
            ->filter()
            ->map(fn ($timestamp) => Carbon::parse($timestamp))
            ->sortDesc()
            ->values();

        return $filtered->first() ?? now();
    }
};
