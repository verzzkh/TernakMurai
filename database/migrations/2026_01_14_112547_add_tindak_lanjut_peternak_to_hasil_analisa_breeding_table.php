<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('hasil_analisa_breeding', function (Blueprint $table) {
            $table->enum('tindak_lanjut_peternak', ['lanjut','pantau','stop'])
                  ->nullable()
                  ->after('rekomendasi');
        });
    }

    public function down(): void
    {
        Schema::table('hasil_analisa_breeding', function (Blueprint $table) {
            $table->dropColumn('tindak_lanjut_peternak');
        });
    }
};
