<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Set perilaku dan hasil_analisis menjadi NULL
        DB::table('deteksi_penyakit')->update([
            'perilaku' => null,
            'hasil_analisis' => null
        ]);

        // 2. Baru ubah tipe kolom
        Schema::table('deteksi_penyakit', function (Blueprint $table) {
            $table->json('perilaku')->nullable()->change();
            $table->json('hasil_analisis')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('deteksi_penyakit', function (Blueprint $table) {
            $table->text('perilaku')->nullable()->change();
            $table->longText('hasil_analisis')->nullable()->change();
        });
    }
};
