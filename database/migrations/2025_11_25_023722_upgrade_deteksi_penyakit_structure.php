<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('deteksi_penyakit', function (Blueprint $table) {

            // Ubah TEXT → JSON


            // Tambahkan severity
            $table->enum('tingkat_keparahan', ['ringan', 'sedang', 'berat'])
                  ->nullable()
                  ->after('tingkat_kepercayaan');

            // Tambahkan perlu_dokter
            $table->boolean('perlu_dokter')
                  ->default(false)
                  ->after('tingkat_keparahan');
        });
    }

    public function down(): void
    {
        Schema::table('deteksi_penyakit', function (Blueprint $table) {
            $table->text('perilaku')->nullable()->change();
            $table->longText('hasil_analisis')->nullable()->change();
            $table->dropColumn(['tingkat_keparahan', 'perlu_dokter']);
        });
    }
};
