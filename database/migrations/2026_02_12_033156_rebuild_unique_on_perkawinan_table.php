<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('perkawinan', function (Blueprint $table) {

            // 1️⃣ Tambahkan index biasa untuk kandang_id
            $table->index('kandang_id', 'perkawinan_kandang_id_index');

            // 2️⃣ Drop unique lama
            $table->dropUnique('perkawinan_kandang_id_nomor_trip_unique');

            // 3️⃣ Tambahkan unique baru berbasis pasangan
            $table->unique(
                ['indukan_jantan_id', 'indukan_betina_id', 'nomor_trip'],
                'perkawinan_pasangan_trip_unique'
            );
        });
    }

    public function down(): void
    {
        Schema::table('perkawinan', function (Blueprint $table) {

            $table->dropUnique('perkawinan_pasangan_trip_unique');

            $table->unique(
                ['kandang_id', 'nomor_trip'],
                'perkawinan_kandang_id_nomor_trip_unique'
            );

            $table->dropIndex('perkawinan_kandang_id_index');
        });
    }
};
